<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Clusters\Attendances\AttendancesCluster;
use App\Filament\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\On;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    protected static ?string $cluster = AttendancesCluster::class;

   
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

        $distance = $this->calculateDistanceMeters(
            $latitude,
            $longitude,
            (float) config('attendance.office_latitude'),
            (float) config('attendance.office_longitude')
        );

        logger()->info('ATTENDANCE DISTANCE', [
            'user_latitude' => $latitude,
            'user_longitude' => $longitude,
            'office_latitude' => config('attendance.office_latitude'),
            'office_longitude' => config('attendance.office_longitude'),
            'distance_meter' => round($distance, 2),
            'allowed_radius' => config('attendance.radius'),
            'allowed' => $distance <= config('attendance.radius'),
        ]);

        Notification::make()
            ->title('Debug Lokasi')
            ->body('Jarak Anda: ' . round($distance) . ' meter')
            ->info()
            ->send();

        return $distance <= config('attendance.radius');
    }

    /**
     * Sebelum create attendance.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        logger()->info('FORM DATA', $data);

        $latitude = isset($data['latitude'])
            ? (float) $data['latitude']
            : null;

        $longitude = isset($data['longitude'])
            ? (float) $data['longitude']
            : null;

        $locationName = $data['location_name'] ?? null;

        logger()->info('GPS RECEIVED', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location_name' => $locationName,
        ]);

        if ($latitude === null || $longitude === null) {

            Notification::make()
                ->title('GPS belum ditemukan')
                ->body('Mohon tunggu hingga lokasi berhasil diperoleh.')
                ->danger()
                ->send();

            $this->halt();

            return [];
        }

        $user = auth()->user();

        $attendanceToday = Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();

        // CHECK OUT
        if ($attendanceToday) {

            if (! $this->isCheckInLocationAllowed(
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

            $attendanceToday->update([
                'check_out' => now(),
                'check_out_latitude' => $latitude,
                'check_out_longitude' => $longitude,
                'check_out_location_name' => $locationName,
            ]);

            Notification::make()
                ->title('Check-out berhasil')
                ->success()
                ->send();

            $this->halt();

            $this->redirect(
                AttendanceResource::getUrl('index')
            );

            return [];
        }

        // CHECK IN
        $data['user_id'] = $user->id;
        $data['date'] = today();
        $data['check_in'] = now();
        $data['nip'] = $user->nip;
        $data['position'] = $user->position;

        if (! $this->isCheckInLocationAllowed(
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

        $data['check_in_latitude'] = $latitude;
        $data['check_in_longitude'] = $longitude;
        $data['check_in_location_name'] = $locationName;

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

    /**
     * Hitung jarak GPS.
     */
    protected function calculateDistanceMeters(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
        ): float {

            $earthRadius = 6371000;

            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);

            $a =
                sin($dLat / 2) * sin($dLat / 2)
                + cos(deg2rad($lat1))
                * cos(deg2rad($lat2))
                * sin($dLon / 2)
                * sin($dLon / 2);

            $c = 2 * atan2(
                sqrt($a),
                sqrt(1 - $a)
            );

            return $earthRadius * $c;
    }

    protected function getRedirectUrl(): string
    {
        return AttendanceResource::getUrl('index');
    }
}