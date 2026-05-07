@extends('layouts.app')

@section('title', 'Pagamentos')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pagamentos</li>
@endsection

@section('actions')
    <a href="{{ route('pagamentos.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Novo Pagamento
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Todos os Pagamentos</h3>
            <span class="ms-auto text-secondary">{{ $pagamentos->total() }} registros</span>
        </div>
        <table class="table table-vcenter table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paciente</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Forma</th>
                    <th>Parcelas</th>
                    <th>Status</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagamentos as $pagamento)
                    <tr>
                        <td class="text-secondary">{{ $pagamento->id }}</td>
                        <td>{{ $pagamento->paciente->nome }}</td>
                        <td class="text-secondary">{{ $pagamento->descricao }}</td>
                        <td>R$ {{ number_format($pagamento->valor_total, 2, ',', '.') }}</td>
                        <td class="text-secondary">{{ ucfirst(str_replace('_', ' ', $pagamento->forma_pagamento)) }}</td>
                        <td>{{ $pagamento->numero_parcelas }}x</td>
                        <td>
                            <span class="badge {{ $pagamento->cor_status }} text-white">
                                {{ ucfirst($pagamento->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">Ações</button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('pagamentos.show', $pagamento->id) }}" class="dropdown-item">Ver</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('pagamentos.destroy', $pagamento->id) }}"
                                        method="POST"
                                        data-confirm="Deseja cancelar este pagamento?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">Cancelar</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-secondary py-4">Nenhum pagamento encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($pagamentos->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $pagamentos->links() }}
            </div>
        @endif
    </div>
@endsection