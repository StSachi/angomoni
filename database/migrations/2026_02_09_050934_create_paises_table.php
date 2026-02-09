<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabela de países
 * Usada para pacientes estrangeiros.
 * Gerida dinamicamente pelo ADMIN.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('paises', function (Blueprint $table) {
            $table->id();

            // Nome oficial do país
            $table->string('nome', 120)->unique();

            // Códigos internacionais (opcionais)
            $table->string('iso2', 2)->nullable()->index();
            $table->string('iso3', 3)->nullable()->index();

            // Permite desativar sem perder histórico
            $table->boolean('ativo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paises');
    }
};
