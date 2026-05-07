@extends('layouts.app')

@section('title', 'Pagamento #' . $pagamento->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pagamentos.index') }}">Pagamentos</a></li>
    <li class="breadcrumb-item active">#{{ $pagamento->id }}</li>
@endsection

@section('actions')
    <a href="{{ route('pagamentos.index') }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
    <div class="row g-3">

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informações</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Paciente</dt>
                        <dd class="col-sm-7">{{ $pagamento->paciente->nome }}</dd>

                        <dt class="col-sm-5">Descrição</dt>
                        <dd class="col-sm-7">{{ $pagamento->descricao }}</dd>

                        <dt class="col-sm-5">Valor Total</dt>
                        <dd class="col-sm-7">R$ {{ number_format($pagamento->valor_total, 2, ',', '.') }}</dd>

                        <dt class="col-sm-5">Forma</dt>
                        <dd class="col-sm-7">{{ ucfirst(str_replace('_', ' ', $pagamento->forma_pagamento)) }}</dd>

                        <dt class="col-sm-5">Parcelas</dt>
                        <dd class="col-sm-7">{{ $pagamento->numero_parcelas }}x</dd>

                        <dt class="col-sm-5">Status</dt>
                        <dd class="col-sm-7">
                            <span class="badge {{ $pagamento->cor_status }} text-white">
                                {{ ucfirst($pagamento->status) }}
                            </span>
                        </dd>

                        @if($pagamento->orcamento)
                            <dt class="col-sm-5">Orçamento</dt>
                            <dd class="col-sm-7">
                                <a href="{{ route('orcamentos.show', $pagamento->orcamento->id) }}">
                                    #{{ $pagamento->orcamento->id }}
                                </a>
                            </dd>
                        @endif

                        @if($pagamento->observacoes)
                            <dt class="col-sm-5">Observações</dt>
                            <dd class="col-sm-7">{{ $pagamento->observacoes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Parcelas</h3>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Pagamento</th>
                            <th>Status</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pagamento->parcelas as $parcela)
                            <tr class="{{ $parcela->status === 'pago' ? 'table-success' : ($parcela->status === 'cancelado' ? 'table-danger' : '') }}">
                                <td>{{ $parcela->numero }}</td>
                                <td>{{ $parcela->data_vencimento->format('d/m/Y') }}</td>
                                <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                                <td>{{ $parcela->data_pagamento ? $parcela->data_pagamento->format('d/m/Y') : '—' }}</td>
                                <td>
                                    <span class="badge {{ $parcela->cor_status }} text-white">
                                        {{ ucfirst($parcela->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($parcela->status === 'pendente')
                                        <div class="d-flex gap-1">
                                            <form action="{{ route('parcelas.baixar', $parcela->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">Baixar</button>
                                            </form>
                                            <form action="{{ route('parcelas.cancelar', $parcela->id) }}"
                                                method="POST"
                                                data-confirm="Deseja cancelar esta parcela?">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-ghost-danger">Cancelar</button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection