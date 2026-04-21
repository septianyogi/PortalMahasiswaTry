<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Classes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = Classes::all();

        foreach ($classes as $class) {
            for ($i = 1; $i <= 3; $i++) {
                Assignment::create([
                    'class_id' => $class->id,
                    'title' => "Tugas $i - {$class->name}",
                    'description' => "Deskripsi tugas $i",
                    'due_date' => now()->addDays(7 * $i),
                    'score' => rand(70, 100),
                ]);
            }
        }
    }
}
