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
        if (!Schema::hasColumn('users', 'papel')) {
                $table->enum('papel', ['ADMIN', 'PROFISSIONAL'])->default('PROFISSIONAL')->after('email');
            }
            if (!Schema::hasColumn('users', 'ativo')) {
                $table->boolean('ativo')->default(true)->after('papel');
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'ativo')) $table->dropColumn('ativo');
            if (Schema::hasColumn('users', 'papel')) $table->dropColumn('papel');
        });
    }
};
