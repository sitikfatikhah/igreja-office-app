<?php

namespace App\Filament\Resources\LeaveDepositBalances\Pages;

use App\Filament\Resources\LeaveDepositBalances\LeaveDepositBalanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLeaveDepositBalance extends EditRecord
{
    protected static string $resource = LeaveDepositBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
