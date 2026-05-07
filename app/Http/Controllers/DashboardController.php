<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Paciente;
use App\Models\Pagamento;
use App\Models\Parcela;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hoje = now()->toDateString();
        $inicioMes = now()->startOfMonth()->toDateString();
        $fimMes = now()->endOfMonth()->toDateString();
        $iniciaSemana = now()->startOfWeek()->toDateString();
        $fimSemana = now()->endOfWeek()->toDateString();

        // Pacientes
        $totalPacientes = Paciente::count();
        $pacientesNoMes = Paciente::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Agendamentos hoje
        $agendamentosHoje = Agendamento::with(['paciente', 'dentista', 'procedimento'])
            ->whereDate('data_hora_inicio', $hoje)
            ->whereNotIn('status', ['cancelado'])
            ->orderBy('data_hora_inicio')
            ->get();

        // Agendamentos da semana
        $agendamentosSemana = Agendamento::whereBetween('data_hora_inicio', [$iniciaSemana . ' 00:00:00', $fimSemana . ' 23:59:59'])
            ->whereNotIn('status', ['cancelado'])
            ->count();

        // Próximos agendamentos (próximos 5 após hoje)
        $proximosAgendamentos = Agendamento::with(['paciente', 'dentista', 'procedimento'])
            ->whereDate('data_hora_inicio', '>', $hoje)
            ->whereNotIn('status', ['cancelado'])
            ->orderBy('data_hora_inicio')
            ->limit(5)
            ->get();

        // Financeiro do mês
        $receitaMes = Parcela::where('status', 'pago')
            ->whereBetween('data_pagamento', [$inicioMes, $fimMes])
            ->sum('valor');

        $parcelasPendentes = Parcela::where('status', 'pendente')
            ->where('data_vencimento', '<=', $hoje)
            ->count();

        $totalPendente = Parcela::where('status', 'pendente')
            ->where('data_vencimento', '<=', $hoje)
            ->sum('valor');

        $parcelasVencendoHoje = Parcela::where('status', 'pendente')
            ->whereDate('data_vencimento', $hoje)
            ->count();

        // Estoque baixo
        $produtosEstoqueBaixo = Produto::whereColumn('estoque_atual', '<=', 'estoque_minimo')
            ->whereNull('deleted_at')
            ->with('categoria')
            ->orderBy('estoque_atual')
            ->get();

        // Gráfico — agendamentos por mês (últimos 6 meses)
        $agendamentosPorMes = Agendamento::selectRaw("TO_CHAR(data_hora_inicio, 'MM/YYYY') as mes, COUNT(*) as total")
            ->whereDate('data_hora_inicio', '>=', now()->subMonths(6)->toDateString())
            ->whereNotIn('status', ['cancelado'])
            ->groupByRaw("TO_CHAR(data_hora_inicio, 'MM/YYYY')")
            ->orderByRaw("MIN(data_hora_inicio)")
            ->get();

        // Gráfico — receita por mês (últimos 6 meses)
        $receitaPorMes = Parcela::selectRaw("TO_CHAR(data_pagamento, 'MM/YYYY') as mes, SUM(valor) as total")
            ->where('status', 'pago')
            ->whereDate('data_pagamento', '>=', now()->subMonths(6)->toDateString())
            ->groupByRaw("TO_CHAR(data_pagamento, 'MM/YYYY')")
            ->orderByRaw("MIN(data_pagamento)")
            ->get();

        // Agendamentos por status hoje
        $statusHoje = Agendamento::selectRaw('status, COUNT(*) as total')
            ->whereDate('data_hora_inicio', $hoje)
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard', compact(
            'totalPacientes',
            'pacientesNoMes',
            'agendamentosHoje',
            'agendamentosSemana',
            'proximosAgendamentos',
            'receitaMes',
            'parcelasPendentes',
            'totalPendente',
            'parcelasVencendoHoje',
            'produtosEstoqueBaixo',
            'agendamentosPorMes',
            'receitaPorMes',
            'statusHoje',
        ));
    }
}