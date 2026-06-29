<?php

namespace App\Filament\Widgets;

use App\Models\Overtimes;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class OvertimesStats extends Widget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;

    protected ?string $pollingInterval = '60s';

    protected string $view = 'filament.widgets.overtimes-stats';

    public function getViewData(): array
    {
        [$start, $end, $prevStart, $prevEnd] = $this->resolvePeriods();

        // overtime_date = tanggal lembur benar-benar terjadi.
        $currentQuery = fn () => Overtimes::query()->whereBetween('overtime_date', [$start->toDateString(), $end->toDateString()]);
        $previousQuery = fn () => Overtimes::query()->whereBetween('overtime_date', [$prevStart->toDateString(), $prevEnd->toDateString()]);

        $totalRequests = $currentQuery()->count();
        $prevTotalRequests = $previousQuery()->count();

        $pendingRequests = $currentQuery()->where('approval_status', 'pending')->count();
        $approvedRequests = $currentQuery()->where('approval_status', 'approved')->count();
        $prevApprovedRequests = $previousQuery()->where('approval_status', 'approved')->count();

        $approvalRate = $totalRequests > 0
            ? round(($approvedRequests / $totalRequests) * 100, 1)
            : 0;

        $prevApprovalRate = $prevTotalRequests > 0
            ? round(($prevApprovedRequests / $prevTotalRequests) * 100, 1)
            : 0;

        $totalOvertimeHours = (float) $currentQuery()->sum('total_hours');

        return [
            'approvalRate' => $approvalRate,
            'approvalRateTrend' => $this->trendLabel($approvalRate, $prevApprovalRate),
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
            'totalRequests' => $totalRequests,
            'totalOvertimeHours' => $totalOvertimeHours,
            'pendingPercent' => $totalRequests > 0 ? round(($pendingRequests / $totalRequests) * 100, 1) : 0,
            'approvedPercent' => $totalRequests > 0 ? round(($approvedRequests / $totalRequests) * 100, 1) : 0,
        ];
    }

    protected function resolvePeriods(): array
    {
        $filters = $this->filters ?? [];

        $start = $filters['startDate'] ?? null;
        $end = $filters['endDate'] ?? null;

        $start = $start ? Carbon::parse($start)->startOfDay() : now()->startOfMonth();
        $end = $end ? Carbon::parse($end)->endOfDay() : now()->endOfDay();

        $days = $start->diffInDays($end) + 1;

        $prevEnd = $start->copy()->subDay()->endOfDay();
        $prevStart = $prevEnd->copy()->subDays($days - 1)->startOfDay();

        return [$start, $end, $prevStart, $prevEnd];
    }

    protected function trendLabel(float $current, float $previous): string
    {
        $diff = round($current - $previous, 1);

        if ($diff == 0) {
            return 'No change vs previous period';
        }

        $sign = $diff > 0 ? '+' : '';

        return "{$sign}{$diff}pp vs previous period";
    }
}