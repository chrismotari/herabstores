<?php

namespace App\Filament\Resources\Refs\Pages;

use App\Filament\Resources\Refs\RefResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRef extends EditRecord
{
    protected static string $resource = RefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
