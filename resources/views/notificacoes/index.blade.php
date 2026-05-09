@extends('layouts.app')

@section('title', 'Notificações')

@section('breadcrumb')
    <li class="breadcrumb-item active">Notificações</li>
@endsection

@section('actions')
    @if(auth()->user()->notificacoesNaoLidas()->count() > 0)
        <form action="{{ route('notificacoes.ler-todas') }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-secondary">
                Marcar todas como lidas
            </button>
        </form>
    @endif
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Todas as Notificações</h3>
            <span class="ms-auto text-secondary">{{ $notificacoes->total() }} no total</span>
        </div>
        <div class="card-body p-0">
            @forelse($notificacoes as $notificacao)
                <div class="d-flex align-items-start p-3 border-bottom {{ !$notificacao->lida ? 'bg-light' : '' }}">
                    <div class="me-3 mt-1" style="font-size: 1.5rem;">
                        {{ $notificacao->icone_tipo }}
                    </div>
                    <div class="flex-fill">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold">{{ $notificacao->titulo }}</span>
                            <span class="badge {{ $notificacao->cor_tipo }} text-white" style="font-size:10px">
                                {{ ucfirst($notificacao->tipo) }}
                            </span>
                            @if(!$notificacao->lida)
                                <span class="badge bg-danger text-white" style="font-size:10px">Nova</span>
                            @endif
                        </div>
                        <div class="text-secondary mb-1">{{ $notificacao->mensagem }}</div>
                        <div class="text-secondary" style="font-size:12px">
                            {{ $notificacao->created_at->diffForHumans() }}
                            — {{ $notificacao->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="d-flex gap-2 ms-3">
                        @if($notificacao->url)
                            <a href="{{ route('notificacoes.ler', $notificacao->id) }}"
                                class="btn btn-sm btn-secondary">Ver</a>
                        @endif
                        @if(!$notificacao->lida)
                            <form action="{{ route('notificacoes.ler', $notificacao->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-ghost-secondary">✓</button>
                            </form>
                        @endif
                        <form action="{{ route('notificacoes.destroy', $notificacao->id) }}"
                            method="POST"
                            data-confirm="Remover esta notificação?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-ghost-danger">✕</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-secondary py-5">
                    🔔 Nenhuma notificação encontrada.
                </div>
            @endforelse
        </div>
        @if($notificacoes->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $notificacoes->links() }}
            </div>
        @endif
    </div>
@endsection