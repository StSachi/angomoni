<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model UnidadeSaude
 *
 * Representa uma unidade de saúde (hospital/centro/posto/clínica).
 * Alinhado com a migration create_unidades_saude_table (provincia_id, municipio_id).
 */
class UnidadeSaude extends Model
{
    protected $table = 'unidades_saude';

    protected $fillable = [
        'nome',
        'tipo',
        'provincia_id',
        'municipio_id',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function casosRegistados(): HasMany
    {
        return $this->hasMany(Caso::class, 'unidade_registo_id');
    }

    public function casosOrigem(): HasMany
    {
        return $this->hasMany(Caso::class, 'unidade_origem_id');
    }

    public function utilizadores(): HasMany
    {
        return $this->hasMany(User::class, 'unidade_saude_id');
    }
}
