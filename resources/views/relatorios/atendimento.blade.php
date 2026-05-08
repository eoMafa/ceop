@extends('layouts.app')

@section('title', 'Relatório de Atendimento')

@section('breadcrumb')
    <li class="breadcrumb-item active">Relatórios</li>
    <li class="breadcrumb-item active">Atendimento</li>
@endsection

@section('actions')
    <a href="{{ route('relatorios.exportar', 'atendimento') }}?data_inicio={{ $dataInicio }}&data_fim={{ $dataFim }}"
        class="btn btn-success" target="_blank">
        📄 Exportar PDF
    </a>
@endsection

@section('content')

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('relatorios.atendimento') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Data Início</label>
                        <input type="date" name="data_inicio" class="form-control" value="{{ $dataInicio }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Fim</label>
                        <input type="date" name="data_fim" class="form-control" value="{{ $dataFim }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dentista</label>
                        <select name="dentista_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($dentistas as $dentista)
                                <option value="{{ $dentista->id }}"
                                    {{ $dentistaId == $dentista->id ? 'selected' : '' }}>
                                    {{ $dentista->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Cards resumo --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Total de Agendamentos</div>
                    <div class="h1">{{ $agendamentos->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Concluídos</div>
                    <div class="h1 text-success">{{ $porStatus->get('concluido', 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Faltas</div>
                    <div class="h1 text-warning">{{ $faltas }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Cancelamentos</div>
                    <div class="h1 text-danger">{{ $cancelamentos }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">

        {{-- Por dentista --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Por Dentista</h3>
                </div>
                <div class="card-body">
                    <canvas id="grafico-dentistas" height="200"></canvas>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr><th>Dentista</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @forelse($porDentista as $dentista => $total)
                            <tr>
                                <td>{{ $dentista }}</td>
                                <td>{{ $total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-secondary py-3">Nenhum dado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Por procedimento --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Por Procedimento</h3>
                </div>
                <div class="card-body">
                    <canvas id="grafico-procedimentos" height="200"></canvas>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr><th>Procedimento</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @forelse($porProcedimento as $proc => $total)
                            <tr>
                                <td>{{ $proc }}</td>
                                <td>{{ $total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-secondary py-3">Nenhum dado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Por status --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Por Status</h3>
                </div>
                <div class="card-body">
                    <canvas id="grafico-status" height="200"></canvas>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr><th>Status</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @forelse($porStatus as $status => $total)
                            <tr>
                                <td>{{ ucfirst($status) }}</td>
                                <td>{{ $total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-secondary py-3">Nenhum dado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Gráfico por mês --}}
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">Agendamentos por Mês (últimos 6 meses)</h3>
        </div>
        <div class="card-body">
            <canvas id="grafico-mes" height="80"></canvas>
        </div>
    </div>

    {{-- Listagem --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listagem de Agendamentos</h3>
            <span class="ms-auto text-secondary">{{ $agendamentos->count() }} registros</span>
        </div>
        <table class="table table-vcenter">
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Paciente</th>
                    <th>Dentista</th>
                    <th>Procedimento</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agendamentos as $agendamento)
                    @php
                        $cores = ['agendado' => 'bg-blue', 'confirmado' => 'bg-success', 'cancelado' => 'bg-danger', 'concluido' => 'bg-purple', 'falta' => 'bg-warning'];
                    @endphp
                    <tr>
                        <td>{{ $agendamento->data_hora_inicio->format('d/m/Y H:i') }}</td>
                        <td>{{ $agendamento->paciente->nome }}</td>
                        <td>{{ $agendamento->dentista->name }}</td>
                        <td>{{ $agendamento->procedimento->nome }}</td>
                        <td><span class="badge {{ $cores[$agendamento->status] }} text-white">{{ ucfirst($agendamento->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-secondary py-4">Nenhum agendamento no período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cores = ['#4299e1','#48bb78','#ed8936','#667eea','#f56565','#38b2ac','#e53e3e','#d69e2e'];

    new Chart(document.getElementById('grafico-dentistas'), {
        type: 'doughnut',
        data: {
            labels: @json($porDentista->keys()),
            datasets: [{ data: @json($porDentista->values()), backgroundColor: cores }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('grafico-procedimentos'), {
        type: 'doughnut',
        data: {
            labels: @json($porProcedimento->keys()),
            datasets: [{ data: @json($porProcedimento->values()), backgroundColor: cores }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('grafico-status'), {
        type: 'doughnut',
        data: {
            labels: @json($porStatus->keys()->map(fn($s) => ucfirst($s))),
            datasets: [{ data: @json($porStatus->values()), backgroundColor: cores }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('grafico-mes'), {
        type: 'bar',
        data: {
            labels: @json($porMes->pluck('mes')),
            datasets: [{
                label: 'Agendamentos',
                data: @json($porMes->pluck('total')),
                backgroundColor: '#4299e1',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@endpush