<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentSemester;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class StudentSemesterSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $students = Student::all();
        $startDate = Carbon::now()->subYears(2)->startOfYear();

        foreach ($students as $student) {
            // Semester 1
            StudentSemester::create([
                'student_id' => $student->id,
                'semester_number' => 1,
                'gpa' => $faker->randomFloat(2, 2.5, 3.8),
                'credits' => $faker->numberBetween(12, 18),
                'status' => 'completed',
                'start_date' => $startDate->copy(),
                'end_date' => $startDate->copy()->addMonths(6),
            ]);

            // Semester 2
            StudentSemester::create([
                'student_id' => $student->id,
                'semester_number' => 2,
                'gpa' => $faker->randomFloat(2, 2.5, 3.8),
                'credits' => $faker->numberBetween(12, 18),
                'status' => 'completed',
                'start_date' => $startDate->copy()->addMonths(6),
                'end_date' => $startDate->copy()->addMonths(12),
            ]);

            // Semester 3 (aktif)
            StudentSemester::create([
                'student_id' => $student->id,
                'semester_number' => 3,
                'gpa' => null,
                'credits' => null,
                'status' => 'active',
                'start_date' => $startDate->copy()->addMonths(12),
                'end_date' => null,
            ]);
        }
    }
}