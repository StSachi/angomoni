<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Municipio
 *
 * Catálogo nacional de municípios.
 * Cada município pertence a uma província e pode ter várias unidades de saúde.
 */
class Municipio extends Model
{
    protected $table = 'municipios';

    protected $guarded = [];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    public function unidadesSaude(): HasMany
    {
        return $this->hasMany(UnidadeSaude::class, 'municipio_id');
    }
}
