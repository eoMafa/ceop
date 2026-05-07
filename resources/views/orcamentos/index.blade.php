@extends('layouts.app')

@section('title', 'Orçamentos')

@section('breadcrumb')
    <li class="breadcrumb-item active">Orçamentos</li>
@endsection

@section('actions')
    <a href="{{ route('orcamentos.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Novo Orçamento
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Todos os Orçamentos</h3>
            <span class="ms-auto text-secondary">{{ $orcamentos->total() }} registros</span>
        </div>
        <table class="table table-vcenter table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paciente</th>
                    <th>Dentista</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orcamentos as $orcamento)
                    <tr>
                        <td class="text-secondary">{{ $orcamento->id }}</td>
                        <td>{{ $orcamento->paciente->nome }}</td>
                        <td class="text-secondary">{{ $orcamento->dentista->name }}</td>
                        <td>R$ {{ number_format($orcamento->total_liquido, 2, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $orcamento->cor_status }} text-white">
                                {{ ucfirst($orcamento->status) }}
                            </span>
                        </td>
                        <td class="text-secondary">{{ $orcamento->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">Ações</button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('orcamentos.show', $orcamento->id) }}" class="dropdown-item">Ver</a>
                                    @if($orcamento->status === 'rascunho')
                                        <a href="{{ route('orcamentos.edit', $orcamento->id) }}" class="dropdown-item">Editar</a>
                                    @endif
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('orcamentos.destroy', $orcamento->id) }}"
                                        method="POST"
                                        data-confirm="Deseja cancelar este orçamento?">
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
                        <td colspan="7" class="text-center text-secondary py-4">Nenhum orçamento encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($orcamentos->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $orcamentos->links() }}
            </div>
        @endif
    </div>
@endsection