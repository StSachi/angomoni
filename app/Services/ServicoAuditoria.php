<?php

namespace App\Services;

use App\Models\AuditoriaAcesso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicoAuditoria
{
    public static function registar(string $acao, ?string $descricao = null, ?Request $request = null): void
    {
        try {
            $request ??= request();

            AuditoriaAcesso::create([
                'user_id' => Auth::id(),
                'acao' => $acao,
                'descricao' => $descricao,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
