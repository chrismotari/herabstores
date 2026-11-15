<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                ImageColumn::make('image_url')->label('')->disk('public')->square(),
                TextColumn::make('name')->searchable()->sortable()
                    ->description(fn (Product $record) => $record->slug),
                TextColumn::make('category.name')->sortable()->toggleable(),
                TextColumn::make('price')->money('KES')->sortable(),
                TextColumn::make('stock')->numeric()->sortable()->badge()
                    ->color(fn (Product $record) => match ($record->is_in_stock) {
                        'Out of Stock' => 'danger',
                        'Few Items Left' => 'warning',
                        default => 'success',
                    }),
                ToggleColumn::make('is_active')->label('Visible'),
                TextColumn::make('updated_at')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')->relationship('category', 'name')->searchable()->preload(),
                TernaryFilter::make('is_active')->label('Visible in shop'),
                Filter::make('low_stock')->label('Low or out of stock')
                    ->query(fn (Builder $query) => $query->where('stock', '<=', Product::LOW_STOCK_THRESHOLD)),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('show')->label('Show in shop')->icon('heroicon-o-eye')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('hide')->label('Hide from shop')->icon('heroicon-o-eye-slash')->color('warning')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
