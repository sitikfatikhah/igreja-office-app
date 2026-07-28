<?php

namespace App\Filament\Resources\AnnualLeaveBalances\Pages;

use App\Filament\Resources\AnnualLeaveBalances\AnnualLeaveBalanceResource;
use App\Models\AnnualLeaveBalance;
use Filament\Resources\Pages\CreateRecord;
use Override;

class CreateAnnualLeaveBalance extends CreateRecord
{
    protected static string $resource = AnnualLeaveBalanceResource::class;

    #[Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        $currentBalance = AnnualLeaveBalance::currentBalance($data['user_id'], $data['year']);
        $newBalance = match ($data['type']) {
            'balanced' => $data['days'],
            'credit'   => $currentBalance + $data['days'],
            'debit'    => $currentBalance - $data['days'],
            default    => $currentBalance,
        };
        $data['balanced'] = $newBalance;

        return ($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
