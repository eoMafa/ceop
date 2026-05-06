<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prontuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'observacoes_gerais',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function anamnese()
    {
        return $this->hasOne(Anamnese::class);
    }

    public function evolucoes()
    {
        return $this->hasMany(Evolucao::class)->latest();
    }
}
