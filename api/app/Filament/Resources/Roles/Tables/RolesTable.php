<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount(['permissions', 'users']))
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('permissions_count')->label('Permissions'),
                TextColumn::make('users_count')->label('Users'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
