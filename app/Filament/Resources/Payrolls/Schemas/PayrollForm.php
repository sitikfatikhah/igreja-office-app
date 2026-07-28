<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use App\Models\Allowance;
use App\Models\AttendanceReport;
use App\Models\LoanInstallments;
use App\Models\User;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Services\PayrollService;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Carbon;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payroll')
                    ->schema([

                        Section::make('Attendance')
                            ->description('Select attendance period to generate payroll.')
                            ->schema([
                                Select::make('attendance_report_id')
                                    ->label('Attendance Period')
                                    ->options(
                                        AttendanceReport::with('user')
                                            ->get()
                                            ->mapWithKeys(fn ($report) => [
                                                $report->id => sprintf(
                                                    '%s | %s - %s',
                                                    $report->user->name,
                                                    $report->start_date,
                                                    $report->end_date,
                                                ),
                                            ])
                                    )
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {

                                        if (! $state) {
                                            return;
                                        }

                                        $report = AttendanceReport::with([
                                            'user.compensation',
                                            'user.allowance',
                                            'user.employeeLoan.installments',
                                        ])->find($state);

                                        if (! $report) {
                                            return;
                                        }

                                        $user = $report->user;

                                        $basicSalary = optional($user->compensation)->basic_salary ?? 0;

                                        $allowanceTotal = $user->allowance->sum('amount');

                                        $overtimeTotal = 0;

                                        $loanTotal = $user->employeeLoan
                                            ->flatMap->installments
                                            ->where('status', 'pending')
                                            ->sum('amount');

                                        $grossPay = $basicSalary
                                            + $allowanceTotal
                                            + $overtimeTotal;

                                        $deductionTotal = $loanTotal;

                                        $netPay = $grossPay - $deductionTotal;

                                        $set('user_id', $user->id);
                                        $set('start_date', $report->start_date);
                                        $set('end_date', $report->end_date);

                                        $set('gross_pay', $grossPay);
                                        $set('addition_total', $allowanceTotal);
                                        $set('deduction_total', $deductionTotal);
                                        $set('loan_total', $loanTotal);
                                        $set('overtime_total', $overtimeTotal);
                                        $set('net_pay', $netPay);

                                        $set('total_present', $report->total_present);
                                        $set('total_absent', $report->total_absent);
                                        $set('total_overtime', $report->total_overtime);
                                    })
                                    ->helperText('Select an attendance period.'),
                            ]),

                        Section::make('Status')
                            ->description('Payroll status')
                            ->schema([
                                DateTimePicker::make('generated_at')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->helperText('Payroll generation date.'),

                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'generated' => 'Generated',
                                        'paid' => 'Paid',
                                    ])
                                    ->required(),
                            ])
                            ->columns(2),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                     
                   Section::make('Additional Earnings')
                        ->description('Additional Earnings')
                        ->schema([
                            TextInput::make('deduction_total')
                                ->numeric()
                                ->prefix('Rp')
                                ->readOnly(),
                            TextInput::make('addition_total')
                                ->numeric()
                                ->prefix('Rp')
                                ->readOnly(),
                            TextInput::make('loan_total')
                                ->numeric()
                                ->prefix('Rp')
                                ->readOnly(),
                            TextInput::make('overtime_total')
                                ->numeric()
                                ->prefix('Rp')
                                ->readOnly(),
                            TextInput::make('gross_pay')
                                ->numeric()
                                ->readOnly()
                                ->helperText('Total gross salary.')
                                ->readOnly(),
                            TextInput::make('net_pay')
                                    ->numeric()
                                    ->readOnly()
                                    ->helperText('Total net salary.')
                                    ->readOnly(),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),
            ]);
    }
}