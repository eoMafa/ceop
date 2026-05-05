@extends('layouts.app')

@section('title', 'Novo Paciente')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pacientes.index') }}">Pacientes</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
    <form action="{{ route('pacientes.store') }}" method="POST">
        @csrf
        @include('pacientes._form')
    </form>
@endsection