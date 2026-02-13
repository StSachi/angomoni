<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Pais
 *
 * Catálogo de países utilizado para pacientes estrangeiros.
 */
class Pais extends Model
{
    protected $table = 'paises';

    protected $fillable = [
        'nome',
        'iso2',
        'iso3',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class, 'pais_id');
    }
}
