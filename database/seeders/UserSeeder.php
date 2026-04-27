<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => "password",
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Dosen 1',
            'email' => 'dosen@gmail.com',
            'password' => "password",
            'role' => 'dosen'
        ]);

        for ($i = 1; $i <= 50; $i++) {
            User::create([
                'name' => "Student $i",
                'email' => "student$i@gmail.com",
                'password' => "password",
                'role' => 'student'
            ]);
        }
    }
}
