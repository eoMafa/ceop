<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

class Procedimento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'duracao_padrao_minutos',
        'valor_padrao',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'valor_padrao' => 'decimal:2',
            'duracao_padrao_minutos' => 'integer',
        ];
    }

    // Retorna duração formatada ex: 1h 30min
    public function getDuracaoFormatadaAttribute(): string
    {
        $horas = intdiv($this->duracao_padrao_minutos, 60);
        $minutos = $this->duracao_padrao_minutos % 60;

        if ($horas > 0 && $minutos > 0) {
            return "{$horas}h {$minutos}min";
        } elseif ($horas > 0) {
            return "{$horas}h";
        }
        return "{$minutos}min";
    }
}
