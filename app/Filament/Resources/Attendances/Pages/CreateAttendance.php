<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Clusters\Attendances\AttendancesCluster;
use App\Filament\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;
    protected static ?string $cluster = AttendancesCluster::class;

    /**
     * Isi otomatis sebelum record dibuat melalui form Filament.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        
        // Debug sementara (aktifkan jika ingin melihat data yang dikirim form)
        // dd($data);

        // Cari absensi user pada hari ini
        $attendanceToday = Attendance::where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();

        /*
        |--------------------------------------------------------------------------
        | CHECK-OUT
        |--------------------------------------------------------------------------
        | Jika sudah ada absensi hari ini, update kolom check_out.
        | Data GPS diambil dari field sementara:
        | - latitude
        | - longitude
        | - location_name
        */
        if ($attendanceToday) {
            $attendanceToday->update([
                'check_out' => now(),
                'check_out_latitude' => $data['latitude'] ?? null,
                'check_out_longitude' => $data['longitude'] ?? null,
                'check_out_location_name' => $data['location_name'] ?? null,
            ]);

            Notification::make()
                ->title('Check-out berhasil!')
                ->body('Sampai jumpa, ' . $user->name . '! Anda telah melakukan check-out.')
                ->success()
                ->send();

            // Hentikan proses create record baru
            $this->halt();

            // Redirect ke halaman index
            $this->redirect(AttendanceResource::getUrl('index'));

            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK-IN
        |--------------------------------------------------------------------------
        */
        $data['user_id'] = $user->id;
        $data['date'] = today();
        $data['check_in'] = now();
        $data['position'] = $user->position ?? null;
        $data['nip'] = $user->nip ?? null;

        // Mapping data GPS dari hidden field sementara
        $data['check_in_latitude'] = $data['latitude'] ?? null;
        $data['check_in_longitude'] = $data['longitude'] ?? null;
        $data['check_in_location_name'] = $data['location_name'] ?? null;

        // Hapus field sementara agar tidak ikut disimpan
        unset(
            $data['latitude'],
            $data['longitude'],
            $data['location_name']
        );

        Notification::make()
            ->title('Check-in berhasil!')
            ->body('Selamat datang, ' . $user->name . '! Anda telah melakukan check-in.')
            ->success()
            ->send();

        return $data;
    }

    /**
     * Redirect default setelah CreateRecord berhasil.
     */
    protected function getRedirectUrl(): string
    {
        return AttendanceResource::getUrl('index');
    }
}