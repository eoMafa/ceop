<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\MovimentacaoEstoque;
use App\Models\Orcamento;
use App\Models\Paciente;
use App\Models\Parcela;
use App\Models\Produto;
use App\Models\User;
use App\Models\Procedimento;
use App\Models\EvolucaoMaterial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    // ==================== FINANCEIRO ====================

    public function financeiro(Request $request)
    {
        $dataInicio = $request->input('data_inicio', now()->startOfMonth()->format('Y-m-d'));
        $dataFim    = $request->input('data_fim', now()->endOfMonth()->format('Y-m-d'));

        // Receita por período
        $receitaPeriodo = Parcela::where('status', 'pago')
            ->whereBetween('data_pagamento', [$dataInicio, $dataFim])
            ->sum('valor');

        // Parcelas vencidas
        $parcelasVencidas = Parcela::where('status', 'pendente')
            ->where('data_vencimento', '<', now()->toDateString())
            ->with('pagamento.paciente')
            ->orderBy('data_vencimento')
            ->get();

        // Parcelas a vencer (próximos 30 dias)
        $parcelasAVencer = Parcela::where('status', 'pendente')
            ->whereBetween('data_vencimento', [now()->toDateString(), now()->addDays(30)->toDateString()])
            ->with('pagamento.paciente')
            ->orderBy('data_vencimento')
            ->get();

        // Receita por forma de pagamento
        $receitaPorForma = Parcela::where('parcelas.status', 'pago')
            ->whereBetween('parcelas.data_pagamento', [$dataInicio, $dataFim])
            ->join('pagamentos', 'parcelas.pagamento_id', '=', 'pagamentos.id')
            ->selectRaw('pagamentos.forma_pagamento, SUM(parcelas.valor) as total, COUNT(*) as quantidade')
            ->groupBy('pagamentos.forma_pagamento')
            ->orderByDesc('total')
            ->get();

        // Orçamentos por status
        $orcamentosPorStatus = Orcamento::whereBetween('created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
            ->selectRaw('status, COUNT(*) as quantidade, SUM(total_liquido) as total')
            ->groupBy('status')
            ->get();

        // Receita por mês (últimos 12 meses)
        $receitaPorMes = Parcela::where('status', 'pago')
            ->whereDate('data_pagamento', '>=', now()->subMonths(12)->toDateString())
            ->selectRaw("TO_CHAR(data_pagamento, 'MM/YYYY') as mes, SUM(valor) as total")
            ->groupByRaw("TO_CHAR(data_pagamento, 'MM/YYYY')")
            ->orderByRaw("MIN(data_pagamento)")
            ->get();

        return view('relatorios.financeiro', compact(
            'dataInicio', 'dataFim',
            'receitaPeriodo',
            'parcelasVencidas',
            'parcelasAVencer',
            'receitaPorForma',
            'orcamentosPorStatus',
            'receitaPorMes',
        ));
    }

    // ==================== ATENDIMENTO ====================

    public function atendimento(Request $request)
    {
        $dataInicio = $request->input('data_inicio', now()->startOfMonth()->format('Y-m-d'));
        $dataFim    = $request->input('data_fim', now()->endOfMonth()->format('Y-m-d'));
        $dentistaId = $request->input('dentista_id');

        $query = Agendamento::with(['paciente', 'dentista', 'procedimento'])
            ->whereBetween('data_hora_inicio', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59']);

        if ($dentistaId) {
            $query->where('dentista_id', $dentistaId);
        }

        $agendamentos = $query->orderBy('data_hora_inicio')->get();

        // Por status
        $porStatus = $agendamentos->groupBy('status')->map->count();

        // Por dentista
        $porDentista = $agendamentos->groupBy('dentista.name')->map->count();

        // Por procedimento
        $porProcedimento = $agendamentos->groupBy('procedimento.nome')->map->count();

        // Faltas e cancelamentos
        $faltas = $agendamentos->where('status', 'falta')->count();
        $cancelamentos = $agendamentos->where('status', 'cancelado')->count();

        // Por mês (últimos 6 meses)
        $porMes = Agendamento::whereDate('data_hora_inicio', '>=', now()->subMonths(6)->toDateString())
            ->whereNotIn('status', ['cancelado'])
            ->selectRaw("TO_CHAR(data_hora_inicio, 'MM/YYYY') as mes, COUNT(*) as total")
            ->groupByRaw("TO_CHAR(data_hora_inicio, 'MM/YYYY')")
            ->orderByRaw("MIN(data_hora_inicio)")
            ->get();

        $dentistas = User::where('role', 'dentista')->where('ativo', true)->orderBy('name')->get();

        return view('relatorios.atendimento', compact(
            'dataInicio', 'dataFim', 'dentistaId',
            'agendamentos',
            'porStatus',
            'porDentista',
            'porProcedimento',
            'faltas',
            'cancelamentos',
            'porMes',
            'dentistas',
        ));
    }

    // ==================== PACIENTES ====================

    public function pacientes(Request $request)
    {
        $dataInicio = $request->input('data_inicio', now()->startOfMonth()->format('Y-m-d'));
        $dataFim    = $request->input('data_fim', now()->endOfMonth()->format('Y-m-d'));
        $mes        = $request->input('mes', now()->month);

        // Cadastrados no período
        $cadastradosPeriodo = Paciente::whereBetween('created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
            ->orderBy('nome')
            ->get();

        // Por convênio
        $porConvenio = Paciente::selectRaw('convenio_id, COUNT(*) as total')
            ->with('convenio')
            ->groupBy('convenio_id')
            ->orderByDesc('total')
            ->get();

        // Aniversariantes do mês
        $aniversariantes = Paciente::whereMonth('data_nascimento', $mes)
            ->whereNotNull('data_nascimento')
            ->orderByRaw("EXTRACT(DAY FROM data_nascimento)")
            ->get();

        // Total geral
        $totalPacientes = Paciente::count();

        // Novos por mês (últimos 6 meses)
        $novosPorMes = Paciente::whereDate('created_at', '>=', now()->subMonths(6)->toDateString())
            ->selectRaw("TO_CHAR(created_at, 'MM/YYYY') as mes, COUNT(*) as total")
            ->groupByRaw("TO_CHAR(created_at, 'MM/YYYY')")
            ->orderByRaw("MIN(created_at)")
            ->get();

        return view('relatorios.pacientes', compact(
            'dataInicio', 'dataFim', 'mes',
            'cadastradosPeriodo',
            'porConvenio',
            'aniversariantes',
            'totalPacientes',
            'novosPorMes',
        ));
    }

    // ==================== ESTOQUE ====================

    public function estoque(Request $request)
    {
        $dataInicio = $request->input('data_inicio', now()->startOfMonth()->format('Y-m-d'));
        $dataFim    = $request->input('data_fim', now()->endOfMonth()->format('Y-m-d'));

        // Produtos com estoque baixo
        $estoqueBaixo = Produto::whereColumn('estoque_atual', '<=', 'estoque_minimo')
            ->whereNull('deleted_at')
            ->with(['categoria', 'fornecedor'])
            ->orderBy('estoque_atual')
            ->get();

        // Movimentações no período
        $movimentacoes = MovimentacaoEstoque::with(['produto', 'user'])
            ->whereBetween('created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
            ->orderByDesc('created_at')
            ->get();

        // Custo de materiais em consultas
        $custoMateriais = EvolucaoMaterial::with(['produto', 'evolucao.prontuario.paciente'])
            ->whereBetween('created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
            ->get();

        $custoPorProduto = $custoMateriais->groupBy('produto.nome')->map(function ($items) {
            return [
                'quantidade' => $items->sum('quantidade'),
                'custo'      => $items->sum(fn($i) => $i->quantidade * $i->valor_unitario),
            ];
        })->sortByDesc('custo');

        $custoTotal = $custoMateriais->sum(fn($i) => $i->quantidade * $i->valor_unitario);

        // Entradas e saídas no período
        $entradas = $movimentacoes->where('tipo', 'entrada')->sum('quantidade');
        $saidas   = $movimentacoes->where('tipo', 'saida')->sum('quantidade');

        return view('relatorios.estoque', compact(
            'dataInicio', 'dataFim',
            'estoqueBaixo',
            'movimentacoes',
            'custoPorProduto',
            'custoTotal',
            'entradas',
            'saidas',
        ));
    }

    // ==================== EXPORTAR PDF ====================

    public function exportarPdf(Request $request, string $tipo)
    {
        $request->merge(['data_inicio' => $request->input('data_inicio', now()->startOfMonth()->format('Y-m-d'))]);
        $request->merge(['data_fim'    => $request->input('data_fim', now()->endOfMonth()->format('Y-m-d'))]);

        $view = match($tipo) {
            'financeiro'  => 'relatorios.pdf.financeiro',
            'atendimento' => 'relatorios.pdf.atendimento',
            'pacientes'   => 'relatorios.pdf.pacientes',
            'estoque'     => 'relatorios.pdf.estoque',
            default       => abort(404),
        };

        $dados = match($tipo) {
            'financeiro'  => $this->dadosFinanceiro($request),
            'atendimento' => $this->dadosAtendimento($request),
            'pacientes'   => $this->dadosPacientes($request),
            'estoque'     => $this->dadosEstoque($request),
        };

        $pdf = Pdf::loadView($view, $dados)->setPaper('a4', 'portrait');

        return $pdf->stream("relatorio-{$tipo}-{$request->data_inicio}-{$request->data_fim}.pdf");
    }

    private function dadosFinanceiro(Request $request): array
    {
        $dataInicio = $request->data_inicio;
        $dataFim    = $request->data_fim;

        return [
            'dataInicio'       => $dataInicio,
            'dataFim'          => $dataFim,
            'receitaPeriodo'   => Parcela::where('status', 'pago')->whereBetween('data_pagamento', [$dataInicio, $dataFim])->sum('valor'),
            'receitaPorForma'  => Parcela::where('parcelas.status', 'pago')
                ->whereBetween('parcelas.data_pagamento', [$dataInicio, $dataFim])
                ->join('pagamentos', 'parcelas.pagamento_id', '=', 'pagamentos.id')
                ->selectRaw('pagamentos.forma_pagamento, SUM(parcelas.valor) as total, COUNT(*) as quantidade')
                ->groupBy('pagamentos.forma_pagamento')
                ->orderByDesc('total')
                ->get(),
            'orcamentosPorStatus' => Orcamento::whereBetween('created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])->selectRaw('status, COUNT(*) as quantidade, SUM(total_liquido) as total')->groupBy('status')->get(),
            'parcelasVencidas' => Parcela::where('status', 'pendente')->where('data_vencimento', '<', now()->toDateString())->with('pagamento.paciente')->orderBy('data_vencimento')->get(),
        ];
    }

    private function dadosAtendimento(Request $request): array
    {
        $dataInicio   = $request->data_inicio;
        $dataFim      = $request->data_fim;
        $agendamentos = Agendamento::with(['paciente', 'dentista', 'procedimento'])->whereBetween('data_hora_inicio', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])->orderBy('data_hora_inicio')->get();

        return [
            'dataInicio'      => $dataInicio,
            'dataFim'         => $dataFim,
            'agendamentos'    => $agendamentos,
            'porStatus'       => $agendamentos->groupBy('status')->map->count(),
            'porDentista'     => $agendamentos->groupBy('dentista.name')->map->count(),
            'porProcedimento' => $agendamentos->groupBy('procedimento.nome')->map->count(),
            'faltas'          => $agendamentos->where('status', 'falta')->count(),
            'cancelamentos'   => $agendamentos->where('status', 'cancelado')->count(),
        ];
    }

    private function dadosPacientes(Request $request): array
    {
        $dataInicio = $request->data_inicio;
        $dataFim    = $request->data_fim;
        $mes        = $request->input('mes', now()->month);

        return [
            'dataInicio'          => $dataInicio,
            'dataFim'             => $dataFim,
            'cadastradosPeriodo'  => Paciente::whereBetween('created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])->orderBy('nome')->get(),
            'aniversariantes'     => Paciente::whereMonth('data_nascimento', $mes)->whereNotNull('data_nascimento')->orderByRaw("EXTRACT(DAY FROM data_nascimento)")->get(),
            'porConvenio'         => Paciente::selectRaw('convenio_id, COUNT(*) as total')->with('convenio')->groupBy('convenio_id')->orderByDesc('total')->get(),
            'totalPacientes'      => Paciente::count(),
            'mes'                 => $mes,
        ];
    }

    private function dadosEstoque(Request $request): array
    {
        $dataInicio    = $request->data_inicio;
        $dataFim       = $request->data_fim;
        $movimentacoes = MovimentacaoEstoque::with(['produto', 'user'])->whereBetween('created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])->orderByDesc('created_at')->get();
        $custoMateriais = EvolucaoMaterial::with(['produto'])->whereBetween('created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])->get();

        return [
            'dataInicio'      => $dataInicio,
            'dataFim'         => $dataFim,
            'estoqueBaixo'    => Produto::whereColumn('estoque_atual', '<=', 'estoque_minimo')->whereNull('deleted_at')->with(['categoria', 'fornecedor'])->get(),
            'movimentacoes'   => $movimentacoes,
            'custoPorProduto' => $custoMateriais->groupBy('produto.nome')->map(fn($items) => ['quantidade' => $items->sum('quantidade'), 'custo' => $items->sum(fn($i) => $i->quantidade * $i->valor_unitario)])->sortByDesc('custo'),
            'custoTotal'      => $custoMateriais->sum(fn($i) => $i->quantidade * $i->valor_unitario),
        ];
    }
}