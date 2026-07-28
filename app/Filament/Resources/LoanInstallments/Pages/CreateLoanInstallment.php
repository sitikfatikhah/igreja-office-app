<?php

namespace App\Filament\Resources\LoanInstallments\Pages;

use App\Filament\Resources\LoanInstallments\LoanInstallmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoanInstallment extends CreateRecord
{
    protected static string $resource = LoanInstallmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
