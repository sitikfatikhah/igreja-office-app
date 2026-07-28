<?php

namespace App\Filament\Resources\EmployeeLoans\Pages;

use App\Filament\Resources\EmployeeLoans\EmployeeLoanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeLoan extends CreateRecord
{
    protected static string $resource = EmployeeLoanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['remaining_balance'] = $data['total_amount'];

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->notify('success', 'Pinjaman karyawan berhasil dibuat.');
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
