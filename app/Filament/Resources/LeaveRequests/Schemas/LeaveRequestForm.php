<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;

use App\Models\LeaveBalance;
use App\Models\LeaveBalances;
use App\Models\LeaveDepositBalance;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Leave Request Details')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->default(fn () => auth()->id())
                            ->disabled(fn () => auth()->user()->hasRole('user'))
                            ->dehydrated()
                            ->live()
                            ->required()
                            ->helperText('Choose employee.'),
                        Select::make('source')
                            ->label('Source')
                            ->options([
                                'annual_leave' => 'Annual Leave',
                                'time_bank'    => 'Deposit Time Bank',
                            ])
                            ->live()
                            ->required()
                            ->afterStateUpdated(fn (Set $set) => $set('leave_deposit_balance_id', null))
                            ->helperText(function (Get $get) {
                                $userId = $get('user_id');

                                if (! $userId) {
                                    return 'Source of leave request.';
                                }

                                $source = $get('source');

                                if (! $source) {
                                    return 'Choose a leave source.';
                                }

                                $startDate = filled($get('start_date'))
                                    ? \Carbon\Carbon::parse($get('start_date'))
                                    : now();

                                $balance = app(\App\Services\LeaveDeductionService::class)
                                    ->remainingBalance(
                                        $userId,
                                        $source,
                                        $startDate
                                    );

                                $label = $source === 'annual_leave'
                                    ? 'Annual Leave'
                                    : 'Deposit Time Bank';

                                    return "{$label} available: {$balance} days.";
                                }),

                        Select::make('leave_type')
                            ->label('Leave Type')
                            ->options([
                                'Annual' => 'Annual Leave',
                                'Maternity' => 'Maternity Leave',
                                'Paternity' => 'Paternity Leave',
                                'Marriage' => 'Marriage Leave',
                                'Bereavement' => 'Bereavement Leave',
                                'Emergency' => 'Emergency Leave',
                            ])
                            ->disabled(fn (Get $get) => $get('source') === 'time_bank')
                            ->helperText('Leave type.'),

                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::recalcDays($get, $set))
                            ->required(),

                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::recalcDays($get, $set))
                            ->required(),

                        TextInput::make('total_days')
                            ->label('Total Days')
                            ->numeric()
                            ->readOnly()
                            ->dehydrated()
                            ->helperText('Total days of leave.'),

                        

                        // Select::make('leave_deposit_balance_id')
                        //     ->label('Deposit Time Bank')
                        //     ->options(function (Get $get) {
                        //         $userId = $get('user_id');
                        //         if (! $userId) return [];

                        //         return LeaveDepositBalance::where('user_id', $userId)
                        //             ->where('balanced', '>', 0)
                        //             ->get()
                        //             ->mapWithKeys(fn ($d) => [
                        //                 $d->id => "{$d->description} (sisa {$d->balanced} hari)",
                        //             ]);
                        //     })
                        //     ->requiredIf('source', 'time_bank') // setara required_if
                        //     ->rule('exists:leave_deposit_balances,id')
                        //     ->visible(fn (Get $get) => $get('source') === 'time_bank')
                        //     ->helperText('Choose deposit.'),

                        Textarea::make('reason')
                            ->label('Reason for Leave')
                            ->required()
                            ->helperText('Reason for leave.'),

                        Select::make('approval_status')
                            ->label('Approval Status')
                            ->options([
                                'Pending'  => 'Pending',
                                'Approved' => 'Approved',
                                'Rejected' => 'Rejected',
                            ])
                            ->default('Pending')
                            ->disabled(fn () => auth()->user()->hasRole('user'))
                            ->required()
                            ->helperText('Approval status.'),
                    ]),
            ]);
    }

    private static function recalcDays(Get $get, Set $set): void
    {
        $start = $get('start_date');
        $end = $get('end_date');

        if ($start && $end) {
            $days = Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1;
            $set('total_days', $days);
        }
    }
}