<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FakultasSeeder::class,
            jurusanSeeder::class,
            UserSeeder::class,
            DosenSeeder::class,
            StudentSeeder::class,
            ClassesSeeder::class,
            StudentSemesterSeeder::class,
            ClassAttendedSeeder::class,
            ClassSessionSeeder::class,   // <- setelah ClassAttendedSeeder
            AttendanceSeeder::class,     // <- terakhir
        ]);
    }
}