<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anamnese extends Model
{
    use HasFactory;

    protected $fillable = [
        'prontuario_id',
        'alergia',
        'alergia_descricao',
        'medicamentos_uso',
        'medicamentos_descricao',
        'pressao_alta',
        'diabetes',
        'cardiopatia',
        'gestante',
        'fumante',
        'alcool',
        'doenca_renal',
        'doenca_hepatica',
        'problemas_coagulacao',
        'outras_doencas',
        'observacoes',
    ];

    protected function casts(): array
    {
        return [
            'alergia'               => 'boolean',
            'medicamentos_uso'      => 'boolean',
            'pressao_alta'          => 'boolean',
            'diabetes'              => 'boolean',
            'cardiopatia'           => 'boolean',
            'gestante'              => 'boolean',
            'fumante'               => 'boolean',
            'alcool'                => 'boolean',
            'doenca_renal'          => 'boolean',
            'doenca_hepatica'       => 'boolean',
            'problemas_coagulacao'  => 'boolean',
        ];
    }

    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class);
    }
}