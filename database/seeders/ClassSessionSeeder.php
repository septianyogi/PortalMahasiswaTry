<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\ClassSession;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClassSessionSeeder extends Seeder
{
    public function run(): void
    {
        $classes = Classes::all();

        // Mapping hari Indonesia ke angka (1=Senin, 7=Minggu)
        $dayMap = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7,
        ];

        foreach ($classes as $class) {
            // Tentukan jumlah pertemuan (12-14)
            $totalWeeks = rand(12, 14);

            // Tentukan tanggal awal semester: misal 2 bulan yang lalu, hari Senin
            $startDate = Carbon::now()->subMonths(2)->startOfWeek(Carbon::MONDAY);

            // Cari hari kelas dalam angka
            $dayOfWeek = $dayMap[$class->date] ?? 1; // default Senin

            // Cari tanggal pertama dari hari tersebut di minggu pertama
            $firstSessionDate = $startDate->copy()->addDays(
                ($dayOfWeek - $startDate->dayOfWeek + 7) % 7
            );

            for ($week = 1; $week <= $totalWeeks; $week++) {
                // Tanggal sesi
                $sessionDate = $firstSessionDate->copy()->addWeeks($week - 1);

                // Waktu mulai dari kelas
                $timeStart = Carbon::parse($class->time_start);
                $timeEnd = Carbon::parse($class->time_end);

                // Gabungkan tanggal dan waktu
                $sessionStart = Carbon::parse(
                    $sessionDate->format('Y-m-d') . ' ' . $timeStart->format('H:i:s')
                );
                $sessionEnd = Carbon::parse(
                    $sessionDate->format('Y-m-d') . ' ' . $timeEnd->format('H:i:s')
                );

                // QR code berlaku 20 menit setelah sesi dimulai
                $expiredAt = $sessionStart->copy()->addMinutes(20);

                ClassSession::create([
                    'class_id'      => $class->id,
                    'week'          => $week,
                    'code_duration' => 20,
                    'qr_token'      => Str::random(40),
                    'expired_at'    => $expiredAt,
                    'is_active'     => true,
                ]);
            }
        }

        $this->command->info('ClassSession seeder completed.');
    }
}