<?php

namespace App\Filament\Resources\TimeBankRequests\Pages;

use App\Filament\Resources\TimeBankRequests\TimeBankRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTimeBankRequests extends ListRecords
{
    protected static string $resource = TimeBankRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
