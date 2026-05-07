@extends('layouts.app')

@section('title', 'Novo Convênio')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('convenios.index') }}">Convênios</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
    <form action="{{ route('convenios.store') }}" method="POST">
        @csrf
        @include('convenios._form')
    </form>
@endsection