@extends('layouts.app')

@section('title', 'Relatório de Lucratividade')

@section('breadcrumb')
    <li class="breadcrumb-item active">Relatórios</li>
    <li class="breadcrumb-item active">Lucratividade</li>
@endsection

@section('content')

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('relatorios.lucratividade') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Data Início</label>
                        <input type="date" name="data_inicio" class="form-control" value="{{ $dataInicio }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Fim</label>
                        <input type="date" name="data_fim" class="form-control" value="{{ $dataFim }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Cards principais --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="subheader text-white-50">Receita Total</div>
                    <div class="h1 text-white">R$ {{ number_format($receita, 2, ',', '.') }}</div>
                    <div class="text-white-50">pagamentos recebidos</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="subheader text-white-50">Custo de Materiais</div>
                    <div class="h1 text-white">R$ {{ number_format($custoMateriais, 2, ',', '.') }}</div>
                    <div class="text-white-50">materiais usados</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-blue text-white">
                <div class="card-body">
                    <div class="subheader text-white-50">Margem Bruta</div>
                    <div class="h1 text-white">R$ {{ number_format($margem, 2, ',', '.') }}</div>
                    <div class="text-white-50">receita - custo materiais</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card {{ $percentual >= 70 ? 'bg-success' : ($percentual >= 50 ? 'bg-warning' : 'bg-danger') }} text-white">
                <div class="card-body">
                    <div class="subheader text-white-50">Percentual de Margem</div>
                    <div class="h1 text-white">{{ number_format($percentual, 1, ',', '.') }}%</div>
                    <div class="text-white-50">margem / receita</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráfico evolução mensal --}}
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">Evolução Mensal (últimos 6 meses)</h3>
        </div>
        <div class="card-body">
            <canvas id="grafico-evolucao" height="80"></canvas>
        </div>
    </div>

    <div class="row g-3 mb-3">

        {{-- Por dentista --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lucratividade por Dentista</h3>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Dentista</th>
                            <th>Receita</th>
                            <th>Custo</th>
                            <th>Margem</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($porDentista as $item)
                            <tr>
                                <td>{{ $item['dentista'] }}</td>
                                <td class="text-success">R$ {{ number_format($item['receita'], 2, ',', '.') }}</td>
                                <td class="text-danger">R$ {{ number_format($item['custo'], 2, ',', '.') }}</td>
                                <td class="fw-bold">R$ {{ number_format($item['margem'], 2, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $item['percentual'] >= 70 ? 'bg-success' : ($item['percentual'] >= 50 ? 'bg-warning' : 'bg-danger') }} text-white">
                                        {{ number_format($item['percentual'], 1) }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-secondary py-3">Nenhum dado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Por procedimento --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lucratividade por Procedimento</h3>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Procedimento</th>
                            <th>Atend.</th>
                            <th>Receita</th>
                            <th>Margem</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($porProcedimento as $item)
                            <tr>
                                <td>{{ $item['procedimento'] }}</td>
                                <td>{{ $item['atendimentos'] }}</td>
                                <td class="text-success">R$ {{ number_format($item['receita'], 2, ',', '.') }}</td>
                                <td class="fw-bold">R$ {{ number_format($item['margem'], 2, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $item['percentual'] >= 70 ? 'bg-success' : ($item['percentual'] >= 50 ? 'bg-warning' : 'bg-danger') }} text-white">
                                        {{ number_format($item['percentual'], 1) }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-secondary py-3">Nenhum dado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Tabela evolução mensal --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detalhamento Mensal</h3>
        </div>
        <table class="table table-vcenter">
            <thead>
                <tr>
                    <th>Mês</th>
                    <th>Receita</th>
                    <th>Custo Materiais</th>
                    <th>Margem</th>
                    <th>% Margem</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evolucaoMensal as $item)
                    @php $pct = $item['receita'] > 0 ? ($item['margem'] / $item['receita']) * 100 : 0; @endphp
                    <tr>
                        <td>{{ $item['mes'] }}</td>
                        <td class="text-success">R$ {{ number_format($item['receita'], 2, ',', '.') }}</td>
                        <td class="text-danger">R$ {{ number_format($item['custo'], 2, ',', '.') }}</td>
                        <td class="fw-bold">R$ {{ number_format($item['margem'], 2, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $pct >= 70 ? 'bg-success' : ($pct >= 50 ? 'bg-warning' : 'bg-danger') }} text-white">
                                {{ number_format($pct, 1) }}%
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('grafico-evolucao'), {
        type: 'bar',
        data: {
            labels: @json($evolucaoMensal->pluck('mes')),
            datasets: [
                {
                    label: 'Receita',
                    data: @json($evolucaoMensal->pluck('receita')),
                    backgroundColor: '#48bb78',
                    borderRadius: 4,
                },
                {
                    label: 'Custo Materiais',
                    data: @json($evolucaoMensal->pluck('custo')),
                    backgroundColor: '#f56565',
                    borderRadius: 4,
                },
                {
                    label: 'Margem',
                    data: @json($evolucaoMensal->pluck('margem')),
                    backgroundColor: '#4299e1',
                    borderRadius: 4,
                },
            ]
        },
        options: {
            responsive: true,
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