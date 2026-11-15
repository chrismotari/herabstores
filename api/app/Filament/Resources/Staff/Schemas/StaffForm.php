<?php

namespace App\Filament\Resources\Staff\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->columnSpanFull()->schema([
                TextInput::make('fname')->label('First name')->required()->maxLength(255),
                TextInput::make('lname')->label('Last name')->required()->maxLength(255),
                TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                TextInput::make('phone')->tel()->required()->unique(ignoreRecord: true),
                TextInput::make('password')->password()->revealable()
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->dehydrateStateUsing(fn (string $state) => Hash::make($state))
                    ->rule('min:8')
                    ->helperText(fn (string $operation) => $operation === 'edit' ? 'Leave blank to keep the current password.' : null),
                Select::make('roles')->relationship('roles', 'name')->multiple()->preload(),
                Toggle::make('is_active')->label('Can log in')->default(true)->inline(false)
                    ->disabled(fn ($record) => $record?->is(auth()->user()))
                    ->helperText(fn ($record) => $record?->is(auth()->user()) ? 'You cannot deactivate yourself.' : null),
            ]),
        ]);
    }
}
