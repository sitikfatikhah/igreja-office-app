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

class LeaveDepositBalancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
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
                TextColumn::make('balance')
                    ->label('Balance')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')              

            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Karyawan')
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
