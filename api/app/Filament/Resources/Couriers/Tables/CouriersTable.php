<?php

namespace App\Filament\Resources\Couriers\Tables;

use App\Enums\OrderStatus;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CouriersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user')
                ->withCount(['orders as active_deliveries' => fn ($q) => $q->where('status', OrderStatus::Dispatched)]))
            ->columns([
                TextColumn::make('name')->searchable(query: fn ($query, $search) => $query->whereHas('user', fn ($u) => $u
                    ->where('fname', 'ilike', "%{$search}%")->orWhere('lname', 'ilike', "%{$search}%")->orWhere('phone', 'ilike', "%{$search}%"))),
                TextColumn::make('user.phone')->label('Phone'),
                TextColumn::make('vehicle_type')->label('Vehicle')->badge(),
                TextColumn::make('vehicle_number')->label('Registration'),
                TextColumn::make('active_deliveries')->label('Out for delivery'),
                ToggleColumn::make('is_active')->label('Available'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
