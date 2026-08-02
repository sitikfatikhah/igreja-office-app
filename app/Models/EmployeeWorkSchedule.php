<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeWorkSchedule extends Model
{
    protected $fillable = [
        'user_id',
        'effective_from',
        'effective_until',
        'off_days',
        'remarks',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_until' => 'date',
        'off_days' => 'array',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
