<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->modifyQueryUsing(fn ($query) => $query->withCount('products'))
            ->columns([
                ImageColumn::make('image_url')->label('')->disk('public')->square(),
                TextColumn::make('name')->searchable()->sortable()
                    ->description(fn (Category $record) => $record->slug),
                TextColumn::make('parent.name')->label('Parent')->placeholder('—'),
                TextColumn::make('products_count')->label('Products')->sortable(),
                ToggleColumn::make('is_active')->label('Visible'),
            ])
            ->filters([TernaryFilter::make('is_active')->label('Visible in shop')])
            ->recordActions([EditAction::make()]);
    }
}
