<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Unidades de saúde (hospitais, centros de saúde, postos médicos).
 * Usadas para:
 * - vincular utilizadores
 * - identificar onde o caso foi registado
 * - identificar a unidade de origem do caso
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('unidades_saude', function (Blueprint $table) {
            $table->id();

            // Nome oficial da unidade
            $table->string('nome', 200);

            // Tipo da unidade (opcional, mas útil para relatórios)
            $table->enum('tipo', [
                'HOSPITAL',
                'CENTRO_SAUDE',
                'POSTO_MEDICO',
                'CLINICA'
            ])->default('HOSPITAL');

            // Localização administrativa
            $table->foreignId('provincia_id')
                  ->constrained('provincias');

            $table->foreignId('municipio_id')
                  ->constrained('municipios');

            // Estado da unidade
            $table->boolean('ativo')->default(true);

            $table->timestamps();

            // Evita duplicar nomes na mesma localidade
            $table->unique(['nome', 'municipio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidades_saude');
    }
};
