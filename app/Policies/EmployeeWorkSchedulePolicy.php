<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EmployeeWorkSchedule;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeWorkSchedulePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EmployeeWorkSchedule');
    }

    public function view(AuthUser $authUser, EmployeeWorkSchedule $employeeWorkSchedule): bool
    {
        return $authUser->can('View:EmployeeWorkSchedule');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EmployeeWorkSchedule');
    }

    public function update(AuthUser $authUser, EmployeeWorkSchedule $employeeWorkSchedule): bool
    {
        return $authUser->can('Update:EmployeeWorkSchedule');
    }

    public function delete(AuthUser $authUser, EmployeeWorkSchedule $employeeWorkSchedule): bool
    {
        return $authUser->can('Delete:EmployeeWorkSchedule');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:EmployeeWorkSchedule');
    }

    public function restore(AuthUser $authUser, EmployeeWorkSchedule $employeeWorkSchedule): bool
    {
        return $authUser->can('Restore:EmployeeWorkSchedule');
    }

    public function forceDelete(AuthUser $authUser, EmployeeWorkSchedule $employeeWorkSchedule): bool
    {
        return $authUser->can('ForceDelete:EmployeeWorkSchedule');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EmployeeWorkSchedule');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EmployeeWorkSchedule');
    }

    public function replicate(AuthUser $authUser, EmployeeWorkSchedule $employeeWorkSchedule): bool
    {
        return $authUser->can('Replicate:EmployeeWorkSchedule');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EmployeeWorkSchedule');
    }

}