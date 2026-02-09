<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Registo de ações relevantes no sistema.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users');

            $table->string('acao', 50);
            $table->string('entidade', 50);
            $table->unsignedBigInteger('entidade_id')->nullable();

            $table->string('ip', 45)->nullable();
            $table->text('detalhes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
