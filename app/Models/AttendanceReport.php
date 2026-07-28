<?php

namespace App\Models;

use App\Services\AttendanceReportService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class AttendanceReport extends Model
{
    protected $appends = ['attendances_in_period', 'total_leave_days'];
    
    protected $casts = ['attendances_in_period' => 'array',];
    
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'position',
        'start_date',
        'end_date',
        'total_present',
        'total_absent',
        'total_overtime',
        'status',
        'report_date',
        'description',
        'attendances_in_period',
        'total_hours'
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
    public function payroll(): HasOne
    {
        return $this->hasOne(Payrolls::class, 'attendance_report_id');
    }
    public function getAttendancesInPeriodAttribute(): Collection
    {
        return app(AttendanceReportService::class)->getAttendancesInPeriod($this);
    }

    public function getTotalLeaveDaysAttribute(): int
    {
        return app(AttendanceReportService::class)->getTotalLeaveDays($this);
    }
    
    
}