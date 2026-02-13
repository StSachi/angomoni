<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('provincias')->updateOrInsert(
            ['nome' => 'Luanda'],
            ['ativo' => true, 'updated_at' => now(), 'created_at' => now()]
        );
    }
}
