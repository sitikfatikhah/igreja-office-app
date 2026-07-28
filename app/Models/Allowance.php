<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Allowance extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'description',
        'calculation_type'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'allowance_id');
    }
}
