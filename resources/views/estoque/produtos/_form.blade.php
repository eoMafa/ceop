<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Dados do Produto</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label required">Nome</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                    value="{{ old('nome', $produto->nome ?? '') }}">
                @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Código</label>
                <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                    value="{{ old('codigo', $produto->codigo ?? '') }}">
                @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label required">Unidade</label>
                <select name="unidade" class="form-select @error('unidade') is-invalid @enderror">
                    @foreach(['un' => 'Unidade', 'cx' => 'Caixa', 'ml' => 'Mililitro', 'g' => 'Grama', 'kg' => 'Quilograma', 'l' => 'Litro', 'pct' => 'Pacote', 'rolo' => 'Rolo'] as $valor => $label)
                        <option value="{{ $valor }}" {{ old('unidade', $produto->unidade ?? 'un') == $valor ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('unidade') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Categoria</label>
                <select name="categoria_estoque_id" class="form-select">
                    <option value="">Sem categoria</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}"
                            {{ old('categoria_estoque_id', $produto->categoria_estoque_id ?? '') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fornecedor</label>
                <select name="fornecedor_id" class="form-select">
                    <option value="">Sem fornecedor</option>
                    @foreach($fornecedores as $fornecedor)
                        <option value="{{ $fornecedor->id }}"
                            {{ old('fornecedor_id', $produto->fornecedor_id ?? '') == $fornecedor->id ? 'selected' : '' }}>
                            {{ $fornecedor->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label required">Valor de Custo (R$)</label>
                <div class="input-group">
                    <span class="input-group-text">R$</span>
                    <input type="number" name="valor_custo" min="0" step="0.01"
                        class="form-control @error('valor_custo') is-invalid @enderror"
                        value="{{ old('valor_custo', $produto->valor_custo ?? '0') }}">
                </div>
                @error('valor_custo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label required">Estoque Atual</label>
                <input type="number" name="estoque_atual" min="0" step="0.01"
                    class="form-control @error('estoque_atual') is-invalid @enderror"
                    value="{{ old('estoque_atual', $produto->estoque_atual ?? '0') }}"
                    {{ isset($produto->id) ? 'readonly' : '' }}>
                @if(isset($produto->id))
                    <small class="text-secondary">Use movimentações para alterar o estoque.</small>
                @endif
                @error('estoque_atual') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label required">Estoque Mínimo</label>
                <input type="number" name="estoque_minimo" min="0" step="0.01"
                    class="form-control @error('estoque_minimo') is-invalid @enderror"
                    value="{{ old('estoque_minimo', $produto->estoque_minimo ?? '0') }}">
                @error('estoque_minimo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" rows="2" class="form-control">{{ old('descricao', $produto->descricao ?? '') }}</textarea>
            </div>

        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ route('estoque.produtos.index') }}" class="btn btn-secondary">Cancelar</a>
</div>