<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        // Aplicar políticas de autorización para todas las acciones del controlador
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): Renderable
    {
        // Autorización usando Policy
        $this->authorize('viewAny', Role::class);

        return view('backend.pages.roles.index', [
            'roles' => Role::all(),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): Renderable
    {
        // Autorización usando Policy
        $this->authorize('create', Role::class);

        return view('backend.pages.roles.create', [
            'all_permissions' => Permission::all(),
            'permission_groups' => Admin::getPermissionGroups(), // Verifica que el método exista
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        // Autorización usando Policy
        $this->authorize('create', Role::class);

        // Crear rol
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'admin',
        ]);

        // Asignar permisos al rol
        $permissions = $request->input('permissions', []);
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        session()->flash('success', 'Role has been created.');
        return redirect()->route('admin.roles.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Role $role): Renderable
    {
        // Autorización usando Policy
        $this->authorize('update', $role);

        return view('backend.pages.roles.edit', [
            'role' => $role,
            'all_permissions' => Permission::all(),
            'permission_groups' => Admin::getPermissionGroups(), // Verifica que el método exista
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        // Autorización usando Policy
        $this->authorize('update', $role);

        // Actualizar rol y sincronizar permisos
        $role->update([
            'name' => $request->name,
        ]);

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        session()->flash('success', 'Role has been updated.');
        return redirect()->route('admin.roles.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Role $role): RedirectResponse
    {
        // Autorización usando Policy
        $this->authorize('delete', $role);

        $role->delete();
        session()->flash('success', 'Role has been deleted.');
        return redirect()->route('admin.roles.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Role $role): Renderable
    {
        // Autorización usando Policy
        $this->authorize('view', $role);

        return view('backend.pages.roles.show', [
            'role' => $role,
            'all_permissions' => Permission::all(),
        ]);
    }
}
