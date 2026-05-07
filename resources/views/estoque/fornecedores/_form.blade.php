<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Dados do Fornecedor</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label required">Nome</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                    value="{{ old('nome', $fornecedor->nome ?? '') }}">
                @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">CNPJ</label>
                <input type="text" name="cnpj" id="cnpj"
                    class="form-control @error('cnpj') is-invalid @enderror cpf-cnpj"
                    value="{{ old('cnpj', $fornecedor->cnpj ?? '') }}"
                    placeholder="00.000.000/0000-00">
                @error('cnpj') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" id="telefone"
                    class="form-control @error('telefone') is-invalid @enderror telefone"
                    value="{{ old('telefone', $fornecedor->telefone ?? '') }}"
                    placeholder="(00) 00000-0000">
                @error('telefone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $fornecedor->email ?? '') }}">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Pessoa de Contato</label>
                <input type="text" name="contato" class="form-control"
                    value="{{ old('contato', $fornecedor->contato ?? '') }}">
            </div>

        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Endereço</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-2">
                <label class="form-label">CEP</label>
                <input type="text" name="cep" id="cep_fornecedor"
                    class="form-control cep viacep"
                    value="{{ old('cep', $fornecedor->cep ?? '') }}"
                    placeholder="00000-000"
                    data-logradouro="#logradouro_fornecedor"
                    data-bairro="#bairro_fornecedor"
                    data-cidade="#cidade_fornecedor"
                    data-estado="#estado_fornecedor">
            </div>

            <div class="col-md-5">
                <label class="form-label">Logradouro</label>
                <input type="text" name="logradouro" id="logradouro_fornecedor"
                    class="form-control"
                    value="{{ old('logradouro', $fornecedor->logradouro ?? '') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">Número</label>
                <input type="text" name="numero" class="form-control"
                    value="{{ old('numero', $fornecedor->numero ?? '') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Complemento</label>
                <input type="text" name="complemento" class="form-control"
                    value="{{ old('complemento', $fornecedor->complemento ?? '') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Bairro</label>
                <input type="text" name="bairro" id="bairro_fornecedor"
                    class="form-control"
                    value="{{ old('bairro', $fornecedor->bairro ?? '') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Cidade</label>
                <input type="text" name="cidade" id="cidade_fornecedor"
                    class="form-control"
                    value="{{ old('cidade', $fornecedor->cidade ?? '') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">Estado</label>
                <input type="text" name="estado" id="estado_fornecedor"
                    class="form-control" maxlength="2" placeholder="GO"
                    value="{{ old('estado', $fornecedor->estado ?? '') }}">
            </div>

        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Observações</h3>
    </div>
    <div class="card-body">
        <textarea name="observacoes" rows="3" class="form-control">{{ old('observacoes', $fornecedor->observacoes ?? '') }}</textarea>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ route('estoque.fornecedores.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

@push('scripts')

@endpush