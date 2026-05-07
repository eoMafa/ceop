@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Cards de resumo --}}
    <div class="row g-3 mb-3">

        {{-- Pacientes --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Total de Pacientes</div>
                    </div>
                    <div class="h1 mb-1">{{ $totalPacientes }}</div>
                    <div class="text-secondary">
                        <span class="text-success">+{{ $pacientesNoMes }}</span> este mês
                    </div>
                </div>
            </div>
        </div>

        {{-- Agendamentos hoje --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Agendamentos Hoje</div>
                    <div class="h1 mb-1">{{ $agendamentosHoje->count() }}</div>
                    <div class="text-secondary">
                        {{ $agendamentosSemana }} na semana
                    </div>
                </div>
            </div>
        </div>

        {{-- Receita do mês --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Receita do Mês</div>
                    <div class="h1 mb-1">R$ {{ number_format($receitaMes, 2, ',', '.') }}</div>
                    <div class="text-secondary">
                        @if($parcelasVencendoHoje > 0)
                            <span class="text-warning">{{ $parcelasVencendoHoje }} vencendo hoje</span>
                        @else
                            Nenhum vencimento hoje
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Parcelas vencidas --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card {{ $parcelasPendentes > 0 ? 'border-danger' : '' }}">
                <div class="card-body">
                    <div class="subheader">Parcelas Vencidas</div>
                    <div class="h1 mb-1 {{ $parcelasPendentes > 0 ? 'text-danger' : '' }}">
                        {{ $parcelasPendentes }}
                    </div>
                    <div class="text-secondary">
                        R$ {{ number_format($totalPendente, 2, ',', '.') }} em aberto
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3 mb-3">

        {{-- Gráfico agendamentos --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Agendamentos por Mês</h3>
                </div>
                <div class="card-body">
                    <canvas id="grafico-agendamentos" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Gráfico receita --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Receita por Mês</h3>
                </div>
                <div class="card-body">
                    <canvas id="grafico-receita" height="120"></canvas>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3">

        {{-- Agendamentos hoje --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Agenda de Hoje</h3>
                    <span class="ms-auto">
                        <a href="{{ route('agendamentos.index') }}" class="btn btn-sm btn-secondary">Ver calendário</a>
                    </span>
                </div>
                <div class="card-body p-0">
                    @forelse($agendamentosHoje as $agendamento)
                        @php
                            $cores = [
                                'agendado'   => 'bg-blue',
                                'confirmado' => 'bg-success',
                                'cancelado'  => 'bg-danger',
                                'concluido'  => 'bg-purple',
                                'falta'      => 'bg-warning',
                            ];
                        @endphp
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="me-3 text-center" style="min-width:50px">
                                <div class="fw-bold">{{ $agendamento->data_hora_inicio->format('H:i') }}</div>
                                <small class="text-secondary">{{ $agendamento->data_hora_fim->format('H:i') }}</small>
                            </div>
                            <div class="flex-fill">
                                <div class="fw-bold">{{ $agendamento->paciente->nome }}</div>
                                <small class="text-secondary">
                                    {{ $agendamento->procedimento->nome }} — {{ $agendamento->dentista->name }}
                                </small>
                            </div>
                            <span class="badge {{ $cores[$agendamento->status] }} text-white ms-2">
                                {{ ucfirst($agendamento->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center text-secondary py-4">
                            Nenhum agendamento para hoje.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">

            {{-- Próximos agendamentos --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Próximos Agendamentos</h3>
                </div>
                <div class="card-body p-0">
                    @forelse($proximosAgendamentos as $agendamento)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="me-3 text-center" style="min-width:60px">
                                <div class="fw-bold">{{ $agendamento->data_hora_inicio->format('d/m') }}</div>
                                <small class="text-secondary">{{ $agendamento->data_hora_inicio->format('H:i') }}</small>
                            </div>
                            <div class="flex-fill">
                                <div class="fw-bold">{{ $agendamento->paciente->nome }}</div>
                                <small class="text-secondary">
                                    {{ $agendamento->procedimento->nome }} — {{ $agendamento->dentista->name }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-secondary py-4">
                            Nenhum agendamento futuro.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Estoque baixo --}}
            @if($produtosEstoqueBaixo->count() > 0)
                <div class="card border-warning">
                    <div class="card-header">
                        <h3 class="card-title text-warning">⚠️ Estoque Baixo</h3>
                        <span class="ms-auto">
                            <a href="{{ route('estoque.produtos.index') }}" class="btn btn-sm btn-secondary">Ver todos</a>
                        </span>
                    </div>
                    <div class="card-body p-0">
                        @foreach($produtosEstoqueBaixo->take(5) as $produto)
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="flex-fill">
                                    <div class="fw-bold">{{ $produto->nome }}</div>
                                    <small class="text-secondary">{{ $produto->categoria->nome ?? 'Sem categoria' }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="text-danger fw-bold">
                                        {{ number_format($produto->estoque_atual, 2, ',', '.') }} {{ $produto->unidade }}
                                    </div>
                                    <small class="text-secondary">mín: {{ number_format($produto->estoque_minimo, 2, ',', '.') }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Dados dos gráficos vindos do PHP
    const agendamentosMeses = @json($agendamentosPorMes->pluck('mes'));
    const agendamentosTotais = @json($agendamentosPorMes->pluck('total'));

    const receitaMeses = @json($receitaPorMes->pluck('mes'));
    const receitaTotais = @json($receitaPorMes->pluck('total'));

    // Gráfico de agendamentos
    new Chart(document.getElementById('grafico-agendamentos'), {
        type: 'bar',
        data: {
            labels: agendamentosMeses,
            datasets: [{
                label: 'Agendamentos',
                data: agendamentosTotais,
                backgroundColor: '#4299e1',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // Gráfico de receita
    new Chart(document.getElementById('grafico-receita'), {
        type: 'line',
        data: {
            labels: receitaMeses,
            datasets: [{
                label: 'Receita (R$)',
                data: receitaTotais,
                borderColor: '#48bb78',
                backgroundColor: 'rgba(72,187,120,0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#48bb78',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'R$ ' + value.toLocaleString('pt-BR')
                    }
                }
            }
        }
    });

});
</script>
@endpush