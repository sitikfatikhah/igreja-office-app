<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',

        'nip',
        'position',

        'date',
        'check_in',
        'check_out',

        'check_in_latitude',
        'check_in_longitude',
        'check_in_location_name',

        'check_out_latitude',
        'check_out_longitude',
        'check_out_location_name',

        'verification_score',
        'verification_method',
        'face_verified',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'verification_score' => 'float',
        'face_verified' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNipAttribute($value): ?string
    {
        return $value ?: $this->user?->nip;
    }

    public function getPositionAttribute($value): ?string
    {
        return $value ?: $this->user?->position;
    }

    public static function officeLatitude(): float
    {
        return (float) config('attendance.office_latitude');
    }

    public static function officeLongitude(): float
    {
        return (float) config('attendance.office_longitude');
    }

    public static function officeRadius(): float
    {
        return (float) config('attendance.radius', 200);
    }

    public static function calculateDistanceMeters(
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

    public static function isWithinOfficeRadius(?float $latitude, ?float $longitude): bool
    {
        if ($latitude === null || $longitude === null) {
            return false;
        }

        $distance = self::calculateDistanceMeters(
            $latitude,
            $longitude,
            self::officeLatitude(),
            self::officeLongitude(),
        );

        return $distance <= self::officeRadius();
    }

    public function isCheckedOut(): bool
    {
        return ! is_null($this->check_out);
    }

    public function getTotalHoursAttribute(): float
    {
        if (! $this->check_in || ! $this->check_out) {
            return 0;
        }

        return round(
            $this->check_in->diffInMinutes($this->check_out) / 60,
            2
        );
    }

    public function getIsLateAttribute(): bool
    {
        if (! $this->check_in) {
            return false;
        }

        $officeHour = $this->check_in
            ->copy()
            ->setTime(8, 0);

        return $this->check_in->greaterThan($officeHour);
    }

    public function getOvertimeHoursAttribute(): float
    {
        return $this->total_hours > 8
            ? round($this->total_hours - 8, 2)
            : 0;
    }
}