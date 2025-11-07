<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Truck;
use Illuminate\Auth\Access\HandlesAuthorization;

class TruckPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_truck');
    }

    public function view(AuthUser $authUser, Truck $truck): bool
    {
        return $authUser->can('view_truck');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_truck');
    }

    public function update(AuthUser $authUser, Truck $truck): bool
    {
        return $authUser->can('update_truck');
    }

    public function delete(AuthUser $authUser, Truck $truck): bool
    {
        return $authUser->can('delete_truck');
    }

    public function restore(AuthUser $authUser, Truck $truck): bool
    {
        return $authUser->can('restore_truck');
    }

    public function forceDelete(AuthUser $authUser, Truck $truck): bool
    {
        return $authUser->can('force_delete_truck');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_truck');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_truck');
    }

    public function replicate(AuthUser $authUser, Truck $truck): bool
    {
        return $authUser->can('replicate_truck');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_truck');
    }

}