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
    Schema::dropIfExists('auditorias');
}

public function down(): void
{
    Schema::create('auditorias', function ($table) {
        $table->id();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    
};
