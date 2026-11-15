<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Contact')->columns(2)->columnSpanFull()->schema([
                TextInput::make('fname')->label('First name')->required()->maxLength(255),
                TextInput::make('lname')->label('Last name')->required()->maxLength(255),
                TextInput::make('phone')->tel()->required()->unique(ignoreRecord: true),
                TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                Toggle::make('is_active')->label('Can log in')->inline(false)
                    ->helperText('Deactivated customers cannot log in or use the app.'),
            ]),
            Section::make('Default delivery address')->columns(2)->columnSpanFull()->schema([
                TextInput::make('address')->maxLength(255),
                TextInput::make('city')->maxLength(255),
                Select::make('delivery_zone_id')->label('Zone')->relationship('deliveryZone', 'name'),
            ]),
        ]);
    }
}
