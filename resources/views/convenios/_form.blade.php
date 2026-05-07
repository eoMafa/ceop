<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dados do Convênio</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label required">Nome</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                    value="{{ old('nome', $convenio->nome ?? '') }}">
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label required">Desconto (%)</label>
                <div class="input-group">
                    <input type="number" name="desconto_percentual" min="0" max="100" step="0.01"
                        class="form-control @error('desconto_percentual') is-invalid @enderror"
                        value="{{ old('desconto_percentual', $convenio->desconto_percentual ?? '0') }}">
                    <span class="input-group-text">%</span>
                </div>
                @error('desconto_percentual')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ route('convenios.index') }}" class="btn btn-secondary">Cancelar</a>
</div>