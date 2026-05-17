@extends('layouts.app')

@section('title', 'Editar Orçamento #' . $orcamento->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('orcamentos.index') }}">Orçamentos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    <form action="{{ route('orcamentos.update', $orcamento->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('orcamentos._form')
    </form>
@endsection