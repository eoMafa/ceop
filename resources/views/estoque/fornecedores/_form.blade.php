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
                    class="form-control @error('cnpj') is-invalid @enderror"
                    value="{{ old('cnpj', $fornecedor->cnpj ?? '') }}"
                    placeholder="00.000.000/0000-00">
                @error('cnpj') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" id="telefone_fornecedor"
                    class="form-control @error('telefone') is-invalid @enderror"
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
                    class="form-control"
                    value="{{ old('cep', $fornecedor->cep ?? '') }}"
                    placeholder="00000-000">
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
<script>
    // Máscara CNPJ
    if (document.getElementById('cnpj')) {
        new Cleave('#cnpj', {
            delimiters: ['.', '.', '/', '-'],
            blocks: [2, 3, 3, 4, 2],
            numericOnly: true,
        });
    }

    // Máscara telefone fornecedor
    if (document.getElementById('telefone_fornecedor')) {
        new Cleave('#telefone_fornecedor', {
            delimiters: ['(', ') ', '-'],
            blocks: [0, 2, 5, 4],
            numericOnly: true,
        });
    }

    // ViaCEP fornecedor
    document.getElementById('cep_fornecedor').addEventListener('blur', function () {
        const cep = this.value.replace(/\D/g, '');
        if (cep.length !== 8) return;
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(r => r.json())
            .then(data => {
                if (data.erro) return;
                document.getElementById('logradouro_fornecedor').value = data.logradouro;
                document.getElementById('bairro_fornecedor').value = data.bairro;
                document.getElementById('cidade_fornecedor').value = data.localidade;
                document.getElementById('estado_fornecedor').value = data.uf;
            });
    });
</script>
@endpush