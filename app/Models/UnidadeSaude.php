<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadeSaude extends Model
{
    protected $table = 'unidades_saude';

    protected $fillable = [
        'nome',
        'provincia',
        'municipio',
        'comuna',
        'bairro',
        'telefone',
    ];
}
