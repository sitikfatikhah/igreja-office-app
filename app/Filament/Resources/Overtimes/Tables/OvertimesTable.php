<?php

namespace App\Filament\Resources\Overtimes\Tables;

use App\Filament\Exports\OvertimeExporter;
use App\Models\Overtimes;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OvertimesTable
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
            ->paginationMode(PaginationMode::Simple)
            ->striped()
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('user.nip')->label('NIP'),
                TextColumn::make('user.name')->label('Nama Pegawai'),
                TextColumn::make('position')->label('Jabatan'),
                TextColumn::make('overtime_date')->date()->label('Tanggal Lembur'),
                TextColumn::make('start_time')->time()->label('Waktu Mulai'),
                TextColumn::make('end_time')->time()->label('Waktu Selesai'),
                TextColumn::make('total_hours')->label('Total Jam'),
                TextColumn::make('description')->label('Deskripsi'),
                TextColumn::make('approval_status')->label('Status Persetujuan'),
                TextColumn::make('approved_by')->label('Disetujui Oleh'),
                TextColumn::make('reason')->label('Alasan'),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Pegawai')
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
                        ->label('Dari'),
                        DatePicker::make('until')
                        ->label('Sampai')
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
                Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (Overtimes $overtime) {
                        $overtime->update(['approval_status' => 'approved']);
                    })
                    ->disabled(fn () =>
                        auth()->user()->hasRole('user')
                    )
                    ->requiresConfirmation()
                    ->action(function (Overtimes $overtime) {
                        if ($overtime->approval_status === 'Approved') {
                            Notification::make()
                                ->warning()
                                ->title('Overtime request sudah di-approve.')
                                ->send();

                            return;
                        }

                        $overtime->update(['approval_status' => 'approved']);
                    }),
                    
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->action(function (Overtimes $overtime) {
                        $overtime->update(['approval_status' => 'rejected']);
                    })
                    ->disabled(fn () =>
                        auth()->user()->hasRole('user')
                    )
                    ->requiresConfirmation()
                    ->action(function (Overtimes $overtime) {
                        if ($overtime->approval_status === 'Rejected') {
                            Notification::make()
                                ->warning()
                                ->title('Overtime request sudah di-reject.')
                                ->send();

                            return;
                        }

                        $overtime->update(['approval_status' => 'rejected']);
                    }),
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
