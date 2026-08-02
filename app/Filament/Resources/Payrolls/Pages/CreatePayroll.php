<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Clusters\Payrolls\PayrollsCluster;
use App\Filament\Resources\Payrolls\PayrollResource;
use App\Models\AttendanceReport;
use App\Models\Payrolls;
use App\Services\PayrollService;
use Filament\Resources\Pages\CreateRecord;
use Override;

class CreatePayroll extends CreateRecord
{
    protected static string $resource = PayrollResource::class;

    protected static ?string $cluster = PayrollsCluster::class;

    protected function handleRecordCreation(array $data): Payrolls
    {
        $report = AttendanceReport::findOrFail($data['attendance_report_id']);

        return app(PayrollService::class)->generate($report);
    }

    #[Override]
    protected function getRedirectUrl(): string
    {
        return PayrollResource::getUrl('index');
    }
}