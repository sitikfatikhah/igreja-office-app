<?php

namespace App\Filament\Resources\AnnualLeaveBalances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AnnualLeaveBalancesTable
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
                TextColumn::make('user.name')->label('Employee Name'),
                TextColumn::make('leave_request.request_date')->label('Request Date'),
                TextColumn::make('year')->label('Year'),
                TextColumn::make('days')->label('Days'),
                TextColumn::make('type')->label('Type'),
                TextColumn::make('leave_type')->label('Leave Type'),
                TextColumn::make('balanced')->label('Balanced'),
                TextColumn::make('source')->label('Source'),
                TextColumn::make('description')->label('Description'),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
                TextColumn::make('updated_at')->label('Updated At')->dateTime(),
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
