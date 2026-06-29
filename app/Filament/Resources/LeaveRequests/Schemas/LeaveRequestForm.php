<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Leave Request Details')
                    ->label('Leave Request Details')
                    ->schema([
                        Select::make('user_id')
                            ->label('User ID')
                            ->default(Auth::id())
                            ->options(fn () =>User::pluck('name', 'id'))
                            ->relationship(
                                name:'user',
                                titleAttribute:'name',
                                modifyQueryUsing: fn (Builder $query)=> $query->whereHas('roles', fn (Builder $query) => $query->where('name', 'user'))
                            )
                            ->disabled(fn() => auth()->user()->hasRole('user')) // Disable for regular users
                            ->required(),
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
                                ->required(),
                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->required(),
                        TextInput::make('total_days')
                            ->label('Total Days')
                            ->fn(getStateUsing: fn (callable $get) => optional($get('start_date'))->diffInDays(optional($get('end_date'))) + 1)
                            ->disabled()
                            ->required(),
                        Textarea::make('reason')
                            ->label('Reason for Leave')
                            ->required(),
                        Select::make('approval_status')
                            ->label('Approval Status')
                            ->options([
                                'Pending' => 'Pending',
                                'Approved' => 'Approved',
                                'Rejected' => 'Rejected',
                            ])
                            ->default('Pending')
                            ->disabled(fn() => auth()->user()->hasRole('user')) // Disable for regular users
                            ->required(),
                    ]),
            ]);
    }
}
