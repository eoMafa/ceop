{{-- Dados principais --}}
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Dados da Consulta</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">

            {{-- Paciente --}}
            @if(!isset($evolucao->id))
                <div class="col-md-6">
                    <label class="form-label required">Paciente</label>
                    <select name="paciente_id" id="paciente_id"
                        class="form-select @error('paciente_id') is-invalid @enderror">
                        <option value="">Selecione...</option>
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->id }}"
                                {{ old('paciente_id', $agendamento?->paciente_id ?? $paciente_id ?? '') == $paciente->id ? 'selected' : '' }}>
                                {{ $paciente->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('paciente_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <input type="hidden" name="paciente_id" value="{{ $evolucao->prontuario->paciente_id }}">
                <div class="col-md-6">
                    <label class="form-label">Paciente</label>
                    <input type="text" class="form-control" readonly
                        value="{{ $evolucao->prontuario->paciente->nome }}">
                </div>
            @endif

            {{-- Dentista --}}
            <div class="col-md-6">
                <label class="form-label required">Dentista</label>
                <select name="dentista_id" class="form-select @error('dentista_id') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    @foreach($dentistas as $dentista)
                        <option value="{{ $dentista->id }}"
                            {{ old('dentista_id', $agendamento?->dentista_id ?? $evolucao->dentista_id ?? '') == $dentista->id ? 'selected' : '' }}>
                            {{ $dentista->name }}
                        </option>
                    @endforeach
                </select>
                @error('dentista_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Procedimento --}}
            <div class="col-md-4">
                <label class="form-label">Procedimento</label>
                <select name="procedimento_id" class="form-select @error('procedimento_id') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    @foreach($procedimentos as $proc)
                        <option value="{{ $proc->id }}"
                            {{ old('procedimento_id', $agendamento?->procedimento_id ?? $evolucao->procedimento_id ?? '') == $proc->id ? 'selected' : '' }}>
                            {{ $proc->nome }}
                        </option>
                    @endforeach
                </select>
                @error('procedimento_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Dente --}}
            <div class="col-md-2">
                <label class="form-label">Dente (FDI)</label>
                <input type="text" name="dente"
                    class="form-control @error('dente') is-invalid @enderror"
                    placeholder="ex: 11, 36"
                    value="{{ old('dente', $evolucao->dente ?? '') }}">
                @error('dente')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Face --}}
            <div class="col-md-2">
                <label class="form-label">Face</label>
                <select name="face" class="form-select">
                    <option value="">—</option>
                    @foreach(['vestibular', 'lingual', 'oclusal', 'mesial', 'distal', 'cervical'] as $face)
                        <option value="{{ $face }}"
                            {{ old('face', $evolucao->face ?? '') == $face ? 'selected' : '' }}>
                            {{ ucfirst($face) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Orçamento --}}
            <div class="col-md-4">
                <label class="form-label">Orçamento Vinculado</label>
                <select name="orcamento_id" id="orcamento_id" class="form-select">
                    <option value="">Nenhum</option>
                    @foreach($orcamentos as $orc)
                        <option value="{{ $orc->id }}"
                            {{ old('orcamento_id', $agendamento?->orcamento_id ?? $evolucao->orcamento_id ?? '') == $orc->id ? 'selected' : '' }}>
                            #{{ $orc->id }} — R$ {{ number_format($orc->total_liquido, 2, ',', '.') }}
                            ({{ $orc->created_at->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Agendamento hidden --}}
            @if(isset($agendamento) && $agendamento)
                <input type="hidden" name="agendamento_id" value="{{ $agendamento->id }}">
            @endif

            {{-- Descrição --}}
            <div class="col-12">
                <label class="form-label required">Descrição da Consulta</label>
                <textarea name="descricao" rows="5"
                    class="form-control @error('descricao') is-invalid @enderror"
                    placeholder="Descreva detalhadamente o que foi realizado na consulta...">{{ old('descricao', $evolucao->descricao ?? '') }}</textarea>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>

{{-- Materiais --}}
@if(!isset($evolucao->id))
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">Materiais Utilizados</h3>
            <button type="button" class="btn btn-sm btn-secondary ms-auto" id="btn-add-material">
                + Adicionar Material
            </button>
        </div>
        <div class="card-body p-0">
            <div id="materiais-container"></div>
        </div>
    </div>
@endif

{{-- Arquivos --}}
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Arquivos</h3>
    </div>
    <div class="card-body">

        {{-- Arquivos existentes no edit --}}
        @if(isset($evolucao->id) && $evolucao->arquivos->count() > 0)
            <div class="row g-2 mb-3">
                @foreach($evolucao->arquivos as $arquivo)
                    <div class="col-auto">
                        <div class="card card-sm">
                            <div class="card-body p-2">
                                @if($arquivo->tipo === 'imagem')
                                    <a href="{{ $arquivo->url }}" target="_blank">
                                        <img src="{{ $arquivo->url }}"
                                            style="width:80px;height:80px;object-fit:cover;border-radius:4px;">
                                    </a>
                                @else
                                    <a href="{{ $arquivo->url }}" target="_blank"
                                        class="btn btn-sm btn-secondary">
                                        {{ $arquivo->tipo === 'video' ? '🎥' : '📄' }}
                                        {{ Str::limit($arquivo->nome_original, 15) }}
                                    </a>
                                @endif
                                <div class="mt-1">
                                    <form action="{{ route('evolucoes.arquivos.destroy', $arquivo->id) }}"
                                        method="POST" data-confirm="Remover este arquivo?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-ghost-danger w-100">
                                            Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <label class="form-label">Adicionar Arquivos</label>
        <input type="file" name="arquivos[]" multiple
            class="form-control @error('arquivos.*') is-invalid @enderror"
            accept="image/*,video/*,.pdf">
        <small class="text-secondary">Máximo 100MB por arquivo. Aceita imagens, vídeos e PDF.</small>
        @error('arquivos.*')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button type="submit" class="btn btn-primary">Salvar Consulta</button>
    <a href="{{ route('evolucoes.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

@push('scripts')
<script>
@php
    $produtosEstoque = \App\Models\Produto::whereRaw('estoque_atual > 0')
        ->orderBy('nome')
        ->get(['id', 'nome', 'unidade', 'estoque_atual']);
@endphp
const produtos = @json($produtosEstoque);

let materialIndex = 0;

const btnAddMaterial = document.getElementById('btn-add-material');
if (btnAddMaterial) {
    btnAddMaterial.addEventListener('click', function () {
        const container = document.getElementById('materiais-container');

        const options = produtos.map(p =>
            `<option value="${p.id}" data-unidade="${p.unidade}" data-estoque="${p.estoque_atual}">
                ${p.nome} (${parseFloat(p.estoque_atual).toLocaleString('pt-BR')} ${p.unidade} disponível)
            </option>`
        ).join('');

        const html = `
            <div class="row g-2 p-3 border-bottom material-row align-items-center">
                <div class="col-md-7">
                    <select name="materiais[${materialIndex}][produto_id]"
                        class="form-select select-produto">
                        <option value="">Selecione o produto...</option>
                        ${options}
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="number" name="materiais[${materialIndex}][quantidade]"
                            class="form-control input-quantidade"
                            placeholder="Qtd" min="0.01" step="0.01">
                        <span class="input-group-text unidade-label">un</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-ghost-danger w-100 btn-remover-material">✕ Remover</button>
                </div>
            </div>`;

        container.insertAdjacentHTML('beforeend', html);
        materialIndex++;

        const rows = container.querySelectorAll('.material-row');
        const lastRow = rows[rows.length - 1];

        lastRow.querySelector('.select-produto').addEventListener('change', function () {
            const option = this.options[this.selectedIndex];
            lastRow.querySelector('.unidade-label').textContent = option.dataset.unidade || 'un';
        });

        lastRow.querySelector('.btn-remover-material').addEventListener('click', function () {
            lastRow.remove();
        });
    });
}

// Carrega orçamentos ao trocar paciente
const selectPaciente = document.getElementById('paciente_id');
if (selectPaciente) {
    selectPaciente.addEventListener('change', function () {
        const pacienteId = this.value;
        const select = document.getElementById('orcamento_id');
        select.innerHTML = '<option value="">Nenhum</option>';

        if (!pacienteId) return;

        fetch(`/evolucoes/orcamentos-por-paciente?paciente_id=${pacienteId}`)
            .then(r => r.json())
            .then(data => {
                data.forEach(orc => {
                    const valor = parseFloat(orc.total_liquido).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                    select.innerHTML += `<option value="${orc.id}">#${orc.id} — R$ ${valor}</option>`;
                });
            });
    });
}
</script>
@endpush