@extends('layouts.app')

@section('title', 'Novo Procedimento')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('procedimentos.index') }}">Procedimentos</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
    <form action="{{ route('procedimentos.store') }}" method="POST">
        @csrf
        @include('procedimentos._form')
    </form>
@endsection