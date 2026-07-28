<?php

namespace App\Filament\Exports;

use App\Models\Attendance;
use App\Models\Overtimes;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class AttendanceDetailExporter extends Exporter
{
    protected static ?string $model = Attendance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('user.name'),
            ExportColumn::make('date')
                ->label('Date')
                ->formatStateUsing(fn ($state) => $state?->format('d M Y')),

            ExportColumn::make('check_in')
                ->label('Check In')
                ->formatStateUsing(fn ($state) => $state?->format('H:i') ?? '-'),

            ExportColumn::make('check_out')
                ->label('Check Out')
                ->formatStateUsing(fn ($state) => $state?->format('H:i') ?? '-'),

            ExportColumn::make('total_days')
                ->label('Total Days')
                ->state(fn (Attendance $record) => $record->total_days),

            ExportColumn::make('late_hours')
                ->label('Late (hrs)')
                ->state(function (Attendance $record): float {
                    if (!$record->is_late || !$record->check_in) return 0;

                    return round(
                        $record->check_in->copy()->setTime(8, 0)
                            ->diffInMinutes($record->check_in) / 60,
                        2
                    );
                }),

            ExportColumn::make('overtime_hours')
                ->label('Overtime (hrs)')
                ->state(fn (Overtimes $record) => $record->total_days),

            ExportColumn::make('check_in_location_name')
                ->label('Location Check In')
                ->formatStateUsing(fn ($state) => $state ?? '-'),

            ExportColumn::make('check_out_location_name')
                ->label('Location Check Out')
                ->formatStateUsing(fn ($state) => $state ?? '-'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your Report Detail export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}