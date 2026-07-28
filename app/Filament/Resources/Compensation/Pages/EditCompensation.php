<?php

namespace App\Filament\Resources\Compensation\Pages;

use App\Filament\Resources\Compensation\CompensationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCompensation extends EditRecord
{
    protected static string $resource = CompensationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
