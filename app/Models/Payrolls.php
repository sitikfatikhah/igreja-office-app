<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payrolls extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'pay_period',
        'gross_pay',
        'net_pay',
        'deductions',
        'additions',
        'generated_at',
        'status',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'generated_at' => 'datetime',
    ];
}
