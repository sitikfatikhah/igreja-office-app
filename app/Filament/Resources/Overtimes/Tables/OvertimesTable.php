<?php

namespace App\Filament\Resources\Overtimes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OvertimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Employee Name'),
                TextColumn::make('position')->label('Position'),
                TextColumn::make('overtime_date')->date()->label('Overtime Date'),
                TextColumn::make('start_time')->time()->label('Start Time'),
                TextColumn::make('end_time')->time()->label('End Time'),
                TextColumn::make('total_hours')->label('Total Hours'),
                TextColumn::make('description')->label('Description'),
                TextColumn::make('approval_status')->label('Approval Status'),
                TextColumn::make('approved_by')->label('Approved By'),
                TextColumn::make('reason')->label('Reason'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
