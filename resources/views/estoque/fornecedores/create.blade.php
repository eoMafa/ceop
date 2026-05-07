@extends('layouts.app')

@section('title', 'Novo Fornecedor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('estoque.fornecedores.index') }}">Fornecedores</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
    <form action="{{ route('estoque.fornecedores.store') }}" method="POST">
        @csrf
        @include('estoque.fornecedores._form')
    </form>
@endsection