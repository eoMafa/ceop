@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuários</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    @if(!$usuario->ativo)
        <div class="alert alert-warning d-flex align-items-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 9v4" />
                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.871l-8.106 -13.534a1.914 1.914 0 0 0 -3.274 0z" />
                <path d="M12 16h.01" />
            </svg>
            <div class="ms-2">
                Este usuário está <strong>inativo</strong>.
                <form action="{{ route('usuarios.restore', $usuario->id) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                </form>
            </div>
        </div>
    @endif

    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('usuarios._form')
    </form>

    {{-- Alterar senha --}}
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Alterar Senha</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('usuarios.password', $usuario->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label required">Nova Senha</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Confirmar Nova Senha</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-warning">Alterar Senha</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection