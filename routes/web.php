<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DoencaController;
use App\Http\Controllers\UnidadeSaudeController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

use App\Models\Caso;
use App\Models\Doenca;
use App\Models\UnidadeSaude;
use App\Models\Auditoria; // ✅ Ajuste: usar Auditoria (tabela auditorias)

/**
 * Página inicial
 * - Se estiver autenticado: vai para dashboard
 * - Se não: mostra dados públicos agregados (sem dados pessoais)
 */
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    // Dados públicos agregados + cache por 60s
    $publicData = Cache::remember('welcome_public_data', 60, function () {
        return [
            'casos_total' => Caso::count(),
            'casos_7d' => Caso::where('created_at', '>=', now()->subDays(7))->count(),
            'doencas_total' => Doenca::count(),
            'unidades_total' => UnidadeSaude::count(),

            // Última atualização baseada em auditoria (se existir)
            'ultima_atualizacao' => optional(
                Auditoria::orderByDesc('id')->first()
            )->created_at,
        ];
    });

    return view('welcome', $publicData);
})->name('home');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Breeze Profile routes (necessárias)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * CASOS (workflow)
     * - REGISTADOR: cria/edita apenas RASCUNHO + submete
     * - TECNICO_UNIDADE: valida/rejeita casos da sua unidade
     * - ADMIN: pode tudo
     *
     * Nota: o controlo final deve ser aplicado também via Policy no CasoController.
     */

    // CRUD base (sem destroy aqui)
    Route::resource('casos', CasoController::class)->except(['destroy']);

    // Delete: só ADMIN
    Route::delete('/casos/{caso}', [CasoController::class, 'destroy'])
        ->middleware('role:ADMIN')
        ->name('casos.destroy');

    // Submeter: REGISTADOR e ADMIN
    Route::post('/casos/{caso}/submit', [CasoController::class, 'submit'])
        ->middleware('role:REGISTADOR,ADMIN')
        ->name('casos.submit');

    // Validar/Rejeitar: TECNICO_UNIDADE e ADMIN
    Route::post('/casos/{caso}/validate', [CasoController::class, 'validateCase'])
        ->middleware('role:TECNICO_UNIDADE,ADMIN')
        ->name('casos.validate');

    Route::post('/casos/{caso}/reject', [CasoController::class, 'rejectCase'])
        ->middleware('role:TECNICO_UNIDADE,ADMIN')
        ->name('casos.reject');

    /**
     * ADMIN: cadastros base + gestão de utilizadores
     */
    Route::middleware(['role:ADMIN'])->group(function () {
        Route::resource('doencas', DoencaController::class);
        Route::resource('unidades-saude', UnidadeSaudeController::class);

        // Gestão de utilizadores (mínimo)
        Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'destroy']);
    });
});

require __DIR__.'/auth.php';
