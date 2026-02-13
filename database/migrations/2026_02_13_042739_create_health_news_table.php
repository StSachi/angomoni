<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabela para cachear notícias de saúde obtidas via RSS (fontes oficiais).
 * Objetivo:
 * - Sensibilização e atualização sem depender de API paga
 * - Guardar notícias em cache para performance e disponibilidade
 *
 * Nota técnica importante:
 * - Em MySQL com utf8mb4, um índice UNIQUE em VARCHAR(800) pode exceder o limite de bytes.
 * - Por isso, usamos url_hash (SHA-256) como chave única e mantemos a URL completa em url.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('health_news', function (Blueprint $table) {
            $table->id();

            // Fonte do feed (ex.: WHO_AFRO_RSS, ECDC_RSS, CDC_EID_RSS)
            $table->string('fonte', 60);

            // Título da notícia
            $table->string('titulo', 255);

            // URL completa da notícia (pode ser grande)
            $table->string('url', 800);

            /**
             * Hash da URL (SHA-256) para garantir unicidade sem estourar o limite do índice.
             * - 64 caracteres hex
             * - índice UNIQUE aqui resolve "Specified key was too long" do MySQL/utf8mb4
             */
            $table->char('url_hash', 64)->unique();

            // Resumo/descrição (RSS costuma trazer HTML; vamos limpar no service)
            $table->text('resumo')->nullable();

            // Data de publicação (quando o feed fornece)
            $table->timestamp('publicado_em')->nullable();

            // Tags simples para pesquisa por doença (ex.: ["malaria","cholera"])
            $table->json('tags')->nullable();

            $table->timestamps();

            // Índices úteis para filtros
            $table->index(['fonte', 'publicado_em']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_news');
    }
};
