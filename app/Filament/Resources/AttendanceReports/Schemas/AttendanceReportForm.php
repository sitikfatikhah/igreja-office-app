<?php

namespace App\Filament\Resources\AttendanceReports\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttendanceReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('user_id')
                    ->label('Employee')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('start_date')
                    ->required(),

                DatePicker::make('end_date')
                    ->required(),

                TextInput::make('total_hours')
                    ->label('Total Working Hours')
                    ->numeric()
                    ->suffix('hrs')
                    ->required(),

                TextInput::make('total_overtime')
                    ->label('Total Overtime')
                    ->numeric()
                    ->suffix('hrs'),

                TextInput::make('total_late')
                    ->label('Total Late')
                    ->numeric()
                    ->suffix('hrs'),

                Select::make('status')
                    ->options([
                        'present' => 'Present',
                        'late' => 'Late',
                        'overtime' => 'Overtime',
                        'absent' => 'Absent',
                    ])
                    ->required(),

                DatePicker::make('report_date')
                    ->label('Report Date')
                    ->default(now())
                    ->required(),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}