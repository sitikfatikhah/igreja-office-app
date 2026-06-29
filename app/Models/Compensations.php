<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compensations extends Model
{
    protected $fillable = [
        'user_id',
        'basic_salary',
        'effective_date',
        'is_active',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
