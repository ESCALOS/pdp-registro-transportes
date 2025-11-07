<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Chassis;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChassisPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_chassis');
    }

    public function view(AuthUser $authUser, Chassis $chassis): bool
    {
        return $authUser->can('view_chassis');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_chassis');
    }

    public function update(AuthUser $authUser, Chassis $chassis): bool
    {
        return $authUser->can('update_chassis');
    }

    public function delete(AuthUser $authUser, Chassis $chassis): bool
    {
        return $authUser->can('delete_chassis');
    }

    public function restore(AuthUser $authUser, Chassis $chassis): bool
    {
        return $authUser->can('restore_chassis');
    }

    public function forceDelete(AuthUser $authUser, Chassis $chassis): bool
    {
        return $authUser->can('force_delete_chassis');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_chassis');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_chassis');
    }

    public function replicate(AuthUser $authUser, Chassis $chassis): bool
    {
        return $authUser->can('replicate_chassis');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_chassis');
    }

}