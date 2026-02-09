<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Permite acesso apenas se o utilizador tiver um dos papéis permitidos.
     * Uso: ->middleware('role:ADMIN,TECNICO_UNIDADE')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        // Se conta estiver desativada, bloqueia
        if (property_exists($user, 'ativo') && ! $user->ativo) {
            abort(403, 'Conta desativada.');
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Sem permissão.');
        }

        return $next($request);
    }
}
