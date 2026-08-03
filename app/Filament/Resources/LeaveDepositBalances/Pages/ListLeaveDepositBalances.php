<?php

namespace App\Filament\Resources\LeaveDepositBalances\Pages;

use App\Filament\Resources\LeaveDepositBalances\LeaveDepositBalanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveDepositBalances extends ListRecords
{
    protected static string $resource = LeaveDepositBalanceResource::class;

    public function getHeading(): string
    {
        return 'Saldo Simpanan Cuti';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola saldo simpanan cuti karyawan.';
    }

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
