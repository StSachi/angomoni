<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model HealthNews
 *
 * Representa uma notícia de saúde obtida via RSS e armazenada em cache local.
 * A coluna url_hash (SHA-256 da URL) é usada como identificador único.
 */
class HealthNews extends Model
{
    protected $table = 'health_news';

    protected $fillable = [
        'fonte',
        'titulo',
        'url',
        'url_hash',
        'resumo',
        'publicado_em',
        'tags',
    ];

    protected $casts = [
        'publicado_em' => 'datetime',
        'tags' => 'array',
    ];
}
