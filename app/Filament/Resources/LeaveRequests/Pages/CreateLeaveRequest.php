<?php

namespace App\Filament\Resources\LeaveRequests\Pages;

use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Leave request created successfully.')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return LeaveRequestResource::getUrl('index');
    }
}
