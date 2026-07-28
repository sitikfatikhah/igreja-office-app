<?php

namespace App\Filament\Resources\EmployeeWorkSchedules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeWorkScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(fn () => auth()->id())
                    ->disabled(fn () => auth()->user()->hasRole('user'))
                    ->dehydrated()
                    ->live()
                    ->required()
                    ->helperText('Choose employee.'),
                DatePicker::make('effective_from'),
                DatePicker::make('effective_untill'),
                Select::make('off_days')
                    ->multiple()
                    ->options([
                        'monday' => 'Monday',
                        'tuesday' => 'Tuesday',
                        'wednesday' => 'Wednesday',
                        'thursday' => 'Thursday',
                        'friday' => 'Friday',
                        'saturday' => 'Saturday',
                        'sunday' => 'Sunday',
                    ]),
                TextInput::make('remarks'),
            ]);
    }
}
