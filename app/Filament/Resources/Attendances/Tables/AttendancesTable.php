<?php

namespace App\Filament\Resources\Attendances\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('user.position')->label('Position')->sortable()->searchable(),
                TextColumn::make('user.nip')->label('NIP')->sortable()->searchable(),
                ImageColumn::make('photo')->label('Photo')->disk('public'),
                TextColumn::make('verification_method')->label('Verification Method')->sortable()->searchable(),
                TextColumn::make('check_in')->dateTime()->sortable(),
                TextColumn::make('check_out')->dateTime()->sortable(),
                TextColumn::make('check_in_latitude')->label('Check-in Latitude')->sortable()->searchable(),
                TextColumn::make('check_in_longitude')->label('Check-in Longitude')->sortable()->searchable(),
                TextColumn::make('check_out_latitude')->label('Check-out Latitude')->sortable()->searchable(),
                TextColumn::make('check_out_longitude')->label('Check-out Longitude')->sortable()->searchable(),
                TextColumn::make('check_in_location_name')->label('Check-in Location')->sortable()->searchable(),
                TextColumn::make('check_out_location_name')->label('Check-out Location')->sortable()->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
