<?php

namespace App\Filament\Resources\LeaveRequests\Tables;

use App\Filament\Exports\LeaveRequestExporter;
use App\Models\AnnualLeaveBalance;
use App\Models\User;
use App\Services\LeaveDeductionService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveRequestsTable
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
                TextColumn::make('id')->label('ID'),
                TextColumn::make('user.nip')->label('NIP Karyawan'),
                TextColumn::make('user.name')->label('Nama Karyawan'),
                TextColumn::make('leave_type')->label('Jenis Cuti'),
                TextColumn::make('source')->label('Sumber'),
                TextColumn::make('start_date')->date()->label('Mulai'),
                TextColumn::make('end_date')->date()->label('Akhir'),
                TextColumn::make('total_days')->label('Total Hari'),
                TextColumn::make('reason')->label('Alasan Cuti'),
                TextColumn::make('approval_status')->label('Status Persetujuan'),
            ])
            ->filters([
                SelectFilter::make('leave_type')
                    ->label('Jenis Cuti')
                    ->options([
                        'Annual' => 'Cuti Tahunan',
                        'Maternity' => 'Cuti Melahirkan',
                        'Paternity' => 'Cuti Ayah',
                        'Marriage' => 'Cuti Pernikahan',
                        'Bereavement' => 'Cuti Duka',
                        'Emergency' => 'Cuti Darurat',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, $value) =>$query->where('leave_type', $value)
                        );
                    }),
                SelectFilter::make('approval_status')
                    ->label('Status Persetujuan')
                    ->options([
                        'Pending' => 'Menunggu',
                        'Approved' => 'Disetujui',
                        'Rejected' => 'Ditolak',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, $value) =>$query->where('approval_status', $value)
                        );
                    }),
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
                // TrashedFilter::make(),
            ])
           ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn () => ! auth()->user()->hasRole('user'))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        try {
                            app(\App\Services\LeaveService::class)->approve($record, auth()->id());

                            Notification::make()
                                ->success()
                                ->title('Pengajuan cuti berhasil disetujui.')
                                ->send();
                        } catch (\Exception $e) {
                            if ($e->getMessage() === 'ALREADY_APPROVED') {
                                Notification::make()
                                    ->warning()
                                    ->title('Pengajuan cuti ini sudah disetujui.')
                                    ->send();
                                return;
                            }

                            Notification::make()
                                ->danger()
                                ->title('Gagal menyetujui pengajuan cuti.')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn () => ! auth()->user()->hasRole('user'))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        try {
                            app(\App\Services\LeaveService::class)->reject($record, auth()->id());

                            Notification::make()
                                ->success()
                                ->title('Pengajuan cuti berhasil ditolak.')
                                ->send();
                        } catch (\Exception $e) {
                            if ($e->getMessage() === 'ALREADY_APPROVED') {
                                Notification::make()
                                    ->warning()
                                    ->title('Pengajuan cuti ini sudah disetujui.')
                                    ->send();
                                return;
                            }

                            Notification::make()
                                ->danger()
                                ->title('Gagal menolak pengajuan cuti.')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
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
