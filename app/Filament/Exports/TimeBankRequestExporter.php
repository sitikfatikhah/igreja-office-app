<?php

namespace App\Filament\Exports;

use App\Models\TimeBankRequest;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class TimeBankRequestExporter extends Exporter
{
    protected static ?string $model = TimeBankRequest::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user_id'),
            ExportColumn::make('position'),
            ExportColumn::make('request_date'),
            ExportColumn::make('approval_status'),
            ExportColumn::make('approved_by')
                 ->state(function (TimeBankRequest $record) {
                        return $record->approvedBy?->name;
                    }),
            ExportColumn::make('reason'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your time bank request export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
