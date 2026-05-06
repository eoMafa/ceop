@extends('layouts.app')

@section('title', 'Procedimentos')

@section('breadcrumb')
    <li class="breadcrumb-item active">Procedimentos</li>
@endsection

@section('actions')
    <a href="{{ route('procedimentos.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Novo Procedimento
    </a>
@endsection

@section('content')
    {{-- Busca --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('procedimentos.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control"
                        placeholder="Buscar por nome ou descrição..."
                        value="{{ $termo ?? '' }}">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    @if(isset($termo))
                        <a href="{{ route('procedimentos.index') }}" class="btn btn-secondary">Limpar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ isset($termo) ? "Resultados para: \"{$termo}\"" : 'Todos os Procedimentos' }}
            </h3>
            <span class="ms-auto text-secondary">{{ $procedimentos->total() }} registros</span>
        </div>
        <table class="table table-vcenter table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Duração</th>
                    <th>Valor Padrão</th>
                    <th>Situação</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($procedimentos as $procedimento)
                    <tr class="{{ $procedimento->trashed() ? 'table-danger' : '' }}">
                        <td>{{ $procedimento->nome }}</td>
                        <td class="text-secondary">{{ $procedimento->duracao_formatada }}</td>
                        <td class="text-secondary">
                            R$ {{ number_format($procedimento->valor_padrao, 2, ',', '.') }}
                        </td>
                        <td>
                            @if($procedimento->trashed())
                                <span class="badge bg-danger">Inativo</span>
                            @else
                                <span class="badge bg-success">Ativo</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    Ações
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('procedimentos.show', $procedimento->id) }}"
                                        class="dropdown-item">Ver</a>
                                    <a href="{{ route('procedimentos.edit', $procedimento->id) }}"
                                        class="dropdown-item">Editar</a>
                                    @if(!$procedimento->trashed())
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('procedimentos.destroy', $procedimento->id) }}"
                                            method="POST"
                                            data-confirm="Deseja inativar o procedimento {{ $procedimento->nome }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                Inativar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">
                            Nenhum procedimento encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($procedimentos->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $procedimentos->links() }}
            </div>
        @endif
    </div>
@endsection