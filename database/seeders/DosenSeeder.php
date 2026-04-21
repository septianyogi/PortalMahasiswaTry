<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $user = User::where('role', 'dosen')->first();

        Dosen::create([
            'user_id' => $user->id,
            'nip' => 12345678,
            'name' => $user->name,
            'email' => $user->email,
            'dob' => '1980-01-01',
        ]);
    }
}
