<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['deliveryZone', 'courier.user']))
            ->columns([
                TextColumn::make('order_number')->label('Order')->searchable()->copyable(),
                TextColumn::make('created_at')->label('Placed')->since()->sortable()
                    ->tooltip(fn (Order $record) => $record->created_at?->toDayDateTimeString()),
                TextColumn::make('customer')
                    ->state(fn (Order $record) => trim("{$record->fname} {$record->lname}"))
                    ->description(fn (Order $record) => $record->phone)
                    ->searchable(['fname', 'lname', 'phone', 'email']),
                TextColumn::make('deliveryZone.name')->label('Zone')->toggleable(),
                TextColumn::make('amount')->label('Total')
                    ->state(fn (Order $record) => $record->amount_due)
                    ->money('KES'),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('courier.name')->label('Courier')->placeholder('—')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(OrderStatus::class)->multiple(),
                SelectFilter::make('delivery_zone')->label('Zone')->relationship('deliveryZone', 'name'),
                Filter::make('placed')
                    ->schema([DatePicker::make('from'), DatePicker::make('until')])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                        ->when($data['until'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '<=', $d))),
            ])
            ->recordActions([ViewAction::make()]);
    }
}
