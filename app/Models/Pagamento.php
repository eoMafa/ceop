<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pagamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paciente_id',
        'orcamento_id',
        'descricao',
        'valor_total',
        'numero_parcelas',
        'forma_pagamento',
        'status',
        'observacoes',
    ];

    protected function casts(): array
    {
        return [
            'valor_total'     => 'decimal:2',
            'numero_parcelas' => 'integer',
        ];
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function parcelas()
    {
        return $this->hasMany(Parcela::class)->orderBy('numero');
    }

    public function getCorStatusAttribute(): string
    {
        return match($this->status) {
            'pendente'  => 'bg-warning',
            'pago'      => 'bg-success',
            'parcial'   => 'bg-blue',
            'cancelado' => 'bg-danger',
            default     => 'bg-secondary',
        };
    }

    // Atualiza status baseado nas parcelas
    public function atualizarStatus(): void
    {
        $total = $this->parcelas->count();
        $pagas = $this->parcelas->where('status', 'pago')->count();

        $this->status = match(true) {
            $pagas === 0        => 'pendente',
            $pagas === $total   => 'pago',
            default             => 'parcial',
        };
        $this->save();
    }
}