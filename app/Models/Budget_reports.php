<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget_reports extends Model
{
    use SoftDeletes;

    protected $fillable =[
        'payroll_id',
        'total_salary_paid',
        'total_overtime_paid',
        'total_deductions',
        'total_budget_used',
        'created_at',
        'updated_at',
    ];
}
