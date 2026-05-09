<?php

namespace App\Services;

use App\Models\Notificacao;
use App\Models\User;

class NotificacaoService
{
    // Envia para um usuário específico
    public static function enviar(
        int $userId,
        string $titulo,
        string $mensagem,
        string $tipo = 'sistema',
        ?string $url = null,
    ): Notificacao {
        return Notificacao::create([
            'user_id'  => $userId,
            'titulo'   => $titulo,
            'mensagem' => $mensagem,
            'tipo'     => $tipo,
            'url'      => $url,
        ]);
    }

    // Envia para todos os admins
    public static function enviarParaAdmins(
        string $titulo,
        string $mensagem,
        string $tipo = 'sistema',
        ?string $url = null,
    ): void {
        User::where('role', 'admin')->where('ativo', true)->each(
            fn($user) => self::enviar($user->id, $titulo, $mensagem, $tipo, $url)
        );
    }

    // Envia para todos os dentistas
    public static function enviarParaDentistas(
        string $titulo,
        string $mensagem,
        string $tipo = 'sistema',
        ?string $url = null,
    ): void {
        User::where('role', 'dentista')->where('ativo', true)->each(
            fn($user) => self::enviar($user->id, $titulo, $mensagem, $tipo, $url)
        );
    }

    // Envia para todos os usuários ativos
    public static function enviarParaTodos(
        string $titulo,
        string $mensagem,
        string $tipo = 'sistema',
        ?string $url = null,
    ): void {
        User::where('ativo', true)->each(
            fn($user) => self::enviar($user->id, $titulo, $mensagem, $tipo, $url)
        );
    }

    // Notificação de novo agendamento
    public static function novoAgendamento($agendamento): void
    {
        $titulo   = "Novo agendamento: {$agendamento->paciente->nome}";
        $mensagem = "Agendado para {$agendamento->data_hora_inicio->format('d/m/Y H:i')} com {$agendamento->dentista->name}";
        $url      = route('agendamentos.show', $agendamento->id);

        // Notifica o dentista
        self::enviar($agendamento->dentista_id, $titulo, $mensagem, 'agendamento', $url);

        // Notifica os admins
        self::enviarParaAdmins($titulo, $mensagem, 'agendamento', $url);
    }

    // Notificação de agendamento cancelado
    public static function agendamentoCancelado($agendamento): void
    {
        $titulo   = "Agendamento cancelado: {$agendamento->paciente->nome}";
        $mensagem = "Cancelado: {$agendamento->data_hora_inicio->format('d/m/Y H:i')} com {$agendamento->dentista->name}";
        $url      = route('agendamentos.show', $agendamento->id);

        self::enviar($agendamento->dentista_id, $titulo, $mensagem, 'agendamento', $url);
        self::enviarParaAdmins($titulo, $mensagem, 'agendamento', $url);
    }

    // Notificação de parcela vencida
    public static function parcelaVencida($parcela): void
    {
        $titulo   = "Parcela vencida: {$parcela->pagamento->paciente->nome}";
        $mensagem = "Parcela #{$parcela->numero} de R$ " . number_format($parcela->valor, 2, ',', '.') . " venceu em {$parcela->data_vencimento->format('d/m/Y')}";
        $url      = route('pagamentos.show', $parcela->pagamento_id);

        self::enviarParaAdmins($titulo, $mensagem, 'financeiro', $url);
    }

    // Notificação de estoque baixo
    public static function estoqueBaixo($produto): void
    {
        $titulo   = "Estoque baixo: {$produto->nome}";
        $mensagem = "Estoque atual: {$produto->estoque_atual} {$produto->unidade} (mínimo: {$produto->estoque_minimo} {$produto->unidade})";
        $url      = route('estoque.produtos.show', $produto->id);

        self::enviarParaAdmins($titulo, $mensagem, 'estoque', $url);
    }

    // Notificação de aniversário
    public static function aniversario($paciente): void
    {
        $titulo   = "🎂 Aniversário: {$paciente->nome}";
        $mensagem = "{$paciente->nome} faz {$paciente->idade} anos hoje!";
        $url      = route('pacientes.show', $paciente->id);

        self::enviarParaTodos($titulo, $mensagem, 'aniversario', $url);
    }
}