<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dados do Procedimento</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label required">Nome</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                    value="{{ old('nome', $procedimento->nome ?? '') }}">
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label required">Duração (minutos)</label>
                <input type="number" name="duracao_padrao_minutos" min="5" max="480"
                    class="form-control @error('duracao_padrao_minutos') is-invalid @enderror"
                    value="{{ old('duracao_padrao_minutos', $procedimento->duracao_padrao_minutos ?? 30) }}">
                @error('duracao_padrao_minutos')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label required">Valor Padrão (R$)</label>
                <div class="input-group">
                    <span class="input-group-text">R$</span>
                    <input type="number" name="valor_padrao" min="0" step="0.01"
                        class="form-control @error('valor_padrao') is-invalid @enderror"
                        value="{{ old('valor_padrao', $procedimento->valor_padrao ?? '0.00') }}">
                </div>
                @error('valor_padrao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" rows="3"
                    class="form-control @error('descricao') is-invalid @enderror">{{ old('descricao', $procedimento->descricao ?? '') }}</textarea>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ route('procedimentos.index') }}" class="btn btn-secondary">Cancelar</a>
</div>