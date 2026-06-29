<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Overtimes;
use Illuminate\Auth\Access\HandlesAuthorization;

class OvertimesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Overtimes');
    }

    public function view(AuthUser $authUser, Overtimes $overtimes): bool
    {
        return $authUser->can('View:Overtimes');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Overtimes');
    }

    public function update(AuthUser $authUser, Overtimes $overtimes): bool
    {
        return $authUser->can('Update:Overtimes');
    }

    public function delete(AuthUser $authUser, Overtimes $overtimes): bool
    {
        return $authUser->can('Delete:Overtimes');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Overtimes');
    }

    public function restore(AuthUser $authUser, Overtimes $overtimes): bool
    {
        return $authUser->can('Restore:Overtimes');
    }

    public function forceDelete(AuthUser $authUser, Overtimes $overtimes): bool
    {
        return $authUser->can('ForceDelete:Overtimes');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Overtimes');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Overtimes');
    }

    public function replicate(AuthUser $authUser, Overtimes $overtimes): bool
    {
        return $authUser->can('Replicate:Overtimes');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Overtimes');
    }

}