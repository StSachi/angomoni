<?php

namespace App\Services;

use App\Models\DiseaseContent;
use App\Models\Doenca;
use Illuminate\Support\Facades\Http;

/**
 * Busca informação educativa de uma doença em fonte externa gratuita (MedlinePlus Web Service).
 * - Primeiro tenta cache em BD.
 * - Se estiver desatualizado, consulta a API e atualiza o cache.
 *
 * Fonte: MedlinePlus Web Service (XML). :contentReference[oaicite:4]{index=4}
 */
class DoencaInfoService
{
    /**
     * Define quantos dias um conteúdo pode ficar em cache antes de atualizar.
     */
    private int $diasCache = 30;

    /**
     * Retorna conteúdo educativo para uma doença (com cache).
     */
    public function obterConteudo(Doenca $doenca, string $idioma = 'en'): ?DiseaseContent
    {
        // 1) Busca cache existente
        $cache = DiseaseContent::where('doenca_id', $doenca->id)
            ->where('fonte', 'MEDLINEPLUS')
            ->where('idioma', $idioma)
            ->first();

        // 2) Se cache existir e estiver recente, devolve sem chamar API
        if ($cache && $cache->obtido_em && $cache->obtido_em->diffInDays(now()) <= $this->diasCache) {
            return $cache;
        }

        // 3) Caso contrário, chama a API para atualizar
        $novo = $this->consultarMedlinePlus($doenca->nome, $idioma);

        if (! $novo) {
            // Se falhar a API, devolve cache antigo (se existir) para não quebrar a UI.
            return $cache;
        }

        // 4) Salva/atualiza cache
        return DiseaseContent::updateOrCreate(
            ['doenca_id' => $doenca->id, 'fonte' => 'MEDLINEPLUS', 'idioma' => $idioma],
            [
                'titulo' => $novo['titulo'] ?? null,
                'resumo' => $novo['resumo'] ?? null,
                'links' => $novo['links'] ?? null,
                'obtido_em' => now(),
            ]
        );
    }

    /**
     * Consulta o MedlinePlus Web Service (XML) por palavra-chave.
     *
     * Como funciona:
     * - enviamos um termo (ex.: "Malaria")
     * - recebemos XML com tópicos relevantes e snippets/sumários
     */
    private function consultarMedlinePlus(string $termo, string $idioma = 'en'): ?array
    {
        // URL base do serviço (documentado pela NLM/NIH) :contentReference[oaicite:5]{index=5}
        $url = 'https://wsearch.nlm.nih.gov/ws/query';

        // Monta parâmetros:
        // - db=healthTopics: base de tópicos do MedlinePlus
        // - term: termo de pesquisa
        // - retmax: número máximo de resultados
        // - rettype=brief: resposta resumida
        $response = Http::timeout(10)->get($url, [
            'db' => 'healthTopics',
            'term' => $termo,
            'retmax' => 5,
            'rettype' => 'brief',
        ]);

        if (! $response->ok()) {
            return null;
        }

        // Converte XML em objeto
        $xml = @simplexml_load_string($response->body());
        if (! $xml) return null;

        // Pega o 1º resultado relevante (rank mais alto)
        $doc = $xml->xpath('//document')[0] ?? null;
        if (! $doc) return null;

        // Extrai título + snippet e links
        $titulo = (string)($doc->xpath('.//content[@name="title"]')[0] ?? '');
        $snippet = (string)($doc->xpath('.//content[@name="snippet"]')[0] ?? '');
        $urlTopico = (string)($doc->xpath('.//content[@name="url"]')[0] ?? '');

        return [
            'titulo' => $titulo ?: $termo,
            'resumo' => $snippet ?: null,
            'links' => $urlTopico ? [['label' => 'MedlinePlus', 'url' => $urlTopico]] : null,
        ];
    }
}
