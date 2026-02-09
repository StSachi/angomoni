<?php

namespace App\Policies;

use App\Models\Caso;
use App\Models\User;

class CasoPolicy
{
    public function view(User $user, Caso $caso): bool
    {
        if ($user->role === 'ADMIN') return true;

        // Técnico vê casos da sua unidade
        if ($user->role === 'TECNICO_UNIDADE') {
            return $user->unidade_saude_id === $caso->unidade_registo_id;
        }

        // Registador vê os próprios
        return $user->id === $caso->user_id;
    }

    public function create(User $user): bool
    {
        // Admin, técnico e registador podem criar (se quiseres, podes limitar)
        return in_array($user->role, ['ADMIN','TECNICO_UNIDADE','REGISTADOR'], true);
    }

    public function update(User $user, Caso $caso): bool
    {
        // Só admin edita (regra que definiste)
        if ($user->role === 'ADMIN') return true;

        // Registador pode editar apenas se for rascunho e autor
        if ($user->role === 'REGISTADOR') {
            return $caso->estado === 'RASCUNHO' && $caso->user_id === $user->id;
        }

        // Técnico não edita dados do caso
        return false;
    }

    public function delete(User $user, Caso $caso): bool
    {
        // Só admin deleta
        return $user->role === 'ADMIN';
    }

    public function submit(User $user, Caso $caso): bool
    {
        // Registador submete apenas o próprio rascunho
        if ($user->role === 'REGISTADOR') {
            return $caso->estado === 'RASCUNHO' && $caso->user_id === $user->id;
        }

        // Admin também pode
        return $user->role === 'ADMIN';
    }

    public function validateCase(User $user, Caso $caso): bool
    {
        // Técnico valida casos da sua unidade que estejam submetidos
        if ($user->role === 'TECNICO_UNIDADE') {
            return $caso->estado === 'SUBMETIDO'
                && $user->unidade_saude_id === $caso->unidade_registo_id;
        }

        return $user->role === 'ADMIN';
    }

    public function rejectCase(User $user, Caso $caso): bool
    {
        // Mesma regra da validação
        return $this->validateCase($user, $caso);
    }
}
