<?php

namespace App\Filament\Exports;

use App\Models\Attendance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class AttendanceExporter extends Exporter
{
    protected static ?string $model = Attendance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.name'),
            ExportColumn::make('position'),
            ExportColumn::make('nip'),
            ExportColumn::make('date'),
            ExportColumn::make('check_in'),
            ExportColumn::make('check_out'),
            ExportColumn::make('check_in_latitude'),
            ExportColumn::make('check_in_longitude'),
            ExportColumn::make('check_out_latitude'),
            ExportColumn::make('check_out_longitude'),
            ExportColumn::make('check_in_location_name'),
            ExportColumn::make('check_out_location_name'),
            ExportColumn::make('verification_score'),
            ExportColumn::make('verification_method'),
            ExportColumn::make('face_verified'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your attendance export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
