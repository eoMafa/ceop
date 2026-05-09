<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'acao',
        'modulo',
        'descricao',
        'loggable_type',
        'loggable_id',
        'dados_anteriores',
        'dados_novos',
        'ip',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'dados_anteriores' => 'array',
            'dados_novos'      => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loggable()
    {
        return $this->morphTo();
    }
}