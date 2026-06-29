<?php

namespace App\Filament\Widgets;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class LeaveStats extends Widget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;

    protected ?string $pollingInterval = '60s';

    protected string $view = 'filament.widgets.leave-stats';

    public function getViewData(): array
    {
        [$start, $end, $prevStart, $prevEnd] = $this->resolvePeriods();

        $totalRequests = $this->overlapQuery($start, $end)->count();
        $prevTotalRequests = $this->overlapQuery($prevStart, $prevEnd)->count();

        $pendingRequests = $this->overlapQuery($start, $end)->where('approval_status', 'pending')->count();
        $approvedRequests = $this->overlapQuery($start, $end)->where('approval_status', 'approved')->count();
        $rejectedRequests = $this->overlapQuery($start, $end)->where('approval_status', 'rejected')->count();

        $totalLeaveDays = (float) $this->overlapQuery($start, $end)->sum('total_days');

        $approvalRate = $totalRequests > 0
            ? round(($approvedRequests / $totalRequests) * 100, 1)
            : 0;

        $prevApprovedRequests = $this->overlapQuery($prevStart, $prevEnd)->where('approval_status', 'approved')->count();
        $prevApprovalRate = $prevTotalRequests > 0
            ? round(($prevApprovedRequests / $prevTotalRequests) * 100, 1)
            : 0;

        return [
            'approvalRate' => $approvalRate,
            'approvalRateTrend' => $this->trendLabel($approvalRate, $prevApprovalRate),
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
            'rejectedRequests' => $rejectedRequests,
            'totalRequests' => $totalRequests,
            'totalLeaveDays' => $totalLeaveDays,
            'pendingPercent' => $totalRequests > 0 ? round(($pendingRequests / $totalRequests) * 100, 1) : 0,
            'approvedPercent' => $totalRequests > 0 ? round(($approvedRequests / $totalRequests) * 100, 1) : 0,
            'rejectedPercent' => $totalRequests > 0 ? round(($rejectedRequests / $totalRequests) * 100, 1) : 0,
        ];
    }

    /**
     * Leave request dianggap "masuk" sebuah rentang ($from-$to) jika periode
     * cuti (start_date..end_date) overlap dengan rentang tersebut.
     */
    protected function overlapQuery(Carbon $from, Carbon $to)
    {
        return LeaveRequest::query()
            ->where('start_date', '<=', $to->toDateString())
            ->where('end_date', '>=', $from->toDateString());
    }

    /**
     * Ambil rentang tanggal dari filter dashboard (startDate/endDate).
     * Jika filter belum diisi, default ke bulan ini.
     */
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