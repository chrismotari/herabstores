<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->columnSpanFull()->schema([
                TextInput::make('name')
                    ->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state, string $operation) {
                        if ($operation === 'create' || blank($get('slug'))) {
                            $set('slug', Str::slug((string) $state));
                        }
                    }),
                TextInput::make('slug')
                    ->required()->maxLength(255)->alphaDash()->unique(ignoreRecord: true)
                    ->helperText('Used in the category URL. Changing it breaks old links.'),
                Select::make('parent_id')
                    ->label('Parent category')
                    ->relationship('parent', 'name', fn (Builder $query, ?Model $record) => $query->when($record, fn ($q) => $q->whereKeyNot($record->getKey())))
                    ->searchable()->preload()
                    ->placeholder('None (top level)'),
                Toggle::make('is_active')->label('Visible in shop')->default(true)->inline(false),
                Textarea::make('description')->required()->rows(3)->columnSpanFull(),
                FileUpload::make('image_url')->label('Image')->image()->disk('public')->directory('categories')->maxSize(4096),
            ]),
        ]);
    }
}
