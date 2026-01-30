<?php

namespace App\Policies;

use App\Models\Caso;
use App\Models\User;

class CasoPolicy
{
    public function viewAny(User $user): bool
    {
        // Ambos podem ver a lista
        return $user->ativo && in_array($user->papel, ['ADMIN', 'PROFISSIONAL']);
    }

    public function view(User $user, Caso $caso): bool
    {
        // Ambos podem ver detalhes (se quiseres restringir depois, mudamos aqui)
        return $user->ativo && in_array($user->papel, ['ADMIN', 'PROFISSIONAL']);
    }

    public function create(User $user): bool
    {
        // Ambos podem registar casos
        return $user->ativo && in_array($user->papel, ['ADMIN', 'PROFISSIONAL']);
    }

    public function update(User $user, Caso $caso): bool
    {
        if (!$user->ativo) return false;

        // ADMIN pode tudo
        if ($user->papel === 'ADMIN') return true;

        // PROFISSIONAL só pode editar casos que ele criou
        return $user->papel === 'PROFISSIONAL' && $caso->user_id === $user->id;
    }

    public function delete(User $user, Caso $caso): bool
    {
        if (!$user->ativo) return false;

        // ADMIN pode tudo
        if ($user->papel === 'ADMIN') return true;

        // PROFISSIONAL só pode apagar casos que ele criou
        return $user->papel === 'PROFISSIONAL' && $caso->user_id === $user->id;
    }
}
