<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parcela extends Model
{
    use HasFactory;

    protected $fillable = [
        'pagamento_id',
        'numero',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'status',
        'pix_txid',
        'pix_copia_cola',
        'pix_qrcode',
    ];

    protected function casts(): array
    {
        return [
            'valor'           => 'decimal:2',
            'data_vencimento' => 'date',
            'data_pagamento'  => 'date',
        ];
    }

    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class);
    }

    public function getCorStatusAttribute(): string
    {
        return match($this->status) {
            'pendente'  => 'bg-warning',
            'pago'      => 'bg-success',
            'cancelado' => 'bg-danger',
            default     => 'bg-secondary',
        };
    }
}