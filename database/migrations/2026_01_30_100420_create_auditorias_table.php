<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();

            // Quem executou a ação
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // O que aconteceu
            $table->string('acao');                 // ex: USER_DELETE
            $table->string('entidade')->nullable(); // ex: User, Caso
            $table->unsignedBigInteger('entidade_id')->nullable();

            // Estado antes e depois
            $table->json('antes')->nullable();
            $table->json('depois')->nullable();

            // Contexto
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent', 500)->nullable();

            $table->timestamps();

            $table->index(['acao']);
            $table->index(['entidade', 'entidade_id']);
        });
    }


        /**
        * Reverse the migrations.
        */
        public function down(): void
        {
            Schema::dropIfExists('auditorias');
        }
};
