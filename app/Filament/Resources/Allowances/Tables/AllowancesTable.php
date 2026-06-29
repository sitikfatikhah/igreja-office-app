<?php

namespace App\Filament\Resources\Allowances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;

class AllowancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginationMode(PaginationMode::Simple)
            ->striped()
            ->columns([
                TextColumn::make('type')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money('idr', true)
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable(),
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
