<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AttendanceReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendanceReportPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AttendanceReport');
    }

    public function view(AuthUser $authUser, AttendanceReport $attendanceReport): bool
    {
        return $authUser->can('View:AttendanceReport');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AttendanceReport');
    }

    public function update(AuthUser $authUser, AttendanceReport $attendanceReport): bool
    {
        return $authUser->can('Update:AttendanceReport');
    }

    public function delete(AuthUser $authUser, AttendanceReport $attendanceReport): bool
    {
        return $authUser->can('Delete:AttendanceReport');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:AttendanceReport');
    }

    public function restore(AuthUser $authUser, AttendanceReport $attendanceReport): bool
    {
        return $authUser->can('Restore:AttendanceReport');
    }

    public function forceDelete(AuthUser $authUser, AttendanceReport $attendanceReport): bool
    {
        return $authUser->can('ForceDelete:AttendanceReport');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AttendanceReport');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AttendanceReport');
    }

    public function replicate(AuthUser $authUser, AttendanceReport $attendanceReport): bool
    {
        return $authUser->can('Replicate:AttendanceReport');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AttendanceReport');
    }

}