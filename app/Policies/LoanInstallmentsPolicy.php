<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LoanInstallments;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanInstallmentsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LoanInstallments');
    }

    public function view(AuthUser $authUser, LoanInstallments $loanInstallments): bool
    {
        return $authUser->can('View:LoanInstallments');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LoanInstallments');
    }

    public function update(AuthUser $authUser, LoanInstallments $loanInstallments): bool
    {
        return $authUser->can('Update:LoanInstallments');
    }

    public function delete(AuthUser $authUser, LoanInstallments $loanInstallments): bool
    {
        return $authUser->can('Delete:LoanInstallments');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LoanInstallments');
    }

    public function restore(AuthUser $authUser, LoanInstallments $loanInstallments): bool
    {
        return $authUser->can('Restore:LoanInstallments');
    }

    public function forceDelete(AuthUser $authUser, LoanInstallments $loanInstallments): bool
    {
        return $authUser->can('ForceDelete:LoanInstallments');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LoanInstallments');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LoanInstallments');
    }

    public function replicate(AuthUser $authUser, LoanInstallments $loanInstallments): bool
    {
        return $authUser->can('Replicate:LoanInstallments');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LoanInstallments');
    }

}