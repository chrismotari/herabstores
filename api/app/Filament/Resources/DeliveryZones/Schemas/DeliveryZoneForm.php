<?php

namespace App\Filament\Resources\DeliveryZones\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeliveryZoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->columnSpanFull()->schema([
                TextInput::make('name')->required()->maxLength(255)->unique(ignoreRecord: true),
                TextInput::make('delivery_fees')->label('Delivery fee')->required()->numeric()->minValue(0)->prefix('KES'),
                Select::make('county_id')->label('County')->relationship('county', 'name')->searchable()->preload(),
                Select::make('estate_id')->label('Estate')->relationship('estate', 'name')->searchable()->preload(),
                Textarea::make('description')->rows(2)->columnSpanFull()->helperText('Shown to customers at checkout, e.g. areas covered.'),
                Toggle::make('is_active')->label('Available at checkout')->default(true),
            ]),
        ]);
    }
}
