<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RolePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        // Obtener el usuario autenticado
        $user = $request->user();

        // Si no hay usuario autenticado o el usuario no tiene los permisos necesarios, bloquear el acceso
        if (!$user || !$this->hasPermissions($user, $permissions)) {
            return response()->json(['message' => 'No tienes permisos para acceder a esta página.'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    /**
     * Verificar si el usuario tiene todos los permisos requeridos.
     *
     * @param Authenticatable $user
     * @param array $permissions
     * @return bool
     */
    private function hasPermissions(Authenticatable $user, array $permissions): bool
    {
        // Comprobar que el usuario tiene todos los permisos
        foreach ($permissions as $permission) {
            if (!Gate::forUser($user)->allows($permission)) {
                return false;
            }
        }

        return true;
    }
}
