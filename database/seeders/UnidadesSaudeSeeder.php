<?php

namespace Database\Seeders;

use App\Models\UnidadeSaude;
use Illuminate\Database\Seeder;

class UnidadesSaudeSeeder extends Seeder
{
    public function run(): void
    {
        UnidadeSaude::firstOrCreate([
            'nome' => 'Hospital Geral Central',
            'provincia_id' => 1,
            'municipio_id' => 1,
            'tipo' => 'HOSPITAL',
            'ativo' => true,
        ]);
    }
}
