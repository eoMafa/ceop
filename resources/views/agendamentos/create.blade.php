@extends('layouts.app')

@section('title', 'Novo Agendamento')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('agendamentos.index') }}">Agendamentos</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
    <form action="{{ route('agendamentos.store') }}" method="POST">
        @csrf
        @include('agendamentos._form')
    </form>
@endsection