<?php

namespace App\Filament\Resources\Compensation\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompensationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->getOptionLabelFromRecordUsing(fn($record)=>"{$record->name}-{$record->nip}")
                    ->label('Karyawan')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Pilih karyawan.'),
                TextInput::make('basic_salary')
                    ->label('Basic Salary')
                    ->numeric()
                    ->helperText('Gaji pokok karyawan.'),
                DatePicker::make('effective_date')
                    ->label('Effective Date')
                    ->required()
                    ->helperText('Tanggal berlaku.'),
                TextInput::make('notes')
                    ->label('Notes')
                    ->nullable()
                    ->helperText('Catatan singkat.'),
            ]);
    }
}
