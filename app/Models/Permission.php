<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['modulo', 'acao', 'nome'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // Módulos do sistema
    public static function modulos(): array
    {
        return [
            'pacientes'     => 'Pacientes',
            'agendamentos'  => 'Agendamentos',
            'prontuario'    => 'Prontuário',
            'financeiro'    => 'Financeiro',
            'estoque'       => 'Estoque',
            'usuarios'      => 'Usuários',
            'relatorios'   => 'Relatórios',
        ];
    }

    // Ações disponíveis
    public static function acoes(): array
    {
        return [
            'ver'     => 'Ver',
            'criar'   => 'Criar',
            'editar'  => 'Editar',
            'deletar' => 'Deletar',
        ];
    }
}