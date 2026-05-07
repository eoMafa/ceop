@extends('layouts.app')

@section('title', 'Fornecedor: ' . $fornecedor->nome)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('estoque.fornecedores.index') }}">Fornecedores</a></li>
    <li class="breadcrumb-item active">{{ $fornecedor->nome }}</li>
@endsection

@section('actions')
    <a href="{{ route('estoque.fornecedores.edit', $fornecedor->id) }}" class="btn btn-primary">Editar</a>
    <a href="{{ route('estoque.fornecedores.index') }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dados do Fornecedor</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nome</dt>
                        <dd class="col-sm-8">{{ $fornecedor->nome }}</dd>

                        <dt class="col-sm-4">CNPJ</dt>
                        <dd class="col-sm-8">{{ $fornecedor->cnpj ?? '—' }}</dd>

                        <dt class="col-sm-4">Telefone</dt>
                        <dd class="col-sm-8">{{ $fornecedor->telefone ?? '—' }}</dd>

                        <dt class="col-sm-4">E-mail</dt>
                        <dd class="col-sm-8">{{ $fornecedor->email ?? '—' }}</dd>

                        <dt class="col-sm-4">Contato</dt>
                        <dd class="col-sm-8">{{ $fornecedor->contato ?? '—' }}</dd>

                        <dt class="col-sm-4">Endereço</dt>
                        <dd class="col-sm-8">
                            {{ $fornecedor->logradouro ?? '—' }}
                            {{ $fornecedor->numero ? ', ' . $fornecedor->numero : '' }}
                            {{ $fornecedor->cidade ? ' - ' . $fornecedor->cidade . '/' . $fornecedor->estado : '' }}
                        </dd>

                        @if($fornecedor->observacoes)
                            <dt class="col-sm-4">Observações</dt>
                            <dd class="col-sm-8">{{ $fornecedor->observacoes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Produtos vinculados</h3>
                </div>
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Estoque</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fornecedor->produtos as $produto)
                            <tr>
                                <td>{{ $produto->nome }}</td>
                                <td>{{ $produto->estoque_atual }} {{ $produto->unidade }}</td>
                                <td>
                                    <a href="{{ route('estoque.produtos.show', $produto->id) }}"
                                        class="btn btn-sm btn-secondary">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-3">Nenhum produto vinculado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection