<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caso extends Model
{
    protected $table = 'casos';

    protected $fillable = [
        'doenca_id',
        'unidade_saude_id',
        'user_id',
        'data_notificacao',
        'idade',
        'sexo',
        'estado',
        'observacoes',
        'paciente_iniciais',
        'telefone_contacto',
    ];

    protected $casts = [
        'data_notificacao' => 'date',
    ];

    public function doenca() { return $this->belongsTo(Doenca::class); }
    public function unidadeSaude() { return $this->belongsTo(UnidadeSaude::class); }
    public function user() { return $this->belongsTo(User::class); }
}
