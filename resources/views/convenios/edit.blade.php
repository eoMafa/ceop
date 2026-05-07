@extends('layouts.app')

@section('title', 'Editar Convênio')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('convenios.index') }}">Convênios</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    @if($convenio->trashed())
        <div class="alert alert-warning d-flex align-items-center mb-3">
            <div class="ms-2">
                Este convênio está <strong>inativo</strong>.
                <form action="{{ route('convenios.restore', $convenio->id) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                </form>
            </div>
        </div>
    @endif

    <form action="{{ route('convenios.update', $convenio->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('convenios._form')
    </form>
@endsection