<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use App\Models\AttendanceReport;
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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Carbon;

class PayrollForm
{
     protected static function calculatePayroll(Get $get, Set $set): void
    {
        if (
            ! $get('user_id') ||
            ! $get('start_date') ||
            ! $get('end_date')
        ) {
            return;
        }

        try {
            $payroll = app(PayrollService::class)->generate(
                $get('user_id'),
                $get('start_date'),
                $get('end_date')
            );

            $set('gross_pay', $payroll->gross_pay);
            $set('additions', $payroll->additions);
            $set('deductions', $payroll->deductfions);
            $set('net_pay', $payroll->net_pay);

            $attendance = AttendanceReport::query()
                ->where('user_id', $get('user_id'))
                ->whereDate('start_date', $get('start_date'))
                ->whereDate('end_date', $get('end_date'))
                ->first();

            $set('total_hours', $attendance?->total_hours ?? 0);
            $set('total_overtime', $attendance?->total_overtime ?? 0);
            $set('total_late', $attendance?->total_late ?? 0);

        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Section::make('Payroll')
                        ->description('Payroll details for the selected employee and period')
                        ->schema([
                            Select::make('attendance_report_id')
                                ->label('Periode Absensi')
                                ->options(
                                    AttendanceReport::with('user')
                                        ->get()
                                        ->mapWithKeys(fn ($report) => [
                                            $report->id =>
                                                $report->user->name .
                                                ' | ' .
                                                $report->start_date .
                                                ' - ' .
                                                $report->end_date
                                        ])
                                )
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set) {

                                    $attendanceReport = AttendanceReport::find($state);

                                    if (! $attendanceReport) {
                                        return;
                                    }

                                    $payroll = app(PayrollService::class)
                                        ->generate($attendanceReport);

                                    $set('gross_pay', $payroll->gross_pay);
                                    $set('additions', $payroll->additions);
                                    $set('deductions', $payroll->deductions);
                                    $set('net_pay', $payroll->net_pay);

                                    $set('total_hours', $attendanceReport->total_hours);
                                    $set('total_overtime', $attendanceReport->total_overtime);
                                    $set('total_late', $attendanceReport->total_late);
                                })
                                ->helperText('Select an attendance period.'),

                            TextInput::make('gross_pay')
                                ->numeric()
                                ->readOnly()
                                ->helperText('Total gross salary.'),

                            TextInput::make('additions')
                                ->numeric()
                                ->readOnly()
                                ->helperText('Additional earnings.'),

                            TextInput::make('deductions')
                                ->numeric()
                                ->readOnly()
                                ->helperText('Salary deductions.'),

                            TextInput::make('net_pay')
                                ->numeric()
                                ->readOnly()
                                ->helperText('Total net salary.'),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),

                    Section::make('Attendance')
                        ->description('Attendance details for the selected period')
                        ->schema([
                            TextInput::make('total_hours')
                                ->readOnly()
                                ->live()
                                ->helperText('Total working hours.'),

                            TextInput::make('total_overtime')
                                ->readOnly()
                                ->live()
                                ->helperText('Total overtime hours.'),

                            TextInput::make('total_late')
                                ->readOnly()
                                ->live()
                                ->helperText('Total late hours.'),
                        ])
                        ->columns(3),

                    Section::make('Status')
                        ->description('Set the payroll status')
                        ->schema([
                        DateTimePicker::make('generated_at')
                            ->default(now())
                            ->disabled()
                            ->helperText('Payroll generation date.'),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'generated' => 'Generated',
                                'paid' => 'Paid',
                            ])
                            ->required()
                            ->helperText('Select the payroll status.'),
                        ])
                        ->columns(2),
                            
                
            ]);
    }
}