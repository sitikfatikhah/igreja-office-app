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
                // Select::make('user_id')
                //     ->relationship('user', 'name')
                //     ->getOptionLabelFromRecordUsing(fn($record)=>"{$record->name}-{$record->nip}")
                //     ->label('Employee')
                //     ->searchable()
                //     ->preload()
                //     ->required()
                //     ->helperText('Choose Employee.'),
                TextInput::make('basic_salary')
                    ->label('Gaji Pokok')
                    ->numeric()
                    ->helperText('Gaji pokok pegawai.'),
                DatePicker::make('effective_date')
                    ->label('Tanggal Berlaku')
                    ->required()
                    ->helperText('Tanggal berlaku.'),
                TextInput::make('notes')
                    ->label('Catatan')
                    ->nullable()
                    ->helperText('Catatan singkat.'),
            ]);
    }
}
