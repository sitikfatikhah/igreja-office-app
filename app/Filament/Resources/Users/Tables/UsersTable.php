<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\Allowance;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->paginationMode(PaginationMode::Simple)
        ->striped()
            ->columns([
                TextColumn::make('id')
                    ->label('User Id')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('position')
                    ->label('Position')
                    ->sortable(),
                TextColumn::make('allowance.type')
                    ->label('Allowance Type'),
                TextColumn::make('allowance.amount')
                    ->label('Allowance Amount')
                    ->money('IDR'),
                TextColumn::make('compensation.basic_salary')
                    ->label('Basic Salary')
                    ->money('IDR'),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->sortable(),
                TextColumn::make('department')
                    ->label('Department')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
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
