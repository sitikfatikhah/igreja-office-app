<?php

namespace App\Filament\Widgets;

use App\Models\LeaveRequest;
use Filament\Widgets\ChartWidget;

class LeaveRequestChart extends ChartWidget
{
    protected static ?int $sort = 5;
    
    protected ?string $heading = 'Leave Request Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'data' => [
                        LeaveRequest::where('approval_status', 'pending')->count(),
                        LeaveRequest::where('approval_status', 'approved')->count(),
                        LeaveRequest::where('approval_status', 'rejected')->count(),
                    ]
                ]
            ],
            'labels' => ['Pending', 'Approved', 'Rejected'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
