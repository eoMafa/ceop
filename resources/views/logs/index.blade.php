@extends('layouts.app')

@section('title', 'Log de Atividades')

@section('breadcrumb')
    <li class="breadcrumb-item active">Logs</li>
@endsection

@section('content')

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('logs.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Usuário</label>
                        <select name="user_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}"
                                    {{ request('user_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Módulo</label>
                        <select name="modulo" class="form-select">
                            <option value="">Todos</option>
                            @foreach($modulos as $modulo)
                                <option value="{{ $modulo }}"
                                    {{ request('modulo') == $modulo ? 'selected' : '' }}>
                                    {{ ucfirst($modulo) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ação</label>
                        <select name="acao" class="form-select">
                            <option value="">Todas</option>
                            @foreach($acoes as $acao)
                                <option value="{{ $acao }}"
                                    {{ request('acao') == $acao ? 'selected' : '' }}>
                                    {{ ucfirst($acao) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Data Início</label>
                        <input type="date" name="data_inicio" class="form-control"
                            value="{{ request('data_inicio') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Data Fim</label>
                        <input type="date" name="data_fim" class="form-control"
                            value="{{ request('data_fim') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('logs.index') }}" class="btn btn-secondary w-100">Limpar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Atividades do Sistema</h3>
            <span class="ms-auto text-secondary">{{ $logs->total() }} registros</span>
        </div>
        <table class="table table-vcenter table-hover">
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Usuário</th>
                    <th>Ação</th>
                    <th>Módulo</th>
                    <th>Descrição</th>
                    <th>IP</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    @php
                        $cores = [
                            'criou'   => 'bg-success',
                            'editou'  => 'bg-blue',
                            'deletou' => 'bg-danger',
                            'acessou' => 'bg-secondary',
                            'login'   => 'bg-purple',
                            'logout'  => 'bg-secondary',
                        ];
                    @endphp
                    <tr>
                        <td class="text-secondary">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $log->user->name ?? 'Sistema' }}</td>
                        <td>
                            <span class="badge {{ $cores[$log->acao] ?? 'bg-secondary' }} text-white">
                                {{ ucfirst($log->acao) }}
                            </span>
                        </td>
                        <td class="text-secondary">{{ ucfirst($log->modulo) }}</td>
                        <td>{{ $log->descricao }}</td>
                        <td class="text-secondary">{{ $log->ip }}</td>
                        <td>
                            <a href="{{ route('logs.show', $log->id) }}"
                                class="btn btn-sm btn-secondary">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">Nenhum log encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($logs->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection