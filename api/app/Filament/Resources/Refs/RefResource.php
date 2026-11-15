<?php

namespace App\Filament\Resources\Refs;

use App\Filament\Resources\Refs\Pages\CreateRef;
use App\Filament\Resources\Refs\Pages\EditRef;
use App\Filament\Resources\Refs\Pages\ListRefs;
use App\Filament\Resources\Refs\Schemas\RefForm;
use App\Filament\Resources\Refs\Tables\RefsTable;
use App\Models\Ref;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RefResource extends Resource
{
    protected static ?string $model = Ref::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static string|UnitEnum|null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'referral code';

    public static function form(Schema $schema): Schema
    {
        return RefForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RefsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRefs::route('/'),
            'create' => CreateRef::route('/create'),
            'edit' => EditRef::route('/{record}/edit'),
        ];
    }
}
