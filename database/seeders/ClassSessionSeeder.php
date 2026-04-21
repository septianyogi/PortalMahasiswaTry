<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\ClassSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClassSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $classes = Classes::all();

        foreach ($classes as $class) {
            for ($week = 1; $week <= 5; $week++) {
                ClassSession::create([
                    'class_id' => $class->id,
                    'week' => $week,
                    'date' => now()->addWeeks($week),
                    'qr_token' => Str::random(40),
                    'expired_at' => now()->addMinutes(10),
                    'is_active' => true,
                ]);
            }
        }
    }
}
