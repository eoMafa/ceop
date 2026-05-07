@extends('layouts.app')

@section('title', 'Fornecedores')

@section('breadcrumb')
    <li class="breadcrumb-item active">Fornecedores</li>
@endsection

@section('actions')
    <a href="{{ route('estoque.fornecedores.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Novo Fornecedor
    </a>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('estoque.fornecedores.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control"
                        placeholder="Buscar por nome ou CNPJ..."
                        value="{{ $termo ?? '' }}">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    @if(isset($termo))
                        <a href="{{ route('estoque.fornecedores.index') }}" class="btn btn-secondary">Limpar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ isset($termo) ? "Resultados para: \"{$termo}\"" : 'Todos os Fornecedores' }}</h3>
            <span class="ms-auto text-secondary">{{ $fornecedores->total() }} registros</span>
        </div>
        <table class="table table-vcenter table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Telefone</th>
                    <th>Contato</th>
                    <th>Situação</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($fornecedores as $fornecedor)
                    <tr class="{{ $fornecedor->trashed() ? 'table-danger' : '' }}">
                        <td>{{ $fornecedor->nome }}</td>
                        <td class="text-secondary">{{ $fornecedor->cnpj ?? '—' }}</td>
                        <td class="text-secondary">{{ $fornecedor->telefone ?? '—' }}</td>
                        <td class="text-secondary">{{ $fornecedor->contato ?? '—' }}</td>
                        <td>
                            @if($fornecedor->trashed())
                                <span class="badge bg-danger text-white">Inativo</span>
                            @else
                                <span class="badge bg-success text-white">Ativo</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">Ações</button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('estoque.fornecedores.show', $fornecedor->id) }}" class="dropdown-item">Ver</a>
                                    <a href="{{ route('estoque.fornecedores.edit', $fornecedor->id) }}" class="dropdown-item">Editar</a>
                                    <div class="dropdown-divider"></div>
                                    @if(!$fornecedor->trashed())
                                        <form action="{{ route('estoque.fornecedores.destroy', $fornecedor->id) }}"
                                            method="POST"
                                            data-confirm="Deseja inativar o fornecedor {{ $fornecedor->nome }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">Inativar</button>
                                        </form>
                                    @else
                                        <form action="{{ route('estoque.fornecedores.restore', $fornecedor->id) }}" method="POST">
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
                        <td colspan="6" class="text-center text-secondary py-4">Nenhum fornecedor encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($fornecedores->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $fornecedores->links() }}
            </div>
        @endif
    </div>
@endsection