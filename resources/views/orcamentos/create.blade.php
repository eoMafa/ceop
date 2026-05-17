@extends('layouts.app')

@section('title', 'Novo Orçamento')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('orcamentos.index') }}">Orçamentos</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
    <form action="{{ route('orcamentos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('orcamentos._form')
    </form>
@endsection