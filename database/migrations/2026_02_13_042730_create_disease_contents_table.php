<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabela para cachear conteúdo educativo de doenças obtido de fontes externas.
 * Objetivo: evitar depender 100% da API em tempo real e acelerar as páginas.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('disease_contents', function (Blueprint $table) {
            $table->id();

            // Relaciona com a tua tabela doencas (ajusta o nome caso seja diferente)
            $table->foreignId('doenca_id')->constrained('doencas')->cascadeOnDelete();

            // Fonte do conteúdo: MEDLINEPLUS, etc.
            $table->string('fonte', 40);

            // Idioma do conteúdo (ex.: en, es). Mantemos aqui para suportar multi-idioma no futuro.
            $table->string('idioma', 10)->default('en');

            $table->string('titulo', 200)->nullable();
            $table->text('resumo')->nullable();

            // Guardamos links externos relevantes (página oficial, prevenção, etc.)
            $table->json('links')->nullable();

            // Para controle de cache (quando foi buscado)
            $table->timestamp('obtido_em')->nullable();

            $table->timestamps();

            $table->unique(['doenca_id', 'fonte', 'idioma']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disease_contents');
    }
};
    