<?php

namespace App\Services;

use App\Models\AuditoriaAcesso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicoAuditoria
{
    /**
     * Regista uma ação de auditoria no sistema.
     *
     * @param string        $acao        Tipo da ação (ex: LOGIN, LOGOUT, CREATE_CASO)
     * @param string|null   $descricao   Descrição opcional da ação
     * @param Request|null  $request     Request opcional (se não fornecido, usa request() global)
     *
     * Observações:
     * - Só grava se existir utilizador autenticado
     * - Limita user_agent para evitar overflow de coluna
     * - Nunca interrompe o fluxo do sistema (try/catch silencioso)
     */
    public static function registar(
        string $acao,
        ?string $descricao = null,
        ?Request $request = null
    ): void {
        try {

            // Se não for passado Request, usa o atual
            $request ??= request();

            $userId = Auth::id();

            // Segurança: não grava auditoria sem utilizador
            if (! $userId) {
                return;
            }

            AuditoriaAcesso::create([
                'user_id'    => $userId,
                'acao'       => strtoupper($acao),
                'descricao'  => $descricao,
                'ip_address' => $request?->ip(),
                'user_agent' => substr((string) $request?->userAgent(), 0, 255),
            ]);

        } catch (\Throwable $e) {

            // Nunca quebrar o sistema por causa de auditoria
            report($e);
        }
    }
}
