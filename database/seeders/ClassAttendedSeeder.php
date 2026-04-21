<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassAttendedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $classes = Classes::all();
        $students = Student::all();

        foreach ($classes as $class) {
            foreach ($students as $student) {
                DB::table('class_attendeds')->insert([
                    'class_id' => $class->id,
                    'student_id' => $student->id,
                    'attendance' => rand(0, 10),
                    'absent' => rand(0, 5),
                    'mid_exam' => rand(60, 100),
                    'final_exam' => rand(60, 100),
                    'final_score' => rand(60, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
