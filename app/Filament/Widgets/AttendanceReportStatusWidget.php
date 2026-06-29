<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceReport;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendanceReportStatusWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalReports = AttendanceReport::count();
        $draftReports = AttendanceReport::where('status', 'draft')->count();
        $completedReports = AttendanceReport::where('status', 'completed')->count();
        $totalOvertimeHours = AttendanceReport::sum('total_overtime');
        return [
            // Stat::make('Total Reports', $totalReports)
            //     ->description('Attendance reports')
            //     ->descriptionIcon('heroicon-m-document-text')
            //     ->color('primary'),

            // Stat::make('Draft Reports', $draftReports)
            //     ->description('Need review')
            //     ->descriptionIcon('heroicon-m-clock')
            //     ->color('warning'),

            // Stat::make('Completed Reports', $completedReports)
            //     ->description('Ready for payroll')
            //     ->descriptionIcon('heroicon-m-check-circle')
            //     ->color('success'),

            // Stat::make('Overtime Hours', number_format($totalOvertimeHours, 1))
            //     ->description('Accumulated overtime')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->color('info'),
        ];
    }
}
