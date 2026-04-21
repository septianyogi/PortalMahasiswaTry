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
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Dosen 1',
            'email' => 'dosen@mail.com',
            'password' => Hash::make('password'),
            'role' => 'dosen'
        ]);

        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Student $i",
                'email' => "student$i@mail.com",
                'password' => Hash::make('password'),
                'role' => 'student'
            ]);
        }
    }
}
