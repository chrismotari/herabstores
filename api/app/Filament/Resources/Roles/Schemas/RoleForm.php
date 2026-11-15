<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->columnSpanFull()->schema([
                TextInput::make('name')->required()->maxLength(255)->unique(ignoreRecord: true),
                TextInput::make('guard_name')->default('web')->required()->readOnly(),
                CheckboxList::make('permissions')->relationship('permissions', 'name')->columns(3)->columnSpanFull()->bulkToggleable(),
            ]),
        ]);
    }
}
