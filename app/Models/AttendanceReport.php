<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class AttendanceReport extends Model
{
    protected $appends = ['attendances_in_period'];
    
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'position',
        'start_date',
        'end_date',
        'total_hours',
        'total_late',
        'total_overtime',
        'status',
        'report_date',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function overtime(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'user_id', 'user_id');
    }
    public function payroll(): HasMany
    {
        return $this->hasMany(Payrolls::class, 'attendance_report_id');
    }
    public function getAttendancesInPeriodAttribute(): Collection
    {
        return Attendance::where('user_id', $this->user_id)
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->orderBy('date')
            ->get();
    }
    
}
