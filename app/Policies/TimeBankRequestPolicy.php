<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TimeBankRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimeBankRequestPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TimeBankRequest');
    }

    public function view(AuthUser $authUser, TimeBankRequest $timeBankRequest): bool
    {
        return $authUser->can('View:TimeBankRequest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TimeBankRequest');
    }

    public function update(AuthUser $authUser, TimeBankRequest $timeBankRequest): bool
    {
        return $authUser->can('Update:TimeBankRequest');
    }

    public function delete(AuthUser $authUser, TimeBankRequest $timeBankRequest): bool
    {
        return $authUser->can('Delete:TimeBankRequest');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TimeBankRequest');
    }

    public function restore(AuthUser $authUser, TimeBankRequest $timeBankRequest): bool
    {
        return $authUser->can('Restore:TimeBankRequest');
    }

    public function forceDelete(AuthUser $authUser, TimeBankRequest $timeBankRequest): bool
    {
        return $authUser->can('ForceDelete:TimeBankRequest');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TimeBankRequest');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TimeBankRequest');
    }

    public function replicate(AuthUser $authUser, TimeBankRequest $timeBankRequest): bool
    {
        return $authUser->can('Replicate:TimeBankRequest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TimeBankRequest');
    }

}