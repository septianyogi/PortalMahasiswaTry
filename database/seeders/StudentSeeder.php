<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();

        foreach ($students as $i => $user) {
            Student::create([
                'user_id' => $user->id,
                'npm' => 2024000 + $i,
                'name' => $user->name,
                'email' => $user->email,
                'jurusan_id' => 1,
                'fakultas_id' => 1,
                'semester' => 2,
                'dob' => '2000-01-01',
                'alamat' => 'Yogyakarta',
                'pembayaran' => true,
            ]);
        }
    }
}
