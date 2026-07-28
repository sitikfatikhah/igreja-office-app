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
                    ->label('Basic Salary')
                    ->money('idr', true),
                TextColumn::make('effective_date')
                    ->label('Effective Date')
                    ->date('M j, Y'),
                IconColumn::make('is_active')
                    ->label('Is Active')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('warning')
                    ->label('Is Active'),
                TextColumn::make('notes')
                    ->label('Notes')
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
