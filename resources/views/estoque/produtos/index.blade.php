@extends('layouts.app')

@section('title', 'Produtos')

@section('breadcrumb')
    <li class="breadcrumb-item active">Produtos</li>
@endsection

@section('actions')
    <a href="{{ route('estoque.produtos.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Novo Produto
    </a>
@endsection

@section('content')

    {{-- Alerta de estoque baixo --}}
    @if($totalBaixo > 0)
        <div class="alert alert-warning d-flex align-items-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 9v4" />
                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.871l-8.106 -13.534a1.914 1.914 0 0 0 -3.274 0z" />
                <path d="M12 16h.01" />
            </svg>
            <div>
                <strong>{{ $totalBaixo }} produto(s)</strong> com estoque abaixo do mínimo!
            </div>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('estoque.produtos.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control"
                        placeholder="Buscar por nome ou código..."
                        value="{{ $termo ?? '' }}">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    @if(isset($termo))
                        <a href="{{ route('estoque.produtos.index') }}" class="btn btn-secondary">Limpar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ isset($termo) ? "Resultados para: \"{$termo}\"" : 'Todos os Produtos' }}</h3>
            <span class="ms-auto text-secondary">{{ $produtos->total() }} registros</span>
        </div>
        <table class="table table-vcenter table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Unidade</th>
                    <th>Estoque Atual</th>
                    <th>Estoque Mín.</th>
                    <th>Valor Custo</th>
                    <th>Situação</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($produtos as $produto)
                    <tr class="{{ $produto->trashed() ? 'table-danger' : ($produto->estoque_baixo ? 'table-warning' : '') }}">
                        <td>
                            {{ $produto->nome }}
                            @if($produto->codigo)
                                <small class="text-secondary">({{ $produto->codigo }})</small>
                            @endif
                        </td>
                        <td class="text-secondary">{{ $produto->categoria->nome ?? '—' }}</td>
                        <td class="text-secondary">{{ $produto->unidade }}</td>
                        <td>
                            <span class="{{ $produto->estoque_baixo ? 'text-danger fw-bold' : '' }}">
                                {{ number_format($produto->estoque_atual, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-secondary">{{ number_format($produto->estoque_minimo, 2, ',', '.') }}</td>
                        <td class="text-secondary">R$ {{ number_format($produto->valor_custo, 2, ',', '.') }}</td>
                        <td>
                            @if($produto->trashed())
                                <span class="badge bg-danger text-white">Inativo</span>
                            @elseif($produto->estoque_baixo)
                                <span class="badge bg-warning text-white">Estoque Baixo</span>
                            @else
                                <span class="badge bg-success text-white">OK</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">Ações</button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('estoque.produtos.show', $produto->id) }}" class="dropdown-item">Ver</a>
                                    <a href="{{ route('estoque.produtos.edit', $produto->id) }}" class="dropdown-item">Editar</a>
                                    @if(!$produto->trashed())
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('estoque.produtos.destroy', $produto->id) }}"
                                            method="POST"
                                            data-confirm="Deseja inativar o produto {{ $produto->nome }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">Inativar</button>
                                        </form>
                                    @else
                                        <form action="{{ route('estoque.produtos.restore', $produto->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item text-success">Restaurar</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-secondary py-4">Nenhum produto encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($produtos->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $produtos->links() }}
            </div>
        @endif
    </div>
@endsection