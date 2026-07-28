<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
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
                    ->required()
                    ->helperText('Enter the user\'s full name.'),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Enter a valid email address.'),

                TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->helperText('Enter the employee ID number.'),

                Select::make('position')
                    ->label('Position')
                    ->required()
                    ->options([
                        'Manager' => 'Manager',
                        'Staff' => 'Staff',
                        'Intern' => 'Intern',
                        'Supervisor' => 'Supervisor',
                        'Director' => 'Director',
                    ])
                    ->helperText('Select the employee\'s position.'),

                Select::make('department')
                    ->label('Department')
                    ->options([
                        'HR' => 'HR',
                        'IT' => 'IT',
                        'Finance' => 'Finance',
                        'Marketing' => 'Marketing',
                        'Operations' => 'Operations',
                    ])
                    ->required()
                    ->helperText('Select the employee\'s department.'),
                
                
                Select::make('compensation_id')
                    ->relationship('compensation', 'basic_salary')
                    ->label('Compensation')
                    ->preload()
                    ->searchable()
                    ->helperText('Employee Compensation'),

                TextInput::make('password')
                    ->password()
                    ->required()
                    ->revealable()
                    ->helperText('Create a secure password.'),
                CheckboxList::make('allowance')
                    ->relationship('allowance', 'type')
                    ->label('Allowance')
                    ->searchable()
                    ->helperText('Select one or more Allowance'),

                CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->helperText('Select one or more roles.'),

                
                
            ]);
    }
}
