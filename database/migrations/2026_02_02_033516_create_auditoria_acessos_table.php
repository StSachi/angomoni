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
    Schema::create('auditoria_acessos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

        $table->string('acao', 30); // LOGIN, LOGOUT, ACESSO, CREATE, UPDATE, DELETE
        $table->string('descricao')->nullable();

        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();

        $table->timestamps();

        $table->index(['user_id', 'acao']);
        $table->index('created_at');
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria_acessos');
    }
};
