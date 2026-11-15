<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Section::make('Details')->columnSpan(2)->columns(2)->schema([
                TextInput::make('name')
                    ->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state, string $operation) {
                        if ($operation === 'create' || blank($get('slug'))) {
                            $set('slug', Str::slug((string) $state));
                        }
                    }),
                TextInput::make('slug')
                    ->required()->maxLength(255)
                    ->alphaDash()
                    ->unique(ignoreRecord: true)
                    ->helperText('Used in the product URL. Changing it breaks old links.'),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()->preload()->required()
                    ->columnSpanFull(),
                Textarea::make('description')->required()->rows(5)->columnSpanFull(),
            ]),

            Section::make('Price & stock')->columnSpan(1)->schema([
                TextInput::make('price')->required()->numeric()->minValue(0)->prefix('KES'),
                TextInput::make('stock')
                    ->required()->integer()->minValue(0)->default(0)
                    ->helperText('Units available to order now.'),
                TextInput::make('total_stock')
                    ->label('Full stock level')
                    ->integer()->minValue(0)->default(0)
                    ->helperText('Optional. "Few items left" shows below 10% of this (or ≤ 5 when 0).'),
                Toggle::make('is_active')->label('Visible in shop')->default(true),
            ]),

            Section::make('Images')->columnSpanFull()->columns(2)->schema([
                FileUpload::make('image_url')
                    ->label('Main image')
                    ->image()->imageEditor()
                    ->disk('public')->directory('products')
                    ->maxSize(4096),
                Repeater::make('images')
                    ->label('Gallery')
                    ->relationship()
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->simple(
                        FileUpload::make('image_url')
                            ->image()->disk('public')->directory('products')->maxSize(4096)->required(),
                    )
                    ->defaultItems(0)
                    ->addActionLabel('Add image'),
            ]),
        ]);
    }
}
