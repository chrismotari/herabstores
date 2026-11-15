<?php

namespace App\Filament\Resources\Offers\Schemas;

use App\Models\Offer;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class OfferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Section::make('Code')->columnSpan(2)->columns(2)->schema([
                TextInput::make('name')->required()->maxLength(255)->helperText('Shown to customers, e.g. "Welcome offer".'),
                TextInput::make('code')
                    ->required()->maxLength(50)
                    ->regex('/^[A-Za-z0-9_-]+$/')
                    ->unique(ignoreRecord: true)
                    ->dehydrateStateUsing(fn (string $state) => strtoupper(trim($state)))
                    ->helperText('Letters, numbers, - and _. Not case-sensitive.'),
                Radio::make('discount_type')
                    ->options([Offer::PERCENT => 'Percentage', Offer::FIXED => 'Fixed amount (KES)'])
                    ->default(Offer::PERCENT)->required()->inline()->live(),
                TextInput::make('value')
                    ->required()->numeric()->minValue(0.01)
                    ->maxValue(fn (Get $get) => $get('discount_type') === Offer::PERCENT ? 100 : null)
                    ->prefix(fn (Get $get) => $get('discount_type') === Offer::FIXED ? 'KES' : null)
                    ->suffix(fn (Get $get) => $get('discount_type') === Offer::PERCENT ? '%' : null),
                Textarea::make('description')->rows(2)->columnSpanFull(),
            ]),

            Section::make('Availability')->columnSpan(1)->schema([
                Toggle::make('is_active')->label('Active')->default(true),
                DateTimePicker::make('start_at')->label('Starts')->default(now())->required(),
                DateTimePicker::make('end_at')->label('Ends')->after('start_at')->helperText('Leave empty for no end date.'),
            ]),

            Section::make('Applies to')->columnSpan(2)->schema([
                Radio::make('applies_to')
                    ->options(['cart' => 'Whole cart', 'category' => 'One category', 'product' => 'One product'])
                    ->default('cart')->inline()->live()->dehydrated(false)
                    ->afterStateHydrated(fn (Radio $component, ?Offer $record) => $component->state(
                        $record?->product_id ? 'product' : ($record?->category_id ? 'category' : 'cart')
                    ))
                    ->afterStateUpdated(function (Set $set) {
                        $set('category_id', null);
                        $set('product_id', null);
                    }),
                Select::make('category_id')->label('Category')->relationship('category', 'name')->searchable()->preload()
                    ->visible(fn (Get $get) => $get('applies_to') === 'category')->required(fn (Get $get) => $get('applies_to') === 'category'),
                Select::make('product_id')->label('Product')->relationship('product', 'name')->searchable()->preload()
                    ->visible(fn (Get $get) => $get('applies_to') === 'product')->required(fn (Get $get) => $get('applies_to') === 'product'),
                TextInput::make('min_order_value')->label('Minimum order (cart subtotal)')->numeric()->minValue(0)->prefix('KES'),
                Placeholder::make('note')->hiddenLabel()->content('The delivery fee is never discounted. One code per order.'),
            ]),

            Section::make('Limits')->columnSpan(1)->schema([
                TextInput::make('max_uses')->label('Total uses')->integer()->minValue(1)->helperText('Empty = unlimited. Canceled orders don\'t count.'),
                TextInput::make('max_uses_per_user')->label('Uses per customer')->integer()->minValue(1)->helperText('Guests are counted by phone number.'),
            ]),
        ]);
    }
}
