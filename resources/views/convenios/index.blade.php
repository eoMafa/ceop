@extends('layouts.app')

@section('title', 'Convênios')

@section('breadcrumb')
    <li class="breadcrumb-item active">Convênios</li>
@endsection

@section('actions')
    <a href="{{ route('convenios.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Novo Convênio
    </a>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('convenios.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control"
                        placeholder="Buscar por nome..."
                        value="{{ $termo ?? '' }}">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    @if(isset($termo))
                        <a href="{{ route('convenios.index') }}" class="btn btn-secondary">Limpar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ isset($termo) ? "Resultados para: \"{$termo}\"" : 'Todos os Convênios' }}</h3>
            <span class="ms-auto text-secondary">{{ $convenios->total() }} registros</span>
        </div>
        <table class="table table-vcenter table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Desconto</th>
                    <th>Situação</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($convenios as $convenio)
                    <tr class="{{ $convenio->trashed() ? 'table-danger' : '' }}">
                        <td>{{ $convenio->nome }}</td>
                        <td>{{ number_format($convenio->desconto_percentual, 2, ',', '.') }}%</td>
                        <td>
                            @if($convenio->trashed())
                                <span class="badge bg-danger text-white">Inativo</span>
                            @else
                                <span class="badge bg-success text-white">Ativo</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">Ações</button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('convenios.edit', $convenio->id) }}" class="dropdown-item">Editar</a>
                                    @if(!$convenio->trashed())
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('convenios.destroy', $convenio->id) }}"
                                            method="POST"
                                            data-confirm="Deseja inativar o convênio {{ $convenio->nome }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">Inativar</button>
                                        </form>
                                    @else
                                        <form action="{{ route('convenios.restore', $convenio->id) }}" method="POST">
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
                        <td colspan="4" class="text-center text-secondary py-4">Nenhum convênio encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($convenios->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $convenios->links() }}
            </div>
        @endif
    </div>
@endsection