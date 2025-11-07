<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Driver;
use Illuminate\Auth\Access\HandlesAuthorization;

class DriverPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_driver');
    }

    public function view(AuthUser $authUser, Driver $driver): bool
    {
        return $authUser->can('view_driver');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_driver');
    }

    public function update(AuthUser $authUser, Driver $driver): bool
    {
        return $authUser->can('update_driver');
    }

    public function delete(AuthUser $authUser, Driver $driver): bool
    {
        return $authUser->can('delete_driver');
    }

    public function restore(AuthUser $authUser, Driver $driver): bool
    {
        return $authUser->can('restore_driver');
    }

    public function forceDelete(AuthUser $authUser, Driver $driver): bool
    {
        return $authUser->can('force_delete_driver');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_driver');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_driver');
    }

    public function replicate(AuthUser $authUser, Driver $driver): bool
    {
        return $authUser->can('replicate_driver');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_driver');
    }

}