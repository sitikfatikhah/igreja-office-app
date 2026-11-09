<?php

namespace App\Filament\Resources\AnnualLeaveBalances\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class AnnualLeaveBalanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(fn () => auth()->id())
                    ->disabled(fn () => auth()->user()->hasRole('user'))
                    ->dehydrated()
                    ->live()
                    ->required()
                    ->helperText('Choose employee.'),
                Select::make('year')
                    ->label('Year')
                    ->default(fn () => now()->year)
                    ->disabled(fn () => auth()->user()->hasRole('user'))
                    ->options(
                        array_combine(
                            range(now()->year - 10, now()->year + 5),
                            range(now()->year - 10, now()->year + 5)
                        )
                    )
                    ->dehydrated()
                    ->required()
                    ->helperText('Year of the leave balance.'),
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'credit' => 'Credit',
                        'debit' => 'Debit',
                        'balance' => 'Balance',
                    ])
                    ->default('balance')
                    ->live()
                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                        if ($state === 'balance') {
                            $set('balanced', $get('days'));
                        }
                    })
                    ->required()
                    ->helperText("Saldo type. 'Credit' adds days, 'Debit' subtracts days, and 'Balance' sets the remaining balance."),
                Select::make('leave_type')
                    ->label('Leave Type')
                    ->options([
                        'Annual Leave' => 'Annual Leave',
                        'Maternity Leave' => 'Maternity Leave',
                        'Paternity Leave' => 'Paternity Leave',
                        'Marriage Leave' => 'Marriage Leave',
                        'Bereavement Leave' => 'Bereavement Leave',
                        'Emergency Leave' => 'Emergency Leave',
                    ])
                    ->default('Annual Leave')
                    ->disabled(fn () => auth()->user()->hasRole('user'))
                    ->dehydrated()
                    ->required()
                    ->helperText('Type of leave.'),
                TextInput::make('days')
                    ->label('Days')
                    ->numeric()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($get('type') === 'balance') {
                            $set('balanced', $state);
                        }
                    }),
                TextInput::make('balanced')
                    ->label('Remaining Balance')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->dehydrated(),
            ]);
    }
}