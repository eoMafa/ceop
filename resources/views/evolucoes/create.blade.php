@extends('layouts.app')

@section('title', 'Nova Consulta')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('evolucoes.index') }}">Consultas</a></li>
    <li class="breadcrumb-item active">Nova</li>
@endsection

@section('content')
    @if($agendamento)
        <div class="alert alert-info mb-3">
            <strong>Agendamento vinculado:</strong>
            {{ $agendamento->paciente->nome }} —
            {{ $agendamento->data_hora_inicio->format('d/m/Y H:i') }} —
            {{ $agendamento->procedimento->nome }}
        </div>
    @endif

    <form action="{{ route('evolucoes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('evolucoes._form')
    </form>
@endsection