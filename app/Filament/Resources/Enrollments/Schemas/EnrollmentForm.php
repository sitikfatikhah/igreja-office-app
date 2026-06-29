<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Face Enrollment')
                    ->description('Allow camera access, then capture your face to enroll for attendance verification.')
                    ->schema([
                        TextInput::make('user_name')
                            ->label('Name')
                            ->default(fn () => auth()->user()?->name)
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Account being enrolled.'),

                        TextInput::make('user_nip')
                            ->label('NIP')
                            ->default(fn () => auth()->user()?->nip)
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Employee ID number.'),

                        View::make('filament.forms.face-enrollment-script')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}