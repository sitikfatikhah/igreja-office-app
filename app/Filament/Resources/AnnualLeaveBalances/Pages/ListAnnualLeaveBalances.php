<?php

namespace App\Filament\Resources\AnnualLeaveBalances\Pages;

use App\Filament\Resources\AnnualLeaveBalances\AnnualLeaveBalanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAnnualLeaveBalances extends ListRecords
{
    protected static string $resource = AnnualLeaveBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Create Annual Leave Balance')
            ->url(static::getResource()::getUrl('create')),
        ];
    }
    
}
