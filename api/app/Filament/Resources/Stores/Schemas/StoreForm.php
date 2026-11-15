<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->columnSpanFull()->schema([
                TextInput::make('name')->required()->maxLength(255)->unique(ignoreRecord: true),
                TextInput::make('phone')->tel()->required()->unique(ignoreRecord: true),
                TextInput::make('email')->email()->unique(ignoreRecord: true),
                TextInput::make('alt_phone')->tel()->unique(ignoreRecord: true),
                Textarea::make('address')->required()->rows(2)->columnSpanFull(),
                Select::make('delivery_zone_id')->label('Zone')->relationship('deliveryZone', 'name'),
                Select::make('manager_id')->label('Manager')->relationship('manager', 'email', fn ($query) => $query->where('is_admin', true)),
                Textarea::make('description')->rows(2)->columnSpanFull(),
                FileUpload::make('logo')->image()->disk('public')->directory('stores'),
                Toggle::make('is_active')->default(true)->inline(false),
            ]),
        ]);
    }
}
