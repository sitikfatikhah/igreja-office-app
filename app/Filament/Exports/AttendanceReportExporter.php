<?php

namespace App\Filament\Exports;

use App\Models\AttendanceReport;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class AttendanceReportExporter extends Exporter
{
    protected static ?string $model = AttendanceReport::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.name'),
            ExportColumn::make('start_date'),
            ExportColumn::make('end_date'),
            ExportColumn::make('total_hours'),
            ExportColumn::make('total_late'),
            ExportColumn::make('total_overtime'),
            ExportColumn::make('status'),
            ExportColumn::make('report_date'),
            ExportColumn::make('description'),
            ExportColumn::make('deleted_at'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your attendance report export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
