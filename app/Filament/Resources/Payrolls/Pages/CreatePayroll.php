<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Clusters\Payrolls\PayrollsCluster;
use App\Filament\Resources\Payrolls\PayrollResource;
use App\Models\AttendanceReport;
use Filament\Resources\Pages\CreateRecord;

class CreatePayroll extends CreateRecord
{
    protected static string $resource = PayrollResource::class;

    protected static ?string $cluster = PayrollsCluster::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['generated_at'] = now();

        $data['status'] = 'generated';

        return $data;
    }
}