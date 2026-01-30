<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditorias';

    protected $fillable = [
        'user_id',
        'acao',
        'entidade',
        'entidade_id',
        'antes',
        'depois',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'antes' => 'array',
        'depois' => 'array',
    ];
}
