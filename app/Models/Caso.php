<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Caso
 *
 * Representa um caso epidemiológico registado no sistema.
 * Alinhado com a migration create_casos_table.
 */
class Caso extends Model
{
    protected $table = 'casos';

    protected $fillable = [
        'paciente_id',
        'doenca_id',
        'unidade_registo_id',
        'unidade_origem_id',
        'user_id',
        'data_notificacao',
        'data_inicio_sintomas',
        'classificacao_caso',
        'tipo_deteccao',
        'fonte_notificacao',
        'estado',
        'submetido_em',
        'parecer_tecnico',
        'validado_por',
        'validado_em',
    ];

    protected $casts = [
        'data_notificacao' => 'date',
        'data_inicio_sintomas' => 'date',
        'submetido_em' => 'datetime',
        'validado_em' => 'datetime',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doenca(): BelongsTo
    {
        return $this->belongsTo(Doenca::class);
    }

    public function unidadeRegisto(): BelongsTo
    {
        return $this->belongsTo(UnidadeSaude::class, 'unidade_registo_id');
    }

    public function unidadeOrigem(): BelongsTo
    {
        return $this->belongsTo(UnidadeSaude::class, 'unidade_origem_id');
    }

    public function utilizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function validador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validado_por');
    }
}
