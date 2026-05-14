@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Boas vindas --}}
    <div class="row mb-3">
        <div class="col">
            <div class="card" style="background: linear-gradient(135deg, #1a0a0a 0%, #2c1010 100%); border: 1px solid #3a1a1a;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-1 text-white">
                                Olá, {{ Auth::user()->name }}! 👋
                            </h3>
                            <p class="mb-0" style="color:#888">
                                {{ now()->translatedFormat('l, d \d\e F \d\e Y') }} —
                                Bem-vindo ao sistema CEOP
                            </p>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cards de resumo --}}
    <div class="row g-3 mb-3">

        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3" style="width:48px;height:48px;border-radius:12px;background:rgba(192,57,43,0.15);display:flex;align-items:center;justify-content:center;font-size:1.5rem">
                            👥
                        </div>
                        <div class="text-secondary">Total de Pacientes</div>
                    </div>
                    <div class="h1 mb-1">{{ $totalPacientes }}</div>
                    <div class="text-secondary small">
                        <span class="text-success fw-bold">+{{ $pacientesNoMes }}</span> este mês
                    </div>
                </div>
                <div style="height:4px;background:linear-gradient(90deg,#c0392b,#e74c3c);border-radius:0 0 4px 4px"></div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3" style="width:48px;height:48px;border-radius:12px;background:rgba(66,153,225,0.15);display:flex;align-items:center;justify-content:center;font-size:1.5rem">
                            📅
                        </div>
                        <div class="text-secondary">Agendamentos Hoje</div>
                    </div>
                    <div class="h1 mb-1">{{ $agendamentosHoje->count() }}</div>
                    <div class="text-secondary small">
                        <span class="fw-bold">{{ $agendamentosSemana }}</span> na semana
                    </div>
                </div>
                <div style="height:4px;background:linear-gradient(90deg,#4299e1,#63b3ed);border-radius:0 0 4px 4px"></div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3" style="width:48px;height:48px;border-radius:12px;background:rgba(72,187,120,0.15);display:flex;align-items:center;justify-content:center;font-size:1.5rem">
                            💰
                        </div>
                        <div class="text-secondary">Receita do Mês</div>
                    </div>
                    <div class="h1 mb-1">R$ {{ number_format($receitaMes, 2, ',', '.') }}</div>
                    <div class="text-secondary small">
                        @if($parcelasVencendoHoje > 0)
                            <span class="text-warning fw-bold">{{ $parcelasVencendoHoje }} vencendo hoje</span>
                        @else
                            Nenhum vencimento hoje
                        @endif
                    </div>
                </div>
                <div style="height:4px;background:linear-gradient(90deg,#48bb78,#68d391);border-radius:0 0 4px 4px"></div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card {{ $parcelasPendentes > 0 ? 'border-danger' : '' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3" style="width:48px;height:48px;border-radius:12px;background:rgba(245,101,101,0.15);display:flex;align-items:center;justify-content:center;font-size:1.5rem">
                            ⚠️
                        </div>
                        <div class="text-secondary">Parcelas Vencidas</div>
                    </div>
                    <div class="h1 mb-1 {{ $parcelasPendentes > 0 ? 'text-danger' : '' }}">
                        {{ $parcelasPendentes }}
                    </div>
                    <div class="text-secondary small">
                        R$ {{ number_format($totalPendente, 2, ',', '.') }} em aberto
                    </div>
                </div>
                <div style="height:4px;background:linear-gradient(90deg,#f56565,#fc8181);border-radius:0 0 4px 4px"></div>
            </div>
        </div>

    </div>

    {{-- Gráficos --}}
    <div class="row g-3 mb-3">

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">
                        📊 Agendamentos por Mês
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="grafico-agendamentos" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">
                        💹 Receita por Mês
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="grafico-receita" height="120"></canvas>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3">

        {{-- Agenda de hoje --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">📅 Agenda de Hoje</h3>
                    <span class="ms-auto">
                        <a href="{{ route('agendamentos.index') }}" class="btn btn-sm btn-secondary">
                            Ver calendário
                        </a>
                    </span>
                </div>
                <div class="card-body p-0">
                    @forelse($agendamentosHoje as $agendamento)
                        @php
                            $cores = [
                                'agendado'   => '#4299e1',
                                'confirmado' => '#48bb78',
                                'cancelado'  => '#f56565',
                                'concluido'  => '#667eea',
                                'falta'      => '#ed8936',
                            ];
                            $cor = $cores[$agendamento->status] ?? '#a0aec0';
                        @endphp
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="me-3 text-center" style="min-width:55px">
                                <div class="fw-bold" style="color:{{ $cor }}">
                                    {{ $agendamento->data_hora_inicio->format('H:i') }}
                                </div>
                                <small class="text-secondary">
                                    {{ $agendamento->data_hora_fim->format('H:i') }}
                                </small>
                            </div>
                            <div style="width:3px;height:40px;background:{{ $cor }};border-radius:2px;margin-right:12px"></div>
                            <div class="flex-fill">
                                <div class="fw-bold">{{ $agendamento->paciente->nome }}</div>
                                <small class="text-secondary">
                                    {{ $agendamento->procedimento->nome }} — {{ $agendamento->dentista->name }}
                                </small>
                            </div>
                            <span class="badge text-white ms-2" style="background:{{ $cor }}">
                                {{ ucfirst($agendamento->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center text-secondary py-5">
                            <div style="font-size:2rem">📭</div>
                            <div class="mt-2">Nenhum agendamento para hoje</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">

            {{-- Próximos agendamentos --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">🔜 Próximos Agendamentos</h3>
                </div>
                <div class="card-body p-0">
                    @forelse($proximosAgendamentos as $agendamento)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="me-3 text-center" style="min-width:55px">
                                <div class="fw-bold text-primary">
                                    {{ $agendamento->data_hora_inicio->format('d/m') }}
                                </div>
                                <small class="text-secondary">
                                    {{ $agendamento->data_hora_inicio->format('H:i') }}
                                </small>
                            </div>
                            <div style="width:3px;height:40px;background:#4299e1;border-radius:2px;margin-right:12px"></div>
                            <div class="flex-fill">
                                <div class="fw-bold">{{ $agendamento->paciente->nome }}</div>
                                <small class="text-secondary">
                                    {{ $agendamento->procedimento->nome }} — {{ $agendamento->dentista->name }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-secondary py-4">
                            <div style="font-size:2rem">📭</div>
                            <div class="mt-2">Nenhum agendamento futuro</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Estoque baixo --}}
            @if($produtosEstoqueBaixo->count() > 0)
                <div class="card" style="border-color:#ed8936">
                    <div class="card-header" style="border-bottom-color:#ed8936">
                        <h3 class="card-title" style="color:#ed8936">
                            ⚠️ Estoque Baixo
                        </h3>
                        <span class="ms-auto">
                            <a href="{{ route('estoque.produtos.index') }}"
                                class="btn btn-sm btn-secondary">Ver todos</a>
                        </span>
                    </div>
                    <div class="card-body p-0">
                        @foreach($produtosEstoqueBaixo->take(4) as $produto)
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="me-3" style="font-size:1.3rem">📦</div>
                                <div class="flex-fill">
                                    <div class="fw-bold">{{ $produto->nome }}</div>
                                    <small class="text-secondary">
                                        {{ $produto->categoria->nome ?? 'Sem categoria' }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="text-danger fw-bold">
                                        {{ number_format($produto->estoque_atual, 2, ',', '.') }} {{ $produto->unidade }}
                                    </div>
                                    <small class="text-secondary">
                                        mín: {{ number_format($produto->estoque_minimo, 2, ',', '.') }}
                                    </small>
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

    const ceopRed = '#c0392b';

    // Gráfico de agendamentos
    new Chart(document.getElementById('grafico-agendamentos'), {
        type: 'bar',
        data: {
            labels: @json($agendamentosPorMes->pluck('mes')),
            datasets: [{
                label: 'Agendamentos',
                data: @json($agendamentosPorMes->pluck('total')),
                backgroundColor: 'rgba(192,57,43,0.7)',
                borderColor: ceopRed,
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Gráfico de receita
    new Chart(document.getElementById('grafico-receita'), {
        type: 'line',
        data: {
            labels: @json($receitaPorMes->pluck('mes')),
            datasets: [{
                label: 'Receita (R$)',
                data: @json($receitaPorMes->pluck('total')),
                borderColor: '#48bb78',
                backgroundColor: 'rgba(72,187,120,0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#48bb78',
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => 'R$ ' + v.toLocaleString('pt-BR')
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

});
</script>
@endpush