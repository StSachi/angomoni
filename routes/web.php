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
use App\Models\AuditoriaAcesso;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    // ✅ Dados públicos (agregados, sem PII) + cache 60s
    $publicData = Cache::remember('welcome_public_data', 60, function () {
        return [
            'casos_total' => Caso::count(),
            'casos_7d' => Caso::where('created_at', '>=', now()->subDays(7))->count(),
            'doencas_total' => Doenca::count(),
            'unidades_total' => UnidadeSaude::count(),
            'ultima_atualizacao' => optional(
                AuditoriaAcesso::orderByDesc('id')->first()
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

    // ✅ PROFISSIONAL (e ADMIN) - CASOS (CRUD completo)
    Route::resource('casos', CasoController::class);

    // ✅ ADMIN - cadastros base + gestão de utilizadores
    Route::middleware(['role:ADMIN'])->group(function () {
        Route::resource('doencas', DoencaController::class);
        Route::resource('unidades-saude', UnidadeSaudeController::class);

        // lista + eliminar (com policy)
        Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'destroy']);
    });
});

require __DIR__.'/auth.php';
