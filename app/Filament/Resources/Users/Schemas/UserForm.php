<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use PhpOption\Option;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Please enter a valid email address.'),
                
                TextInput::make('nip')
                    ->label('NIP')
                    ->required(),

                Select::make('position')
                    ->label('Position')
                    ->required()
                    ->options([
                        'Manager' => 'Manager',
                        'Staff' => 'Staff',
                        'Intern' => 'Intern',
                        'Supervisor' => 'Supervisor',
                        'Director' => 'Director',
                    ]),
                Select::make('department')
                    ->label('Department')
                    ->options([
                        'HR' => 'HR',
                        'IT' => 'IT',
                        'Finance' => 'Finance',
                        'Marketing' => 'Marketing',
                        'Operations' => 'Operations',
                    ])
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
            ]);
    }
}
