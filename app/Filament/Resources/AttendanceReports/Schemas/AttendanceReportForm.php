<?php

namespace App\Filament\Resources\AttendanceReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AttendanceReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('user_id')
                //     ->required()
                //     ->numeric(),
                // TextInput::make('position')
                //     ->required(),
                // TextInput::make('start_time')
                //     ->required()
                //     ->numeric()
                //     ->default(0),
                // TextInput::make('end_time')
                //     ->required()
                //     ->numeric()
                //     ->default(0),
                // TextInput::make('total_hours')
                //     ->required()
                //     ->numeric()
                //     ->default(0),
                // DatePicker::make('date'),
                // TextInput::make('status')
                //     ->required()
                //     ->default('present'),
                // DatePicker::make('report_date'),
                // Textarea::make('description')
                //     ->columnSpanFull(),
            ]);
    }
}
