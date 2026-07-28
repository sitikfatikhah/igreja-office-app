<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceReport;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendanceStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalReports = AttendanceReport::count();
        $totalDays = AttendanceReport::sum('total_present');
        $totalLate = AttendanceReport::sum('total_absent');
        $totalOvertime = AttendanceReport::sum('total_overtime');

        return [
            // Stat::make('Attendance Reports', number_format($totalReports))
            //     ->description('Total generated reports')
            //     ->descriptionIcon('heroicon-m-document-text')
            //     ->color('primary'),

            // Stat::make('Working Hours', number_format($totalHours, 1))
            //     ->description('Accumulated work hours')
            //     ->descriptionIcon('heroicon-m-clock')
            //     ->color('success'),

            // Stat::make('Late Hours', number_format($totalLate, 1))
            //     ->description('Total late hours')
            //     ->descriptionIcon('heroicon-m-exclamation-triangle')
            //     ->color('warning'),

            // Stat::make('Overtime Hours', number_format($totalOvertime, 1))
            //     ->description('Total overtime hours')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->color('info'),
        ];
    }
}
