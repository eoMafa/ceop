<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovimentacaoEstoque extends Model
{
    use HasFactory;

    protected $table = 'movimentacao_estoques';

    protected $fillable = [
        'produto_id',
        'user_id',
        'tipo',
        'quantidade',
        'valor_unitario',
        'estoque_anterior',
        'estoque_posterior',
        'motivo',
        'documento',
    ];

    protected function casts(): array
    {
        return [
            'quantidade'        => 'decimal:2',
            'valor_unitario'    => 'decimal:2',
            'estoque_anterior'  => 'decimal:2',
            'estoque_posterior' => 'decimal:2',
        ];
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}