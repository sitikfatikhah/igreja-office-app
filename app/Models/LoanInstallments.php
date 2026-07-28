<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanInstallments extends Model
{
    protected $fillable = [
        'employee_loan_id',
        'payroll_id',
        'amount',
        'status',
        'deducted_at',
    ];

    protected $casts = ['deducted_at' => 'date', 'amount' => 'float'];

    public function employeeLoan(): BelongsTo
    {
        return $this->belongsTo(EmployeeLoan::class, 'employee_loan_id');
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payrolls::class, 'payroll_id');
    }
}
