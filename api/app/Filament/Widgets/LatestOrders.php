<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestOrders extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->with('deliveryZone')->latest()->limit(8))
            ->paginated(false)
            ->recordUrl(fn (Order $record) => OrderResource::getUrl('view', ['record' => $record]))
            ->columns([
                TextColumn::make('order_number')->label('Order'),
                TextColumn::make('created_at')->label('Placed')->since(),
                TextColumn::make('customer')->state(fn (Order $record) => trim("{$record->fname} {$record->lname}")),
                TextColumn::make('deliveryZone.name')->label('Zone'),
                TextColumn::make('amount')->label('Total')
                    ->state(fn (Order $record) => $record->amount_due)->money('KES'),
                TextColumn::make('status')->badge(),
            ]);
    }
}
