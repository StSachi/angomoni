<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditoriaAcesso extends Model
{
    protected $table = 'auditoria_acessos';

    protected $fillable = [
        'user_id',
        'acao',
        'descricao',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
