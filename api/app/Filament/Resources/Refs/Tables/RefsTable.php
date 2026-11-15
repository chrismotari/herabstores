<?php

namespace App\Filament\Resources\Refs\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class RefsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('ref_code')->label('Code')->searchable()->copyable(),
                TextColumn::make('user.email')->label('Referrer')->placeholder('—'),
                TextColumn::make('description')->limit(40),
                TextColumn::make('expires_at')->label('Expires')->date()->placeholder('Never'),
                ToggleColumn::make('is_active'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
