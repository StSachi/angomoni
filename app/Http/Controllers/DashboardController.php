<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Caso;
use App\Models\Doenca;
use App\Models\UnidadeSaude;
use App\Models\Auditoria;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $kpis = [
            'casos_total' => Caso::count(),
            'casos_7d' => Caso::where('created_at', '>=', now()->subDays(7))->count(),
            'unidades_total' => UnidadeSaude::count(),
            'doencas_total' => Doenca::count(),
        ];

        // Últimos 10 casos (com relações se existirem)
        $ultimosCasos = Caso::query()
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        // Últimas 10 auditorias
        $ultimasAuditorias = Auditoria::query()
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('dashboard', compact('kpis', 'ultimosCasos', 'ultimasAuditorias'));
    }
}
