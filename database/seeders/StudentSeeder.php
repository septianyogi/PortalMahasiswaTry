<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 10 user student pertama
        $users = User::where('role', 'student')->take(10)->get();

        foreach ($users as $index => $user) {
            Student::create([
                'user_id' => $user->id,
                'npm' => 100000 + $index + 1,
                'name' => $user->name,          // gunakan nama dari user
                'email' => $user->email,
                'jurusan' => 'Informatika',
                'jurusan_id' => 1,
                'fakultas_id' => 1,
                'credits' => 0,
                'gpa' => 0,
                'semester' => 3,
                'dob' => '2000-01-01',
                'country' => 'Indonesia',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'subdistrict' => 'Kebayoran Baru',
                'postal_code' => '12120',
                'alamat' => 'Jl. Contoh No. 123',
                'pembayaran' => true,
                'semester_start_date' => now()->subMonths(6),
            ]);
        }
    }
}