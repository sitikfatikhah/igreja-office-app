<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User ID')
                    ->options(fn () =>User::pluck('name', 'id'))
                    ->live()
                   ->required(),
                DatePicker::make('pay_period')
                    ->date()
                    ->format('Y-m')
                    ->displayFormat('F Y')
                    ->label('Pay Period')
                    ->required(), 
                TextInput::make('gross_pay')
                    ->numeric()
                    ->label('Gross Pay')
                    ->required(),
                TextInput::make('deductions')
                    ->numeric()
                    ->label('Deductions')
                    ->required(),
                TextInput::make('additions')
                    ->numeric()
                    ->label('Additions')
                    ->required(),
                TextInput::make('net_pay')
                    ->numeric()
                    ->label('Net Pay')
                    ->required(),
                DateTimePicker::make('generated_at')
                    ->label('Generated At')
                    ->live()
                    ->required(),
                TextInput::make('status')
                    ->label('Status')
                    ->required(),
                
            ]);
    }
}
