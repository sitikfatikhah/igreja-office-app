<?php

namespace App\Filament\Resources\Enrollments\Pages;

use App\Filament\Resources\Enrollments\EnrollmentResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateEnrollment extends CreateRecord
{
    // protected static string $resource = EnrollmentResource::class;

    /**
     * Sembunyikan tombol bawaan Filament (Create, Create & create another,
     * Cancel) sepenuhnya dari halaman ini.
     *
     * Sama seperti CreateAttendance: ini mencegah user mengklik tombol
     * "Create" bawaan secara langsung, yang akan submit form TANPA melalui
     * alur custom kita (kamera → capture wajah → submitEnrollment()).
     * Satu-satunya cara submit adalah lewat tombol custom "Save enrollment"
     * di enroll-face.blade.php.
     */
    protected function getFormActions(): array
    {
        return [];
    }

    /**
     * Dipanggil langsung dari JS via $wire.submitEnrollment(...).
     *
     * PENTING: enrollment TIDAK membuat record baru di tabel terpisah —
     * data wajah disimpan langsung sebagai kolom di tabel `users`
     * (face_descriptor, reference_photo), sama seperti perilaku
     * EnrollFaceController sebelumnya. Karena itu method ini menulis
     * langsung ke auth()->user() dan TIDAK memanggil $this->create()
     * (yang akan mencoba membuat record Eloquent baru sesuai model
     * resource ini, yang bukan itu yang kita inginkan).
     *
     * Data tetap dikirim sebagai ARGUMEN method call (bukan di-set ke
     * property form satu-satu lalu submit terpisah), mengikuti pola yang
     * sama dengan CreateAttendance::submitAttendance() — selalu sampai
     * utuh dalam SATU request Livewire yang atomic, menggantikan
     * pendekatan lama yang memakai endpoint API tersendiri (fetch() +
     * CSRF token manual ke EnrollFaceController).
     */
    // public function submitEnrollment(
    //     string $descriptor,
    //     string $referencePhoto,
    // ): void {
    //     $user = auth()->user();

    //     logger()->info('SUBMIT ENROLLMENT CALLED', [
    //         'user_id' => $user->id,
    //         'descriptor_length' => strlen($descriptor),
    //         'reference_photo_length' => strlen($referencePhoto),
    //     ]);

    //     $user->face_descriptor = $descriptor;
    //     $user->reference_photo = $referencePhoto;
    //     $user->save();

    //     Notification::make()
    //         ->title('Face enrollment berhasil disimpan.')
    //         ->success()
    //         ->send();

    //     $this->redirect(EnrollmentResource::getUrl('index'));
    // }
}