<?php

namespace App\Filament\Resources\DeliveryZones\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class DeliveryZonesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->modifyQueryUsing(fn ($query) => $query->withCount('orders'))
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('delivery_fees')->label('Fee')->money('KES')->sortable(),
                TextColumn::make('county.name')->label('County')->placeholder('—'),
                TextColumn::make('orders_count')->label('Orders')->sortable(),
                ToggleColumn::make('is_active')->label('At checkout'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
