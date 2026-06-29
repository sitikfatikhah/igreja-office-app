<?php

namespace App\Filament\Resources\TimeBankRequests\Pages;

use App\Filament\Resources\TimeBankRequests\TimeBankRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTimeBankRequest extends EditRecord
{
    protected static string $resource = TimeBankRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return TimeBankRequestResource::getUrl('index');
    }
}
