<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->columnSpanFull()->schema([
                TextInput::make('name')->required()->maxLength(255)->unique(ignoreRecord: true)->helperText('e.g. "manage orders"'),
                TextInput::make('guard_name')->default('web')->required()->readOnly(),
            ]),
        ]);
    }
}
