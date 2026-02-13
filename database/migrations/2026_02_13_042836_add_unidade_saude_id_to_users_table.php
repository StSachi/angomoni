<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Liga o utilizador (profissional) a uma unidade de saúde.
 * Isso permite aplicar a regra: profissional só regista caso na sua unidade.
 * Não altera tipos/papéis (RBAC), apenas adiciona a referência.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'unidade_saude_id')) {
                $table->foreignId('unidade_saude_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('unidades_saude')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'unidade_saude_id')) {
                $table->dropConstrainedForeignId('unidade_saude_id');
            }
        });
    }
};
