<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\ServicoAuditoria;

class LogLogin
{
    public function handle(Login $event): void
    {
        ServicoAuditoria::registar('LOGIN', 'Utilizador iniciou sessão');
    }
}
