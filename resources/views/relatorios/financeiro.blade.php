@extends('layouts.app')

@section('title', 'Relatório Financeiro')

@section('breadcrumb')
    <li class="breadcrumb-item active">Relatórios</li>
    <li class="breadcrumb-item active">Financeiro</li>
@endsection

@section('actions')
    <a href="{{ route('relatorios.exportar', 'financeiro') }}?data_inicio={{ $dataInicio }}&data_fim={{ $dataFim }}"
        class="btn btn-success" target="_blank">
        📄 Exportar PDF
    </a>
@endsection

@section('content')

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('relatorios.financeiro') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Data Início</label>
                        <input type="date" name="data_inicio" class="form-control"
                            value="{{ $dataInicio }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Fim</label>
                        <input type="date" name="data_fim" class="form-control"
                            value="{{ $dataFim }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Cards de resumo --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="subheader text-white-50">Receita no Período</div>
                    <div class="h1 text-white">R$ {{ number_format($receitaPeriodo, 2, ',', '.') }}</div>
                    <div class="text-white-50">{{ $dataInicio }} até {{ $dataFim }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="subheader text-white-50">Parcelas Vencidas</div>
                    <div class="h1 text-white">{{ $parcelasVencidas->count() }}</div>
                    <div class="text-white-50">R$ {{ number_format($parcelasVencidas->sum('valor'), 2, ',', '.') }} em aberto</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="subheader text-white-50">A Vencer (30 dias)</div>
                    <div class="h1 text-white">{{ $parcelasAVencer->count() }}</div>
                    <div class="text-white-50">R$ {{ number_format($parcelasAVencer->sum('valor'), 2, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">

        {{-- Receita por forma de pagamento --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Receita por Forma de Pagamento</h3>
                </div>
                <div class="card-body">
                    <canvas id="grafico-formas" height="200"></canvas>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Forma</th>
                            <th>Qtd</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receitaPorForma as $forma)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $forma->forma_pagamento)) }}</td>
                                <td>{{ $forma->quantidade }}</td>
                                <td>R$ {{ number_format($forma->total, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-3">Nenhum dado encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Orçamentos por status --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Orçamentos por Status</h3>
                </div>
                <div class="card-body">
                    <canvas id="grafico-orcamentos" height="200"></canvas>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orcamentosPorStatus as $orc)
                            <tr>
                                <td>{{ ucfirst($orc->status) }}</td>
                                <td>{{ $orc->quantidade }}</td>
                                <td>R$ {{ number_format($orc->total, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-3">Nenhum dado encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Receita por mês --}}
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">Receita por Mês (últimos 12 meses)</h3>
        </div>
        <div class="card-body">
            <canvas id="grafico-receita-mes" height="80"></canvas>
        </div>
    </div>

    {{-- Parcelas vencidas --}}
    @if($parcelasVencidas->count() > 0)
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title text-danger">Parcelas Vencidas</h3>
            </div>
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($parcelasVencidas as $parcela)
                        <tr>
                            <td>{{ $parcela->pagamento->paciente->nome }}</td>
                            <td class="text-danger">{{ $parcela->data_vencimento->format('d/m/Y') }}</td>
                            <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('pagamentos.show', $parcela->pagamento_id) }}"
                                    class="btn btn-sm btn-secondary">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Parcelas a vencer --}}
    @if($parcelasAVencer->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-warning">Parcelas a Vencer (próximos 30 dias)</h3>
            </div>
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($parcelasAVencer as $parcela)
                        <tr>
                            <td>{{ $parcela->pagamento->paciente->nome }}</td>
                            <td>{{ $parcela->data_vencimento->format('d/m/Y') }}</td>
                            <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('pagamentos.show', $parcela->pagamento_id) }}"
                                    class="btn btn-sm btn-secondary">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Receita por forma
    new Chart(document.getElementById('grafico-formas'), {
        type: 'doughnut',
        data: {
            labels: @json($receitaPorForma->pluck('forma_pagamento')->map(fn($f) => ucfirst(str_replace('_', ' ', $f)))),
            datasets: [{
                data: @json($receitaPorForma->pluck('total')),
                backgroundColor: ['#4299e1','#48bb78','#ed8936','#667eea','#f56565','#38b2ac'],
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Orçamentos por status
    new Chart(document.getElementById('grafico-orcamentos'), {
        type: 'doughnut',
        data: {
            labels: @json($orcamentosPorStatus->pluck('status')->map(fn($s) => ucfirst($s))),
            datasets: [{
                data: @json($orcamentosPorStatus->pluck('quantidade')),
                backgroundColor: ['#a0aec0','#48bb78','#f56565','#ed8936'],
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Receita por mês
    new Chart(document.getElementById('grafico-receita-mes'), {
        type: 'bar',
        data: {
            labels: @json($receitaPorMes->pluck('mes')),
            datasets: [{
                label: 'Receita (R$)',
                data: @json($receitaPorMes->pluck('total')),
                backgroundColor: '#48bb78',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => 'R$ ' + v.toLocaleString('pt-BR') }
                }
            }
        }
    });

});
</script>
@endpush