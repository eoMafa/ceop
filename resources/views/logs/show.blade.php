@extends('layouts.app')

@section('title', 'Detalhe do Log #' . $log->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('logs.index') }}">Logs</a></li>
    <li class="breadcrumb-item active">#{{ $log->id }}</li>
@endsection

@section('actions')
    <a href="{{ route('logs.index') }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
    <div class="row g-3">

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informações</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Data/Hora</dt>
                        <dd class="col-sm-7">{{ $log->created_at->format('d/m/Y H:i:s') }}</dd>

                        <dt class="col-sm-5">Usuário</dt>
                        <dd class="col-sm-7">{{ $log->user->name ?? 'Sistema' }}</dd>

                        <dt class="col-sm-5">Ação</dt>
                        <dd class="col-sm-7">{{ ucfirst($log->acao) }}</dd>

                        <dt class="col-sm-5">Módulo</dt>
                        <dd class="col-sm-7">{{ ucfirst($log->modulo) }}</dd>

                        <dt class="col-sm-5">Descrição</dt>
                        <dd class="col-sm-7">{{ $log->descricao }}</dd>

                        <dt class="col-sm-5">IP</dt>
                        <dd class="col-sm-7">{{ $log->ip }}</dd>

                        <dt class="col-sm-5">Navegador</dt>
                        <dd class="col-sm-7">{{ Str::limit($log->user_agent, 50) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-8">

            @if($log->dados_anteriores)
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Dados Anteriores</h3>
                    </div>
                    <div class="card-body">
                        <pre class="mb-0" style="font-size:12px">{{ json_encode($log->dados_anteriores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            @endif

            @if($log->dados_novos)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Dados Novos</h3>
                    </div>
                    <div class="card-body">
                        <pre class="mb-0" style="font-size:12px">{{ json_encode($log->dados_novos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            @endif

            @if(!$log->dados_anteriores && !$log->dados_novos)
                <div class="card">
                    <div class="card-body text-center text-secondary py-4">
                        Nenhum dado adicional registrado.
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection