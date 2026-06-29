<?php

namespace App\Filament\Widgets;

use App\Models\Payrolls;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PayrollStatusWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;
    protected ?string $heading = 'Payroll Status Overview';
    protected ?string $description = 'An overview of payroll status.';
    protected function getStats(): array
    {
        $draft = Payrolls::where('status', 'draft')->count();
        $generated = Payrolls::where('status', 'generated')->count();
        $paid = Payrolls::where('status', 'paid')->count();
        $payrollValue = Payrolls::sum('net_pay');

        return [
            Stat::make('Draft', $draft)
                ->description('Waiting processing')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Generated', $generated)
                ->description('Ready for payment')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('info'),

            Stat::make('Paid', $paid)
                ->description('Completed payroll')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make(
                'Payroll Value',
                'Rp ' . number_format($payrollValue, 0, ',', '.')
            )
                ->description('Paid payroll amount')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),
        ];
    }
}
