@extends('layouts.app')

@section('title', 'Usuários')

@section('breadcrumb')
    <li class="breadcrumb-item active">Usuários</li>
@endsection

@section('actions')
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Novo Usuário
    </a>
@endsection

@section('content')
    {{-- Busca --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('usuarios.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control"
                        placeholder="Buscar por nome ou e-mail..."
                        value="{{ $termo ?? '' }}">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    @if(isset($termo))
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Limpar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ isset($termo) ? "Resultados para: \"{$termo}\"" : 'Todos os Usuários' }}
            </h3>
            <span class="ms-auto text-secondary">{{ $usuarios->total() }} registros</span>
        </div>
        <table class="table table-vcenter table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>CRO</th>
                    <th>Situação</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr class="{{ !$usuario->ativo ? 'table-danger' : '' }}">
                        <td>{{ $usuario->name }}</td>
                        <td class="text-secondary">{{ $usuario->email }}</td>
                        <td>
                            @if($usuario->role === 'admin')
                                <span class="badge bg-purple text-white">Admin</span>
                            @elseif($usuario->role === 'dentista')
                                <span class="badge bg-blue text-white">Dentista</span>
                            @else
                                <span class="badge bg-cyan text-white">Recepcionista</span>
                            @endif
                        </td>
                        <td class="text-secondary">{{ $usuario->cro ?? '—' }}</td>
                        <td>
                            @if($usuario->ativo)
                                <span class="badge bg-success text-white">Ativo</span>
                            @else
                                <span class="badge bg-danger text-white">Inativo</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    Ações
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('usuarios.show', $usuario->id) }}"
                                        class="dropdown-item">Ver</a>
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}"
                                        class="dropdown-item">Editar</a>
                                    @if($usuario->id !== auth()->id())
                                        <div class="dropdown-divider"></div>
                                        @if($usuario->ativo)
                                            <form action="{{ route('usuarios.destroy', $usuario->id) }}"
                                                method="POST"
                                                data-confirm="Deseja inativar o usuário {{ $usuario->name }}?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    Inativar
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('usuarios.restore', $usuario->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-success">
                                                    Restaurar
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">
                            Nenhum usuário encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($usuarios->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $usuarios->links() }}
            </div>
        @endif
    </div>
@endsection