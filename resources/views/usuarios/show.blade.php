@extends('layouts.app')

@section('title', 'Detalhes do Usuário')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuários</a></li>
    <li class="breadcrumb-item active">{{ $usuario->name }}</li>
@endsection

@section('actions')
    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-primary">Editar</a>
    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $usuario->name }}</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Nome</dt>
                <dd class="col-sm-9">{{ $usuario->name }}</dd>

                <dt class="col-sm-3">E-mail</dt>
                <dd class="col-sm-9">{{ $usuario->email }}</dd>

                <dt class="col-sm-3">Perfil</dt>
                <dd class="col-sm-9">
                    @if($usuario->role === 'admin')
                        <span class="badge bg-purple text-white">Admin</span>
                    @elseif($usuario->role === 'dentista')
                        <span class="badge bg-blue text-white">Dentista</span>
                    @else
                        <span class="badge bg-cyan text-white">Recepcionista</span>
                    @endif
                </dd>

                <dt class="col-sm-3">CRO</dt>
                <dd class="col-sm-9">{{ $usuario->cro ?? '—' }}</dd>

                <dt class="col-sm-3">Telefone</dt>
                <dd class="col-sm-9">{{ $usuario->telefone ?? '—' }}</dd>

                <dt class="col-sm-3">Situação</dt>
                <dd class="col-sm-9">
                    @if($usuario->ativo)
                        <span class="badge bg-success text-white">Ativo</span>
                    @else
                        <span class="badge bg-danger text-white">Inativo</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>
@endsection