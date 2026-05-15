<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Dosen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosen = Dosen::first();
        $dayList = ['senin', 'selasa', 'rabu', 'kamis'];
        for ($i = 1; $i <= 50; $i++) {
            Classes::create([
                'code' => 'IF10' . $i,
                'jurusan_id' => 1,
                'name' => "Kelas Pemrograman $i",
                'date' => $dayList[array_rand($dayList)],
                'time_start' => '08:00:00',
                'time_end' => '10:00:00',
                'credits' => rand(2, 4),
                'dosen_id' => $dosen->id,
                'quota' => 30,
                'room' => "Lab $i",
                'semester' => '2',
            ]);
        }
    }
}
