<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Payrolls;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayrollsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Payrolls');
    }

    public function view(AuthUser $authUser, Payrolls $payrolls): bool
    {
        return $authUser->can('View:Payrolls');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Payrolls');
    }

    public function update(AuthUser $authUser, Payrolls $payrolls): bool
    {
        return $authUser->can('Update:Payrolls');
    }

    public function delete(AuthUser $authUser, Payrolls $payrolls): bool
    {
        return $authUser->can('Delete:Payrolls');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Payrolls');
    }

    public function restore(AuthUser $authUser, Payrolls $payrolls): bool
    {
        return $authUser->can('Restore:Payrolls');
    }

    public function forceDelete(AuthUser $authUser, Payrolls $payrolls): bool
    {
        return $authUser->can('ForceDelete:Payrolls');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Payrolls');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Payrolls');
    }

    public function replicate(AuthUser $authUser, Payrolls $payrolls): bool
    {
        return $authUser->can('Replicate:Payrolls');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Payrolls');
    }

}