<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dados do Agendamento</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label required">Paciente</label>
                <select name="paciente_id" class="form-select @error('paciente_id') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->id }}"
                            {{ old('paciente_id', $agendamento->paciente_id ?? '') == $paciente->id ? 'selected' : '' }}>
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
                            {{ old('dentista_id', $agendamento->dentista_id ?? '') == $dentista->id ? 'selected' : '' }}>
                            {{ $dentista->name }}
                        </option>
                    @endforeach
                </select>
                @error('dentista_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label required">Procedimento</label>
                <select name="procedimento_id" id="procedimento_id"
                    class="form-select @error('procedimento_id') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    @foreach($procedimentos as $procedimento)
                        <option value="{{ $procedimento->id }}"
                            data-duracao="{{ $procedimento->duracao_padrao_minutos }}"
                            {{ old('procedimento_id', $agendamento->procedimento_id ?? '') == $procedimento->id ? 'selected' : '' }}>
                            {{ $procedimento->nome }} ({{ $procedimento->duracao_formatada }})
                        </option>
                    @endforeach
                </select>
                @error('procedimento_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label required">Data e Hora</label>
                <input type="datetime-local" name="data_hora_inicio" id="data_hora_inicio"
                    class="form-control @error('data_hora_inicio') is-invalid @enderror"
                    value="{{ old('data_hora_inicio', isset($agendamento->data_hora_inicio) ? $agendamento->data_hora_inicio->format('Y-m-d\TH:i') : ($data_hora ?? '')) }}">
                @error('data_hora_inicio')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Duração</label>
                <input type="text" id="duracao_display" class="form-control" readonly
                    placeholder="Selecione um procedimento">
            </div>

            <div class="col-md-3">
                <label class="form-label required">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="agendado" {{ old('status', $agendamento->status ?? 'agendado') == 'agendado' ? 'selected' : '' }}>Agendado</option>
                    <option value="confirmado" {{ old('status', $agendamento->status ?? '') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                    <option value="cancelado" {{ old('status', $agendamento->status ?? '') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    <option value="concluido" {{ old('status', $agendamento->status ?? '') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                    <option value="falta" {{ old('status', $agendamento->status ?? '') == 'falta' ? 'selected' : '' }}>Falta</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" rows="3"
                    class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes', $agendamento->observacoes ?? '') }}</textarea>
                @error('observacoes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ route('agendamentos.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

@push('scripts')
<script>
    // Atualiza display de duração ao selecionar procedimento
    document.getElementById('procedimento_id').addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        const duracao = option.dataset.duracao;
        const display = document.getElementById('duracao_display');

        if (duracao) {
            const horas = Math.floor(duracao / 60);
            const minutos = duracao % 60;
            display.value = horas > 0
                ? `${horas}h ${minutos > 0 ? minutos + 'min' : ''}`
                : `${minutos}min`;
        } else {
            display.value = '';
        }
    });

    // Dispara ao carregar para preencher duração no edit
    document.getElementById('procedimento_id').dispatchEvent(new Event('change'));
</script>
@endpush