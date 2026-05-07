@extends('layouts.app')

@section('title', 'Editar Produto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('estoque.produtos.index') }}">Produtos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    @if($produto->trashed())
        <div class="alert alert-warning d-flex align-items-center mb-3">
            <div class="ms-2">
                Este produto está <strong>inativo</strong>.
                <form action="{{ route('estoque.produtos.restore', $produto->id) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                </form>
            </div>
        </div>
    @endif

    <form action="{{ route('estoque.produtos.update', $produto->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('estoque.produtos._form')
    </form>
@endsection