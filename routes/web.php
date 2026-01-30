<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoencaController;
use App\Http\Controllers\UnidadeSaudeController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Breeze Profile routes (necessárias)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ PROFISSIONAL (e ADMIN) - CASOS (CRUD completo)
    Route::resource('casos', CasoController::class);

    // ADMIN - cadastros base
    Route::middleware(['role:ADMIN'])->group(function () {
        Route::resource('doencas', DoencaController::class);
        Route::resource('unidades-saude', UnidadeSaudeController::class);
    });
});

require __DIR__.'/auth.php';
