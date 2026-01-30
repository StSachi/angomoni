<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doencas', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();          // Ex: "Malária"
            $table->string('codigo', 50)->nullable()->unique(); // Ex: "MAL-001"
            $table->text('descricao')->nullable();
            $table->boolean('ativa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doencas');
    }
};
