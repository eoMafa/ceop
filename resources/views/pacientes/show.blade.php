@extends('layouts.app')

@section('title', 'Detalhes do Paciente')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pacientes.index') }}">Pacientes</a></li>
    <li class="breadcrumb-item active">{{ $paciente->nome }}</li>
@endsection

@section('actions')
    <a href="{{ route('prontuarios.show', $paciente->id) }}" class="btn btn-info text-white">📋 Prontuário</a>
    <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-primary">Editar</a>
    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
    @if($paciente->trashed())
        <div class="alert alert-warning mb-3">
            Este paciente está <strong>inativo</strong>.
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dados Pessoais</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nome</dt>
                        <dd class="col-sm-8">{{ $paciente->nome }}</dd>

                        <dt class="col-sm-4">CPF</dt>
                        <dd class="col-sm-8">{{ $paciente->cpf ?? '—' }}</dd>

                        <dt class="col-sm-4">RG</dt>
                        <dd class="col-sm-8">{{ $paciente->rg ?? '—' }}</dd>

                        <dt class="col-sm-4">Nascimento</dt>
                        <dd class="col-sm-8">
                            {{ $paciente->data_nascimento ? $paciente->data_nascimento->format('d/m/Y') . ' (' . $paciente->idade . ' anos)' : '—' }}
                        </dd>

                        <dt class="col-sm-4">Sexo</dt>
                        <dd class="col-sm-8">
                            {{ match($paciente->sexo) {
                                'M' => 'Masculino',
                                'F' => 'Feminino',
                                'outro' => 'Outro',
                                default => '—'
                            } }}
                        </dd>

                        <dt class="col-sm-4">Telefone</dt>
                        <dd class="col-sm-8">{{ $paciente->telefone ?? '—' }}</dd>

                        <dt class="col-sm-4">E-mail</dt>
                        <dd class="col-sm-8">{{ $paciente->email ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Endereço</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">CEP</dt>
                        <dd class="col-sm-8">{{ $paciente->cep ?? '—' }}</dd>

                        <dt class="col-sm-4">Logradouro</dt>
                        <dd class="col-sm-8">
                            {{ $paciente->logradouro ?? '—' }}
                            {{ $paciente->numero ? ', ' . $paciente->numero : '' }}
                            {{ $paciente->complemento ? ' - ' . $paciente->complemento : '' }}
                        </dd>

                        <dt class="col-sm-4">Bairro</dt>
                        <dd class="col-sm-8">{{ $paciente->bairro ?? '—' }}</dd>

                        <dt class="col-sm-4">Cidade</dt>
                        <dd class="col-sm-8">
                            {{ $paciente->cidade ?? '—' }}
                            {{ $paciente->estado ? ' - ' . $paciente->estado : '' }}
                        </dd>
                    </dl>
                </div>
            </div>

            @if($paciente->observacoes)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Observações</h3>
                    </div>
                    <div class="card-body">
                        {{ $paciente->observacoes }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection