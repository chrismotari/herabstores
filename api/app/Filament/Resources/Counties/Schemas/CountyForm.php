<?php

namespace App\Filament\Resources\Counties\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CountyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->columnSpanFull()->schema([
                TextInput::make('name')->required()->maxLength(255)->unique(ignoreRecord: true),
                TextInput::make('code')->required()->integer()->unique(ignoreRecord: true),
                Textarea::make('description')->rows(2)->columnSpanFull(),
                Toggle::make('is_active')->default(true),
            ]),
        ]);
    }
}
