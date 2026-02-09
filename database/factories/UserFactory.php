<?php

namespace Database\Factories;

use App\Models\UnidadeSaude;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Factory para criação de utilizadores em testes.
 * Alinhada com o modelo RBAC do sistema.
 */
class UserFactory extends Factory
{
    /**
     * Password padrão reutilizada
     */
    protected static ?string $password;

    /**
     * Estado base do utilizador
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),

            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            // RBAC
            'role' => 'REGISTADOR',
            'ativo' => true,

            // Por padrão não associamos a unidade
            // (pode ser definido em states específicos)
            'unidade_saude_id' => null,
            'criado_por' => null,
        ];
    }

    /**
     * Utilizador ADMIN
     */
    public function admin(): static
    {
        return $this->state(fn () => [
            'role' => 'ADMIN',
            'unidade_saude_id' => null,
        ]);
    }

    /**
     * Técnico de unidade hospitalar
     */
    public function tecnico(UnidadeSaude $unidade = null): static
    {
        return $this->state(fn () => [
            'role' => 'TECNICO_UNIDADE',
            'unidade_saude_id' => $unidade?->id ?? UnidadeSaude::factory(),
        ]);
    }

    /**
     * Utilizador inativo
     */
    public function inativo(): static
    {
        return $this->state(fn () => [
            'ativo' => false,
        ]);
    }

    /**
     * Email não verificado
     */
    public function unverified(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
        ]);
    }
}
