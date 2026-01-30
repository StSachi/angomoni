<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
        public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (!$user || !$user->ativo) {
            abort(403, 'Utilizador inativo ou não autenticado.');
        }

        if ($user->papel !== $role) {
            abort(403, 'Acesso negado.');
        }

        return $next($request);
    }

}
