<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'cro',
        'telefone',
        'ativo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'ativo'             => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDentista(): bool
    {
        return $this->role === 'dentista';
    }

    public function isRecepcionista(): bool
    {
        return $this->role === 'recepcionista';
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function temPermissao(string $ability): bool
    {
        if ($this->isAdmin()) return true;

        [$modulo, $acao] = explode('.', $ability);

        return $this->permissions
            ->where('modulo', $modulo)
            ->where('acao', $acao)
            ->isNotEmpty();
    }

    public function podeVer(string $modulo): bool
    {
        return $this->temPermissao("{$modulo}.ver");
    }

    public function podeCriar(string $modulo): bool
    {
        return $this->temPermissao("{$modulo}.criar");
    }

    public function podeEditar(string $modulo): bool
    {
        return $this->temPermissao("{$modulo}.editar");
    }

    public function podeDeletar(string $modulo): bool
    {
        return $this->temPermissao("{$modulo}.deletar");
    }
}