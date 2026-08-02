<?php

namespace App\Filament\Resources\Payrolls\Tables;

use App\Exports\PayrollSlipExport;
use App\Filament\Exports\PayrollExporter;
use App\Models\Payrolls;
use App\Services\SettingsService;
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
use Maatwebsite\Excel\Facades\Excel;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginationMode(PaginationMode::Simple)
            ->striped()
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gross_pay')
                    ->label('Penghasilan Kotor')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('net_pay')
                    ->label('Penghasilan Bersih')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('deductions')
                    ->label('Potongan')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('additions')
                    ->label('Tambahan')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('generated_at')
                    ->label('Tanggal Dibuat')
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
                EditAction::make(),

                Action::make('downloadPdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function ($record) {

                        $pdf = Pdf::loadView('filament.forms.slip', [
                            'payroll' => $record,
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'Slip-Gaji-' . $record->id . '.pdf'
                        );
                    }),

                Action::make('downloadExcel')
                    ->label('Excel')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->action(function ($record) {

                        return Excel::download(
                            new PayrollSlipExport(
                                $record,
                                app(\App\Services\SettingsService::class)->all(auth()->user())
                            ),
                            'Slip-Gaji-' . $record->id . '.xlsx'
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
                ->label('Ekspor Penggajian'),
                
                // Action::make('slip')
                // ->label('Slip Gaji')
                // ->icon('heroicon-o-document-text')
                // ->url(fn ($record) => route('payroll.slip', $record))
                // ->openUrlInNewTab(),
            ]);
    }
}
