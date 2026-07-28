<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'approval_status',
        'source',
        // 'leave_deposit_balance_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveDepositBalance(): BelongsTo
    {
        return $this->belongsTo(LeaveDepositBalance::class);
    }

    public function leaveBalance(): BelongsTo
    {
        return $this->belongsTo(LeaveBalances::class);
    }

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
