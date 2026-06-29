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
                    ->required()
                    ->helperText('Pilih pegawai terkait.'),

                DatePicker::make('start_date')
                    ->required()
                    ->helperText('Tanggal mulai laporan.'),

                DatePicker::make('end_date')
                    ->required()
                    ->helperText('Tanggal akhir laporan.'),

                TextInput::make('total_hours')
                    ->label('Total Working Hours')
                    ->numeric()
                    ->suffix('hrs')
                    ->required()
                    ->helperText('Jumlah jam kerja.'),

                TextInput::make('total_overtime')
                    ->label('Total Overtime')
                    ->numeric()
                    ->suffix('hrs')
                    ->helperText('Jam lembur tambahan.'),

                TextInput::make('total_late')
                    ->label('Total Late')
                    ->numeric()
                    ->suffix('hrs')
                    ->helperText('Jam keterlambatan.'),

                Select::make('status')
                    ->options([
                        'present' => 'Present',
                        'late' => 'Late',
                        'overtime' => 'Overtime',
                        'absent' => 'Absent',
                    ])
                    ->required()
                    ->helperText('Status laporan absensi.'),

                DatePicker::make('report_date')
                    ->label('Report Date')
                    ->default(now())
                    ->required()
                    ->helperText('Tanggal pembuatan laporan.'),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->columnSpanFull()
                    ->helperText('Catatan singkat.'),
            ])
            ->columns(2);
    }
}