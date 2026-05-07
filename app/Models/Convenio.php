<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Convenio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'desconto_percentual',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'desconto_percentual' => 'decimal:2',
            'ativo'               => 'boolean',
        ];
    }

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }

    public function orcamentos()
    {
        return $this->hasMany(Orcamento::class);
    }
}