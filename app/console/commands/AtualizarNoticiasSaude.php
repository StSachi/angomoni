<?php

namespace App\Console\Commands;

use App\Services\NoticiasSaudeService;
use Illuminate\Console\Command;

/**
 * Comando para atualizar o cache de notícias de saúde via RSS.
 * Será executado pelo scheduler (ex.: 2x por dia).
 */
class AtualizarNoticiasSaude extends Command
{
    protected $signature = 'saude:noticias-atualizar';

    protected $description = 'Atualiza notícias de saúde (RSS) e grava no cache do sistema';

    public function handle(NoticiasSaudeService $service): int
    {
        // Chama o serviço que lê RSS e salva na BD
        $qtd = $service->atualizarNoticias();

        $this->info("Notícias inseridas: {$qtd}");

        return Command::SUCCESS;
    }
}
