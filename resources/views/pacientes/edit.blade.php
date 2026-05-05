@extends('layouts.app')

@section('title', 'Editar Paciente')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pacientes.index') }}">Pacientes</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    @if($paciente->trashed())
        <div class="alert alert-warning d-flex align-items-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 9v4" />
                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.871l-8.106 -13.534a1.914 1.914 0 0 0 -3.274 0z" />
                <path d="M12 16h.01" />
            </svg>
            <div class="ms-2">
                Este paciente está <strong>inativo</strong>.
                <form action="{{ route('pacientes.restore', $paciente->id) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Restaurar Paciente</button>
                </form>
            </div>
        </div>
    @endif

    <form action="{{ route('pacientes.update', $paciente->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('pacientes._form')
    </form>
@endsection