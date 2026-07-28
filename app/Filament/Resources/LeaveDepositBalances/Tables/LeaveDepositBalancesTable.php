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
                TextColumn::make('user.name'),
                TextColumn::make('time_bank_request_id')
                    ->label('Request Id')
                    ->searchable(),
                TextColumn::make('days')
                    ->label('Days')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->searchable(),
                TextColumn::make('balanced')
                    ->label('Balanced')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')              

            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Employee')
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
