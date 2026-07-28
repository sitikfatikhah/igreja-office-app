<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compensations extends Model
{
    protected $fillable = [
        'basic_salary',
        'effective_date',
        'is_active',
        'notes',
    ];
    
    public function user()
    {
        return $this->hasMany(User::class, 'compensation_id');
    }
}
