<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Validator;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Validator::make($data, [
            'email' => ['required', 'email', 'unique:users,email'],
        ])->validate();

        return parent::mutateFormDataBeforeCreate($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
