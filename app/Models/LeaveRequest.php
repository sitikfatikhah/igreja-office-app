<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
