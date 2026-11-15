<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Section::make('Items')->columnSpan(2)->schema([
                RepeatableEntry::make('order_items')->hiddenLabel()->columns(4)->schema([
                    TextEntry::make('name')->label('Item'),
                    TextEntry::make('quantity')->label('Qty'),
                    TextEntry::make('price')->label('Unit price')->money('KES'),
                    TextEntry::make('type')->label('Type')->badge()->default('product'),
                ]),
                TextEntry::make('total')->label('Subtotal')->money('KES')->inlineLabel(),
                TextEntry::make('discount')->label(fn (Order $record) => 'Discount ('.$record->discount_code.')')->money('KES')->inlineLabel()
                    ->visible(fn (Order $record) => (float) $record->discount > 0),
                TextEntry::make('shipping_fee')->label('Delivery')->money('KES')->inlineLabel(),
                TextEntry::make('amount_due')->label('Total due on delivery')->inlineLabel()
                    ->money('KES')->weight('bold'),
            ]),

            Section::make('Status')->columnSpan(1)->schema([
                TextEntry::make('order_number')->copyable(),
                TextEntry::make('status')->badge(),
                TextEntry::make('payment_status')->badge()->color(fn ($state) => $state === 'paid' ? 'success' : 'gray'),
                TextEntry::make('created_at')->label('Placed')->dateTime(),
                TextEntry::make('courier.name')->placeholder('Not dispatched'),
                TextEntry::make('store.name')->label('From store')->placeholder('—'),
                TextEntry::make('shipped_at')->label('Dispatched')->dateTime()->placeholder('—'),
                TextEntry::make('delivered_at')->label('Delivered')->dateTime()->placeholder('—'),
                TextEntry::make('canceled_at')->label('Canceled')->dateTime()->placeholder('—')
                    ->visible(fn (Order $record) => $record->canceled_at !== null),
            ]),

            Section::make('Delivery')->columnSpanFull()->columns(3)->schema([
                TextEntry::make('customer')->state(fn (Order $record) => trim("{$record->fname} {$record->lname}")),
                TextEntry::make('phone')->copyable(),
                TextEntry::make('alt_phone')->placeholder('—'),
                TextEntry::make('email')->placeholder('—'),
                TextEntry::make('address'),
                TextEntry::make('city'),
                TextEntry::make('deliveryZone.name')->label('Zone'),
                TextEntry::make('user.email')->label('Account')->placeholder('Guest checkout'),
                TextEntry::make('message')->label('Notes')->placeholder('—')->columnSpanFull(),
            ]),
        ]);
    }
}
