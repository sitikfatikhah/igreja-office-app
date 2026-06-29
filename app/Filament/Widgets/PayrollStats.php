<?php

namespace App\Filament\Widgets;

use App\Models\Payrolls;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PayrollStats extends StatsOverviewWidget
{
    // protected static ?int $sort = 4;
    protected function getStats(): array
    {
        // $totalPayrolls = Payrolls::count();
        // $draftPayrolls = Payrolls::where('status', 'draft')->count();
        // $paidPayrolls = Payrolls::where('status', 'paid')->count();
        // $totalNetPay = Payrolls::sum('net_pay');

        return [
            // Stat::make('Payroll Records', number_format($totalPayrolls))
            //     ->description('Generated payrolls')
            //     ->descriptionIcon('heroicon-m-banknotes')
            //     ->color('primary'),

            // Stat::make('Draft Payroll', number_format($draftPayrolls))
            //     ->description('Need processing')
            //     ->descriptionIcon('heroicon-m-clock')
            //     ->color('warning'),

            // Stat::make('Paid Payroll', number_format($paidPayrolls))
            //     ->description('Completed payrolls')
            //     ->descriptionIcon('heroicon-m-check-circle')
            //     ->color('success'),

            // Stat::make('Net Pay', 'Rp ' . number_format($totalNetPay, 0, ',', '.'))
            //     ->description('Total payroll value')
            //     ->descriptionIcon('heroicon-m-currency-dollar')
            //     ->color('info'),
        ];
    }
}
