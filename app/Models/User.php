<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignment
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'papel',
        'ativo',
        'unidade_saude_id',
    ];

    /**
     * Hidden
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ativo' => 'boolean',
            'unidade_saude_id' => 'integer',
        ];
    }

    /**
     * Unidade de Saúde associada ao utilizador (profissional).
     * Serve para impor a regra institucional: profissional só regista caso na sua unidade.
     */
    public function unidadeSaude(): BelongsTo
    {
        return $this->belongsTo(UnidadeSaude::class, 'unidade_saude_id');
    }

    /**
     * Helpers de papel (opcional, mas ajuda a manter o código limpo)
     */
    public function isAdmin(): bool
    {
        return ($this->papel ?? null) === 'ADMIN';
    }

    public function isProfissional(): bool
    {
        return ($this->papel ?? null) === 'PROFISSIONAL';
    }
}
