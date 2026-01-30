<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // (Opcional) criar utilizador de teste
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'papel' => 'PROFISSIONAL',
            'ativo' => true,
        ]);

        // ✅ Admin SEMPRE
        $this->call(AdminSeeder::class);
    }
}
