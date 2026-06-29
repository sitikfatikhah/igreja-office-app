<?php

namespace App\Filament\Resources\Overtimes\Schemas;

use App\Models\User;
use Carbon\Carbon;
use Dom\Text;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class OvertimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Select::make('user_id')
                        ->label('User')
                        ->options(fn () => User::pluck('name', 'id'))
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $userId = $get('user_id');
                            $user = User::find($userId);
                            $set('position', $user?->position);
                        })
                        ->required()
                        ->helperText('Select an employee.'),

                    TextInput::make('position')
                        ->label('Position')
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->helperText('Employee position.'),

                    DatePicker::make('overtime_date')
                        ->label('Overtime Date')
                        ->date()
                        ->required()
                        ->helperText('Select the overtime date.'),

                    TextInput::make('total_hours')
                        ->label('Total Hours')
                        ->disabled()
                        ->numeric()
                        ->required()
                        ->helperText('Calculated overtime hours.'),

                    TimePicker::make('start_time')
                        ->label('Start Time')
                        ->time()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            static::calculateTotalHours($get, $set);
                        })
                        ->required()
                        ->helperText('Select the start time.'),

                    TimePicker::make('end_time')
                        ->label('End Time')
                        ->time()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            static::calculateTotalHours($get, $set);
                        })
                        ->required()
                        ->helperText('Select the end time.'),

                    TextInput::make('description')
                        ->label('Description')
                        ->required()
                        ->helperText('Enter the work description.'),

                    Select::make('approval_status')
                        ->label('Approval Status')
                        ->options([
                            'Pending' => 'Pending',
                            'Approved' => 'Approved',
                            'Rejected' => 'Rejected',
                        ])
                        ->required()
                        ->helperText('Select the approval status.'),

                    TextInput::make('approved_by')
                        ->default(fn () => auth()->user()?->name)
                        ->disabled()
                        ->label('Approved By')
                        ->helperText('Approver name.'),

                    Textarea::make('reason')
                        ->rows(3)
                        ->autosize()
                        ->label('Reason')
                        ->helperText('Enter the reason for overtime.'),
            ]);
    }

    // Tambahkan method ini di class OvertimeForm

    protected static function calculateTotalHours(Get $get, Set $set): void
    {
        $startTime = $get('start_time');
        $endTime = $get('end_time');

        if (blank($startTime) || blank($endTime)) {
            $set('total_hours', 0);
            return;
        }

        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        // Jika melewati tengah malam
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        $totalHours = round(
            $start->diffInMinutes($end) / 60,
            2
        );

        $set('total_hours', $totalHours);
    }   
}
