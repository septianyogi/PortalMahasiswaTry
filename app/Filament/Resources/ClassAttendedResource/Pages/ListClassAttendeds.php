<?php

namespace App\Filament\Resources\ClassAttendedResource\Pages;

use App\Filament\Resources\ClassAttendedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassAttendeds extends ListRecords
{
    protected static string $resource = ClassAttendedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make('Tambah'),
        ];
    }
}
