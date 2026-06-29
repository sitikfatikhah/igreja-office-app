<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeBankRequest extends Model
{
    protected $fillable = [
        'user_id',
        'position',
        'request_date',
        'approval_status',
        'approved_by',
        'reason',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
