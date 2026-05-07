@extends('layouts.app')

@section('title', 'Editar Fornecedor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('estoque.fornecedores.index') }}">Fornecedores</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    @if($fornecedor->trashed())
        <div class="alert alert-warning d-flex align-items-center mb-3">
            <div class="ms-2">
                Este fornecedor está <strong>inativo</strong>.
                <form action="{{ route('estoque.fornecedores.restore', $fornecedor->id) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                </form>
            </div>
        </div>
    @endif

    <form action="{{ route('estoque.fornecedores.update', $fornecedor->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('estoque.fornecedores._form')
    </form>
@endsection