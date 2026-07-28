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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $report = AttendanceReport::findOrFail($data['attendance_report_id']);

        // Generate payroll via service to ensure calculations and details
        // are centralized in App\Services\PayrollService.
        app(PayrollService::class)->generate($report);

        // Prevent the default record creation by returning an empty payload
        // and redirecting to index (service already persisted payroll).
        $this->redirect(self::getUrl('index'));
        $this->halt();

        return [];
    }

    #[Override]
    protected function getRedirectUrl(): string
    {
        return PayrollResource::getUrl('index');
    }
}