<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'cpf',
        'rg',
        'data_nascimento',
        'sexo',
        'telefone',
        'email',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'observacoes',
        'ativo', 
        'convenio_id'
    ];

    protected function casts(): array {
        return [
            'data_nascimento' => 'date',
            'ativo' => 'boolean',
        ];
    }

    public function getIdadeAttribute(): int{
        return $this->data_nascimento->age;
    }

    public function prontuario()
    {
        return $this->hasOne(Prontuario::class);
    }

    public function convenio()
    {
        return $this->belongsTo(Convenio::class);
    }
}
