<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogo interno de doenças.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('doencas', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 150)->unique();
            $table->text('descricao')->nullable();

            // Ativo/inativo
            $table->boolean('ativo')->default(true);

            // Quem criou
            $table->foreignId('criado_por')
                  ->nullable()
                  ->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doencas');
    }
};
