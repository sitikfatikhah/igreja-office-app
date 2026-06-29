<?php

namespace App\Filament\Widgets;

use App\Models\LeaveRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeaveStats extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

        protected ?string $heading = 'Leave Analytics';
        
        protected ?string $description = 'An overview of leave analytics.';

    protected function getStats(): array
    {
        $totalRequests = LeaveRequest::count();
        $pendingRequests = LeaveRequest::where('approval_status', 'pending')->count();
        $approvedRequests = LeaveRequest::where('approval_status', 'approved')->count();
        $rejectedRequests = LeaveRequest::where('approval_status', 'rejected')->count();
        $totalLeaveDays = LeaveRequest::sum('total_days');
        return [
            Stat::make('Leave Requests', number_format($totalRequests))
                ->description('Total leave requests')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Pending', number_format($pendingRequests))
                ->description('Pending leave requests')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Approved', number_format($approvedRequests))
                ->description('Approved leave requests')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Leave Days', number_format($totalLeaveDays))
                ->description('Total leave days requested')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
