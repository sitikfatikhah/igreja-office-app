<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compensations extends Model
{
    protected $fillable = [
        'basic_salary',
        'position_allowance',
        'transport_allowance',
        'meal_allowance',
        'communication_allowance',
        'health_benefit',
        'insurance_benefit',
        'retirement_benefit',
        'effective_date',
        'end_date',
        'is_active',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
