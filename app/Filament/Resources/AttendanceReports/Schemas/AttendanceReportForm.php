<?php

namespace App\Filament\Resources\AttendanceReports\Schemas;

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
                    ->required()
                    ->helperText('Select the employee.'),

                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required()
                    ->helperText('Select the report start date.'),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->required()
                    ->helperText('Select the report end date.'),

                TextInput::make('total_days')
                    ->label('Total Working Days')
                    ->numeric()
                    ->suffix('days')
                    ->required()
                    ->helperText('Total number of working days.'),

                TextInput::make('total_overtime')
                    ->label('Total Overtime')
                    ->numeric()
                    ->suffix('hours')
                    ->helperText('Total overtime hours worked.'),

                TextInput::make('total_late')
                    ->label('Total Late Days')
                    ->numeric()
                    ->suffix('days')
                    ->helperText('Total number of late attendance days.'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'present' => 'Present',
                        'late' => 'Late',
                        'overtime' => 'Overtime',
                        'absent' => 'Absent',
                    ])
                    ->required()
                    ->helperText('Select the attendance status.'),

                DatePicker::make('report_date')
                    ->label('Report Date')
                    ->default(now())
                    ->required()
                    ->helperText('Date when the report was generated.'),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->columnSpanFull()
                    ->helperText('Enter additional notes or remarks.'),
            ])
            ->columns(2);
    }
}