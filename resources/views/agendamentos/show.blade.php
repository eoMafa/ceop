@extends('layouts.app')

@section('title', 'Detalhes do Agendamento')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('agendamentos.index') }}">Agendamentos</a></li>
    <li class="breadcrumb-item active">Detalhes</li>
@endsection

@section('actions')
    @if(!in_array($agendamento->status, ['concluido', 'cancelado']))
        <a href="{{ route('evolucoes.create', ['agendamento_id' => $agendamento->id]) }}"
            class="btn btn-success">
            📋 Registrar Consulta
        </a>
    @endif
    <a href="{{ route('agendamentos.edit', $agendamento->id) }}" class="btn btn-primary">Editar</a>
    <a href="{{ route('agendamentos.index') }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $agendamento->paciente->nome }}</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Paciente</dt>
                <dd class="col-sm-9">{{ $agendamento->paciente->nome }}</dd>

                <dt class="col-sm-3">Dentista</dt>
                <dd class="col-sm-9">{{ $agendamento->dentista->name }}</dd>

                <dt class="col-sm-3">Procedimento</dt>
                <dd class="col-sm-9">{{ $agendamento->procedimento->nome }}</dd>

                <dt class="col-sm-3">Início</dt>
                <dd class="col-sm-9">{{ $agendamento->data_hora_inicio->format('d/m/Y H:i') }}</dd>

                <dt class="col-sm-3">Fim</dt>
                <dd class="col-sm-9">{{ $agendamento->data_hora_fim->format('d/m/Y H:i') }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    @php
                        $cores = [
                            'agendado'   => 'bg-blue',
                            'confirmado' => 'bg-success',
                            'cancelado'  => 'bg-danger',
                            'concluido'  => 'bg-purple',
                            'falta'      => 'bg-warning',
                        ];
                    @endphp
                    <span class="badge {{ $cores[$agendamento->status] }} text-white">
                        {{ ucfirst($agendamento->status) }}
                    </span>
                </dd>

                <dt class="col-sm-3">Observações</dt>
                <dd class="col-sm-9">{{ $agendamento->observacoes ?? '—' }}</dd>
            </dl>
        </div>
        {{-- Evoluções vinculadas --}}
        @if($agendamento->evolucoes->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Consultas Registradas</h3>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Dentista</th>
                            <th>Procedimento</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agendamento->evolucoes as $evolucao)
                            <tr>
                                <td>{{ $evolucao->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $evolucao->dentista->name }}</td>
                                <td>{{ $evolucao->procedimento->nome ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('evolucoes.show', $evolucao->id) }}"
                                        class="btn btn-sm btn-secondary">Ver</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection