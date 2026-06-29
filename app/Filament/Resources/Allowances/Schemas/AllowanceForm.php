<?php

namespace App\Filament\Resources\Allowances\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AllowanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->label('Nama')
                    ->required(),

                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
            ]);
    }
}
