<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LeaveDepositBalance;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveDepositBalancePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LeaveDepositBalance');
    }

    public function view(AuthUser $authUser, LeaveDepositBalance $leaveDepositBalance): bool
    {
        return $authUser->can('View:LeaveDepositBalance');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LeaveDepositBalance');
    }

    public function update(AuthUser $authUser, LeaveDepositBalance $leaveDepositBalance): bool
    {
        return $authUser->can('Update:LeaveDepositBalance');
    }

    public function delete(AuthUser $authUser, LeaveDepositBalance $leaveDepositBalance): bool
    {
        return $authUser->can('Delete:LeaveDepositBalance');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LeaveDepositBalance');
    }

    public function restore(AuthUser $authUser, LeaveDepositBalance $leaveDepositBalance): bool
    {
        return $authUser->can('Restore:LeaveDepositBalance');
    }

    public function forceDelete(AuthUser $authUser, LeaveDepositBalance $leaveDepositBalance): bool
    {
        return $authUser->can('ForceDelete:LeaveDepositBalance');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LeaveDepositBalance');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LeaveDepositBalance');
    }

    public function replicate(AuthUser $authUser, LeaveDepositBalance $leaveDepositBalance): bool
    {
        return $authUser->can('Replicate:LeaveDepositBalance');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LeaveDepositBalance');
    }

}