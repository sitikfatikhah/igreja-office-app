<?php

namespace App\Filament\Widgets;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class LeaveRequestChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 5;

    protected ?string $heading = 'Rincian Permohonan Cuti';

    protected ?string $description = 'Distribusi permohonan cuti berdasarkan status persetujuan.';

    protected ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        $filters = $this->filters ?? [];

        $start = isset($filters['startDate'])
            ? Carbon::parse($filters['startDate'])->startOfDay()
            : null;

        $end = isset($filters['endDate'])
            ? Carbon::parse($filters['endDate'])->endOfDay()
            : null;

        $query = fn () => LeaveRequest::query()
            ->when($start && $end, fn ($q) => $q->where('start_date', '<=', $end->toDateString())
                ->where('end_date', '>=', $start->toDateString()));

        $pending = $query()->where('approval_status', 'pending')->count();
        $approved = $query()->where('approval_status', 'approved')->count();
        $rejected = $query()->where('approval_status', 'rejected')->count();

        $total = $pending + $approved + $rejected;

        $labelWithPercent = fn (string $label, int $count) => $total > 0
            ? "{$label} (" . round(($count / $total) * 100, 1) . '%)'
            : $label;

        return [
            'datasets' => [
                [
                    'data' => [$pending, $approved, $rejected],
                    'backgroundColor' => ['#f59e0b', '#22c55e', '#ef4444'],
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => [
                $labelWithPercent('Menunggu', $pending),
                $labelWithPercent('Disetujui', $approved),
                $labelWithPercent('Ditolak', $rejected),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
        ];
    }
}