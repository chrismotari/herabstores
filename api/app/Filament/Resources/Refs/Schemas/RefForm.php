<?php

namespace App\Filament\Resources\Refs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RefForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->columnSpanFull()->schema([
                TextInput::make('ref_code')->label('Code')->disabled()->dehydrated(false)
                    ->placeholder('Generated when saved')->visibleOn('edit'),
                Select::make('user_id')->label('Referrer')->relationship('user', 'email')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->getFilamentName().' ('.$record->email.')')
                    ->searchable(['fname', 'lname', 'email', 'phone']),
                DateTimePicker::make('expires_at')->label('Expires'),
                Toggle::make('is_active')->default(true)->inline(false),
                Textarea::make('description')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }
}
