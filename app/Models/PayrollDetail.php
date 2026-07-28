<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollDetail extends Model
{
    use SoftDeletes;

    protected $table = 'payroll_details';

    protected $fillable = [
        'payroll_id',
        'user_id',

        'type',            // earning | deduction
        'category',        // basic_salary, overtime, allowance, loan, tax, leave

        'reference_type',  // LoanInstallment, Allowance, LeaveRequest dll
        'reference_id',

        'description',

        'qty',
        'rate',

        'amount',
    ];

    protected $casts = [
        'qty'    => 'decimal:2',
        'rate'   => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payrolls::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}