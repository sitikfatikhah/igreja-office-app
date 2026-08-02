<?php

namespace App\Filament\Resources\AnnualLeaveBalances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AnnualLeaveBalancesTable
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
                TextColumn::make('user.name')->label('Nama Pegawai'),
                TextColumn::make('leave_request.request_date')->label('Tanggal Permohonan'),
                TextColumn::make('year')->label('Tahun'),
                TextColumn::make('days')->label('Hari'),
                TextColumn::make('type')->label('Jenis'),
                TextColumn::make('leave_type')->label('Jenis Cuti'),
                TextColumn::make('balanced')->label('Saldo'),
                TextColumn::make('source')->label('Sumber'),
                TextColumn::make('description')->label('Deskripsi'),
                TextColumn::make('created_at')->label('Dibuat Pada')->dateTime(),
                TextColumn::make('updated_at')->label('Diperbarui Pada')->dateTime(),
            ])
            ->filters([
                //
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
