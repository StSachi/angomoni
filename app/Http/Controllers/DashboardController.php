<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Caso;
use App\Models\Doenca;
use App\Models\UnidadeSaude;
use App\Models\AuditoriaAcesso;

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

        $ultimosCasos = Caso::query()
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $user = $request->user();

        $audQuery = AuditoriaAcesso::query()
            ->with('user')
            ->whereIn('acao', ['LOGIN', 'LOGOUT', 'CREATE', 'UPDATE', 'DELETE'])
            ->orderByDesc('id')
            ->limit(10);

        if ($user->papel !== 'ADMIN') {
            $audQuery->where('user_id', $user->id);
        }

        $ultimasAuditorias = $audQuery->get();

        return view('dashboard', compact('kpis', 'ultimosCasos', 'ultimasAuditorias'));
    }
}
