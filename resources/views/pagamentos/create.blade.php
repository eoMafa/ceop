@extends('layouts.app')

@section('title', 'Novo Pagamento')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pagamentos.index') }}">Pagamentos</a></li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
    <form action="{{ route('pagamentos.store') }}" method="POST">
        @csrf

        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Dados do Pagamento</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label required">Paciente</label>
                        <select name="paciente_id" class="form-select @error('paciente_id') is-invalid @enderror">
                            <option value="">Selecione...</option>
                            @foreach($pacientes as $paciente)
                                <option value="{{ $paciente->id }}"
                                    {{ old('paciente_id', $paciente_id ?? '') == $paciente->id ? 'selected' : '' }}>
                                    {{ $paciente->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('paciente_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($orcamento)
                        <input type="hidden" name="orcamento_id" value="{{ $orcamento->id }}">
                        <div class="col-md-6">
                            <label class="form-label">Orçamento vinculado</label>
                            <input type="text" class="form-control" readonly
                                value="#{{ $orcamento->id }} — R$ {{ number_format($orcamento->total_liquido, 2, ',', '.') }}">
                        </div>
                    @endif

                    <div class="col-md-8">
                        <label class="form-label required">Descrição</label>
                        <input type="text" name="descricao"
                            class="form-control @error('descricao') is-invalid @enderror"
                            value="{{ old('descricao', isset($orcamento) ? 'Orçamento #' . $orcamento->id : '') }}"
                            placeholder="Ex: Tratamento de canal dente 36">
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">Valor Total (R$)</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" name="valor_total" min="0.01" step="0.01"
                                class="form-control @error('valor_total') is-invalid @enderror"
                                value="{{ old('valor_total', isset($orcamento) ? $orcamento->total_liquido : '') }}">
                        </div>
                        @error('valor_total')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">Forma de Pagamento</label>
                        <select name="forma_pagamento"
                            class="form-select @error('forma_pagamento') is-invalid @enderror">
                            <option value="">Selecione...</option>
                            <option value="dinheiro" {{ old('forma_pagamento') == 'dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                            <option value="cartao_credito" {{ old('forma_pagamento') == 'cartao_credito' ? 'selected' : '' }}>Cartão de Crédito</option>
                            <option value="cartao_debito" {{ old('forma_pagamento') == 'cartao_debito' ? 'selected' : '' }}>Cartão de Débito</option>
                            <option value="pix" {{ old('forma_pagamento') == 'pix' ? 'selected' : '' }}>PIX</option>
                            <option value="convenio" {{ old('forma_pagamento') == 'convenio' ? 'selected' : '' }}>Convênio</option>
                            <option value="boleto" {{ old('forma_pagamento') == 'boleto' ? 'selected' : '' }}>Boleto</option>
                        </select>
                        @error('forma_pagamento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">Número de Parcelas</label>
                        <input type="number" name="numero_parcelas" min="1" max="60"
                            class="form-control @error('numero_parcelas') is-invalid @enderror"
                            value="{{ old('numero_parcelas', 1) }}">
                        @error('numero_parcelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">Primeiro Vencimento</label>
                        <input type="date" name="data_primeiro_vencimento"
                            class="form-control @error('data_primeiro_vencimento') is-invalid @enderror"
                            value="{{ old('data_primeiro_vencimento', now()->format('Y-m-d')) }}">
                        @error('data_primeiro_vencimento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Observações</label>
                        <textarea name="observacoes" rows="2" class="form-control">{{ old('observacoes') }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Registrar Pagamento</button>
            <a href="{{ route('pagamentos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection