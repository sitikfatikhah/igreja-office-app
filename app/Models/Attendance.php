<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\AttendanceReportService;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',

        // Data karyawan
        'nip',
        'position',

        // Tanggal & waktu
        'date',
        'check_in',
        'check_out',

        // Lokasi check-in
        'check_in_latitude',
        'check_in_longitude',
        'check_in_location_name',

        // Lokasi check-out
        'check_out_latitude',
        'check_out_longitude',
        'check_out_location_name',

        // Verifikasi wajah
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
    

    /**
     * Relasi ke user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor fallback:
     * Jika nip kosong, ambil dari user.
     */
    public function getNipAttribute($value): ?string
    {
        return $value ?: $this->user?->nip;
    }

    protected static function booted(): void
    {
        static::saved(function ($attendance) {

            app(AttendanceReportService::class)
                ->generate($attendance);
        });
    }
}