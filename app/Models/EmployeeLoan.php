<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLoan extends Model
{
    use SoftDeletes;

        protected $fillable = [
            'user_id', 'total_amount', 'installment_count',
            'installment_amount', 'remaining_balance',
            'start_date', 'status', 'description',
        ];

    protected $casts = [
            'total_amount' => 'float',
            'installment_amount' => 'float',
            'remaining_balance' => 'float',
            'start_date' => 'date',
        ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(LoanInstallments::class);
    }
}
