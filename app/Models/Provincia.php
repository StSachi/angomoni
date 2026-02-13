<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Provincia
 *
 * Catálogo nacional de províncias.
 * Relaciona-se com Municípios e Unidades de Saúde.
 */
class Provincia extends Model
{
    protected $table = 'provincias';

    protected $guarded = [];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class, 'provincia_id');
    }

    public function unidadesSaude(): HasMany
    {
        return $this->hasMany(UnidadeSaude::class, 'provincia_id');
    }
}
