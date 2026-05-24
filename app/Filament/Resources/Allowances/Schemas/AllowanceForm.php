<?php

namespace App\Filament\Resources\Allowances\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AllowanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('description')
                    ->required(),
            ]);
    }
}
