<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipiosSeeder extends Seeder
{
    public function run(): void
    {
        $provinciaId = DB::table('provincias')->where('nome', 'Luanda')->value('id');

        DB::table('municipios')->updateOrInsert(
            ['nome' => 'Luanda', 'provincia_id' => $provinciaId],
            ['ativo' => true, 'updated_at' => now(), 'created_at' => now()]
        );
    }
}
