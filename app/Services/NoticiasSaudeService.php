<?php

namespace App\Services;

use App\Models\HealthNews;
use Illuminate\Support\Facades\Http;

/**
 * NoticiasSaudeService
 *
 * Responsabilidade:
 * - Buscar notícias de saúde a partir de feeds RSS (fontes oficiais/gratuitas).
 * - Normalizar os dados (título, URL, resumo, data).
 * - Guardar em cache na base de dados (tabela health_news) para:
 *   - não depender da internet a toda hora
 *   - acelerar a aplicação
 *   - permitir pesquisa/filtragem interna
 *
 * Nota técnica importante (MySQL + utf8mb4):
 * - URLs podem ser longas (ex.: 800 chars). Indexar UNIQUE diretamente em url pode falhar
 *   por limite de bytes do índice.
 * - Por isso, a tabela usa url_hash (SHA-256) como chave única, e mantém a URL completa em url.
 */
class NoticiasSaudeService
{
    /**
     * Lista de feeds RSS.
     *
     * Como funciona:
     * - A chave do array é um nome interno da fonte (vai para a coluna "fonte").
     * - O valor é o URL do feed RSS.
     *
     * Dica:
     * - Podes adicionar WHO África e ECDC quando escolheres os links finais.
     */
    private array $feeds = [
        // Exemplo oficial CDC/EID (gratuito)
        'CDC_EID_AHEAD_OF_PRINT' => 'http://wwwnc.cdc.gov/eid/rss/ahead-of-print.xml',

        // Exemplos (descomentarias quando definires os URLs finais)
        // 'WHO_AFRO_RSS' => 'https://...rss...',
        // 'ECDC_RSS'     => 'https://...rss...',
    ];

    /**
     * Atualiza o cache de notícias:
     * - Lê cada feed RSS
     * - Cria registos novos (sem duplicar) usando url_hash como chave única
     *
     * @return int Quantidade de notícias inseridas nesta execução.
     */
    public function atualizarNoticias(): int
    {
        $totalInseridas = 0;

        foreach ($this->feeds as $fonte => $feedUrl) {
            $itens = $this->lerFeedRss($feedUrl);

            foreach ($itens as $item) {
                // Segurança: se vier item sem URL, ignora (não conseguimos identificar/armazenar).
                if (empty($item['url'])) {
                    continue;
                }

                /**
                 * Gera hash SHA-256 da URL.
                 *
                 * Por quê?
                 * - A URL pode ser muito longa para index UNIQUE no MySQL (utf8mb4).
                 * - O hash tem tamanho fixo (64 chars) e pode ser indexado sem problemas.
                 *
                 * O que este comando faz:
                 * - hash('sha256', $string) devolve um texto hexadecimal com 64 caracteres.
                 */
                $hash = hash('sha256', $item['url']);

                /**
                 * firstOrCreate:
                 * - Primeiro tenta encontrar um registo com o "url_hash" fornecido.
                 * - Se não existir, cria com os dados indicados.
                 *
                 * Isso evita duplicar notícias quando o job rodar de novo.
                 */
                $news = HealthNews::firstOrCreate(
                    ['url_hash' => $hash],
                    [
                        'fonte'        => $fonte,
                        'titulo'       => $item['titulo'] ?? 'Sem título',
                        'url'          => $item['url'],
                        'resumo'       => $item['resumo'] ?? null,
                        'publicado_em' => $item['publicado_em'] ?? null,
                        'tags'         => $item['tags'] ?? null,
                    ]
                );

                // wasRecentlyCreated = true quando o firstOrCreate acabou de criar um registo novo.
                if ($news->wasRecentlyCreated) {
                    $totalInseridas++;
                }
            }
        }

        return $totalInseridas;
    }

    /**
     * Lê e interpreta um feed RSS (XML).
     *
     * @param string $feedUrl URL do RSS
     * @return array Lista de itens normalizados
     */
    private function lerFeedRss(string $feedUrl): array
    {
        /**
         * Http::timeout(10)->get($url)
         * - Faz uma requisição HTTP GET
         * - timeout(10) define tempo máximo (10s) para não travar o sistema
         */
        $response = Http::timeout(10)->get($feedUrl);

        // ok() verifica se a resposta HTTP foi 200-299.
        if (! $response->ok()) {
            return [];
        }

        /**
         * simplexml_load_string:
         * - Converte XML em objeto SimpleXMLElement
         * - O @ suprime warnings caso o XML venha malformado
         */
        $xml = @simplexml_load_string($response->body());
        if (! $xml) {
            return [];
        }

        // RSS padrão: <channel><item>...</item></channel>
        $items = $xml->channel->item ?? [];
        $saida = [];

        foreach ($items as $item) {
            $titulo = (string)($item->title ?? '');
            $url = (string)($item->link ?? '');
            $resumo = (string)($item->description ?? '');

            /**
             * pubDate vem como texto (ex.: "Fri, 12 Jan 2026 10:00:00 GMT")
             * strtotime converte para timestamp Unix
             * date('Y-m-d H:i:s', ...) converte para formato compatível com MySQL
             */
            $pubDate = (string)($item->pubDate ?? '');
            $publicadoEm = $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : null;

            $saida[] = [
                'titulo' => $titulo ?: 'Sem título',
                'url' => $url,
                /**
                 * strip_tags remove HTML que costuma vir no RSS.
                 * Ajuda a evitar HTML bruto na tua view.
                 */
                'resumo' => $resumo ? strip_tags($resumo) : null,
                'publicado_em' => $publicadoEm,
                'tags' => $this->extrairTags($titulo),
            ];
        }

        return $saida;
    }

    /**
     * Extrai tags simples do título para facilitar "notícias relacionadas à doença".
     *
     * Estratégia simples e defendível:
     * - quebra o título em palavras
     * - remove palavras muito comuns (stopwords)
     * - limita quantidade de tags
     */
    private function extrairTags(string $titulo): array
    {
        // mb_strtolower baixa o texto respeitando caracteres Unicode.
        $titulo = mb_strtolower($titulo);

        // preg_split quebra em palavras removendo sinais/pontuação.
        $palavras = preg_split('/\W+/u', $titulo, -1, PREG_SPLIT_NO_EMPTY);

        // Stopwords básicas (podes ampliar conforme necessidade)
        $stop = ['the','and','for','with','from','sobre','para','com','uma','um','de','da','do','e','a','o','em','no','na'];

        // array_diff remove as stopwords
        $tags = array_values(array_diff($palavras, $stop));

        // array_slice limita o tamanho do array (evita poluição)
        return array_slice($tags, 0, 8);
    }
}
