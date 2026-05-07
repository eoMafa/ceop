<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvolucaoMaterial extends Model
{
    use HasFactory;

    protected $table = 'evolucao_materiais';

    protected $fillable = [
        'evolucao_id',
        'produto_id',
        'quantidade',
        'valor_unitario',
    ];

    protected function casts(): array
    {
        return [
            'quantidade'     => 'decimal:2',
            'valor_unitario' => 'decimal:2',
        ];
    }

    public function evolucao()
    {
        return $this->belongsTo(Evolucao::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}