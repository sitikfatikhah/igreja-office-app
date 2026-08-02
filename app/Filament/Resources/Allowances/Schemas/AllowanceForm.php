<?php

namespace App\Filament\Resources\Allowances\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AllowanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Nama')
                    ->required()
                    ->helperText('Masukkan nama tunjangan.'),

                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->helperText('Masukkan nominal tunjangan.'),

                TextInput::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull()
                    ->helperText('Masukkan deskripsi singkat.'),
                Select::make('calculation_type')
                    ->options([
                        'fixed'=> 'Tetap',
                        'attendance' => 'Kehadiran', 
                        'overtime' => 'Lembur',
                        ])
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Pilih jenis perhitungan.'),
            ]);
    }
}
