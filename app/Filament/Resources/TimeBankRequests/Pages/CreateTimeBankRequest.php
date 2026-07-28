<?php

namespace App\Filament\Resources\TimeBankRequests\Pages;

use App\Filament\Resources\TimeBankRequests\TimeBankRequestResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTimeBankRequest extends CreateRecord
{
    protected static string $resource = TimeBankRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->success()
            ->title('Permintaan Time Bank berhasil dibuat')
            ->body('Permintaan time bank berhasil dibuat.')
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return TimeBankRequestResource::getUrl('index');
    }
}
