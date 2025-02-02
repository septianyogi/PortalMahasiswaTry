<?php

namespace App\Filament\Resources\ClassResource\Pages;

use App\Filament\Resources\ClassResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateClass extends CreateRecord
{
    protected static string $resource = ClassResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        $record['code'] = Carbon::now()->format('dmy').'-'.$record->jurusan_id.'-'.Carbon::now()->format('Hi');

        return $record;
    }
}
