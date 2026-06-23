<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Dosen;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Set locale Indonesia untuk nama hari
        $dosenIds = Dosen::pluck('id')->toArray();

        if (empty($dosenIds)) {
            $this->command->error('No dosen found. Please run DosenSeeder first.');
            return;
        }

        // Daftar hari dalam bahasa Indonesia
        $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Array untuk menyimpan daftar waktu mulai yang masuk akal (07:00 - 16:00)
        $possibleStartTimes = [
            '07:00:00', '07:30:00', '08:00:00', '08:30:00',
            '09:00:00', '09:30:00', '10:00:00', '10:30:00',
            '11:00:00', '11:30:00', '12:30:00', '13:00:00',
            '13:30:00', '14:00:00', '14:30:00', '15:00:00',
            '15:30:00', '16:00:00',
        ];

        $semesters = [
            '1' => [
                ['code' => 'CS101', 'name' => 'Programming Basics', 'credits' => 3],
                ['code' => 'CS102', 'name' => 'Data Structures', 'credits' => 3],
                ['code' => 'CS103', 'name' => 'Algorithm', 'credits' => 3],
                ['code' => 'CS104', 'name' => 'Discrete Mathematics', 'credits' => 2],
                ['code' => 'CS105', 'name' => 'Computer Organization', 'credits' => 3],
                ['code' => 'CS106', 'name' => 'Operating Systems', 'credits' => 3],
            ],
            '2' => [
                ['code' => 'CS201', 'name' => 'Database Systems', 'credits' => 3],
                ['code' => 'CS202', 'name' => 'Computer Networks', 'credits' => 3],
                ['code' => 'CS203', 'name' => 'Web Programming', 'credits' => 3],
                ['code' => 'CS204', 'name' => 'Object Oriented Programming', 'credits' => 3],
                ['code' => 'CS205', 'name' => 'Software Engineering', 'credits' => 3],
                ['code' => 'CS206', 'name' => 'Human Computer Interaction', 'credits' => 2],
            ],
            '3' => [
                ['code' => 'CS301', 'name' => 'Artificial Intelligence', 'credits' => 3],
                ['code' => 'CS302', 'name' => 'Machine Learning', 'credits' => 3],
                ['code' => 'CS303', 'name' => 'Cloud Computing', 'credits' => 2],
                ['code' => 'CS304', 'name' => 'Mobile Development', 'credits' => 3],
                ['code' => 'CS305', 'name' => 'Network Security', 'credits' => 3],
                ['code' => 'CS306', 'name' => 'Natural Language Processing', 'credits' => 3],
                ['code' => 'CS307', 'name' => 'Data Visualization', 'credits' => 2],
            ],
            '4' => [
                ['code' => 'CS401', 'name' => 'Advanced Programming', 'credits' => 3],
                ['code' => 'CS402', 'name' => 'Data Science', 'credits' => 3],
                ['code' => 'CS403', 'name' => 'UI/UX Design', 'credits' => 2],
                ['code' => 'CS404', 'name' => 'Project Management', 'credits' => 2],
                ['code' => 'CS405', 'name' => 'DevOps', 'credits' => 3],
                ['code' => 'CS406', 'name' => 'Big Data', 'credits' => 3],
                ['code' => 'CS407', 'name' => 'Internet of Things', 'credits' => 3],
                ['code' => 'CS408', 'name' => 'Blockchain', 'credits' => 2],
                ['code' => 'CS409', 'name' => 'Game Development', 'credits' => 3],
                ['code' => 'CS410', 'name' => 'Research Methodology', 'credits' => 2],
            ],
        ];

        foreach ($semesters as $semesterNumber => $classList) {
            // Untuk setiap semester, kita buat jadwal yang terdistribusi
            // Acak urutan hari agar tidak semua kelas di hari yang sama
            $shuffledDays = $daysOfWeek;
            shuffle($shuffledDays);

            foreach ($classList as $index => $classData) {
                // Pilih hari secara bergiliran dari shuffledDays, jika habis ulangi
                $dayIndex = $index % count($shuffledDays);
                $day = $shuffledDays[$dayIndex];

                // Pilih waktu start yang masuk akal, pastikan tidak terlalu dekat dengan end
                // Ambil dari possibleStartTimes, kalau index terlalu besar, acak ulang
                $startTime = $faker->randomElement($possibleStartTimes);
                // Jika credits 2, durasi 90 menit, jika 3 durasi 110 menit
                $durationMinutes = $classData['credits'] == 2 ? 90 : 110;
                $startTimestamp = strtotime($startTime);
                $endTimestamp = $startTimestamp + ($durationMinutes * 60);
                $endTime = date('H:i:s', $endTimestamp);

                Classes::create([
                    'code' => $classData['code'],
                    'jurusan_id' => 1,
                    'name' => $classData['name'],
                    'date' => $day,
                    'time_start' => $startTime,
                    'time_end' => $endTime,
                    'credits' => $classData['credits'],
                    'dosen_id' => $faker->randomElement($dosenIds),
                    'quota' => 40,
                    'current_quota' => 0,
                    'room' => 'R.' . $faker->numberBetween(101, 300),
                    'semester' => $semesterNumber,
                    'weight_assignment' => 40,
                    'weight_mid' => 30,
                    'weight_final' => 30,
                    'weight_assignment_1' => 10,
                    'weight_assignment_2' => 10,
                    'weight_assignment_3' => 10,
                    'weight_assignment_4' => 10,
                ]);
            }
        }
    }
}