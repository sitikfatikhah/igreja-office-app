<?php

namespace App\Filament\Resources\Compensation\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CompensationTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('basic_salary')
                    ->label('Basic Salary')
                    ->money('idr', true),
                TextColumn::make('position_allowance')
                    ->label('Position Allowance')
                    ->money('idr', true),
                TextColumn::make('transport_allowance')
                    ->label('Transport Allowance')
                    ->money('idr', true),
                TextColumn::make('meal_allowance')
                    ->label('Meal Allowance')
                    ->money('idr', true),
                TextColumn::make('communication_allowance')
                    ->label('Communication Allowance')
                    ->money('idr', true),
                TextColumn::make('health_benefit')
                    ->label('Health Benefit')
                    ->money('idr', true),
                TextColumn::make('insurance_benefit')
                    ->label('Insurance Benefit')
                    ->money('idr', true),
                TextColumn::make('retirement_benefit')
                    ->label('Retirement Benefit')
                    ->money('idr', true),
                TextColumn::make('effective_date')
                    ->label('Effective Date')
                    ->dateTime(),
                TextColumn::make('end_date')
                    ->label('End Date')
                    ->dateTime(),
                ToggleColumn::make('is_active')
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
