<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Controlo de permissões e vínculo institucional.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Papel do utilizador
            $table->enum('role', [
                'ADMIN',
                'TECNICO_UNIDADE',
                'REGISTADOR'
            ])->default('REGISTADOR');

            // Unidade de saúde (obrigatória exceto ADMIN)
            $table->foreignId('unidade_saude_id')
                  ->nullable()
                  ->constrained('unidades_saude');

            // Ativação/desativação do utilizador
            $table->boolean('ativo')->default(true);

            // Quem criou o utilizador
            $table->foreignId('criado_por')
                  ->nullable()
                  ->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unidade_saude_id');
            $table->dropConstrainedForeignId('criado_por');
            $table->dropColumn(['role', 'ativo']);
        });
    }
};
