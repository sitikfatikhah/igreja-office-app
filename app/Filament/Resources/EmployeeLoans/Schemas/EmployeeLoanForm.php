<?php

namespace App\Filament\Resources\EmployeeLoans\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EmployeeLoanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee Loan')
                    ->schema([

                        Select::make('user_id')
                            ->label('Employee')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('total_amount')
                            ->label('Total Loan')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                // dd($state);
                                $total = (float) ($get('total_amount') ?? 0);
                                $count = max((int) ($get('installment_count') ?? 1), 1);

                                $set(
                                    'installment_amount',
                                    round($total / $count, 2)
                                );
                            }),

                        TextInput::make('installment_count')
                            ->label('Installment Count')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {

                                $total = (float) ($get('total_amount') ?? 0);
                                $count = max((int) ($get('installment_count') ?? 1), 1);

                                $set(
                                    'installment_amount',
                                    round($total / $count, 2)
                                );
                            }),

                        TextInput::make('installment_amount')
                            ->label('Installment / Month')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->dehydrated(),

                        DatePicker::make('start_date')
                            ->label('Loan Date')
                            ->default(now())
                            ->required(),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}