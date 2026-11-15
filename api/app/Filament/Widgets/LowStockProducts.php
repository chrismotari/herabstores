<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LowStockProducts extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Low stock';

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->where('is_active', true)->where('stock', '<=', Product::LOW_STOCK_THRESHOLD)->orderBy('stock'))
            ->defaultPaginationPageOption(5)
            ->recordUrl(fn (Product $record) => ProductResource::getUrl('edit', ['record' => $record]))
            ->emptyStateHeading('Everything is well stocked')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('category.name'),
                TextInputColumn::make('stock')->type('number')->rules(['required', 'integer', 'min:0'])->label('Stock (edit)'),
            ]);
    }
}
