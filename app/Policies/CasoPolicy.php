<?php

namespace App\Policies;

use App\Models\Caso;
use App\Models\User;

class CasoPolicy
{
    public function viewAny(User $user): bool
    {
        return (bool) $user->ativo;
    }

    public function view(User $user, Caso $caso): bool
    {
        if (! (bool) $user->ativo) return false;

        if (($user->papel ?? null) === 'ADMIN') return true;

        // REGISTADOR/TECNICO_UNIDADE: só casos da própria unidade
        return $user->unidade_saude_id !== null
            && (int) $user->unidade_saude_id === (int) $caso->unidade_registo_id;
    }

    public function create(User $user): bool
    {
        if (! (bool) $user->ativo) return false;

        if (($user->papel ?? null) === 'ADMIN') return true;

        return in_array(($user->papel ?? null), ['REGISTADOR'], true)
            && $user->unidade_saude_id !== null;
    }

    public function update(User $user, Caso $caso): bool
    {
        if (! (bool) $user->ativo) return false;

        if (($user->papel ?? null) === 'ADMIN') return true;

        // REGISTADOR só edita casos da própria unidade
        return (($user->papel ?? null) === 'REGISTADOR')
            && $user->unidade_saude_id !== null
            && (int) $user->unidade_saude_id === (int) $caso->unidade_registo_id;
    }

    public function delete(User $user, Caso $caso): bool
    {
        return (bool) $user->ativo && (($user->papel ?? null) === 'ADMIN');
    }

    public function submit(User $user, Caso $caso): bool
    {
        if (! (bool) $user->ativo) return false;

        if (($user->papel ?? null) === 'ADMIN') return true;

        return (($user->papel ?? null) === 'REGISTADOR')
            && $user->unidade_saude_id !== null
            && (int) $user->unidade_saude_id === (int) $caso->unidade_registo_id;
    }

    public function validate(User $user, Caso $caso): bool
    {
        if (! (bool) $user->ativo) return false;

        return in_array(($user->papel ?? null), ['TECNICO_UNIDADE', 'ADMIN'], true);
    }

    public function reject(User $user, Caso $caso): bool
    {
        if (! (bool) $user->ativo) return false;

        return in_array(($user->papel ?? null), ['TECNICO_UNIDADE', 'ADMIN'], true);
    }
}
