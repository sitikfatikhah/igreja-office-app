<?php

namespace App\Filament\Resources\TimeBankRequests\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class TimeBankRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User ID')
                    ->options(fn () =>User::pluck('name', 'id'))
                    ->live()
                    ->afterStateUpdated(function(Get $get, Set $set){
                        $userId = $get('user_id');
                        $user = User::find($userId);
                        $set('position', $user?->position);
                    })
                    ->required()
                    ->helperText('Pilih pegawai.'),
                TextInput::make('position')
                    ->label('Jabatan')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->helperText('Jabatan pegawai.'),
                DatePicker::make('request_date')
                    ->label('Request Date')
                    ->date()
                    ->required()
                    ->helperText('Tanggal pengajuan.'),
                Textarea::make('reason')
                    ->label('Reason')
                    ->required()
                    ->helperText('Alasan pengajuan.'),
                Select::make('approval_status')
                    ->label('Approval Status')
                    ->default('Pending')
                    ->disabled(fn() =>auth()->user()->hasRole('user'))
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ])
                    ->required()
                    ->helperText('Status persetujuan.'),
                Select::make('approved_by')
                    ->label('Approved By')
                    ->options(fn () =>User::pluck('name', 'id'))
                    ->live()
                    ->default('pending')
                    ->disabled(fn() =>auth()->user()->hasRole('user'))
                    ->helperText('Disetujui oleh.'), // Disable for regular users
            ]);
    }
}
