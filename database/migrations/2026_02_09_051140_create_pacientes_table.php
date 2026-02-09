<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dados do paciente.
 * Separado de casos para evitar duplicação e manter histórico.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();

            // Identificação básica
            $table->string('nome_completo', 200);
            $table->date('data_nascimento');
            $table->enum('sexo', ['M', 'F']);
            $table->string('telefone', 30);
            $table->string('endereco', 255)->nullable();

            // Nacionalidade
            $table->enum('nacionalidade', ['NACIONAL', 'ESTRANGEIRO']);

            // Apenas para estrangeiros
            $table->foreignId('pais_id')
                  ->nullable()
                  ->constrained('paises');

            // Apenas para nacionais
            $table->foreignId('provincia_id')
                  ->nullable()
                  ->constrained('provincias');

            $table->foreignId('municipio_id')
                  ->nullable()
                  ->constrained('municipios');

            // Documento
            $table->enum('tipo_documento', [
                'NAO_TEM',
                'ASSENTO',
                'CEDULA',
                'PASSAPORTE',
                'BI'
            ]);

            $table->string('numero_documento', 60)->nullable();

            $table->timestamps();

            // Índices úteis
            $table->index('nome_completo');
            $table->index('nacionalidade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
