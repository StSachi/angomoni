<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DoencaController;
use App\Http\Controllers\UnidadeSaudeController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

use App\Models\Caso;
use App\Models\Doenca;
use App\Models\UnidadeSaude;
use App\Models\AuditoriaAcesso;

/*
|--------------------------------------------------------------------------
| Página inicial pública
|--------------------------------------------------------------------------
| - Se autenticado → dashboard
| - Se não autenticado → mostra dados agregados públicos
|   (sem dados pessoais)
*/

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

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


/*
|--------------------------------------------------------------------------
| Rotas protegidas (auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Perfil (Breeze)
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | PACIENTES
    |--------------------------------------------------------------------------
    | - REGISTADOR cria/edita
    | - ADMIN pode gerir
    | (controlo final deve estar também em Policy)
    */

    Route::resource('pacientes', PacienteController::class)
        ->middleware('role:REGISTADOR,TECNICO_UNIDADE,ADMIN');


    /*
    |--------------------------------------------------------------------------
    | CASOS (workflow)
    |--------------------------------------------------------------------------
    */

    // CRUD base (sem destroy aqui)
    Route::resource('casos', CasoController::class)
        ->except(['destroy']);

    // Delete: apenas ADMIN
    Route::delete('/casos/{caso}', [CasoController::class, 'destroy'])
        ->middleware('role:ADMIN')
        ->name('casos.destroy');

    // Submeter: REGISTADOR e ADMIN
    Route::post('/casos/{caso}/submit', [CasoController::class, 'submit'])
        ->middleware('role:REGISTADOR,ADMIN')
        ->name('casos.submit');

    // Validar: TECNICO_UNIDADE e ADMIN
    Route::post('/casos/{caso}/validate', [CasoController::class, 'validateCase'])
        ->middleware('role:TECNICO_UNIDADE,ADMIN')
        ->name('casos.validate');

    // Rejeitar: TECNICO_UNIDADE e ADMIN
    Route::post('/casos/{caso}/reject', [CasoController::class, 'rejectCase'])
        ->middleware('role:TECNICO_UNIDADE,ADMIN')
        ->name('casos.reject');


    /*
    |--------------------------------------------------------------------------
    | ADMIN – Cadastros base
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:ADMIN'])->group(function () {

        Route::resource('doencas', DoencaController::class);

        Route::resource('unidades-saude', UnidadeSaudeController::class);

        Route::resource('users', UserController::class)
            ->only(['index', 'create', 'store', 'destroy']);
    });

});


/*
|--------------------------------------------------------------------------
| Auth (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
