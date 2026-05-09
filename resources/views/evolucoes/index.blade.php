@extends('layouts.app')

@section('title', 'Consultas')

@section('breadcrumb')
    <li class="breadcrumb-item active">Consultas</li>
@endsection

@section('actions')
    <a href="{{ route('evolucoes.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Nova Consulta
    </a>
@endsection

@section('content')

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('evolucoes.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Paciente</label>
                        <select name="paciente_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($pacientes as $paciente)
                                <option value="{{ $paciente->id }}"
                                    {{ request('paciente_id') == $paciente->id ? 'selected' : '' }}>
                                    {{ $paciente->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dentista</label>
                        <select name="dentista_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($dentistas as $dentista)
                                <option value="{{ $dentista->id }}"
                                    {{ request('dentista_id') == $dentista->id ? 'selected' : '' }}>
                                    {{ $dentista->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Procedimento</label>
                        <select name="procedimento_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($procedimentos as $proc)
                                <option value="{{ $proc->id }}"
                                    {{ request('procedimento_id') == $proc->id ? 'selected' : '' }}>
                                    {{ $proc->nome }}
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
                </div>
                @if(request()->hasAny(['paciente_id', 'dentista_id', 'procedimento_id', 'data_inicio', 'data_fim']))
                    <div class="mt-2">
                        <a href="{{ route('evolucoes.index') }}" class="btn btn-sm btn-secondary">Limpar filtros</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Listagem --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Todas as Consultas</h3>
            <span class="ms-auto text-secondary">{{ $evolucoes->total() }} registros</span>
        </div>
        <table class="table table-vcenter table-hover">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Paciente</th>
                    <th>Dentista</th>
                    <th>Procedimento</th>
                    <th>Dente</th>
                    <th>Materiais</th>
                    <th>Arquivos</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($evolucoes as $evolucao)
                    <tr>
                        <td class="text-secondary">{{ $evolucao->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('pacientes.show', $evolucao->prontuario->paciente->id) }}">
                                {{ $evolucao->prontuario->paciente->nome }}
                            </a>
                        </td>
                        <td class="text-secondary">{{ $evolucao->dentista->name }}</td>
                        <td class="text-secondary">{{ $evolucao->procedimento->nome ?? '—' }}</td>
                        <td>
                            @if($evolucao->dente)
                                <span class="badge bg-blue text-white">{{ $evolucao->dente }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($evolucao->materiais->count() > 0)
                                <span class="badge bg-secondary text-white">
                                    {{ $evolucao->materiais->count() }} item(s)
                                </span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($evolucao->arquivos->count() > 0)
                                <span class="badge bg-secondary text-white">
                                    {{ $evolucao->arquivos->count() }} arquivo(s)
                                </span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">Ações</button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('evolucoes.show', $evolucao->id) }}" class="dropdown-item">Ver</a>
                                    <a href="{{ route('evolucoes.edit', $evolucao->id) }}" class="dropdown-item">Editar</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-secondary py-4">Nenhuma consulta encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($evolucoes->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $evolucoes->links() }}
            </div>
        @endif
    </div>

@endsection