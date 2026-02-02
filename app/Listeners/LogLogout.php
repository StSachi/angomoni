<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Services\ServicoAuditoria;

class LogLogout
{
    public function handle(Logout $event): void
    {
        ServicoAuditoria::registar('LOGOUT', 'Utilizador terminou sessão');
    }
}
