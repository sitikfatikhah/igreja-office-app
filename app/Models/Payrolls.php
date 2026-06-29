<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'deductions', // ada view untuk deduction detail
        'additions', // ada view untuk addition detail
        'generated_at',
        'status',
        'total_hours',
        'total_overtime',
        'total_late',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'gross_pay' => 'float',
        'additions' => 'float',
        'deductions' => 'float',
        'net_pay' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function compensation()
    {
        return $this->belongsTo(Compensations::class, 'user_id', 'user_id');
    }

    public function attendanceReport()
    {
        return $this->belongsTo(AttendanceReport::class, 'attendance_report_id');
    }

   

}
