<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('casos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doenca_id')->constrained('doencas')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('unidade_saude_id')->constrained('unidades_saude')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();

            $table->date('data_notificacao');
            $table->unsignedSmallInteger('idade')->nullable();
            $table->enum('sexo', ['M', 'F', 'N'])->default('N');

            $table->enum('estado', ['SUSPEITO', 'CONFIRMADO', 'DESCARTADO', 'OBITO'])->default('SUSPEITO');

            $table->text('observacoes')->nullable();

            // Dados sensíveis: por agora guardamos mínimo; depois mascaramos/isolamos
            $table->string('paciente_iniciais', 10)->nullable(); // ex: "A.M."
            $table->string('telefone_contacto', 30)->nullable();

            $table->timestamps();

            $table->index(['doenca_id', 'data_notificacao']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('casos');
    }
};
