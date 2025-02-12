<?php

namespace App\Filament\Resources\ClassAttendedResource\Pages;

use App\Filament\Resources\ClassAttendedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassAttended extends EditRecord
{
    protected static string $resource = ClassAttendedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
