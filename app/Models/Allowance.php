<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
