<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'role' => 'admin',
            'pin' => '123456',
        ]);

        // Dosen
        User::create([
            'name' => $faker->name,
            'email' => 'dosen@gmail.com',
            'password' => 'password',
            'role' => 'dosen',
            'pin' => '123456',
        ]);

        // 20 Student dengan nama acak (Faker), email tetap pola
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => "student$i@gmail.com",
                'password' => 'password',
                'role' => 'student',
                'pin' => '123456',
            ]);
        }
    }
}