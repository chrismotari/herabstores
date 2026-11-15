<?php

namespace App\Filament\Resources\Offers\Tables;

use App\Enums\OrderStatus;
use App\Models\Offer;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class OffersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with(['category', 'product'])
                ->withCount(['orders as uses' => fn ($q) => $q->where('status', '!=', OrderStatus::Canceled)])
                ->withSum(['orders as discount_given' => fn ($q) => $q->where('status', '!=', OrderStatus::Canceled)], 'discount'))
            ->columns([
                TextColumn::make('code')->searchable()->copyable()->weight('bold')
                    ->description(fn (Offer $record) => $record->name),
                TextColumn::make('discount')->state(fn (Offer $record) => $record->discountLabel()),
                TextColumn::make('applies')->label('Applies to')->state(fn (Offer $record) => $record->appliesToLabel()),
                TextColumn::make('end_at')->label('Ends')->date()->placeholder('No end')->sortable()
                    ->color(fn (Offer $record) => $record->end_at?->isPast() ? 'danger' : null),
                TextColumn::make('uses')->state(fn (Offer $record) => $record->uses.($record->max_uses ? ' / '.$record->max_uses : '')),
                TextColumn::make('discount_given')->label('Discount given')->money('KES')->placeholder('—'),
                ToggleColumn::make('is_active')->label('Active'),
            ])
            ->filters([TernaryFilter::make('is_active')->label('Active')])
            ->recordActions([EditAction::make()]);
    }
}
