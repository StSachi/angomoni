<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Paciente
 *
 * Representa um paciente registado no sistema.
 * Tabela: pacientes
 *
 * Regras de negócio:
 * - nacionalidade = ESTRANGEIRO => pais_id deve ser informado (catálogo de países)
 * - nacionalidade = NACIONAL => pais_id deve ser null
 */
class Paciente extends Model
{
    protected $table = 'pacientes';

    /**
     * Campos permitidos para mass assignment (alinhado ao DESCRIBE pacientes).
     */
    protected $fillable = [
        'nome_completo',
        'data_nascimento',
        'sexo',
        'telefone',
        'endereco',
        'nacionalidade',
        'pais_id',
        'provincia_id',
        'municipio_id',
        'tipo_documento',
        'numero_documento',
    ];

    /**
     * Conversões automáticas de tipos.
     */
    protected $casts = [
        'data_nascimento' => 'date',
    ];

    /**
     * Relacionamento: um paciente pode ter vários casos.
     */
    public function casos(): HasMany
    {
        return $this->hasMany(Caso::class, 'paciente_id');
    }

    /**
     * Relacionamento: país (usado quando nacionalidade = ESTRANGEIRO).
     */
    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }

    /**
     * Relacionamento: província (opcional).
     */
    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    /**
     * Relacionamento: município (opcional).
     */
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }
}
