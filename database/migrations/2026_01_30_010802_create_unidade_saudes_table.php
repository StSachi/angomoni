<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('unidades_saude', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('provincia', 100);
            $table->string('municipio', 100);
            $table->string('comuna', 100)->nullable();
            $table->string('bairro', 100)->nullable();
            $table->string('telefone', 30)->nullable();
            $table->timestamps();

            $table->index(['provincia', 'municipio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidades_saude');
    }
};

