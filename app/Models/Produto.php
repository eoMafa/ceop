<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'categoria_estoque_id',
        'fornecedor_id',
        'nome',
        'codigo',
        'descricao',
        'unidade',
        'estoque_atual',
        'estoque_minimo',
        'valor_custo',
    ];

    protected function casts(): array
    {
        return [
            'estoque_atual'  => 'decimal:2',
            'estoque_minimo' => 'decimal:2',
            'valor_custo'    => 'decimal:2',
        ];
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaEstoque::class, 'categoria_estoque_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoEstoque::class)->latest();
    }

    public function getEstoqueBaixoAttribute(): bool
    {
        return $this->estoque_atual <= $this->estoque_minimo;
    }
}