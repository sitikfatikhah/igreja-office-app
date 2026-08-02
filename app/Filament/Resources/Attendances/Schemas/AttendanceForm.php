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
                Section::make('Verifikasi Kehadiran')
                    ->description('Izinkan akses kamera dan lokasi, lalu verifikasi wajah Anda untuk menyelesaikan kehadiran.')
                    ->schema([
                        TextInput::make('nip')
                            ->label('NIP')
                            ->default(fn () => auth()->user()?->nip)
                            ->disabled()
                            ->helperText('Nomor identitas pegawai.'),

                        TextInput::make('position')
                            ->label('Jabatan')
                            ->default(fn () => auth()->user()?->position)
                            ->disabled()
                            ->helperText('Jabatan kerja saat ini.'),

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