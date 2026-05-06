@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuários</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf
        @include('usuarios._form')
    </form>
@endsection