<?php

namespace App\Filament\Resources\Estates\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EstatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('county.name')->label('County')->placeholder('—')->sortable(),
                ToggleColumn::make('is_active'),
            ])
            ->filters([SelectFilter::make('county')->relationship('county', 'name')])
            ->recordActions([EditAction::make()]);
    }
}
