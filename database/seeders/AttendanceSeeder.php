<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassAttended;
use App\Models\ClassSession;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil semua sesi beserta data kelasnya (gunakan relasi class)
        $sessions = ClassSession::with('class')->get();

        if ($sessions->isEmpty()) {
            $this->command->error('No class sessions found. Please run ClassSessionSeeder first.');
            return;
        }

        foreach ($sessions as $session) {
            // Ambil semua student yang terdaftar di kelas ini (dari class_attendeds)
            $classAttendeds = ClassAttended::where('class_id', $session->class_id)
                ->with('student')
                ->get();

            if ($classAttendeds->isEmpty()) {
                continue;
            }

            foreach ($classAttendeds as $classAttended) {
                $student = $classAttended->student;

                // Probabilitas status kehadiran (70% hadir, 20% izin, 10% absent)
                $roll = $faker->numberBetween(1, 100);
                if ($roll <= 70) {
                    $status = 'hadir';
                } elseif ($roll <= 90) {
                    $status = 'izin';
                } else {
                    $status = 'absent';
                }

                // Tentukan waktu scan (jika hadir atau izin)
                $scannedAt = null;
                if ($status === 'hadir' || $status === 'izin') {
                    // Ambil jadwal dari relasi class
                    $class = $session->class;

                    // Kita tidak punya tanggal sesi di class_sessions, gunakan created_at sebagai perkiraan
                    // Lebih akurat: kita bisa menambahkan kolom session_date di class_sessions
                    // Untuk sekarang, kita gunakan tanggal sesi dari perhitungan (week)
                    // Asumsikan sesi pertama dimulai 2 bulan lalu + (week-1) minggu
                    $startDate = Carbon::now()->subMonths(2)->startOfWeek(Carbon::MONDAY);
                    $dayMap = [
                        'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3,
                        'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7,
                    ];
                    $dayOfWeek = $dayMap[$class->date] ?? 1;
                    $firstSessionDate = $startDate->copy()->addDays(
                        ($dayOfWeek - $startDate->dayOfWeek + 7) % 7
                    );
                    $sessionDate = $firstSessionDate->copy()->addWeeks($session->week - 1);

                    // Gabungkan dengan waktu mulai kelas
                    $timeStart = Carbon::parse($class->time_start);
                    $scannedAt = Carbon::parse(
                        $sessionDate->format('Y-m-d') . ' ' . $timeStart->format('H:i:s')
                    )->addMinutes($faker->numberBetween(5, 30));
                }

                // Cegah duplikasi (unique: session_id + student_id)
                Attendance::firstOrCreate(
                    [
                        'session_id' => $session->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'class_id'   => $session->class_id,
                        'week'       => $session->week,
                        'status'     => $status,
                        'scanned_at' => $scannedAt,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        $this->command->info('Attendance seeder completed.');
    }
}