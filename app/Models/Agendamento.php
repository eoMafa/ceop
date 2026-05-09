<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agendamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paciente_id',
        'dentista_id',
        'procedimento_id',
        'orcamento_id',
        'data_hora_inicio',
        'data_hora_fim',
        'status',
        'tipo',
        'observacoes'
    ];

    protected function casts(): array
    {
        return [
            'data_hora_inicio' => 'datetime',
            'data_hora_fim' => 'datetime',
        ];
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function dentista()
    {
        return $this->belongsTo(User::class, 'dentista_id');
    }

    public function procedimento()
    {
        return $this->belongsTo(Procedimento::class);
    }

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function evolucoes()
    {
        return $this->hasMany(Evolucao::class);
    }

    public function getCorStatusAttribute(): string
    {
        return match ($this->status) {
            'agendado' => '#4299e1',
            'confirmado' => '#48bb78',
            'cancelado' => '#f56565',
            'concluido' => '#667eea',
            'falta' => '#ed8936',
            default => '#a0aec0',
        };
    }

    public function getCorTipoAttribute(): string
    {
        return match($this->tipo) {
            'orcamento'  => '#ed8936',
            'consulta'   => '#4299e1',
            'retorno'    => '#48bb78',
            'avaliacao'  => '#667eea',
            default      => '#a0aec0',
        };
    }
}
