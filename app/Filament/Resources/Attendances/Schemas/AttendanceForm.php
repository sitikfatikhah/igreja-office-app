<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                /*
                |--------------------------------------------------------------------------
                | Informasi User
                |--------------------------------------------------------------------------
                */
                Hidden::make('user_id')
                    ->default(fn () => auth()->id())
                    ->dehydrated(),

                TextInput::make('nip')
                    ->label('NIP')
                    ->default(fn () => auth()->user()?->nip)
                    ->disabled(),

                TextInput::make('position')
                    ->label('Jabatan')
                    ->default(fn () => auth()->user()?->position)
                    ->disabled()
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | Tanggal Absensi
                |--------------------------------------------------------------------------
                */
                Hidden::make('date')
                    ->default(fn () => now()->toDateString())
                    ->dehydrated(),

                /*
                |--------------------------------------------------------------------------
                | Data GPS sementara (diisi oleh gps-script.blade.php)
                | JS akan mengisi:
                | - data.latitude
                | - data.longitude
                | - data.location_name
                |--------------------------------------------------------------------------
                */
                Hidden::make('latitude')
                    ->dehydrated(),

                Hidden::make('longitude')
                    ->dehydrated(),

                Hidden::make('location_name')
                    ->dehydrated(),

                /*
                |--------------------------------------------------------------------------
                | Script GPS
                |--------------------------------------------------------------------------
                */
                View::make('filament.forms.gps-script')
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Script Face Recognition
                |--------------------------------------------------------------------------
                */
                View::make('filament.forms.face-attendance-script')
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Data Verifikasi Wajah
                |--------------------------------------------------------------------------
                */
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