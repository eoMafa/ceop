<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogService
{
    public static function log(
        string $acao,
        string $modulo,
        string $descricao,
        ?Model $model = null,
        ?array $dadosAnteriores = null,
        ?array $dadosNovos = null,
    ): void {
        $request = app(Request::class);

        ActivityLog::create([
            'user_id'          => auth()->id(),
            'acao'             => $acao,
            'modulo'           => $modulo,
            'descricao'        => $descricao,
            'loggable_type'    => $model ? get_class($model) : null,
            'loggable_id'      => $model?->id,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos'      => $dadosNovos,
            'ip'               => $request->ip(),
            'user_agent'       => $request->userAgent(),
        ]);
    }

    // Atalhos para ações comuns
    public static function criou(string $modulo, string $descricao, Model $model): void
    {
        self::log('criou', $modulo, $descricao, $model, null, $model->toArray());
    }

    public static function editou(string $modulo, string $descricao, Model $model, array $dadosAnteriores): void
    {
        self::log('editou', $modulo, $descricao, $model, $dadosAnteriores, $model->toArray());
    }

    public static function deletou(string $modulo, string $descricao, Model $model): void
    {
        self::log('deletou', $modulo, $descricao, $model, $model->toArray(), null);
    }

    public static function acessou(string $modulo, string $descricao): void
    {
        self::log('acessou', $modulo, $descricao);
    }

    public static function login(string $descricao): void
    {
        self::log('login', 'autenticacao', $descricao);
    }

    public static function logout(string $descricao): void
    {
        self::log('logout', 'autenticacao', $descricao);
    }
}