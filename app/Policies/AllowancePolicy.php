<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Allowance;
use Illuminate\Auth\Access\HandlesAuthorization;

class AllowancePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Allowance');
    }

    public function view(AuthUser $authUser, Allowance $allowance): bool
    {
        return $authUser->can('View:Allowance');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Allowance');
    }

    public function update(AuthUser $authUser, Allowance $allowance): bool
    {
        return $authUser->can('Update:Allowance');
    }

    public function delete(AuthUser $authUser, Allowance $allowance): bool
    {
        return $authUser->can('Delete:Allowance');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Allowance');
    }

    public function restore(AuthUser $authUser, Allowance $allowance): bool
    {
        return $authUser->can('Restore:Allowance');
    }

    public function forceDelete(AuthUser $authUser, Allowance $allowance): bool
    {
        return $authUser->can('ForceDelete:Allowance');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Allowance');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Allowance');
    }

    public function replicate(AuthUser $authUser, Allowance $allowance): bool
    {
        return $authUser->can('Replicate:Allowance');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Allowance');
    }

}