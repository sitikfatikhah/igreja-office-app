<?php

namespace App\Filament\Resources\Payrolls\Tables;

use App\Filament\Exports\PayrollExporter;
use App\Models\Payrolls;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginationMode(PaginationMode::Simple)
            ->striped()
            ->columns([
                TextColumn::make('user.name')
                    ->label('Employee Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gross_pay')
                    ->label('Gross')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('net_pay')
                    ->label('Net Pay')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('deductions')
                    ->label('Deductions')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('additions')
                    ->label('Additions')
                    ->money('IDR', true)
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
                        'success' => 'success',
                        'danger' => 'failed',
                    ])
                    ->sortable(),
            ])

            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                ,
                Action::make('downloadSlip')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function ($record) {

                    $pdf = Pdf::loadView(
                        'filament.forms.slip',
                        [
                            'payroll' => $record,
                        ]
                    );

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'slip-gaji-' . $record->id . '.pdf'
                    );
                }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
                ExportAction::make()
                ->exporter(PayrollExporter::class)
                ->label('Export Payrolls'),
                
                // Action::make('slip')
                // ->label('Slip Gaji')
                // ->icon('heroicon-o-document-text')
                // ->url(fn ($record) => route('payroll.slip', $record))
                // ->openUrlInNewTab(),
            ]);
    }
}
