<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoriaEstoque extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categoria_estoques';

    protected $fillable = ['nome', 'descricao'];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}