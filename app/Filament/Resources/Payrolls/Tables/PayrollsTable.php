<?php

namespace App\Filament\Resources\Payrolls\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Employee Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pay_period')
                    ->label('Pay Period')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gross_pay')
                    ->label('Gross Pay')
                    ->money('USD', true)
                    ->sortable(),
                TextColumn::make('net_pay')
                    ->label('Net Pay')
                    ->money('USD', true)
                    ->sortable(),
                TextColumn::make('deductions')
                    ->label('Deductions')
                    ->money('USD', true)
                    ->sortable(),
                TextColumn::make('generated_at')
                    ->label('Generated At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'processed',
                        'danger' => 'failed',
                    ])
                    ->sortable(),
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
