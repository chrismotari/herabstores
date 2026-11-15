<?php

namespace App\Filament\Resources\Baskets\Tables;

use App\Models\Basket;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BasketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('effective_to', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->withCount('basket_products'))
            ->columns([
                ImageColumn::make('image')->label('')->disk('public')->square(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('price')->money('KES')->sortable(),
                TextColumn::make('stock')->sortable(),
                TextColumn::make('basket_products_count')->label('Products'),
                TextColumn::make('effective_from')->label('From')->date()->sortable(),
                TextColumn::make('effective_to')->label('Until')->date()->sortable()
                    ->color(fn (Basket $record) => $record->effective_to?->isPast() ? 'danger' : null),
                ToggleColumn::make('is_active')->label('Visible'),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Visible in shop'),
                Filter::make('current')->label('Available now')->query(fn ($query) => $query->available()),
            ])
            ->recordActions([EditAction::make()]);
    }
}
