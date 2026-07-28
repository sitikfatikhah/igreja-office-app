<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalances extends Model
{
    protected $fillable = [
        'user_id',
        'leave_request_id',
        'year',
        'leave_type',
        'days',
        'adjustment', //credit or debit
        'balanced',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }
    public static function currentBalance(int $userId, ?int $year = null): int
    {
        $year ??= now()->year;

        return static::where('user_id', $userId)
            ->where('year', $year)
            ->latest('id')
            ->value('balanced') ?? 0;
    }
}
