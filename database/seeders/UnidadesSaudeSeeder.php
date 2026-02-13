<?php

namespace Database\Seeders;

use App\Models\UnidadeSaude;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnidadesSaudeSeeder extends Seeder
{
    public function run(): void
    {
        $provinciaId = DB::table('provincias')->where('nome', 'Luanda')->value('id');
        $municipioId = DB::table('municipios')->where('nome', 'Luanda')->where('provincia_id', $provinciaId)->value('id');

        UnidadeSaude::firstOrCreate(
            ['nome' => 'Hospital Geral Central', 'municipio_id' => $municipioId],
            [
                'provincia_id' => $provinciaId,
                'tipo' => 'HOSPITAL',
                'ativo' => true,
            ]
        );
    }
}
