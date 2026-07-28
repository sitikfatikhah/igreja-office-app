<?php

namespace App\Filament\Exports;

use App\Models\Payrolls;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PayrollExporter extends Exporter
{
    protected static ?string $model = Payrolls::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('user.name')
                ->label('Employee'),

            ExportColumn::make('start_date')
                ->label('Start Date'),

            ExportColumn::make('end_date')
                ->label('End Date'),

            ExportColumn::make('gross_pay')
                ->label('Gross Pay'),
            
            ExportColumn::make('attendanceReport.total_days')
                ->label('Working Hours'),

            ExportColumn::make('attendanceReport.total_overtime')
                ->label('Overtime Hours'),

            ExportColumn::make('attendanceReport.total_late')
                ->label('Late Hours'),

            ExportColumn::make('additions')
                ->label('Additions'),

            ExportColumn::make('deductions')
                ->label('Deductions'),

            ExportColumn::make('net_pay')
                ->label('Net Pay'),

            ExportColumn::make('generated_at')
                ->label('Generated At'),

            ExportColumn::make('status')
                ->label('Status'),
            
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your payroll export has completed and '
            . Number::format($export->successful_rows)
            . ' '
            . str('row')->plural($export->successful_rows)
            . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '
                . Number::format($failedRowsCount)
                . ' '
                . str('row')->plural($failedRowsCount)
                . ' failed to export.';
        }

        return $body;
    }
}