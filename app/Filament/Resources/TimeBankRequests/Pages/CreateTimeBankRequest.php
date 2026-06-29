<?php

namespace App\Filament\Resources\TimeBankRequests\Pages;

use App\Filament\Resources\TimeBankRequests\TimeBankRequestResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTimeBankRequest extends CreateRecord
{
    protected static string $resource = TimeBankRequestResource::class;

    protected function afterCreate(): void
    {
        Notification::make()
            ->success()
            ->title('Time Bank Request Created')
            ->body('The time bank request has been created successfully.')
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return TimeBankRequestResource::getUrl('index');
    }
}
