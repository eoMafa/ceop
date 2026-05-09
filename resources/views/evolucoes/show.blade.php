@extends('layouts.app')

@section('title', 'Consulta #' . $evolucao->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('evolucoes.index') }}">Consultas</a></li>
    <li class="breadcrumb-item active">#{{ $evolucao->id }}</li>
@endsection

@section('actions')
    <a href="{{ route('evolucoes.edit', $evolucao->id) }}" class="btn btn-primary">Editar</a>
    <a href="{{ route('evolucoes.index') }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
    <div class="row g-3">

        {{-- Coluna esquerda --}}
        <div class="col-md-4">

            {{-- Dados do paciente --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Paciente</h3>
                    <a href="{{ route('pacientes.show', $evolucao->prontuario->paciente->id) }}"
                        class="btn btn-sm btn-secondary ms-auto">Ver ficha</a>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nome</dt>
                        <dd class="col-sm-7">{{ $evolucao->prontuario->paciente->nome }}</dd>

                        <dt class="col-sm-5">Nascimento</dt>
                        <dd class="col-sm-7">
                            {{ $evolucao->prontuario->paciente->data_nascimento
                                ? $evolucao->prontuario->paciente->data_nascimento->format('d/m/Y') . ' (' . $evolucao->prontuario->paciente->idade . ' anos)'
                                : '—' }}
                        </dd>

                        <dt class="col-sm-5">Telefone</dt>
                        <dd class="col-sm-7">{{ $evolucao->prontuario->paciente->telefone ?? '—' }}</dd>

                        <dt class="col-sm-5">Prontuário</dt>
                        <dd class="col-sm-7">
                            <a href="{{ route('prontuarios.show', $evolucao->prontuario->paciente->id) }}">
                                Ver prontuário
                            </a>
                        </dd>
                    </dl>
                </div>
            </div>

            {{-- Dados da consulta --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Dados da Consulta</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Data</dt>
                        <dd class="col-sm-7">{{ $evolucao->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-5">Dentista</dt>
                        <dd class="col-sm-7">{{ $evolucao->dentista->name }}</dd>

                        <dt class="col-sm-5">Procedimento</dt>
                        <dd class="col-sm-7">{{ $evolucao->procedimento->nome ?? '—' }}</dd>

                        <dt class="col-sm-5">Dente</dt>
                        <dd class="col-sm-7">
                            @if($evolucao->dente)
                                <span class="badge bg-blue text-white">{{ $evolucao->dente }}</span>
                                @if($evolucao->face)
                                    <span class="badge bg-secondary text-white ms-1">{{ ucfirst($evolucao->face) }}</span>
                                @endif
                            @else
                                —
                            @endif
                        </dd>

                        @if($evolucao->agendamento)
                            <dt class="col-sm-5">Agendamento</dt>
                            <dd class="col-sm-7">
                                <a href="{{ route('agendamentos.show', $evolucao->agendamento->id) }}">
                                    #{{ $evolucao->agendamento->id }}
                                    — {{ $evolucao->agendamento->data_hora_inicio->format('d/m/Y H:i') }}
                                </a>
                            </dd>
                        @endif

                        @if($evolucao->orcamento)
                            <dt class="col-sm-5">Orçamento</dt>
                            <dd class="col-sm-7">
                                <a href="{{ route('orcamentos.show', $evolucao->orcamento->id) }}">
                                    #{{ $evolucao->orcamento->id }}
                                    — R$ {{ number_format($evolucao->orcamento->total_liquido, 2, ',', '.') }}
                                </a>
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Materiais utilizados --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Materiais Utilizados</h3>
                    @if($evolucao->materiais->count() > 0)
                        <span class="ms-auto text-secondary">
                            Custo: R$ {{ number_format($evolucao->custo_total, 2, ',', '.') }}
                        </span>
                    @endif
                </div>
                <div class="card-body p-0">
                    @forelse($evolucao->materiais as $material)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="flex-fill">
                                <div class="fw-bold">{{ $material->produto->nome }}</div>
                                <small class="text-secondary">{{ $material->produto->unidade }}</small>
                            </div>
                            <div class="text-end">
                                <div>{{ number_format($material->quantidade, 2, ',', '.') }} {{ $material->produto->unidade }}</div>
                                <small class="text-secondary">
                                    R$ {{ number_format($material->quantidade * $material->valor_unitario, 2, ',', '.') }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-secondary py-3">Nenhum material registrado.</div>
                    @endforelse
                </div>
            </div>

            {{-- Histórico do paciente --}}
            @if($historicoEvolucoes->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Últimas Consultas do Paciente</h3>
                    </div>
                    <div class="card-body p-0">
                        @foreach($historicoEvolucoes as $hist)
                            <a href="{{ route('evolucoes.show', $hist->id) }}"
                                class="d-flex align-items-center p-3 border-bottom text-decoration-none text-body">
                                <div class="flex-fill">
                                    <div class="fw-bold">{{ $hist->created_at->format('d/m/Y') }}</div>
                                    <small class="text-secondary">
                                        {{ $hist->procedimento->nome ?? '—' }} — {{ $hist->dentista->name }}
                                    </small>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-secondary" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <polyline points="9 6 15 12 9 18" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Coluna direita --}}
        <div class="col-md-8">

            {{-- Descrição --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Descrição da Consulta</h3>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $evolucao->descricao }}</p>
                </div>
            </div>

            {{-- Orçamento vinculado --}}
            @if($evolucao->orcamento)
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Orçamento Vinculado #{{ $evolucao->orcamento->id }}</h3>
                        <a href="{{ route('orcamentos.show', $evolucao->orcamento->id) }}"
                            class="btn btn-sm btn-secondary ms-auto">Ver orçamento</a>
                    </div>
                    <table class="table table-vcenter mb-0">
                        <thead>
                            <tr>
                                <th>Procedimento</th>
                                <th>Dente</th>
                                <th>Qtd</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evolucao->orcamento->itens as $item)
                                <tr>
                                    <td>{{ $item->procedimento->nome }}</td>
                                    <td>{{ $item->dente ?? '—' }}</td>
                                    <td>{{ $item->quantidade }}</td>
                                    <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                <td><strong>R$ {{ number_format($evolucao->orcamento->total_liquido, 2, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif

            {{-- Arquivos --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Arquivos</h3>
                    <span class="ms-auto text-secondary">{{ $evolucao->arquivos->count() }} arquivo(s)</span>
                </div>
                <div class="card-body">
                    @if($evolucao->arquivos->count() > 0)
                        <div class="row g-3">
                            @foreach($evolucao->arquivos as $arquivo)
                                <div class="col-auto">
                                    <div class="card card-sm">
                                        <div class="card-body p-2">
                                            @if($arquivo->tipo === 'imagem')
                                                <a href="{{ $arquivo->url }}" target="_blank">
                                                    <img src="{{ $arquivo->url }}"
                                                        style="width:120px;height:120px;object-fit:cover;border-radius:4px;">
                                                </a>
                                            @elseif($arquivo->tipo === 'video')
                                                <a href="{{ $arquivo->url }}" target="_blank"
                                                    class="btn btn-secondary w-100">
                                                    🎥 {{ Str::limit($arquivo->nome_original, 20) }}
                                                </a>
                                            @else
                                                <a href="{{ $arquivo->url }}" target="_blank"
                                                    class="btn btn-secondary w-100">
                                                    📄 {{ Str::limit($arquivo->nome_original, 20) }}
                                                </a>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <small class="text-secondary">{{ $arquivo->tamanho_formatado }}</small>
                                                <form action="{{ route('evolucoes.arquivos.destroy', $arquivo->id) }}"
                                                    method="POST"
                                                    data-confirm="Remover este arquivo?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-ghost-danger py-0">✕</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-secondary mb-0">Nenhum arquivo anexado.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection