<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        FakultasSeeder::class,
        jurusanSeeder::class,
        UserSeeder::class,
        DosenSeeder::class,
        StudentSeeder::class,
        ClassesSeeder::class,
        ClassAttendedSeeder::class,
        ClassSessionSeeder::class,
        AttendanceSeeder::class,
        AssignmentSeeder::class,
    ]);
    }
}
