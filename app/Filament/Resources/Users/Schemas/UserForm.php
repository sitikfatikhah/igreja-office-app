<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use PhpOption\Option;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->helperText('Masukkan nama lengkap pegawai.'),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Masukkan alamat email yang valid.'),

                TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->helperText('Masukkan nomor identitas pegawai.'),

                Select::make('position')
                    ->label('Jabatan')
                    ->required()
                    ->options([
                        'Manager' => 'Manager',
                        'Staff' => 'Staff',
                        'Intern' => 'Intern',
                        'Supervisor' => 'Supervisor',
                        'Director' => 'Director',
                    ])
                    ->helperText('Pilih jabatan pegawai.'),

                Select::make('department')
                    ->label('Departemen')
                    ->options([
                        'HR' => 'HR',
                        'IT' => 'IT',
                        'Finance' => 'Finance',
                        'Marketing' => 'Marketing',
                        'Operations' => 'Operations',
                    ])
                    ->required()
                    ->helperText('Pilih departemen pegawai.'),
                
                
                Select::make('compensation_id')
                    ->relationship('compensation', 'basic_salary')
                    ->label('Kompensasi')
                    ->preload()
                    ->searchable()
                    ->helperText('Kompensasi pegawai.'),

                TextInput::make('password')
                    ->password()
                    ->required()
                    ->revealable()
                    ->helperText('Buat kata sandi yang aman.'),
                CheckboxList::make('allowance')
                    ->relationship('allowance', 'type')
                    ->label('Tunjangan')
                    ->searchable()
                    ->helperText('Pilih satu atau lebih tunjangan.'),

                CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->helperText('Pilih satu atau lebih peran.'),

                
                
            ]);
    }
}
