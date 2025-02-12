<?php

namespace App\Filament\Resources\AddClassResource\Pages;

use App\Filament\Resources\AddClassResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAddClass extends EditRecord
{
    protected static string $resource = AddClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
