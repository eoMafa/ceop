<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacoes';

    protected $fillable = [
        'user_id',
        'titulo',
        'mensagem',
        'icone',
        'tipo',
        'url',
        'lida_em',
    ];

    protected function casts(): array
    {
        return [
            'lida_em' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLidaAttribute(): bool
    {
        return !is_null($this->lida_em);
    }

    public function getCorTipoAttribute(): string
    {
        return match($this->tipo) {
            'agendamento' => 'bg-blue',
            'financeiro'  => 'bg-success',
            'estoque'     => 'bg-warning',
            'aniversario' => 'bg-pink',
            'sistema'     => 'bg-secondary',
            default       => 'bg-secondary',
        };
    }

    public function getIconeTipoAttribute(): string
    {
        return match($this->tipo) {
            'agendamento' => '📅',
            'financeiro'  => '💰',
            'estoque'     => '📦',
            'aniversario' => '🎂',
            'sistema'     => '🔔',
            default       => '🔔',
        };
    }
}