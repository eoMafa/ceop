@extends('layouts.app')

@section('title', 'Editar Procedimento')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('procedimentos.index') }}">Procedimentos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    @if($procedimento->trashed())
        <div class="alert alert-warning d-flex align-items-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 9v4" />
                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.871l-8.106 -13.534a1.914 1.914 0 0 0 -3.274 0z" />
                <path d="M12 16h.01" />
            </svg>
            <div class="ms-2">
                Este procedimento está <strong>inativo</strong>.
                <form action="{{ route('procedimentos.restore', $procedimento->id) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                </form>
            </div>
        </div>
    @endif

    <form action="{{ route('procedimentos.update', $procedimento->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('procedimentos._form')
    </form>
@endsection