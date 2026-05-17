<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Dados Gerais</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label required">Paciente</label>
                <select name="paciente_id" id="paciente_id"
                    class="form-select @error('paciente_id') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->id }}"
                            {{ old('paciente_id', $orcamento->paciente_id ?? $paciente_id ?? '') == $paciente->id ? 'selected' : '' }}>
                            {{ $paciente->nome }}
                        </option>
                    @endforeach
                </select>
                @error('paciente_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label required">Dentista</label>
                <select name="dentista_id" class="form-select @error('dentista_id') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    @foreach($dentistas as $dentista)
                        <option value="{{ $dentista->id }}"
                            {{ old('dentista_id', $orcamento->dentista_id ?? '') == $dentista->id ? 'selected' : '' }}>
                            {{ $dentista->name }}
                        </option>
                    @endforeach
                </select>
                @error('dentista_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipo de Desconto</label>
                <select name="desconto_tipo" id="desconto_tipo"
                    class="form-select @error('desconto_tipo') is-invalid @enderror">
                    <option value="nenhum" {{ old('desconto_tipo', $orcamento->desconto_tipo ?? 'nenhum') == 'nenhum' ? 'selected' : '' }}>Nenhum</option>
                    <option value="percentual" {{ old('desconto_tipo', $orcamento->desconto_tipo ?? '') == 'percentual' ? 'selected' : '' }}>Percentual (%)</option>
                    <option value="valor_fixo" {{ old('desconto_tipo', $orcamento->desconto_tipo ?? '') == 'valor_fixo' ? 'selected' : '' }}>Valor Fixo (R$)</option>
                    <option value="convenio" {{ old('desconto_tipo', $orcamento->desconto_tipo ?? '') == 'convenio' ? 'selected' : '' }}>Convênio</option>
                </select>
            </div>

            <div class="col-md-4" id="campo_desconto_valor" style="display:none">
                <label class="form-label">Valor do Desconto</label>
                <input type="number" name="desconto_valor" min="0" step="0.01"
                    class="form-control"
                    value="{{ old('desconto_valor', $orcamento->desconto_valor ?? '0') }}">
            </div>

            <div class="col-md-4" id="campo_convenio" style="display:none">
                <label class="form-label">Convênio</label>
                <select name="convenio_id" class="form-select">
                    <option value="">Selecione...</option>
                    @foreach($convenios as $convenio)
                        <option value="{{ $convenio->id }}"
                            {{ old('convenio_id', $orcamento->convenio_id ?? '') == $convenio->id ? 'selected' : '' }}>
                            {{ $convenio->nome }} ({{ $convenio->desconto_percentual }}%)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" rows="2"
                    class="form-control">{{ old('observacoes', $orcamento->observacoes ?? '') }}</textarea>
            </div>

            {{-- Arquivos da ficha --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">📎 Ficha do Paciente</h3>
                </div>
                <div class="card-body">

                    {{-- Arquivos existentes no edit --}}
                    @if(isset($orcamento->id) && $orcamento->arquivos->count() > 0)
                        <div class="row g-2 mb-3">
                            @foreach($orcamento->arquivos as $arquivo)
                                <div class="col-auto">
                                    <div class="card card-sm">
                                        <div class="card-body p-2 text-center">
                                            @if(str_starts_with($arquivo->tipo_mime, 'image/'))
                                                <a href="{{ $arquivo->url }}" target="_blank">
                                                    <img src="{{ $arquivo->url }}"
                                                        style="width:100px;height:100px;object-fit:cover;border-radius:4px;">
                                                </a>
                                            @else
                                                <a href="{{ $arquivo->url }}" target="_blank"
                                                    class="btn btn-sm btn-secondary">
                                                    📄 {{ Str::limit($arquivo->nome_original, 20) }}
                                                </a>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <small class="text-secondary">{{ $arquivo->tamanho_formatado }}</small>
                                                <form action="{{ route('orcamentos.arquivos.destroy', $arquivo->id) }}"
                                                    method="POST"
                                                    data-confirm="Remover este arquivo?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-ghost-danger py-0">✕</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <label class="form-label">Adicionar Fotos da Ficha</label>
                    <input type="file" name="arquivos[]" multiple
                        class="form-control @error('arquivos.*') is-invalid @enderror"
                        accept="image/*,.pdf">
                    <small class="text-secondary">
                        Aceita imagens e PDF. Máximo 10MB por arquivo.
                    </small>
                    @error('arquivos.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Itens --}}
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Procedimentos</h3>
        <div class="card-options">
            <button type="button" class="btn btn-sm btn-primary" id="btn-add-item">+ Adicionar</button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-vcenter mb-0" id="tabela-itens">
            <thead>
                <tr>
                    <th>Procedimento</th>
                    <th style="width:100px">Dente</th>
                    <th style="width:80px">Qtd</th>
                    <th style="width:130px">Valor Unit.</th>
                    <th style="width:130px">Total</th>
                    <th style="width:40px"></th>
                </tr>
            </thead>
            <tbody id="itens-container">
                @php
                    $itensExistentes = old('itens', isset($orcamento) ? $orcamento->itens->toArray() : [[]]);
                @endphp
                @foreach($itensExistentes as $index => $item)
                    <tr class="item-row">
                        <td>
                            <select name="itens[{{ $index }}][procedimento_id]"
                                class="form-select form-select-sm select-procedimento">
                                <option value="">Selecione...</option>
                                @foreach($procedimentos as $proc)
                                    <option value="{{ $proc->id }}"
                                        data-valor="{{ $proc->valor_padrao }}"
                                        {{ ($item['procedimento_id'] ?? '') == $proc->id ? 'selected' : '' }}>
                                        {{ $proc->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="itens[{{ $index }}][dente]"
                                class="form-control form-control-sm"
                                value="{{ $item['dente'] ?? '' }}"
                                placeholder="ex: 11">
                        </td>
                        <td>
                            <input type="number" name="itens[{{ $index }}][quantidade]"
                                class="form-control form-control-sm input-quantidade"
                                value="{{ $item['quantidade'] ?? 1 }}" min="1">
                        </td>
                        <td>
                            <input type="number" name="itens[{{ $index }}][valor_unitario]"
                                class="form-control form-control-sm input-valor"
                                value="{{ $item['valor_unitario'] ?? '' }}"
                                step="0.01" min="0">
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm input-total"
                                value="{{ isset($item['valor_total']) ? number_format($item['valor_total'], 2, ',', '.') : '' }}"
                                readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-ghost-danger btn-remover-item">✕</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end"><strong>Total</strong></td>
                    <td><strong id="total-geral">R$ 0,00</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button type="submit" class="btn btn-primary">Salvar Orçamento</button>
    <a href="{{ route('orcamentos.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

@push('scripts')
<script>
    let itemIndex = {{ count($itensExistentes ?? [[]]) }};

    // Template de nova linha
    function novaLinha(index) {
        return `
        <tr class="item-row">
            <td>
                <select name="itens[${index}][procedimento_id]" class="form-select form-select-sm select-procedimento">
                    <option value="">Selecione...</option>
                    @foreach($procedimentos as $proc)
                    <option value="{{ $proc->id }}" data-valor="{{ $proc->valor_padrao }}">{{ $proc->nome }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="itens[${index}][dente]" class="form-control form-control-sm" placeholder="ex: 11"></td>
            <td><input type="number" name="itens[${index}][quantidade]" class="form-control form-control-sm input-quantidade" value="1" min="1"></td>
            <td><input type="number" name="itens[${index}][valor_unitario]" class="form-control form-control-sm input-valor" step="0.01" min="0"></td>
            <td><input type="text" class="form-control form-control-sm input-total" readonly></td>
            <td><button type="button" class="btn btn-sm btn-ghost-danger btn-remover-item">✕</button></td>
        </tr>`;
    }

    // Adicionar item
    document.getElementById('btn-add-item').addEventListener('click', function () {
        document.getElementById('itens-container').insertAdjacentHTML('beforeend', novaLinha(itemIndex++));
        bindEventos();
        calcularTotal();
    });

    // Calcular total da linha e geral
    function calcularLinha(row) {
        const qtd = parseFloat(row.querySelector('.input-quantidade').value) || 0;
        const valor = parseFloat(row.querySelector('.input-valor').value) || 0;
        const total = qtd * valor;
        row.querySelector('.input-total').value = total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
        return total;
    }

    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            total += calcularLinha(row);
        });
        document.getElementById('total-geral').textContent = 'R$ ' + total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    }

    function bindEventos() {
        // Selecionar procedimento preenche valor
        document.querySelectorAll('.select-procedimento').forEach(sel => {
            sel.onchange = function () {
                const option = this.options[this.selectedIndex];
                const valor = option.dataset.valor || 0;
                const row = this.closest('.item-row');
                row.querySelector('.input-valor').value = valor;
                calcularLinha(row);
                calcularTotal();
            };
        });

        // Recalcular ao mudar qtd ou valor
        document.querySelectorAll('.input-quantidade, .input-valor').forEach(input => {
            input.oninput = function () {
                calcularLinha(this.closest('.item-row'));
                calcularTotal();
            };
        });

        // Remover linha
        document.querySelectorAll('.btn-remover-item').forEach(btn => {
            btn.onclick = function () {
                this.closest('.item-row').remove();
                calcularTotal();
            };
        });
    }

    // Desconto tipo
    document.getElementById('desconto_tipo').addEventListener('change', function () {
        document.getElementById('campo_desconto_valor').style.display =
            ['percentual', 'valor_fixo'].includes(this.value) ? '' : 'none';
        document.getElementById('campo_convenio').style.display =
            this.value === 'convenio' ? '' : 'none';
    });

    // Inicializa
    bindEventos();
    calcularTotal();

    // Dispara desconto_tipo ao carregar
    document.getElementById('desconto_tipo').dispatchEvent(new Event('change'));
</script>
@endpush