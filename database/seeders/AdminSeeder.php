<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@angomoni.test'],
            [
                'name' => 'Administrador AngoMoni',
                'password' => Hash::make('Admin@12345'),
                'papel' => 'ADMIN',
                'ativo' => true,
            ]
        );
    }
}
