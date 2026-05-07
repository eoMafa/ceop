<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orcamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orcamentos';

    protected $fillable = [
        'paciente_id',
        'dentista_id',
        'convenio_id',
        'status',
        'desconto_tipo',
        'desconto_valor',
        'total_bruto',
        'total_liquido',
        'observacoes',
    ];

    protected function casts(): array
    {
        return [
            'desconto_valor' => 'decimal:2',
            'total_bruto'    => 'decimal:2',
            'total_liquido'  => 'decimal:2',
        ];
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function dentista()
    {
        return $this->belongsTo(User::class, 'dentista_id');
    }

    public function convenio()
    {
        return $this->belongsTo(Convenio::class);
    }

    public function itens()
    {
        return $this->hasMany(OrcamentoItem::class);
    }

    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class);
    }

    public function calcularTotais(): void
    {
        $bruto = $this->itens->sum('valor_total');
        $this->total_bruto = $bruto;

        $desconto = match($this->desconto_tipo) {
            'percentual' => $bruto * ($this->desconto_valor / 100),
            'valor_fixo' => $this->desconto_valor,
            'convenio'   => $this->convenio
                ? $bruto * ($this->convenio->desconto_percentual / 100)
                : 0,
            default => 0,
        };

        $this->total_liquido = (string) max(0, $bruto - $desconto);
        $this->save();
    }

    public function getCorStatusAttribute(): string
    {
        return match($this->status) {
            'rascunho'  => 'bg-secondary',
            'aprovado'  => 'bg-success',
            'recusado'  => 'bg-danger',
            'cancelado' => 'bg-warning',
            default     => 'bg-secondary',
        };
    }
}