<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model DiseaseContent
 *
 * Cache de conteúdo educativo obtido de fontes externas (ex.: MedlinePlus).
 */
class DiseaseContent extends Model
{
    protected $table = 'disease_contents';

    protected $fillable = [
        'doenca_id',
        'fonte',
        'idioma',
        'titulo',
        'resumo',
        'links',
        'obtido_em',
    ];

    protected $casts = [
        'links' => 'array',
        'obtido_em' => 'datetime',
    ];

    public function doenca(): BelongsTo
    {
        return $this->belongsTo(Doenca::class, 'doenca_id');
    }
}
