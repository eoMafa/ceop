<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evolucao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'evolucoes';

    protected $fillable = [
        'prontuario_id',
        'dentista_id',
        'agendamento_id',
        'dente',
        'face',
        'descricao',
    ];

    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class);
    }

    public function dentista()
    {
        return $this->belongsTo(User::class, 'dentista_id');
    }

    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class);
    }

    public function arquivos()
    {
        return $this->hasMany(EvolucaoArquivo::class);
    }
}