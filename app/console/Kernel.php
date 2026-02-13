
protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule): void
{
    // Executa 2x por dia (09:00 e 18:00) para manter notícias atuais.
    $schedule->command('saude:noticias-atualizar')->twiceDaily(9, 18);
}