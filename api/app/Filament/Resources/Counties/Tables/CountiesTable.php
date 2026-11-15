<?php

namespace App\Filament\Resources\Counties\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CountiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->modifyQueryUsing(fn ($query) => $query->withCount('estates'))
            ->columns([
                TextColumn::make('code')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('estates_count')->label('Estates'),
                ToggleColumn::make('is_active'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
