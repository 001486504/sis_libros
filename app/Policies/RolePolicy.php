<?php

namespace App\Policies;

use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin): bool
    {
        return $admin->hasPermissionTo('role.view');
    }

    public function create(Admin $admin): bool
    {
        return $admin->hasPermissionTo('role.create');
    }

    public function update(Admin $admin, Role $role): bool
    {
        return $admin->hasPermissionTo('role.edit');
    }

    public function delete(Admin $admin, Role $role): bool
    {
        return $admin->hasPermissionTo('role.delete');
    }
}
