<?php

namespace App\Filament\Resources\Overtimes\Tables;

use App\Filament\Exports\OvertimeExporter;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OvertimesTable
{
    public static function configure(Table $table): Table
    {
        return $table        
            ->paginationMode(PaginationMode::Simple)
            ->striped()
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
                SelectFilter::make('user_id')
                    ->label('Karyawan')
                    ->options(
                        User::query()
                            ->get()
                            ->mapWithKeys(fn ($user) => [
                                $user->id => "{$user->nip} - {$user->name}"
                            ])
                    )
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, $value) =>$query->where('user_id', $value)
                        );
                    }),
                Filter::make('date')
                    ->label('Tanggal')
                    ->form([
                        DatePicker::make('from')
                        ->label('from'),
                        DatePicker::make('until')
                        ->label('to')
                    ])
                    ->query(function(Builder $query, array $data){
                        return $query
                        ->when(
                            $data['from'] ?? null,
                            fn (Builder $query, $date) =>$query->whereDate('date', '>=', $date)
                        )
                        ->when(
                            $data['until'] ?? null,
                            fn (Builder $query, $date) =>$query->whereDate('date', '<=', $date)
                        );
                    }
                    ),
                // TrashedFilter::make(),
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
                ExportAction::make()
                ->exporter(OvertimeExporter::class)
            ]);
    }
}
