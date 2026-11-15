<?php

namespace App\Filament\Resources\Stores\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class StoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('phone'),
                TextColumn::make('deliveryZone.name')->label('Zone')->placeholder('—'),
                TextColumn::make('manager.email')->label('Manager')->placeholder('—'),
                ToggleColumn::make('is_active'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
