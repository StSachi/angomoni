<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('casos', function (Blueprint $table) {
            $table->string('provincia', 100)->nullable()->after('unidade_saude_id');
            $table->string('cidade', 100)->nullable()->after('provincia');

            // (Opcional) índice para pesquisas rápidas por local
            $table->index(['provincia', 'cidade']);
        });
    }

    public function down(): void
    {
        Schema::table('casos', function (Blueprint $table) {
            $table->dropIndex(['provincia', 'cidade']);
            $table->dropColumn(['provincia', 'cidade']);
        });
    }
};
