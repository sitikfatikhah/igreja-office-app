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

                                    //  dd([
                                    //     'id' => $attendanceReport?->id,
                                    //     'hours' => $attendanceReport?->total_hours,
                                    //     'overtime' => $attendanceReport?->total_overtime,
                                    //     'late' => $attendanceReport?->total_late,
                                    // ]);

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
                                }),

                            TextInput::make('gross_pay')
                                ->numeric()
                                // ->formatStateUsing(
                                //     fn ($state) => number_format($state ?? 0, 0, ',', '.')
                                // )
                                ->readOnly(),

                            TextInput::make('additions')
                                ->numeric()
                                // ->formatStateUsing(
                                //     fn ($state) => number_format($state ?? 0, 0, ',', '.')
                                // )
                                ->readOnly(),

                            TextInput::make('deductions')
                                ->numeric()
                                // ->formatStateUsing(
                                //     fn ($state) => number_format($state ?? 0, 0, ',', '.')
                                // )
                                ->readOnly(),

                            TextInput::make('net_pay')
                                ->numeric()
                                // ->formatStateUsing(
                                //     fn ($state) => number_format($state ?? 0, 0, ',', '.')
                                // )
                                ->readOnly(),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),

                    Section::make('Attendance')
                        ->description('Attendance details for the selected period')
                        ->schema([
                            TextInput::make('total_hours')
                                ->readOnly()
                                ->live(),

                            TextInput::make('total_overtime')
                                ->readOnly()
                                ->live(),

                            TextInput::make('total_late')
                                ->readOnly()
                                ->live(),
                        ])
                        ->columns(3),

                    Section::make('Status')
                        ->description('Set the payroll status')
                        ->schema([
                        DateTimePicker::make('generated_at')
                            ->default(now())
                            ->disabled(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'generated' => 'Generated',
                                'paid' => 'Paid',
                            ])
                            ->required(),
                        ])
                        ->columns(2),
                            
                
            ]);
    }
}