<?php

namespace App\Filament\Resources\DosenResource\Pages;

use App\Filament\Resources\DosenResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateDosen extends CreateRecord
{
    protected static string $resource = DosenResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        $user = new User();
        $user->id_number = $data['nip'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['nip']);
        $user->role = 'dosen';
        $user->save();

        return $record;
    }
}
