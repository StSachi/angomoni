<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Municípios pertencem a uma província.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();

            // Relação com a província
            $table->foreignId('provincia_id')
                  ->constrained('provincias')
                  ->cascadeOnDelete();

            $table->string('nome', 120);

            // Ativo/inativo
            $table->boolean('ativo')->default(true);

            $table->timestamps();

            // Evita municípios duplicados na mesma província
            $table->unique(['provincia_id', 'nome']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
