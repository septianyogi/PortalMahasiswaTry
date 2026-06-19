<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\ClassAttended;
use App\Models\Student;
use App\Services\GradeCalculator;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ClassAttendedSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $classes = Classes::all();
        $students = Student::all();

        if ($classes->isEmpty() || $students->isEmpty()) {
            $this->command->error('Classes or Students not found. Please run seeders first.');
            return;
        }

        // Inisialisasi service kalkulator
        $calculator = app(GradeCalculator::class);

        foreach ($classes as $class) {
            foreach ($students as $student) {
                // Generate nilai assignment (masing-masing 10-100, 10% kemungkinan null)
                $assignments = [];
                for ($i = 1; $i <= 4; $i++) {
                    if ($faker->boolean(10)) {
                        $assignments['assignment_' . $i] = null;
                    } else {
                        $assignments['assignment_' . $i] = $faker->numberBetween(50, 100);
                    }
                }

                // Nilai mid dan final (10% kemungkinan null)
                $mid = $faker->boolean(10) ? null : $faker->numberBetween(40, 100);
                $final = $faker->boolean(10) ? null : $faker->numberBetween(40, 100);

                // Kehadiran (misal total pertemuan 12-14)
                $totalMeetings = $faker->numberBetween(12, 14);
                $attendance = $faker->numberBetween(6, $totalMeetings);
                $absent = $totalMeetings - $attendance;

                // Cek apakah sudah ada (hindari duplikat)
                $classAttended = ClassAttended::firstOrCreate(
                    [
                        'class_id' => $class->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'verified_at'  => $faker->dateTimeBetween('-1 month', 'now'),
                        'attendance'   => $attendance,
                        'absent'       => $absent,
                        'assignment_1' => $assignments['assignment_1'],
                        'assignment_2' => $assignments['assignment_2'],
                        'assignment_3' => $assignments['assignment_3'],
                        'assignment_4' => $assignments['assignment_4'],
                        'mid_exam'     => $mid,
                        'final_exam'   => $final,
                    ]
                );

                // Jika baru dibuat, hitung nilai akhir
                if ($classAttended->wasRecentlyCreated) {
                    $calculator->calculate($classAttended);
                    $this->command->info("ClassAttended created for student {$student->id} in class {$class->id}");
                } else {
                    $this->command->info("ClassAttended already exists for student {$student->id} in class {$class->id}");
                }
            }
        }

        $this->command->info('ClassAttended seeder completed.');
    }
}