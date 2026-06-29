<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceReport;
use App\Models\LeaveRequest;
use App\Models\Payrolls;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RecentActivitiesWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            // Stat::make(
            //     'New Leave Requests',
            //     LeaveRequest::whereDate('created_at', today())->count()
            // )
            //     ->description('Submitted today')
            //     ->descriptionIcon('heroicon-m-calendar-days')
            //     ->color('warning'),

            // Stat::make(
            //     'Attendance Reports',
            //     AttendanceReport::whereDate('created_at', today())->count()
            // )
            //     ->description('Created today')
            //     ->descriptionIcon('heroicon-m-document-text')
            //     ->color('info'),

            // Stat::make(
            //     'Payroll Generated',
            //     Payrolls::whereDate('created_at', today())->count()
            // )
            //     ->description('Generated today')
            //     ->descriptionIcon('heroicon-m-banknotes')
            //     ->color('success'),

            // Stat::make(
            //     'Pending Leave',
            //     LeaveRequest::where('approval_status', 'pending')->count()
            // )
            //     ->description('Need review')
            //     ->descriptionIcon('heroicon-m-clock')
            //     ->color('danger'),
        ];
    }
}
