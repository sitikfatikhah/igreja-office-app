<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Clusters\Attendances\AttendancesCluster;
use App\Filament\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    protected static ?string $cluster = AttendancesCluster::class;

    /**
     * Sembunyikan tombol bawaan Filament (Create, Create & create another,
     * Cancel) sepenuhnya dari halaman ini.
     *
     * PENTING: ini untuk mencegah bug di mana user mengklik tombol "Create"
     * bawaan secara langsung — itu akan submit form Livewire standar TANPA
     * melalui alur custom kita (kamera → GPS → verifikasi wajah →
     * submitAttendance()), sehingga latitude/longitude masih null dan
     * selalu gagal dengan notifikasi "GPS belum ditemukan". Dengan tombol
     * bawaan disembunyikan, satu-satunya cara submit adalah lewat tombol
     * custom "Verify & Check In/Out" di face-attendance-script.blade.php.
     */
    protected function getFormActions(): array
    {
        return [];
    }

    /**
     * Dipanggil langsung dari JS via $wire.submitAttendance(...).
     *
     * Pendekatan ini dipilih (bukan component.set() berulang + form submit)
     * karena data dikirim sebagai ARGUMEN method call, bukan lewat property
     * yang di-set satu-satu lalu diharap "nyangkut" sebelum create() jalan.
     * Argumen method selalu sampai utuh dalam SATU request Livewire — tidak
     * ada lagi kemungkinan race condition, defer yang belum terkirim, atau
     * referensi komponen yang stale.
     */
    public function submitAttendance(
        ?float $latitude,
        ?float $longitude,
        ?string $locationName,
        bool $faceVerified,
        ?float $verificationScore,
    ): void {
        logger()->info('SUBMIT ATTENDANCE CALLED', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location_name' => $locationName,
            'face_verified' => $faceVerified,
            'verification_score' => $verificationScore,
        ]);

        $this->form->fill([
            ...$this->form->getState(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location_name' => $locationName,
            'face_verified' => $faceVerified,
            'verification_score' => $verificationScore,
            'verification_method' => 'face_recognition',
        ]);

        $this->create();
    }

    /**
     * Validasi lokasi absensi.
     */
    protected function isCheckInLocationAllowed(
            ?float $latitude,
            ?float $longitude
        ): bool {

        if ($latitude === null || $longitude === null) {

            logger()->warning('GPS NULL', [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);

            return false;
        }

        $distance = Attendance::calculateDistanceMeters(
            $latitude,
            $longitude,
            Attendance::officeLatitude(),
            Attendance::officeLongitude()
        );

        logger()->info('ATTENDANCE DISTANCE', [
            'user_latitude' => $latitude,
            'user_longitude' => $longitude,
            'office_latitude' => Attendance::officeLatitude(),
            'office_longitude' => Attendance::officeLongitude(),
            'distance_meter' => round($distance, 2),
            'allowed_radius' => Attendance::officeRadius(),
            'allowed' => $distance <= Attendance::officeRadius(),
        ]);

        return $distance <= Attendance::officeRadius();
    }

    /**
     * Sebelum create attendance.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        logger()->info('FORM DATA', $data);

        [$latitude, $longitude, $locationName] = app(AttendanceService::class)->parseCoordinates($data);

        logger()->warning('FINAL GPS CHECK', [
            'raw_lat' => $data['latitude'] ?? null,
            'raw_lng' => $data['longitude'] ?? null,
            'parsed_lat' => $latitude,
            'parsed_lng' => $longitude,
        ]);

        logger()->info('GPS RECEIVED', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location_name' => $locationName,
        ]);

        if (! is_numeric($latitude) || ! is_numeric($longitude)) {
            Notification::make()
                ->title('GPS belum ditemukan')
                ->body('Mohon tunggu hingga lokasi berhasil diperoleh.')
                ->danger()
                ->send();

            $this->halt();

            return [];
        }

        $user = auth()->user();

        $attendanceService = app(AttendanceService::class);

        $attendanceToday = $attendanceService->getAttendanceToday($user);

        // CHECK OUT
        if ($attendanceToday) {

            if ($attendanceToday->check_out) {
                Notification::make()
                    ->title('Check-out sudah dilakukan')
                    ->body('Anda sudah melakukan check-out hari ini.')
                    ->warning()
                    ->send();

                $this->halt();

                return [];
            }

            if (! $attendanceService->isCheckInLocationAllowed(
                $latitude,
                $longitude
            )) {

                Notification::make()
                    ->title('Check-out gagal')
                    ->body('Anda berada di luar area absen.')
                    ->danger()
                    ->send();

                $this->halt();

                return [];
            }

            $attendanceService->handleCheckOut($attendanceToday, $latitude, $longitude, $locationName);

            Notification::make()
                ->title('Check-out berhasil')
                ->success()
                ->send();

            $this->redirect(AttendanceResource::getUrl('index'));
            $this->halt();
        }

        // CHECK IN
        if (! $attendanceService->isCheckInLocationAllowed(
            $latitude,
            $longitude
        )) {

            Notification::make()
                ->title('Absen gagal')
                ->body('Anda berada di luar area absen.')
                ->danger()
                ->send();

            $this->halt();

            return [];
        }

        $data = $attendanceService->prepareCheckInData($user, $latitude, $longitude, $locationName, $data);

        unset(
            $data['latitude'],
            $data['longitude'],
            $data['location_name']
        );

        Notification::make()
            ->title('Check-in berhasil')
            ->success()
            ->send();

        return $data;
    }


    protected function getRedirectUrl(): string
    {
        return AttendanceResource::getUrl('index');
    }
}