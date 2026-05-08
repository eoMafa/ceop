@extends('layouts.app')

@section('title', 'Relatório de Pacientes')

@section('breadcrumb')
    <li class="breadcrumb-item active">Relatórios</li>
    <li class="breadcrumb-item active">Pacientes</li>
@endsection

@section('actions')
    <a href="{{ route('relatorios.exportar', 'pacientes') }}?data_inicio={{ $dataInicio }}&data_fim={{ $dataFim }}&mes={{ $mes }}"
        class="btn btn-success" target="_blank">
        📄 Exportar PDF
    </a>
@endsection

@section('content')

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('relatorios.pacientes') }}">
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
                        <label class="form-label">Mês Aniversário</label>
                        <select name="mes" class="form-select">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Total de Pacientes</div>
                    <div class="h1">{{ $totalPacientes }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Novos no Período</div>
                    <div class="h1 text-success">{{ $cadastradosPeriodo->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Aniversariantes do Mês</div>
                    <div class="h1 text-primary">{{ $aniversariantes->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">

        {{-- Novos por mês --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Novos Pacientes por Mês</h3>
                </div>
                <div class="card-body">
                    <canvas id="grafico-novos" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Por convênio --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Por Convênio</h3>
                </div>
                <div class="card-body">
                    <canvas id="grafico-convenio" height="200"></canvas>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr><th>Convênio</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($porConvenio as $item)
                            <tr>
                                <td>{{ $item->convenio->nome ?? 'Particular' }}</td>
                                <td>{{ $item->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Aniversariantes --}}
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">🎂 Aniversariantes do Mês</h3>
            <span class="ms-auto text-secondary">{{ $aniversariantes->count() }} pacientes</span>
        </div>
        <table class="table table-vcenter">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Nascimento</th>
                    <th>Idade</th>
                    <th>Telefone</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($aniversariantes as $paciente)
                    <tr>
                        <td>{{ $paciente->nome }}</td>
                        <td>{{ $paciente->data_nascimento->format('d/m/Y') }}</td>
                        <td>{{ $paciente->idade }} anos</td>
                        <td>{{ $paciente->telefone ?? '—' }}</td>
                        <td>
                            <a href="{{ route('pacientes.show', $paciente->id) }}"
                                class="btn btn-sm btn-secondary">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-secondary py-4">Nenhum aniversariante este mês.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Novos no período --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pacientes Cadastrados no Período</h3>
            <span class="ms-auto text-secondary">{{ $cadastradosPeriodo->count() }} pacientes</span>
        </div>
        <table class="table table-vcenter">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>Cadastro</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($cadastradosPeriodo as $paciente)
                    <tr>
                        <td>{{ $paciente->nome }}</td>
                        <td class="text-secondary">{{ $paciente->cpf ?? '—' }}</td>
                        <td class="text-secondary">{{ $paciente->telefone ?? '—' }}</td>
                        <td class="text-secondary">{{ $paciente->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('pacientes.show', $paciente->id) }}"
                                class="btn btn-sm btn-secondary">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-secondary py-4">Nenhum paciente cadastrado no período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('grafico-novos'), {
        type: 'bar',
        data: {
            labels: @json($novosPorMes->pluck('mes')),
            datasets: [{
                label: 'Novos Pacientes',
                data: @json($novosPorMes->pluck('total')),
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

    new Chart(document.getElementById('grafico-convenio'), {
        type: 'doughnut',
        data: {
            labels: @json($porConvenio->map(fn($i) => $i->convenio->nome ?? 'Particular')),
            datasets: [{
                data: @json($porConvenio->pluck('total')),
                backgroundColor: ['#4299e1','#48bb78','#ed8936','#667eea','#f56565','#38b2ac'],
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
});
</script>
@endpush