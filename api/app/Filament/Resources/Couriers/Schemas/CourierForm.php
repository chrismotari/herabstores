<?php

namespace App\Filament\Resources\Couriers\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->columnSpanFull()->schema([
                Select::make('user_id')
                    ->label('Person')
                    ->relationship('user', 'email')
                    ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->getFilamentName()} ({$record->phone})")
                    ->searchable(['fname', 'lname', 'email', 'phone'])
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('The courier needs a user account.'),
                Select::make('vehicle_type')->options(['bike' => 'Motorbike', 'car' => 'Car', 'van' => 'Van', 'truck' => 'Truck', 'bicycle' => 'Bicycle']),
                TextInput::make('vehicle_number')->label('Registration')->maxLength(20),
                Toggle::make('is_active')->label('Available for dispatch')->default(true)->inline(false),
            ]),
        ]);
    }
}
