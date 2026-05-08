@extends('layouts.app')

@section('title', 'Relatório de Estoque')

@section('breadcrumb')
    <li class="breadcrumb-item active">Relatórios</li>
    <li class="breadcrumb-item active">Estoque</li>
@endsection

@section('actions')
    <a href="{{ route('relatorios.exportar', 'estoque') }}?data_inicio={{ $dataInicio }}&data_fim={{ $dataFim }}"
        class="btn btn-success" target="_blank">
        📄 Exportar PDF
    </a>
@endsection

@section('content')

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('relatorios.estoque') }}">
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

    {{-- Cards resumo --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="subheader text-white-50">Estoque Baixo</div>
                    <div class="h1 text-white">{{ $estoqueBaixo->count() }}</div>
                    <div class="text-white-50">produtos abaixo do mínimo</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="subheader text-white-50">Entradas no Período</div>
                    <div class="h1 text-white">{{ number_format($entradas, 2, ',', '.') }}</div>
                    <div class="text-white-50">unidades</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="subheader text-white-50">Saídas no Período</div>
                    <div class="h1 text-white">{{ number_format($saidas, 2, ',', '.') }}</div>
                    <div class="text-white-50">unidades</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-blue text-white">
                <div class="card-body">
                    <div class="subheader text-white-50">Custo em Consultas</div>
                    <div class="h1 text-white">R$ {{ number_format($custoTotal, 2, ',', '.') }}</div>
                    <div class="text-white-50">materiais usados</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estoque baixo --}}
    @if($estoqueBaixo->count() > 0)
        <div class="card mb-3 border-danger">
            <div class="card-header">
                <h3 class="card-title text-danger">⚠️ Produtos com Estoque Baixo</h3>
            </div>
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Fornecedor</th>
                        <th>Estoque Atual</th>
                        <th>Estoque Mín.</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estoqueBaixo as $produto)
                        <tr>
                            <td>{{ $produto->nome }}</td>
                            <td class="text-secondary">{{ $produto->categoria->nome ?? '—' }}</td>
                            <td class="text-secondary">{{ $produto->fornecedor->nome ?? '—' }}</td>
                            <td class="text-danger fw-bold">
                                {{ number_format($produto->estoque_atual, 2, ',', '.') }} {{ $produto->unidade }}
                            </td>
                            <td class="text-secondary">
                                {{ number_format($produto->estoque_minimo, 2, ',', '.') }} {{ $produto->unidade }}
                            </td>
                            <td>
                                <a href="{{ route('estoque.produtos.show', $produto->id) }}"
                                    class="btn btn-sm btn-secondary">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Custo por produto em consultas --}}
    @if($custoPorProduto->count() > 0)
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Custo de Materiais em Consultas</h3>
                <span class="ms-auto text-secondary">
                    Total: R$ {{ number_format($custoTotal, 2, ',', '.') }}
                </span>
            </div>
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade Usada</th>
                        <th>Custo Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($custoPorProduto as $nome => $dados)
                        <tr>
                            <td>{{ $nome }}</td>
                            <td>{{ number_format($dados['quantidade'], 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($dados['custo'], 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Movimentações --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Movimentações no Período</h3>
            <span class="ms-auto text-secondary">{{ $movimentacoes->count() }} registros</span>
        </div>
        <table class="table table-vcenter">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Produto</th>
                    <th>Tipo</th>
                    <th>Quantidade</th>
                    <th>Motivo</th>
                    <th>Usuário</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimentacoes as $mov)
                    <tr>
                        <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $mov->produto->nome }}</td>
                        <td>
                            @if($mov->tipo === 'entrada')
                                <span class="badge bg-success text-white">Entrada</span>
                            @elseif($mov->tipo === 'saida')
                                <span class="badge bg-danger text-white">Saída</span>
                            @else
                                <span class="badge bg-secondary text-white">Ajuste</span>
                            @endif
                        </td>
                        <td>{{ number_format($mov->quantidade, 2, ',', '.') }}</td>
                        <td class="text-secondary">{{ $mov->motivo ?? '—' }}</td>
                        <td class="text-secondary">{{ $mov->user->name }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">Nenhuma movimentação no período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection