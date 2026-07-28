<?php

namespace App\Filament\Resources\LoanInstallments\Pages;

use App\Filament\Resources\LoanInstallments\LoanInstallmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoanInstallment extends EditRecord
{
    protected static string $resource = LoanInstallmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),   
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
