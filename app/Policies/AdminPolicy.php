<?php

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any admin.
     *
     * @param Admin $admin
     * @return bool
     */
    public function viewAny(Admin $admin): bool
    {
        // Verifica si el usuario tiene permiso para ver cualquier admin
        return $admin->hasPermissionTo('admin.view');
    }

    /**
     * Determine whether the user can create an admin.
     *
     * @param Admin $admin
     * @return bool
     */
    public function create(Admin $admin)
    {
        // Verifica si el usuario tiene permiso para crear un admin
        return $admin->hasPermissionTo('admin.create');
    }

    /**
     * Determine whether the user can update the admin.
     *
     * @param Admin $admin
     * @param Admin $model
     * @return bool
     */
    public function update(Admin $admin, Admin $model)
    {
        // Verifica si el usuario tiene permiso para actualizar el admin
        return $admin->hasPermissionTo('admin.edit');
    }

    /**
     * Determine whether the user can delete the admin.
     *
     * @param Admin $admin
     * @param Admin $model
     * @return bool
     */
    public function delete(Admin $admin, Admin $model)
    {
        // Verifica si el usuario tiene permiso para eliminar el admin
        return $admin->hasPermissionTo('admin.delete');
    }
}
