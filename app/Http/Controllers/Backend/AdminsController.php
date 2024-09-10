<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminsController extends Controller
{
    public function __construct()
    {
        // Aplicar políticas de autorización para todas las acciones del controlador
        $this->authorizeResource(Admin::class, 'admin');
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): Renderable
    {
        // Autorización usando Policy
        $this->authorize('viewAny', Admin::class);

        return view('backend.pages.admins.index', [
            'admins' => Admin::all(),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(AdminRequest $request): RedirectResponse
    {
        //dd($request->all());
        // Autorización usando Policy
        $this->authorize('create', Admin::class);

        // Verificar si el admin es un superusuario y tiene roles asignados
        if ($request->boolean('is_superuser') && !empty($request->roles)) {
            return redirect()->back()->witherrors(['roles' => 'Superusers cannot have roles assigned.']);
        }

        $admin = Admin::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_superuser' => $request->boolean('is_superuser'),
        ]);

        // Asignar roles
        /*if ($request->roles) {
            // Verificar si los roles son nombres o IDs
            $roles = $request->roles;

            // Si los roles son IDs, conviértelos en nombres
            if (is_array($roles) && is_numeric($roles[0])) {
                $roles = Role::whereIn('id', $roles)->pluck('name')->toArray();
            }

            // Asignar roles por nombre
            $admin->assignRole($roles);
        }*/

        /*if (!$request->boolean('is_superuser') && $request->has('roles')) {
            $roles = $request->input('roles');

            // Verificar si los roles son IDs o nombres
            if (is_array($roles) && is_numeric($roles[0])) {
                // Si son IDs, obtener los nombres de los roles
                $roleNames = Role::whereIn('id', $roles)->pluck('name')->toArray();
                $admin->assignRole($roleNames);
            } else {
                // Si ya son nombres, asignar roles directamente
                $admin->assignRole($roles);
            }
        }*/

        // Asignar roles si el usuario no es un superusuario
        if (!$request->boolean('is_superuser') && $request->has('roles')) {
            $roles = $request->input('roles');

            // Verificar si los roles son IDs o nombres
            /*if (is_array($roles) && is_numeric($roles[0])) {
                // Si son IDs, obtener los nombres de los roles
                $roleNames = Role::whereIn('id', $roles)->pluck('name')->toArray();
                $admin->assignRole($roleNames);
            } else {
                // Si ya son nombres, asignar roles directamente
                $admin->assignRole($roles);
            }*/
            $roleNames = is_array($roles) && is_numeric($roles[0])
                ? Role::whereIn('id', $roles)->pluck('name')->toArray()
                : $roles;
            $admin->assignRole($roleNames);
        }

        session()->flash('success', __('Admin has been created.'));
        return redirect()->route('admin.admins.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): Renderable
    {
        // Autorización usando Policy
        $this->authorize('create', Admin::class);

        //return view('backend.pages.admins.create', [
        //    'roles' => Role::all(),
        //]);
        // Obtener todos los roles desde la base de datos
        $roles = Role::all();

        // Verificar si el rol de Superadmin está seleccionado en la solicitud anterior
        //$isSuperadminSelected = old('roles', []) && in_array(1, old('roles', []));
        $isSuperadminSelected = old('is_superuser', false);

        return view('backend.pages.admins.create', [
            'roles' => $roles,
            'isSuperadminSelected' => $isSuperadminSelected
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Admin $admin): Renderable
    {
        // Autorización usando Policy
        $this->authorize('update', $admin);

        return view('backend.pages.admins.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(AdminRequest $request, Admin $admin): RedirectResponse
    {
        // Autorización usando Policy
        $this->authorize('update', $admin);

        // Verificar si el administrador es un superusuario y tiene roles asignado
        //if ($request->boolean('is_superuser') && !empty($request->roles)) {
        //    // Verificar si el usuario actual tiene permiso para crear superusuarios
        //    return redirect()->back()->witherrors(['roles' => 'If Superusers cannot have roles.']);
        //}
        if ($request->boolean('is_superuser') && $request->has('roles')) {
            return redirect()->back()->withErrors(['roles' => 'Superusers cannot have roles assigned.']);
        }

        //$admin->update([
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            //'password' => $request->password ? Hash::make($request->password) : $admin->password,
            'is_superuser' => $request->boolean('is_superuser'),
        ];

        // Si se proporciona una nueva contraseña, la añadimos al array de datos
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Actualizar el administrador
        $admin->update($data);

        // Sincronizar roles
        //if ($request->roles) {
        //    $admin->syncRoles($request->roles);
        //} else {
        //    $admin->syncRoles([]);
        //}

        // Sincronizar roles solo si no es superuser
        /*if (!$request->boolean('is_superuser')) {
            if ($request->roles) {
                // Asignar roles seleccionados
                $admin->syncRoles($request->roles);
            } else {
                // Si no se seleccionaron roles, limpiar los roles asignados
                $admin->syncRoles([]);
            }
        } else {
            // Si es superuser, asegurarse de que no tenga roles adicionales
            $admin->syncRoles([]);
        }*/

        // Sincronizar roles solo si no es superusuario
        if (!$request->boolean('is_superuser')) {
            $roles = $request->input('roles', []);
            $roleNames = is_array($roles) && is_numeric($roles[0])
                ? Role::whereIn('id', $roles)->pluck('name')->toArray()
                : $roles;

            $admin->syncRoles($roleNames);
        } else {
            $admin->syncRoles([]);
        }

        session()->flash('success', 'Admin has been updated.');
        return redirect()->route('admin.admins.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Admin $admin): RedirectResponse
    {
        // Autorización usando Policy
        $this->authorize('delete', $admin);

        // Verificar si el administrador es un superusuario
        if ($admin->is_superuser) {
            // Redirigir con un mensaje de error si se intenta eliminar a un superusuario
            session()->flash('error', 'Cannot remove superuser.');
            return redirect()->route('admin.admins.index');
        }

        $admin->delete();
        session()->flash('success', 'Admin has been deleted.');
        return redirect()->route('admin.admins.index');
    }
}
