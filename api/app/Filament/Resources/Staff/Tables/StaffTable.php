<?php

namespace App\Filament\Resources\Staff\Tables;

use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('fname')
            ->columns([
                TextColumn::make('name')->state(fn (User $record) => $record->getFilamentName())
                    ->description(fn (User $record) => $record->email)
                    ->searchable(['fname', 'lname', 'email']),
                TextColumn::make('phone'),
                TextColumn::make('roles.name')->badge()->placeholder('—'),
                ToggleColumn::make('is_active')->label('Active')
                    ->disabled(fn (User $record) => $record->is(auth()->user())),
            ])
            ->recordActions([EditAction::make()]);
    }
}
