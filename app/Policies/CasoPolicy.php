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
        if (! $user->ativo) {
            return false;
        }

        if ($user->papel === 'ADMIN') {
            return true;
        }

        // REGISTADOR e TECNICO_UNIDADE: só casos da sua unidade
        return !is_null($user->unidade_saude_id)
            && $user->unidade_saude_id === $caso->unidade_registo_id;
    }

    public function create(User $user): bool
    {
        if (! $user->ativo) {
            return false;
        }

        if ($user->papel === 'ADMIN') {
            return true;
        }

        return $user->papel === 'REGISTADOR'
            && !is_null($user->unidade_saude_id);
    }

    public function update(User $user, Caso $caso): bool
    {
        if (! $user->ativo) {
            return false;
        }

        if ($user->papel === 'ADMIN') {
            return true;
        }

        return $user->papel === 'REGISTADOR'
            && !is_null($user->unidade_saude_id)
            && $user->unidade_saude_id === $caso->unidade_registo_id;
    }

    public function delete(User $user, Caso $caso): bool
    {
        if (! $user->ativo) {
            return false;
        }

        return $user->papel === 'ADMIN';
    }

    /**
     * WORKFLOW: submeter
     * REGISTADOR da própria unidade ou ADMIN
     */
    public function submit(User $user, Caso $caso): bool
    {
        if (! $user->ativo) {
            return false;
        }

        if ($user->papel === 'ADMIN') {
            return true;
        }

        return $user->papel === 'REGISTADOR'
            && !is_null($user->unidade_saude_id)
            && $user->unidade_saude_id === $caso->unidade_registo_id;
    }

    /**
     * WORKFLOW: validar
     * TECNICO_UNIDADE da própria unidade ou ADMIN
     */
    public function validate(User $user, Caso $caso): bool
    {
        if (! $user->ativo) {
            return false;
        }

        if ($user->papel === 'ADMIN') {
            return true;
        }

        return $user->papel === 'TECNICO_UNIDADE'
            && !is_null($user->unidade_saude_id)
            && $user->unidade_saude_id === $caso->unidade_registo_id;
    }

    /**
     * WORKFLOW: rejeitar
     * Mesma regra do validate
     */
    public function reject(User $user, Caso $caso): bool
    {
        return $this->validate($user, $caso);
    }
}
