<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceReport;
use App\Models\OvertimeRequest;
use App\Models\Overtimes;
use App\Models\Payrolls;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OvertimesStats extends StatsOverviewWidget
{
    protected static ?int $sort = 2;
    
    protected ?string $heading = 'Overtime Analytics';
    
    protected ?string $description = 'An overview of overtime analytics.';

    public function getColumns(): int | array
    {
        return [
            'md' => 3,
            'lg' => 3,
            'xl' => 4,
            '2xl' => 4,
        ];
    }

    protected function getStats(): array
    {
        $totalRequests = Overtimes::count();
        $pendingRequests = Overtimes::where('approval_status', 'pending')->count();
        $approvedRequests = Overtimes::where('approval_status', 'approved')->count();
        return [
            Stat::make('Overtime Requests', number_format($totalRequests))
                ->description('Total Overtime requests')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Pending', number_format($pendingRequests))
                ->description('Pending Overtime requests')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Approved', number_format($approvedRequests))
                ->description('Approved Overtime requests')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
           ];
    }
}
