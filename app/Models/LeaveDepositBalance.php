<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveDepositBalance extends Model
{
    protected $fillable = [
        'user_id',
        'time_bank_request_id',
        'days',
        'type',
        'balance',
        'description'
    ];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function timeBankRequest(): BelongsTo
    {
        return $this->belongsTo(TimeBankRequest::class, 'time_bank_request_id');
    }

}
