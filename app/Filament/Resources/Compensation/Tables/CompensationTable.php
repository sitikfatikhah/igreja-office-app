<?php

namespace App\Filament\Resources\Compensation\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;

class CompensationTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginationMode(PaginationMode::Simple)
            ->striped()
            ->columns([
                TextColumn::make('basic_salary')
                    ->label('Gaji Pokok')
                    ->money('idr', true),
                TextColumn::make('effective_date')
                    ->label('Tanggal Berlaku')
                    ->date('M j, Y'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('warning')
                    ->label('Is Active'),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
