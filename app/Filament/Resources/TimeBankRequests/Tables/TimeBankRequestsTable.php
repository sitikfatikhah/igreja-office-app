<?php

namespace App\Filament\Resources\TimeBankRequests\Tables;

use App\Filament\Exports\TimeBankRequestExporter;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TimeBankRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
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
