<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
class jurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('jurusans')->insert([
            ['code' => 'IF', 'name' => 'Informatika'],
            ['code' => 'SI', 'name' => 'Sistem Informasi'],
        ]);
    }
}
