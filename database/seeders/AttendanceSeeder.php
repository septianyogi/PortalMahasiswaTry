<?php

namespace Database\Seeders;

use App\Models\ClassSession;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sessions = ClassSession::all();
        $students = Student::all();
        $statusList = ['hadir', 'absent', 'izin'];
        foreach ($sessions as $session) {
            foreach ($students as $student) {
                DB::table('attendances')->insert([
                    'session_id' => $session->id,
                    'student_id' => $student->id,
                    'status' => $statusList[array_rand($statusList)],
                    'scanned_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
