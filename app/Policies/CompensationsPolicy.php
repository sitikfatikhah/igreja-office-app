<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Compensations;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompensationsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Compensations');
    }

    public function view(AuthUser $authUser, Compensations $compensations): bool
    {
        return $authUser->can('View:Compensations');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Compensations');
    }

    public function update(AuthUser $authUser, Compensations $compensations): bool
    {
        return $authUser->can('Update:Compensations');
    }

    public function delete(AuthUser $authUser, Compensations $compensations): bool
    {
        return $authUser->can('Delete:Compensations');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Compensations');
    }

    public function restore(AuthUser $authUser, Compensations $compensations): bool
    {
        return $authUser->can('Restore:Compensations');
    }

    public function forceDelete(AuthUser $authUser, Compensations $compensations): bool
    {
        return $authUser->can('ForceDelete:Compensations');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Compensations');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Compensations');
    }

    public function replicate(AuthUser $authUser, Compensations $compensations): bool
    {
        return $authUser->can('Replicate:Compensations');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Compensations');
    }

}