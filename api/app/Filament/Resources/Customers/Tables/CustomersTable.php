<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->withCount('orders')->withSum('orders as orders_value', 'total'))
            ->columns([
                TextColumn::make('name')->state(fn (User $record) => $record->getFilamentName())
                    ->description(fn (User $record) => $record->email)
                    ->searchable(['fname', 'lname', 'email', 'phone']),
                TextColumn::make('phone'),
                TextColumn::make('orders_count')->label('Orders')->sortable(),
                TextColumn::make('orders_value')->label('Spent (items)')->money('KES')->sortable()->placeholder('—'),
                TextColumn::make('created_at')->label('Joined')->since()->sortable(),
                ToggleColumn::make('is_active')->label('Active'),
            ])
            ->filters([TernaryFilter::make('is_active')->label('Active')])
            ->recordActions([EditAction::make()]);
    }
}
