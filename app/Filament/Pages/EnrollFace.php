<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Attendances\AttendancesCluster;
use App\Filament\Resources\Attendances\AttendanceResource;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

use function Laravel\Prompts\notify;

class EnrollFace extends Page
{
    protected string $view = 'filament.pages.enroll-face';

    protected static ?string $cluster = AttendancesCluster::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCamera;
    
    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $title = 'Pendaftaran Wajah';

    protected static ?string $navigationLabel = 'Pendaftaran Wajah';
    

    public ?string $descriptor = null;
    public ?string $referencePhoto = null;

    public function saveFace(?string $descriptor = null, ?string $referencePhoto = null): void
    {
        $descriptor = $descriptor ?? $this->descriptor;
        $referencePhoto = $referencePhoto ?? $this->referencePhoto;

        $this->descriptor = $descriptor;
        $this->referencePhoto = $referencePhoto;

        if (empty($descriptor) || empty($referencePhoto)) {
            Notification::make()
                ->title('Pendaftaran gagal')
                ->body('Face descriptor dan reference photo harus tersedia sebelum menyimpan pendaftaran.')
                ->danger()
                ->send();

            return;
        }

        $user = auth()->user();
        $user->face_descriptor = $descriptor;
        $user->reference_photo = $referencePhoto;
        $user->save();

        Notification::make()
            ->title('Berhasil')
            ->body('Data wajah berhasil disimpan!')
            ->success()
            ->send();

        redirect()->to(AttendanceResource::getUrl('index'));
    }
}