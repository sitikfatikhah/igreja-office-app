<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceReport;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class AttendanceTrendChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 5;

    protected ?string $heading = 'Tren Kehadiran';

    protected ?string $description = 'Total hari kerja, jam terlambat, dan jam lembur dari waktu ke waktu.';

    protected ?string $pollingInterval = '60s';

    // Tampilkan beberapa filter rentang bawaan ChartWidget sebagai pelengkap filter dashboard
    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Harian (rentang terpilih)',
            'monthly' => 'Bulanan (tahun ini)',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? 'monthly';

        return $filter === 'daily'
            ? $this->dailyData()
            : $this->monthlyData();
    }

    /**
     * Mode bulanan: total jam kerja per bulan pada tahun berjalan.
     * Dipertahankan dari versi awal sebagai default view.
     */
    protected function monthlyData(): array
    {
        $data = AttendanceReport::query()
            ->select(
                DB::raw('MONTH(report_date) as month'),
                DB::raw('SUM(total_present) as total_present'),
                DB::raw('SUM(total_absent) as total_absent'),
                DB::raw('SUM(total_overtime) as total_overtime')
            )
            ->whereYear('report_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Working Days',
                    'data' => $data->pluck('total_present')->toArray(),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Hari Tidak Hadir',
                    'data' => $data->pluck('total_absent')->toArray(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Overtime Hours',
                    'data' => $data->pluck('total_overtime')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $data
                ->pluck('month')
                ->map(fn ($month) => now()->startOfYear()->addMonths($month - 1)->format('M'))
                ->toArray(),
        ];
    }

    /**
     * Mode harian: memakai rentang startDate/endDate dari filter Dashboard.
     * report_date di-cast sebagai string ('Y-m-d') sesuai migration, jadi
     * perbandingannya tetap lewat whereBetween pada string tanggal.
     */
    protected function dailyData(): array
    {
        $filters = $this->filters ?? [];

        $start = isset($filters['startDate'])
            ? Carbon::parse($filters['startDate'])
            : now()->subDays(13);

        $end = isset($filters['endDate'])
            ? Carbon::parse($filters['endDate'])
            : now();

        $data = AttendanceReport::query()
            ->select(
                'report_date',
                DB::raw('SUM(total_present) as total_present'),
                DB::raw('SUM(total_absent) as total_absent'),
                DB::raw('SUM(total_overtime) as total_overtime')
            )
            ->whereBetween('report_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->get()
            ->keyBy('report_date');

        $labels = [];
        $hours = [];
        $late = [];
        $overtime = [];

        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $key = $cursor->format('Y-m-d');
            $row = $data->get($key);

            $labels[] = $cursor->format('d M');
            $hours[] = $row?->total_present ?? 0;
            $late[] = $row?->total_late ?? 0;
            $overtime[] = $row?->total_overtime ?? 0;

            $cursor->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Working Days',
                    'data' => $hours,
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Jam Terlambat',
                    'data' => $late,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Overtime Hours',
                    'data' => $overtime,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}