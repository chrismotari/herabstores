<?php

namespace App\Filament\Resources\Contacts\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Received')->since()->sortable(),
                TextColumn::make('name')->searchable()->placeholder('—')
                    ->description(fn ($record) => $record->email ?? $record->phone),
                TextColumn::make('subject')->limit(40)->placeholder('—')->searchable(),
                TextColumn::make('source')->badge(),
                IconColumn::make('consented')->label('Marketing')->boolean(),
            ])
            ->filters([
                SelectFilter::make('source')->options(['Website' => 'Contact form', 'Purchase' => 'Checkout']),
                TernaryFilter::make('consented')->label('Agreed to marketing'),
            ])
            ->recordActions([ViewAction::make(), DeleteAction::make()]);
    }
}
