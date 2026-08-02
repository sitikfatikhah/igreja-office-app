<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceReport;
use App\Models\LeaveRequest;
use App\Models\Overtimes;
use App\Models\Payrolls;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendingApprovalWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;
    
    protected ?string $heading = 'Pusat Tindakan';

    protected function getStats(): array
    {
        return [
        //     Stat::make(
        //         'Pending Leave',
        //         LeaveRequest::where('approval_status', 'pending')->count()
        //     )
        //         ->description('Awaiting leave approval')
        //         ->descriptionIcon('heroicon-m-calendar-days')
        //         ->color('warning'),

        //     Stat::make(
        //         'Pending Overtime',
        //         Overtimes::where('approval_status', 'pending')->count()
        //     )
        //         ->description('Awaiting overtime approval')
        //         ->descriptionIcon('heroicon-m-clock')
        //         ->color('warning'),

        //     Stat::make(
        //         'Draft Attendance',
        //         AttendanceReport::where('status', 'draft')->count()
        //     )
        //         ->description('Reports need review')
        //         ->descriptionIcon('heroicon-m-document-text')
        //         ->color('info'),

        //     Stat::make(
        //         'Draft Payroll',
        //         Payrolls::where('status', 'draft')->count()
        //     )
        //         ->description('Payrolls need processing')
        //         ->descriptionIcon('heroicon-m-banknotes')
        //         ->color('danger'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}