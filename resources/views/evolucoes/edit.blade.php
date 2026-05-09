@extends('layouts.app')

@section('title', 'Editar Consulta #' . $evolucao->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('evolucoes.index') }}">Consultas</a></li>
    <li class="breadcrumb-item"><a href="{{ route('evolucoes.show', $evolucao->id) }}">#{{ $evolucao->id }}</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    <form action="{{ route('evolucoes.update', $evolucao->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('evolucoes._form')
    </form>
@endsection