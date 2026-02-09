<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@angomoni.test'],
            [
                'name' => 'Administrador do Sistema',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
                'ativo' => true,
                'unidade_saude_id' => null,
            ]
        );
    }
}
