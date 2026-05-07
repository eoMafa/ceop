<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dados Pessoais</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label required">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror"
                    value="{{ old('nome', $paciente->nome ?? '') }}">
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">CPF</label>
                <input type="text" name="cpf" id="cpf" class="form-control @error('cpf') is-invalid @enderror cpf-cnpj"
                    value="{{ old('cpf', $paciente->cpf ?? '') }}" placeholder="000.000.000-00" maxlength="14">
                @error('cpf')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">RG</label>
                <input type="text" name="rg" id="rg" class="form-control @error('rg') is-invalid @enderror"
                    value="{{ old('rg', $paciente->rg ?? '') }}">
                @error('rg')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento"
                    class="form-control @error('data_nascimento') is-invalid @enderror"
                    value="{{ old('data_nascimento', isset($paciente->data_nascimento) ? $paciente->data_nascimento->format('Y-m-d') : '') }}">
                @error('data_nascimento')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Sexo</label>
                <select name="sexo" id="sexo" class="form-select @error('sexo') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    <option value="M" {{ old('sexo', $paciente->sexo ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ old('sexo', $paciente->sexo ?? '') == 'F' ? 'selected' : '' }}>Feminino</option>
                    <option value="outro" {{ old('sexo', $paciente->sexo ?? '') == 'outro' ? 'selected' : '' }}>Outro</option>
                </select>
                @error('sexo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" id="telefone" class="form-control @error('telefone') is-invalid @enderror telefone"
                    value="{{ old('telefone', $paciente->telefone ?? '') }}" placeholder="(00) 00000-0000">
                @error('telefone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $paciente->email ?? '') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Endereço</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-2">
                <label class="form-label">CEP</label>
                <input type="text" name="cep" id="cep"
                    class="form-control @error('cep') is-invalid @enderror cep viacep"
                    data-logradouro="#logradouro"
                    data-bairro="#bairro"
                    data-cidade="#cidade"
                    data-estado="#estado"
                    value="{{ old('cep', $paciente->cep ?? '') }}" placeholder="00000-000">
                @error('cep')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-5">
                <label class="form-label">Logradouro</label>
                <input type="text" name="logradouro" id="logradouro"
                    class="form-control @error('logradouro') is-invalid @enderror"
                    value="{{ old('logradouro', $paciente->logradouro ?? '') }}">
                @error('logradouro')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label">Número</label>
                <input type="text" name="numero" id="numero" class="form-control @error('numero') is-invalid @enderror"
                    value="{{ old('numero', $paciente->numero ?? '') }}">
                @error('numero')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Complemento</label>
                <input type="text" name="complemento" id="complemento"
                    class="form-control @error('complemento') is-invalid @enderror"
                    value="{{ old('complemento', $paciente->complemento ?? '') }}">
                @error('complemento')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Bairro</label>
                <input type="text" name="bairro" id="bairro"
                    class="form-control @error('bairro') is-invalid @enderror"
                    value="{{ old('bairro', $paciente->bairro ?? '') }}">
                @error('bairro')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Cidade</label>
                <input type="text" name="cidade" id="cidade"
                    class="form-control @error('cidade') is-invalid @enderror"
                    value="{{ old('cidade', $paciente->cidade ?? '') }}">
                @error('cidade')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label">Estado</label>
                <input type="text" name="estado" id="estado"
                    class="form-control @error('estado') is-invalid @enderror"
                    value="{{ old('estado', $paciente->estado ?? '') }}" maxlength="2" placeholder="GO">
                @error('estado')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Observações</h3>
    </div>
    <div class="card-body">
        <textarea name="observacoes" id="observacoes" class="form-control @error('observacoes') is-invalid @enderror"
            rows="3">{{ old('observacoes', $paciente->observacoes ?? '') }}</textarea>
        @error('observacoes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

@push('scripts')

@endpush