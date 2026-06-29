<?php

namespace App\Filament\Exports;

use App\Models\Overtime;
use App\Models\Overtimes;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class OvertimeExporter extends Exporter
{
    protected static ?string $model = Overtimes::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.name'),
            ExportColumn::make('position'),
            ExportColumn::make('overtime_date'),
            ExportColumn::make('start_time'),
            ExportColumn::make('end_time'),
            ExportColumn::make('total_hours'),
            ExportColumn::make('description'),
            ExportColumn::make('approval_status'),
            ExportColumn::make('approved_by')
                 ->state(function (Overtimes $record) {
                        return $record->approvedBy?->name;
                    }),
            ExportColumn::make('reason'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your overtime export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
