<?php

namespace App\Filament\Resources\LeaveRequests\Tables;

use App\Filament\Exports\LeaveRequestExporter;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Employee Name'),
                TextColumn::make('leave_type')->label('Leave Type'),
                TextColumn::make('start_date')->date()->label('Start Date'),
                TextColumn::make('end_date')->date()->label('End Date'),
                TextColumn::make('total_days')->label('Total Days'),
                TextColumn::make('reason')->label('Reason for Leave'),
                TextColumn::make('approval_status')->label('Approval Status'),
            ])
            ->filters([
                SelectFilter::make('leave_type')
                    ->label('Leave Type')
                    ->options([
                        'Annual' => 'Annual Leave',
                        'Maternity' => 'Maternity Leave',
                        'Paternity' => 'Paternity Leave',
                        'Marriage' => 'Marriage Leave',
                        'Bereavement' => 'Bereavement Leave',
                        'Emergency' => 'Emergency Leave',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, $value) =>$query->where('leave_type', $value)
                        );
                    }),
                SelectFilter::make('approval_status')
                    ->label('Approval Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, $value) =>$query->where('approval_status', $value)
                        );
                    }),
                SelectFilter::make('user_id')
                    ->label('Employee')
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
                // TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                ExportAction::make()
                ->exporter(LeaveRequestExporter::class)
            ]);
    }
}
