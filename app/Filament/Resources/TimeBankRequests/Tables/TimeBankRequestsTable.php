<?php

namespace App\Filament\Resources\TimeBankRequests\Tables;

use App\Filament\Exports\TimeBankRequestExporter;
use App\Models\LeaveDepositBalance;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TimeBankRequestsTable
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
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('user.name')->label('User Name')->sortable()->searchable(),
                TextColumn::make('position')->label('Position'),
                TextColumn::make('request_date')->label('Request Date')->sortable(),
                TextColumn::make('approval_status')->label('Approval Status')->sortable(),
                TextColumn::make('approvedBy.name')->label('Approved By'),
                TextColumn::make('reason')->label('Reason'),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->options(User::pluck('name', 'id')),
                Filter::make('request_date')
                    ->label('Request Date')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('to')->label('To'),
                    ])
                    ->query(function ($query, $data) {
                        if ($data['from'] ?? null) {
                            $query->where('request_date', '>=', $data['from']);
                        }
                        if ($data['to'] ?? null) {
                            $query->where('request_date', '<=', $data['to']);
                        }
                    }),
                Filter::make('approval_status')
                    ->label('Approval Status')
                    ->form([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->label('Status'),
                    ])
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('approved')
                ->label('Approved')
                ->disabled(fn () =>
                    auth()->user()->hasRole('user')
                )
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function($record)
                {
                    $record->update([
                        'approval_status' => 'approved',
                        'approved_by' => auth()->id(),
                    ]);

                    $lastBalance = LeaveDepositBalance::where(
                        'user_id',
                        $record->user_id
                    )
                    ->latest('id')
                    ->value('balanced') ?? 0;

                    LeaveDepositBalance::create([
                        'user_id' => $record->user_id,
                        'time_bank_request_id' => $record->id,
                        'days' => 1,
                        'type' => 'credit',
                        'balanced' =>$lastBalance +1,
                        'description'=> 'Approved'
                    ]);

                    if ($record->approval_status === 'Approved') {
                        return;
                    }

                    Notification::make()
                        ->success()
                        ->title('Request Approved')
                        ->body('Deposit leave request has been approved successfully.')
                        ->send();
                }),
                Action::make('reject')
                    ->label('Reject')
                     ->disabled(fn () =>
                        auth()->user()->hasRole('user')
                    )
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function($record)
                    {
                        $record->update([
                            'approval_status' => 'Rejected',
                            'approved_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->danger()
                            ->title('Request Rejected')
                            ->body('Deposit leave request has been rejected.')
                            ->send();
                                }),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                ExportAction::make()
                ->exporter(TimeBankRequestExporter::class),
            ]);
    }
}
