<?php

namespace App\Filament\Resources\Compensation\Pages;

use App\Filament\Resources\Compensation\CompensationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCompensation extends ViewRecord
{
    protected static string $resource = CompensationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
