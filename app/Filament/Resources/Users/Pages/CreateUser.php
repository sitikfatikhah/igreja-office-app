<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Override;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    
    
    #[Override]
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {

    //     if (isset($data['email'])) {
    //         $user = DB::table('users')->where('email', $data['email'])->first();

    //         if ($user) {
    //             Notification::make()
    //                 ->title('User already exists!')
    //                 ->body('A user with this email already exists. Please use a different email address.')
    //                 ->danger()
    //             ->send();

    //         $this->halt();
    //     }
    // }

    //     return parent::mutateFormDataBeforeCreate($data);
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
