<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceReport;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AttendanceTrendChart extends ChartWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Attendance Trend Chart';

    protected function getData(): array
    {
        $data = AttendanceReport::query()
            ->select(
                DB::raw('MONTH(report_date) as month'),
                DB::raw('SUM(total_hours) as total_hours')
            )
            ->whereYear('report_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Working Hours',
                    'data' => $data->pluck('total_hours')->toArray(),
                ],
            ],
            'labels' => $data
                ->pluck('month')
                ->map(fn ($month) => now()->startOfYear()->addMonths($month - 1)->format('M'))
                ->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
