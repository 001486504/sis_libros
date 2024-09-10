<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;

trait AuthorizationChecker
{
    /**
     * Check if the user is authorized to perform the action.
     *
     * @param array|string $permissions
     * @param string|null $message Custom unauthorized message (optional)
     * @return void
     */
    public function checkAuthorization(array|string $permissions, ?string $message = null): void
    {
        $user = auth()->user();

        // Si no hay usuario autenticado, o si el usuario no tiene los permisos requeridos
        if (!$user || !$this->hasAllPermissions($user, $permissions)) {
            $message = $message ?? 'Lo siento, no tienes permiso para realizar esta acción.';
            abort(403, $message);
        }
    }

    /**
     * Check if the user has all required permissions.
     *
     * @param Authenticatable $user
     * @param array|string $permissions
     * @return bool
     */
    private function hasAllPermissions(Authenticatable $user, array|string $permissions): bool
    {
        // Convertir el permiso a un array si es un string
        $permissions = (array) $permissions;

        // Verificar que el usuario tenga todos los permisos
        foreach ($permissions as $permission) {
            if (!Gate::forUser($user)->allows($permission)) {
                return false;
            }
        }

        return true;
    }
}
