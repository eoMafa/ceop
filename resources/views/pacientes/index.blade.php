@extends('layouts.app')

@section('title', 'Pacientes')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pacientes</li>
@endsection

@section('actions')
    <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Novo Paciente
    </a>
@endsection

@section('content')
    {{-- Busca --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('pacientes.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control"
                        placeholder="Buscar por nome, CPF, telefone ou e-mail..."
                        value="{{ $termo ?? '' }}">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    @if(isset($termo))
                        <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Limpar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ isset($termo) ? "Resultados para: \"{$termo}\"" : 'Todos os Pacientes' }}
            </h3>
            <span class="ms-auto text-secondary">{{ $pacientes->total() }} registros</span>
        </div>
        <table class="table table-vcenter table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th>Situação</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pacientes as $paciente)
                        <tr class="{{ $paciente->trashed() ? 'table-danger' : '' }}">
                            <td>{{ $paciente->nome }}</td>
                            <td class="text-secondary">{{ $paciente->cpf ?? '—' }}</td>
                            <td class="text-secondary">{{ $paciente->telefone ?? '—' }}</td>
                            <td class="text-secondary">{{ $paciente->email ?? '—' }}</td>
                            <td>
                                @if($paciente->trashed())
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
                                        <a href="{{ route('pacientes.show', $paciente->id) }}"
                                            class="dropdown-item">Ver</a>
                                        <a href="{{ route('pacientes.edit', $paciente->id) }}"
                                            class="dropdown-item">Editar</a>
                                        @if(!$paciente->trashed())
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('pacientes.destroy', $paciente->id) }}"
                                                method="POST"
                                                data-confirm="Deseja inativar o paciente {{ $paciente->nome }}?">
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
                            <td colspan="6" class="text-center text-secondary py-4">
                                Nenhum paciente encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pacientes->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $pacientes->links() }}
            </div>
        @endif
    </div>
@endsection