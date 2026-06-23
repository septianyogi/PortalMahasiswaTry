<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user dosen yang sudah ada (dari UserSeeder)
        $userDosen = User::where('role', 'dosen')->first();

        if ($userDosen) {
            Dosen::create([
                'user_id' => $userDosen->id,
                'name' => $userDosen->name,
                'email' => $userDosen->email,
                'nip' => '1234567890',
            ]);
        } else {
            // Jika tidak ada, buat dosen baru dengan user baru
            $user = User::create([
                'name' => 'Dosen',
                'email' => 'dosen@example.com',
                'password' => 'password',
                'role' => 'dosen',
                'pin' => '123456',
            ]);
            Dosen::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nip' => '1234567890',
            ]);
        }
    }
}