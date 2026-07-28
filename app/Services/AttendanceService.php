<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Parse coordinate inputs from form payload.
     */
    public function parseCoordinates(array $data): array
    {
        $latitude = isset($data['latitude']) && $data['latitude'] !== '' && is_numeric($data['latitude'])
            ? (float) $data['latitude']
            : null;

        $longitude = isset($data['longitude']) && $data['longitude'] !== '' && is_numeric($data['longitude'])
            ? (float) $data['longitude']
            : null;

        $locationName = $data['location_name'] ?? null;

        return [$latitude, $longitude, $locationName];
    }

    /**
     * Check whether given coordinates are within allowed office radius.
     */
    public function isCheckInLocationAllowed(?float $latitude, ?float $longitude): bool
    {
        if ($latitude === null || $longitude === null) {
            return false;
        }

        $distance = Attendance::calculateDistanceMeters(
            $latitude,
            $longitude,
            Attendance::officeLatitude(),
            Attendance::officeLongitude()
        );

        return $distance <= Attendance::officeRadius();
    }

    /**
     * Get today's attendance record for a user.
     */
    public function getAttendanceToday(User $user): ?Attendance
    {
        return Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();
    }

    /**
     * Perform check-out update on existing attendance record.
     */
    public function handleCheckOut(Attendance $attendance, ?float $latitude, ?float $longitude, ?string $locationName): Attendance
    {
        $attendance->update([
            'check_out' => now(),
            'check_out_latitude' => $latitude,
            'check_out_longitude' => $longitude,
            'check_out_location_name' => $locationName,
        ]);

        return $attendance->fresh();
    }

    /**
     * Prepare form data for creating a new check-in attendance.
     */
    public function prepareCheckInData(User $user, ?float $latitude, ?float $longitude, ?string $locationName, array $formState = []): array
    {
        return array_replace($formState, [
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now(),
            'nip' => $user->nip,
            'position' => $user->position,
            'check_in_latitude' => $latitude,
            'check_in_longitude' => $longitude,
            'check_in_location_name' => $locationName,
        ]);
    }
}
