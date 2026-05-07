<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrcamentoItem extends Model
{
    use HasFactory;

    protected $table = 'orcamento_itens';

    protected $fillable = [
        'orcamento_id',
        'procedimento_id',
        'dente',
        'quantidade',
        'valor_unitario',
        'valor_total',
    ];

    protected function casts(): array
    {
        return [
            'valor_unitario' => 'decimal:2',
            'valor_total'    => 'decimal:2',
            'quantidade'     => 'integer',
        ];
    }

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function procedimento()
    {
        return $this->belongsTo(Procedimento::class);
    }
}