<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function delete(User $authUser, User $user): bool
    {
        // Só ADMIN pode apagar
        if ($authUser->papel !== 'ADMIN') {
            return false;
        }

        // Não pode apagar a si próprio
        if ($authUser->id === $user->id) {
            return false;
        }

        // Não permitir apagar o último ADMIN ativo
        if ($user->papel === 'ADMIN') {
            $adminsAtivos = User::where('papel', 'ADMIN')
                ->where('ativo', true)
                ->count();

            if ($adminsAtivos <= 1) {
                return false;
            }
        }

        return true;
    }
}
