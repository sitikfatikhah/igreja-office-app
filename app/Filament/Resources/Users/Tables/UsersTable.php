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
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('position')
                    ->label('Jabatan')
                    ->sortable(),
                TextColumn::make('allowance.type')
                    ->label('Jenis Tunjangan'),
                TextColumn::make('allowance.amount')
                    ->label('Nominal Tunjangan')
                    ->money('IDR'),
                TextColumn::make('compensation.basic_salary')
                    ->label('Gaji Pokok')
                    ->money('IDR'),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->sortable(),
                TextColumn::make('department')
                    ->label('Departemen')
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
