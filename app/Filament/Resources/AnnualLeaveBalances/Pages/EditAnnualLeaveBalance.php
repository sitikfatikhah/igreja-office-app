<?php

namespace App\Filament\Resources\AnnualLeaveBalances\Pages;

use App\Filament\Resources\AnnualLeaveBalances\AnnualLeaveBalanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAnnualLeaveBalance extends EditRecord
{
    protected static string $resource = AnnualLeaveBalanceResource::class;

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
