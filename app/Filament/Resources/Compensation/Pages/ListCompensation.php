<?php

namespace App\Filament\Resources\Compensation\Pages;

use App\Filament\Resources\Compensation\CompensationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompensation extends ListRecords
{
    protected static string $resource = CompensationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
