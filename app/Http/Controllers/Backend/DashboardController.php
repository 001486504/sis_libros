<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Contracts\Support\Renderable;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    /*public function index(): View|Factory|Application
    {
        $this->checkAuthorization(auth()->user());

        return view(
            'backend.pages.dashboard.index',
            [
                'total_admins' => Admin::count(),
                'total_roles' => Role::count(),
                'total_permissions' => Permission::count(),
            ]
        );
    }*/
    public function index(): Renderable
    {
        // Asegúrate de pasar un array o cadena de permisos
        $this->checkAuthorization(['dashboard.view']);
        //$this->checkAuthorization(auth()->user());

        return view('backend.pages.dashboard.index', [
            //'total_roles' => $total_roles,
            'total_admins' => Admin::count(),
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
        ]);
    }
}
