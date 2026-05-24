<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Overtimes extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'position',
        'overtime_date',
        'start_time',
        'end_time',
        'total_hours',
        'description',
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
}
