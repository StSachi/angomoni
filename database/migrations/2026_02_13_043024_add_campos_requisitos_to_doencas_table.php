<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona campos necessários para cumprir os requisitos do projeto
 * na tabela doencas, SEM recriar a tabela (mantém dados existentes).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('doencas', function (Blueprint $table) {
            // Só adiciona se ainda não existir
            if (!Schema::hasColumn('doencas', 'nome')) {
                $table->string('nome', 120)->unique()->after('id');
            }

            if (!Schema::hasColumn('doencas', 'descricao')) {
                $table->text('descricao')->nullable()->after('nome');
            }

            if (!Schema::hasColumn('doencas', 'sintomas_resumo')) {
                $table->text('sintomas_resumo')->nullable()->after('descricao');
            }

            if (!Schema::hasColumn('doencas', 'prevencao_resumo')) {
                $table->text('prevencao_resumo')->nullable()->after('sintomas_resumo');
            }

            if (!Schema::hasColumn('doencas', 'ativa')) {
                $table->boolean('ativa')->default(true)->after('prevencao_resumo');
            }

            if (!Schema::hasColumn('doencas', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('ativa')
                    ->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('doencas', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->after('created_by')
                    ->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('doencas', function (Blueprint $table) {
            // Remoção segura (só remove se existir)
            if (Schema::hasColumn('doencas', 'updated_by')) {
                $table->dropConstrainedForeignId('updated_by');
            }
            if (Schema::hasColumn('doencas', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }
            if (Schema::hasColumn('doencas', 'ativa')) {
                $table->dropColumn('ativa');
            }
            if (Schema::hasColumn('doencas', 'prevencao_resumo')) {
                $table->dropColumn('prevencao_resumo');
            }
            if (Schema::hasColumn('doencas', 'sintomas_resumo')) {
                $table->dropColumn('sintomas_resumo');
            }
            if (Schema::hasColumn('doencas', 'descricao')) {
                $table->dropColumn('descricao');
            }
            // NÃO removo 'nome' por segurança, porque pode já existir e estar em uso.
        });
    }
};
