<?php

namespace App\Filament\Resources\Baskets\Schemas;

use Filament\Forms\Components\DateTimePicker;
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

class BasketForm
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
                TextInput::make('slug')->required()->maxLength(255)->alphaDash()->unique(ignoreRecord: true),
                Textarea::make('description')->required()->rows(3)->columnSpanFull(),
                FileUpload::make('image')->image()->disk('public')->directory('baskets')->maxSize(4096)->columnSpanFull(),
            ]),

            Section::make('Price, stock & availability')->columnSpan(1)->schema([
                TextInput::make('price')->required()->numeric()->minValue(0)->prefix('KES')
                    ->helperText('Sold as one item at this price.'),
                TextInput::make('stock')->required()->integer()->minValue(0)->default(0)
                    ->helperText('Baskets have their own stock; contents are not deducted.'),
                DateTimePicker::make('effective_from')->label('Available from')->required()->default(now()),
                DateTimePicker::make('effective_to')->label('Available until')->required()->after('effective_from'),
                Toggle::make('is_active')->label('Visible in shop')->default(true),
            ]),

            Section::make('Contents')->columnSpanFull()->schema([
                Repeater::make('basket_products')
                    ->hiddenLabel()
                    ->relationship()
                    ->columns(3)
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->searchable()->preload()->required()
                            ->distinct()->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                        TextInput::make('quantity')->required()->integer()->minValue(1)->default(1),
                        Toggle::make('is_active')->label('Included')->default(true)->inline(false),
                    ])
                    ->defaultItems(0)
                    ->addActionLabel('Add product'),
            ]),
        ]);
    }
}
