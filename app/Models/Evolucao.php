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
        'procedimento_id',
        'orcamento_id',
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

    public function procedimento()
    {
        return $this->belongsTo(Procedimento::class);
    }

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function arquivos()
    {
        return $this->hasMany(EvolucaoArquivo::class);
    }

    public function materiais()
    {
        return $this->hasMany(EvolucaoMaterial::class);
    }

    public function getCustoTotalAttribute(): float
    {
        return $this->materiais->sum(fn($m) => $m->quantidade * $m->valor_unitario);
    }
}