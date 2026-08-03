<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AnnualLeaveBalance;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnnualLeaveBalancePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AnnualLeaveBalance');
    }

    public function view(AuthUser $authUser, AnnualLeaveBalance $annualLeaveBalance): bool
    {
        return $authUser->can('View:AnnualLeaveBalance');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AnnualLeaveBalance');
    }

    public function update(AuthUser $authUser, AnnualLeaveBalance $annualLeaveBalance): bool
    {
        return $authUser->can('Update:AnnualLeaveBalance');
    }

    public function delete(AuthUser $authUser, AnnualLeaveBalance $annualLeaveBalance): bool
    {
        return $authUser->can('Delete:AnnualLeaveBalance');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:AnnualLeaveBalance');
    }

    public function restore(AuthUser $authUser, AnnualLeaveBalance $annualLeaveBalance): bool
    {
        return $authUser->can('Restore:AnnualLeaveBalance');
    }

    public function forceDelete(AuthUser $authUser, AnnualLeaveBalance $annualLeaveBalance): bool
    {
        return $authUser->can('ForceDelete:AnnualLeaveBalance');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AnnualLeaveBalance');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AnnualLeaveBalance');
    }

    public function replicate(AuthUser $authUser, AnnualLeaveBalance $annualLeaveBalance): bool
    {
        return $authUser->can('Replicate:AnnualLeaveBalance');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AnnualLeaveBalance');
    }

}