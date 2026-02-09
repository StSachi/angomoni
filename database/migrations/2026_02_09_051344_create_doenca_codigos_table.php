<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Permite associar múltiplos códigos (ICD, LOCAL, etc.)
 * a uma mesma doença.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('doenca_codigos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doenca_id')
                  ->constrained('doencas')
                  ->cascadeOnDelete();

            $table->string('sistema', 20); // ICD10, ICD11, LOCAL
            $table->string('codigo', 50);
            $table->boolean('principal')->default(false);

            $table->timestamps();

            $table->unique(['doenca_id', 'sistema', 'codigo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doenca_codigos');
    }
};
