<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Doenca
 *
 * Cadastro de doenças endémicas monitorizadas pelo sistema.
 * Alinhado com create_doencas_table e com os campos adicionados por add_campos_requisitos_to_doencas_table.
 */
class Doenca extends Model
{
    protected $table = 'doencas';

    protected $fillable = [
        'nome',
        'descricao',
        'sintomas_resumo',
        'prevencao_resumo',
        'ativa',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'ativa' => 'boolean',
    ];

    public function casos(): HasMany
    {
        return $this->hasMany(Caso::class);
    }

    public function conteudosExternos(): HasMany
    {
        return $this->hasMany(DiseaseContent::class, 'doenca_id');
    }
}
