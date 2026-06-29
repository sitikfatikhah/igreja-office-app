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
                    ->required(),
                TextInput::make('position')
                    ->label('Jabatan')
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                DatePicker::make('request_date')
                    ->label('Request Date')
                    ->date()
                    ->required(),
                Textarea::make('reason')
                    ->label('Reason')
                    ->required(),
                Select::make('approval_status')
                    ->label('Approval Status')
                    ->default('Pending')
                    ->disabled(fn() =>auth()->user()->hasRole('user')) // Disable for regular users
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ])
                    ->required(),
                Select::make('approved_by')
                    ->label('Approved By')
                    ->options(fn () =>User::pluck('name', 'id'))
                    ->live()
                    ->default('pending')
                    ->disabled(fn() =>auth()->user()->hasRole('user')), // Disable for regular users
            ]);
    }
}
