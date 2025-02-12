<?php

namespace App\Filament\Resources\AddClassResource\Pages;

use App\Filament\Resources\AddClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAddClasses extends ListRecords
{
    protected static string $resource = AddClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
