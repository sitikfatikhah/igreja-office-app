<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Attendance Verification')
                    ->description('Allow camera and location access, then verify your face to complete attendance.')
                    ->schema([
                        TextInput::make('nip')
                            ->label('NIP')
                            ->default(fn () => auth()->user()?->nip)
                            ->disabled()
                            ->helperText('Employee ID number.'),

                        TextInput::make('position')
                            ->label('Position')
                            ->default(fn () => auth()->user()?->position)
                            ->disabled()
                            ->helperText('Current job position.'),

                        View::make('filament.forms.face-attendance-script')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Hidden::make('user_id')
                    ->default(fn () => auth()->id())
                    ->dehydrated(),

                Hidden::make('date')
                    ->default(fn () => now()->toDateString())
                    ->dehydrated(),

                Hidden::make('latitude')
                    ->default(null)
                    ->dehydrated(),

                Hidden::make('longitude')
                    ->default(null)
                    ->dehydrated(),

                Hidden::make('location_name')
                    ->default(null)
                    ->dehydrated(),

                Hidden::make('verification_score')
                    ->dehydrated(),

                Hidden::make('verification_method')
                    ->default('face_recognition')
                    ->dehydrated(),

                Hidden::make('face_verified')
                    ->default(false)
                    ->dehydrated(),
            ]);
    }
}