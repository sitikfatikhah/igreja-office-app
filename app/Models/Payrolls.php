<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\LoanInstallments;

class Payrolls extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'attendance_report_id',
        'start_date',
        'end_date',
        'gross_pay',
        'net_pay',
        'deduction_total', // ada view untuk deduction detail
        'addition_total', // ada view untuk addition detail
        'loan_total', // ada view untuk addition detail
        'overtime_total', // ada view untuk addition detail
        'generated_at',
        'status',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'gross_pay' => 'float',
        'additions' => 'float',
        'deductions' => 'float',
        'net_pay' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendanceReport(): BelongsTo
    {
        return $this->belongsTo(AttendanceReport::class, 'attendance_report_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PayrollDetail::class, 'payroll_id', 'id');
    }

    public function loanInstallments(): HasMany
    {
        return $this->hasMany(LoanInstallments::class, 'payroll_id', 'id');
    }

    public function getAdditionsAttribute(): float
    {
        return $this->addition_total ?? 0;
    }

    public function getDeductionsAttribute(): float
    {
        return $this->deduction_total ?? 0;
    }

    public function getPayPeriodAttribute(): string
    {
        return sprintf('%s - %s', $this->start_date, $this->end_date);
    }

    public function getBaseSalaryAttribute(): float
    {
        if ($this->relationLoaded('details')) {
            return (float) $this->details
                ->where('category', 'basic_salary')
                ->sum('amount');
        }

        return (float) ($this->details()->where('category', 'basic_salary')->value('amount') ?? 0);
    }

    public function getOvertimePayAttribute(): float
    {
        if ($this->relationLoaded('details')) {
            return (float) $this->details
                ->where('category', 'overtime')
                ->sum('amount');
        }

        return (float) $this->details()->where('category', 'overtime')->sum('amount');
    }

    public function getOvertimeHoursAttribute(): float
    {
        if ($this->relationLoaded('details')) {
            return (float) $this->details
                ->where('category', 'overtime')
                ->sum('qty');
        }

        return (float) $this->details()->where('category', 'overtime')->sum('qty');
    }

    public function getAllowanceAttribute(): float
    {
        if ($this->relationLoaded('details')) {
            return (float) $this->details
                ->where('category', 'allowance')
                ->sum('amount');
        }

        return (float) $this->details()->where('category', 'allowance')->sum('amount');
    }
}
