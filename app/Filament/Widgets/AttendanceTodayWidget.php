<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceReport;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class AttendanceTodayWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;
    
    protected function getStats(): array
    {
        $today = Carbon::today();

        $todayReports = AttendanceReport::whereDate('report_date', $today);

        $reportsCount = $todayReports->count();

        $totalDays = (clone $todayReports)->sum('total_present');

        $totalLate = (clone $todayReports)->sum('total_absent');

        $totalOvertime = (clone $todayReports)->sum('total_overtime');
        return [
            // Stat::make('Reports Today', $reportsCount)
            //     ->description('Attendance reports created today')
            //     ->descriptionIcon('heroicon-m-document-text')
            //     ->color('primary'),

            // Stat::make('Working Hours', number_format($totalHours, 1))
            //     ->description('Total working hours today')
            //     ->descriptionIcon('heroicon-m-clock')
            //     ->color('success'),

            // Stat::make('Late Hours', number_format($totalLate, 1))
            //     ->description('Total late hours today')
            //     ->descriptionIcon('heroicon-m-exclamation-triangle')
            //     ->color('warning'),

            // Stat::make('Overtime Hours', number_format($totalOvertime, 1))
            //     ->description('Total overtime hours today')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->color('info'),
        ];
    }
}
