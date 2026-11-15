<?php

namespace App\Filament\Resources\Refs\Pages;

use App\Filament\Resources\Refs\RefResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRefs extends ListRecords
{
    protected static string $resource = RefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
