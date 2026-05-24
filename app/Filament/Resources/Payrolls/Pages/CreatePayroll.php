<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Clusters\Payrolls\PayrollsCluster;
use App\Filament\Resources\Payrolls\PayrollResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayroll extends CreateRecord
{
    protected static string $resource = PayrollResource::class;

    protected static ?string $cluster = PayrollsCluster::class;

    protected function getMutatedFormData(array $data): array
    {
        $data['user_id'] = auth()->id(); // Set user_id otomatis berdasarkan user yang sedang login
        $data['generated_at'] = now(); // Set generated_at otomatis ke waktu saat ini
        $data['status'] = 'pending'; // Set status default ke 'pending'

        return $data;
    }

    
}
