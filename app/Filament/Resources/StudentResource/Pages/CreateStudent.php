<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        $user = new User();
        $user->id_number = $data['npm'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = 'student';
        $user->password = Hash::make($data['password']);
        $user->save();

        $data['semester'] = 1;

        return $record;
    }
}
