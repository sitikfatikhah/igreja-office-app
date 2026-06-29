<?php

namespace App\Filament\Widgets;

use App\Models\Payrolls;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class PayrollStatusWidget extends Widget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;

    protected ?string $pollingInterval = '60s';

    protected string $view = 'filament.widgets.payroll-status-widget';

    public function getViewData(): array
    {
        [$start, $end, $prevStart, $prevEnd] = $this->resolvePeriods();

        $draft = $this->overlapQuery($start, $end)->where('status', 'draft')->count();
        $generated = $this->overlapQuery($start, $end)->where('status', 'generated')->count();
        $paid = $this->overlapQuery($start, $end)->where('status', 'paid')->count();

        $payrollValue = (float) $this->overlapQuery($start, $end)->where('status', 'paid')->sum('net_pay');
        $prevPayrollValue = (float) $this->overlapQuery($prevStart, $prevEnd)->where('status', 'paid')->sum('net_pay');

        $totalPayrolls = $draft + $generated + $paid;
        $paidRate = $totalPayrolls > 0 ? round(($paid / $totalPayrolls) * 100, 1) : 0;

        return [
            'paidRate' => $paidRate,
            'payrollValueTrend' => $this->trendLabel($payrollValue, $prevPayrollValue),
            'draft' => $draft,
            'generated' => $generated,
            'paid' => $paid,
            'totalPayrolls' => $totalPayrolls,
            'payrollValue' => $payrollValue,
            'draftPercent' => $totalPayrolls > 0 ? round(($draft / $totalPayrolls) * 100, 1) : 0,
            'generatedPercent' => $totalPayrolls > 0 ? round(($generated / $totalPayrolls) * 100, 1) : 0,
            'paidPercent' => $totalPayrolls > 0 ? round(($paid / $totalPayrolls) * 100, 1) : 0,
        ];
    }

    /**
     * Payroll dianggap "masuk" sebuah rentang ($from-$to) jika periode kerja
     * payroll (start_date..end_date) overlap dengan rentang tersebut.
     */
    protected function overlapQuery(Carbon $from, Carbon $to)
    {
        return Payrolls::query()
            ->where('start_date', '<=', $to->toDateString())
            ->where('end_date', '>=', $from->toDateString());
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
        if ($previous == 0) {
            return $current > 0 ? 'New activity vs previous period' : 'No change vs previous period';
        }

        $change = round((($current - $previous) / $previous) * 100, 1);
        $sign = $change > 0 ? '+' : '';

        return "{$sign}{$change}% vs previous period";
    }
}