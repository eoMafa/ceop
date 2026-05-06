@extends('layouts.app')

@section('title', 'Editar Agendamento')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('agendamentos.index') }}">Agendamentos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    <form action="{{ route('agendamentos.update', $agendamento->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('agendamentos._form')
    </form>

    {{-- Cancelar agendamento --}}
    <div class="card mt-3 border-danger">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <h4 class="text-danger mb-1">Cancelar Agendamento</h4>
                    <p class="text-secondary mb-0">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="ms-auto">
                    <form action="{{ route('agendamentos.destroy', $agendamento->id) }}"
                        method="POST"
                        data-confirm="Deseja cancelar este agendamento?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Cancelar Agendamento</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection