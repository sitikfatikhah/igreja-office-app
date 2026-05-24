<?php

namespace App\Filament\Resources\Compensation\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompensationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('basic_salary')
                    ->label('Basic Salary')
                    ->required(),
                TextInput::make('position_allowance')
                    ->label('Position Allowance')
                    ->required(),
                TextInput::make('transport_allowance')
                    ->label('Transport Allowance')
                    ->required(),
                TextInput::make('meal_allowance')
                    ->label('Meal Allowance')
                    ->required(),
                TextInput::make('communication_allowance')
                    ->label('Communication Allowance')
                    ->required(),
                TextInput::make('health_benefit')
                    ->label('Health Benefit')
                    ->required(),
                TextInput::make('insurance_benefit')
                    ->label('Insurance Benefit')
                    ->required(),
                TextInput::make('retirement_benefit')
                    ->label('Retirement Benefit')
                    ->required(),
                TextInput::make('effective_date')
                    ->label('Effective Date')
                    ->required(),
                TextInput::make('end_date')
                    ->label('End Date')
                    ->required(),
                TextInput::make('is_active')
                    ->label('Is Active')
                    ->required(),
                TextInput::make('notes')
                    ->label('Notes')
                    ->nullable(),
            ]);
    }
}
