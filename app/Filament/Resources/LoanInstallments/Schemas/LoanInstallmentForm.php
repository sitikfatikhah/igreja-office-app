<?php

namespace App\Filament\Resources\LoanInstallments\Schemas;

use App\Models\EmployeeLoan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LoanInstallmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_loan_id')
                    ->label('Employee Loan')
                    ->options(
                        EmployeeLoan::with('user')
                            ->get()
                            ->mapWithKeys(fn (EmployeeLoan $loan) => [
                                $loan->id => sprintf(
                                    '%s — Loan #%s (%s)',
                                    $loan->user?->name,
                                    $loan->id,
                                    $loan->status,
                                ),
                            ])
                    )
                    ->searchable()
                    ->required(),
                TextInput::make('amount')
                    ->label('Installment Amount')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                TextInput::make('status')
                    ->label('Status')
                    ->nullable(),
                DatePicker::make('deducted_at')
                    ->label('Deducted At')
                    ->required(),
            ]);
    }
}
