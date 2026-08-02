<?php

namespace App\Filament\Resources\LeaveDepositBalances\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LeaveDepositBalancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function(Builder $query){
                $user = Auth::user();

                if ($user->hasRole('super_admin')){
                    return $query;
                }

                return $query->where('user_id', $user->id);
            })
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('user.name')->label('Nama Pegawai'),
                TextColumn::make('time_bank_request_id')
                    ->label('ID Permohonan')
                    ->searchable(),
                TextColumn::make('days')
                    ->label('Hari')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->searchable(),
                TextColumn::make('balanced')
                    ->label('Saldo')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Deskripsi')              

            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Pegawai')
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
