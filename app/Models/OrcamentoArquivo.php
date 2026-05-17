<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class OrcamentoArquivo extends Model
{
    use HasFactory;

    protected $table = 'orcamento_arquivos';

    protected $fillable = [
        'orcamento_id',
        'nome_original',
        'caminho',
        'tipo_mime',
        'tamanho',
    ];

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->caminho);
    }

    public function getTamanhoFormatadoAttribute(): string
    {
        $bytes = $this->tamanho;
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' bytes';
    }
}