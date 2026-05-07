<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fornecedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fornecedores';

    protected $fillable = [
        'nome', 'cnpj', 'telefone', 'email', 'contato',
        'cep', 'logradouro', 'numero', 'complemento',
        'bairro', 'cidade', 'estado', 'observacoes',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}