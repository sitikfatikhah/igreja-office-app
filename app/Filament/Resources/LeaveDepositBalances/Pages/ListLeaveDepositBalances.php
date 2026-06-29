<?php

namespace App\Filament\Resources\LeaveDepositBalances\Pages;

use App\Filament\Resources\LeaveDepositBalances\LeaveDepositBalanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveDepositBalances extends ListRecords
{
    protected static string $resource = LeaveDepositBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
