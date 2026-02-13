<?php

namespace App\Http\Controllers;

use App\Models\Doenca;
use App\Models\HealthNews;
use App\Services\DoencaInfoService;
use Illuminate\Http\Request;

/**
 * Controller para a “consulta da doença”.
 * Mostra: dados internos + conteúdo educativo (API) + notícias relacionadas (RSS).
 */
class DoencaPublicaController extends Controller
{
    public function show(Request $request, Doenca $doenca, DoencaInfoService $infoService)
    {
        // Conteúdo educativo (cache + API)
        $conteudo = $infoService->obterConteudo($doenca, 'en');

        // Notícias relacionadas (busca por nome da doença no título/tags)
        $termo = mb_strtolower($doenca->nome);

        $noticias = HealthNews::query()
            ->where(function ($q) use ($termo) {
                $q->whereRaw('LOWER(titulo) LIKE ?', ["%{$termo}%"])
                  ->orWhereJsonContains('tags', $termo);
            })
            ->orderByDesc('publicado_em')
            ->limit(10)
            ->get();

        return view('doencas.publica_show', compact('doenca', 'conteudo', 'noticias'));
    }
}
