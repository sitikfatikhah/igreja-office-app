<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll_details extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'payroll_id',
        'basic_salary',
        'overtime_pay',
        'allowances',
        'deductions',
        'total_salary',
        'attendance',
        'description',
    ];
}
