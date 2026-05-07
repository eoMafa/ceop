@extends('layouts.app')

@section('title', 'Produto: ' . $produto->nome)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('estoque.produtos.index') }}">Produtos</a></li>
    <li class="breadcrumb-item active">{{ $produto->nome }}</li>
@endsection

@section('actions')
    <a href="{{ route('estoque.produtos.edit', $produto->id) }}" class="btn btn-secondary">Editar</a>
    <a href="{{ route('estoque.produtos.index') }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
    <div class="row g-3">

        <div class="col-md-4">

            {{-- Dados do produto --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Dados do Produto</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nome</dt>
                        <dd class="col-sm-7">{{ $produto->nome }}</dd>

                        <dt class="col-sm-5">Código</dt>
                        <dd class="col-sm-7">{{ $produto->codigo ?? '—' }}</dd>

                        <dt class="col-sm-5">Categoria</dt>
                        <dd class="col-sm-7">{{ $produto->categoria->nome ?? '—' }}</dd>

                        <dt class="col-sm-5">Fornecedor</dt>
                        <dd class="col-sm-7">{{ $produto->fornecedor->nome ?? '—' }}</dd>

                        <dt class="col-sm-5">Unidade</dt>
                        <dd class="col-sm-7">{{ $produto->unidade }}</dd>

                        <dt class="col-sm-5">Estoque Atual</dt>
                        <dd class="col-sm-7">
                            <span class="{{ $produto->estoque_baixo ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                {{ number_format($produto->estoque_atual, 2, ',', '.') }} {{ $produto->unidade }}
                            </span>
                        </dd>

                        <dt class="col-sm-5">Estoque Mín.</dt>
                        <dd class="col-sm-7">{{ number_format($produto->estoque_minimo, 2, ',', '.') }} {{ $produto->unidade }}</dd>

                        <dt class="col-sm-5">Valor Custo</dt>
                        <dd class="col-sm-7">R$ {{ number_format($produto->valor_custo, 2, ',', '.') }}</dd>
                    </dl>
                </div>
            </div>

            {{-- Movimentar estoque --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Movimentar Estoque</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('estoque.produtos.movimentar', $produto->id) }}" method="POST">
                        @csrf

                        <div class="mb-2">
                            <label class="form-label required">Tipo</label>
                            <select name="tipo" class="form-select form-select-sm">
                                <option value="entrada">Entrada</option>
                                <option value="saida">Saída</option>
                                <option value="ajuste">Ajuste de Estoque</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label required">Quantidade</label>
                            <input type="number" name="quantidade" min="0.01" step="0.01"
                                class="form-control form-control-sm @error('quantidade') is-invalid @enderror">
                            @error('quantidade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Valor Unitário (R$)</label>
                            <input type="number" name="valor_unitario" min="0" step="0.01"
                                class="form-control form-control-sm"
                                value="{{ $produto->valor_custo }}">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Motivo</label>
                            <input type="text" name="motivo" class="form-control form-control-sm"
                                placeholder="Ex: Compra, Uso em procedimento...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Documento</label>
                            <input type="text" name="documento" class="form-control form-control-sm"
                                placeholder="NF, pedido...">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrar</button>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Histórico de Movimentações</h3>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Quantidade</th>
                            <th>Ant.</th>
                            <th>Post.</th>
                            <th>Valor Unit.</th>
                            <th>Motivo</th>
                            <th>Usuário</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produto->movimentacoes as $mov)
                            <tr>
                                <td class="text-secondary">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($mov->tipo === 'entrada')
                                        <span class="badge bg-success text-white">Entrada</span>
                                    @elseif($mov->tipo === 'saida')
                                        <span class="badge bg-danger text-white">Saída</span>
                                    @else
                                        <span class="badge bg-secondary text-white">Ajuste</span>
                                    @endif
                                </td>
                                <td>{{ number_format($mov->quantidade, 2, ',', '.') }}</td>
                                <td class="text-secondary">{{ number_format($mov->estoque_anterior, 2, ',', '.') }}</td>
                                <td class="text-secondary">{{ number_format($mov->estoque_posterior, 2, ',', '.') }}</td>
                                <td class="text-secondary">
                                    {{ $mov->valor_unitario ? 'R$ ' . number_format($mov->valor_unitario, 2, ',', '.') : '—' }}
                                </td>
                                <td class="text-secondary">{{ $mov->motivo ?? '—' }}</td>
                                <td class="text-secondary">{{ $mov->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-secondary py-4">Nenhuma movimentação registrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection