@extends('layouts.app')

@section('title', 'Novo Produto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('estoque.produtos.index') }}">Produtos</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
    <form action="{{ route('estoque.produtos.store') }}" method="POST">
        @csrf
        @include('estoque.produtos._form')
    </form>
@endsection