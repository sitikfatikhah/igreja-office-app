<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'nip', 'position', 'department', 'allowance_id', 'compensation_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use Notifiable, SoftDeletes, HasRoles;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('super_admin');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'face_descriptor' => 'array',
        'reference_photo' => 'string',
        'position' => 'string',
        'nip' => 'string',
        'department' => 'string',
    ];
    
    public function workSchedule() : HasMany
    {
        return $this->hasMany(EmployeeWorkSchedule::class);
    }
    public function compensation()
    {
        return $this->belongsTo(Compensations::class, 'compensation_id');
    }
    public function allowance() : BelongsToMany
    {
        return $this->belongsToMany(Allowance::class);
    }
    public function annualLeaveBalance()
    {
        return $this->hasOne(AnnualLeaveBalance::class);
    }
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
    public function employeeLoan()
    {
        return $this->hasMany(EmployeeLoan::class);
    }
    public function payroll()
    {
        return $this->hasMany(Payrolls::class);
    }
    public function timeBankRequest()
    {
        return $this->hasOne(TimeBankRequest::class);
    }
    public function employeeWorkSchedules(): HasMany
    {
        return $this->hasMany(EmployeeWorkSchedule::class);
    }

    public function latestWorkSchedule(): HasOne
    {
        return $this->hasOne(EmployeeWorkSchedule::class)
            ->latestOfMany('effective_from');
    }

}
