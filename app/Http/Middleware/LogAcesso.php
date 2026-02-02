<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ServicoAuditoria;

class LogAcesso
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Evitar poluir: só GET, só páginas web, ignorar assets
        if ($request->isMethod('GET') && !$request->ajax()) {
            $path = $request->path();

            // ignora arquivos (css/js/png) e storage
            if (!str_starts_with($path, 'storage') && !str_contains($path, '.')) {
                ServicoAuditoria::registar('ACESSO', "Acesso: {$path}", $request);
            }
        }

        return $response;
    }
}
