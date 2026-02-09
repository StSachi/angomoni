<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Casos de doenças endémicas.
 * Representa o registo inicial do caso no sistema.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('casos', function (Blueprint $table) {
            $table->id();

            // Relações principais
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('doenca_id')->constrained('doencas');

            // Unidade onde o caso foi registado
            $table->foreignId('unidade_registo_id')->constrained('unidades_saude');

            // Unidade de origem (opcional)
            $table->foreignId('unidade_origem_id')->nullable()->constrained('unidades_saude');

            // Utilizador que registou
            $table->foreignId('user_id')->constrained('users');

            // Datas
            $table->date('data_notificacao');
            $table->date('data_inicio_sintomas')->nullable();

            // Classificação epidemiológica
            $table->enum('classificacao_caso', ['SUSPEITO','PROVAVEL','CONFIRMADO'])
                  ->default('SUSPEITO');

            $table->enum('tipo_deteccao', ['PASSIVA','ATIVA','TRIAGEM','REFERENCIADO'])
                  ->default('PASSIVA');

            $table->enum('fonte_notificacao', ['HOSPITAL','CENTRO_SAUDE','COMUNIDADE','OUTRO'])
                  ->default('HOSPITAL');

            // Workflow
            $table->enum('estado', ['RASCUNHO','SUBMETIDO','VALIDADO','REJEITADO'])
                  ->default('RASCUNHO');

            $table->timestamp('submetido_em')->nullable();
            $table->text('parecer_tecnico')->nullable();
            $table->foreignId('validado_por')->nullable()->constrained('users');
            $table->timestamp('validado_em')->nullable();

            $table->timestamps();

            // Índices úteis
            $table->index('estado');
            $table->index('data_notificacao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('casos');
    }
};
