@extends('layouts.app')

@section('title', 'Orçamento #' . $orcamento->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('orcamentos.index') }}">Orçamentos</a></li>
    <li class="breadcrumb-item active">#{{ $orcamento->id }}</li>
@endsection

@section('actions')
    @if($orcamento->status === 'aprovado')
        <a href="{{ route('agendamentos.create', [
                'orcamento_id' => $orcamento->id,
                'paciente_id'  => $orcamento->paciente_id
            ]) }}"
            class="btn btn-primary">
            📅 Agendar Consulta
        </a>
        <a href="{{ route('pagamentos.create', [
                'orcamento_id' => $orcamento->id,
                'paciente_id'  => $orcamento->paciente_id
            ]) }}"
            class="btn btn-success">
            💰 Gerar Pagamento
        </a>
    @endif
    @if($orcamento->status === 'rascunho')
        <a href="{{ route('orcamentos.edit', $orcamento->id) }}" class="btn btn-secondary">Editar</a>
    @endif
    <a href="{{ route('orcamentos.index') }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-md-8">

            {{-- Itens --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Itens do Orçamento</h3>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Procedimento</th>
                            <th>Dente</th>
                            <th>Qtd</th>
                            <th>Valor Unit.</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orcamento->itens as $item)
                            <tr>
                                <td>{{ $item->procedimento->nome }}</td>
                                <td class="text-secondary">{{ $item->dente ?? '—' }}</td>
                                <td>{{ $item->quantidade }}</td>
                                <td>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total Bruto</strong></td>
                            <td><strong>R$ {{ number_format($orcamento->total_bruto, 2, ',', '.') }}</strong></td>
                        </tr>
                        @if($orcamento->desconto_tipo !== 'nenhum')
                            <tr class="text-danger">
                                <td colspan="4" class="text-end">Desconto</td>
                                <td>- R$ {{ number_format($orcamento->total_bruto - $orcamento->total_liquido, 2, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total Líquido</strong></td>
                            <td><strong class="text-success">R$ {{ number_format($orcamento->total_liquido, 2, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Pagamentos vinculados --}}
            @if($orcamento->pagamentos->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pagamentos</h3>
                    </div>
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Parcelas</th>
                                <th>Status</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orcamento->pagamentos as $pagamento)
                                <tr>
                                    <td>{{ $pagamento->descricao }}</td>
                                    <td>R$ {{ number_format($pagamento->valor_total, 2, ',', '.') }}</td>
                                    <td>{{ $pagamento->numero_parcelas }}x</td>
                                    <td>
                                        <span class="badge {{ $pagamento->cor_status }} text-white">
                                            {{ ucfirst($pagamento->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pagamentos.show', $pagamento->id) }}"
                                            class="btn btn-sm btn-secondary">Ver</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>

        <div class="col-md-4">

            {{-- Dados --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Informações</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Paciente</dt>
                        <dd class="col-sm-7">
                            <a href="{{ route('pacientes.show', $orcamento->paciente->id) }}">
                                {{ $orcamento->paciente->nome }}
                            </a>
                        </dd>

                        <dt class="col-sm-5">Dentista</dt>
                        <dd class="col-sm-7">{{ $orcamento->dentista->name }}</dd>

                        <dt class="col-sm-5">Convênio</dt>
                        <dd class="col-sm-7">{{ $orcamento->convenio->nome ?? '—' }}</dd>

                        <dt class="col-sm-5">Desconto</dt>
                        <dd class="col-sm-7">
                            {{ match($orcamento->desconto_tipo) {
                                'nenhum'    => '—',
                                'percentual'=> $orcamento->desconto_valor . '%',
                                'valor_fixo'=> 'R$ ' . number_format($orcamento->desconto_valor, 2, ',', '.'),
                                'convenio'  => 'Convênio',
                                default     => '—'
                            } }}
                        </dd>

                        <dt class="col-sm-5">Status</dt>
                        <dd class="col-sm-7">
                            <span class="badge {{ $orcamento->cor_status }} text-white">
                                {{ ucfirst($orcamento->status) }}
                            </span>
                        </dd>

                        <dt class="col-sm-5">Data</dt>
                        <dd class="col-sm-7">{{ $orcamento->created_at->format('d/m/Y') }}</dd>

                        @if($orcamento->observacoes)
                            <dt class="col-sm-5">Observações</dt>
                            <dd class="col-sm-7">{{ $orcamento->observacoes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Atualizar status --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Atualizar Status</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('orcamentos.status', $orcamento->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-2">
                            <select name="status" class="form-select">
                                @foreach(['rascunho', 'aprovado', 'recusado', 'cancelado'] as $status)
                                    <option value="{{ $status }}"
                                        {{ $orcamento->status === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Atualizar</button>
                    </form>
                </div>
            </div>

            {{-- Gerar pagamento --}}
            @if($orcamento->status === 'aprovado')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Gerar Pagamento</h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('pagamentos.create', ['orcamento_id' => $orcamento->id, 'paciente_id' => $orcamento->paciente_id]) }}"
                            class="btn btn-success w-100">
                            💰 Gerar Pagamento
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection