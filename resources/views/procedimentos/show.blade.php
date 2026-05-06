@extends('layouts.app')

@section('title', 'Detalhes do Procedimento')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('procedimentos.index') }}">Procedimentos</a></li>
    <li class="breadcrumb-item active">{{ $procedimento->nome }}</li>
@endsection

@section('actions')
    <a href="{{ route('procedimentos.edit', $procedimento->id) }}" class="btn btn-primary">Editar</a>
    <a href="{{ route('procedimentos.index') }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
    @if($procedimento->trashed())
        <div class="alert alert-warning mb-3">
            Este procedimento está <strong>inativo</strong>.
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $procedimento->nome }}</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Nome</dt>
                <dd class="col-sm-9">{{ $procedimento->nome }}</dd>

                <dt class="col-sm-3">Descrição</dt>
                <dd class="col-sm-9">{{ $procedimento->descricao ?? '—' }}</dd>

                <dt class="col-sm-3">Duração</dt>
                <dd class="col-sm-9">{{ $procedimento->duracao_formatada }}</dd>

                <dt class="col-sm-3">Valor Padrão</dt>
                <dd class="col-sm-9">R$ {{ number_format($procedimento->valor_padrao, 2, ',', '.') }}</dd>

                <dt class="col-sm-3">Situação</dt>
                <dd class="col-sm-9">
                    @if($procedimento->trashed())
                        <span class="badge bg-danger text-white">Inativo</span>
                    @else
                        <span class="badge bg-success text-white">Ativo</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>
@endsection