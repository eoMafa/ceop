@extends('layouts.app')

@section('title', 'Prontuário - ' . $paciente->nome)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pacientes.index') }}">Pacientes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pacientes.show', $paciente->id) }}">{{ $paciente->nome }}</a></li>
    <li class="breadcrumb-item active">Prontuário</li>
@endsection

@section('actions')
    <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
    <div class="row g-3">

        {{-- Coluna esquerda: Dados + Anamnese --}}
        <div class="col-md-4">

            {{-- Dados do Paciente --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Dados do Paciente</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nome</dt>
                        <dd class="col-sm-7">{{ $paciente->nome }}</dd>

                        <dt class="col-sm-5">Nascimento</dt>
                        <dd class="col-sm-7">
                            {{ $paciente->data_nascimento ? $paciente->data_nascimento->format('d/m/Y') . ' (' . $paciente->idade . ' anos)' : '—' }}
                        </dd>

                        <dt class="col-sm-5">Sexo</dt>
                        <dd class="col-sm-7">
                            {{ match($paciente->sexo) {
                                'M' => 'Masculino',
                                'F' => 'Feminino',
                                'outro' => 'Outro',
                                default => '—'
                            } }}
                        </dd>

                        <dt class="col-sm-5">Telefone</dt>
                        <dd class="col-sm-7">{{ $paciente->telefone ?? '—' }}</dd>
                    </dl>
                </div>
            </div>

            {{-- Anamnese --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Anamnese</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('prontuarios.anamnese', $prontuario->id) }}" method="POST">
                        @csrf

                        @php $a = $prontuario->anamnese; @endphp

                        <div class="mb-2">
                            <label class="form-check form-switch">
                                <input type="hidden" name="alergia" value="0">
                                <input class="form-check-input" type="checkbox" name="alergia" value="1"
                                    id="alergia" {{ old('alergia', $a->alergia ?? false) ? 'checked' : '' }}>
                                <span class="form-check-label">Alergia</span>
                            </label>
                            <textarea name="alergia_descricao" class="form-control form-control-sm mt-1"
                                placeholder="Descreva as alergias..." rows="2"
                                id="alergia_desc">{{ old('alergia_descricao', $a->alergia_descricao ?? '') }}</textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-check form-switch">
                                <input type="hidden" name="medicamentos_uso" value="0">
                                <input class="form-check-input" type="checkbox" name="medicamentos_uso" value="1"
                                    id="medicamentos" {{ old('medicamentos_uso', $a->medicamentos_uso ?? false) ? 'checked' : '' }}>
                                <span class="form-check-label">Medicamentos em uso</span>
                            </label>
                            <textarea name="medicamentos_descricao" class="form-control form-control-sm mt-1"
                                placeholder="Quais medicamentos..." rows="2">{{ old('medicamentos_descricao', $a->medicamentos_descricao ?? '') }}</textarea>
                        </div>

                        <div class="row g-2 mb-2">
                            @foreach([
                                'pressao_alta'         => 'Pressão Alta',
                                'diabetes'             => 'Diabetes',
                                'cardiopatia'          => 'Cardiopatia',
                                'gestante'             => 'Gestante',
                                'fumante'              => 'Fumante',
                                'alcool'               => 'Uso de Álcool',
                                'doenca_renal'         => 'Doença Renal',
                                'doenca_hepatica'      => 'Doença Hepática',
                                'problemas_coagulacao' => 'Prob. de Coagulação',
                            ] as $campo => $label)
                                <div class="col-6">
                                    <label class="form-check form-switch">
                                        <input type="hidden" name="{{ $campo }}" value="0">
                                        <input class="form-check-input" type="checkbox"
                                            name="{{ $campo }}" value="1"
                                            {{ old($campo, $a->$campo ?? false) ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ $label }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Outras doenças</label>
                            <textarea name="outras_doencas" class="form-control form-control-sm"
                                rows="2">{{ old('outras_doencas', $a->outras_doencas ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observações</label>
                            <textarea name="observacoes" class="form-control form-control-sm"
                                rows="2">{{ old('observacoes', $a->observacoes ?? '') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Salvar Anamnese</button>
                    </form>
                </div>
            </div>

        </div>

        {{-- Coluna direita: Evoluções --}}
        <div class="col-md-8">

            {{-- Nova Evolução --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Nova Evolução</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('prontuarios.evolucoes.store', $prontuario->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-2">

                            <div class="col-md-6">
                                <label class="form-label required">Dentista</label>
                                <select name="dentista_id"
                                    class="form-select form-select-sm @error('dentista_id') is-invalid @enderror">
                                    <option value="">Selecione...</option>
                                    @foreach($dentistas as $dentista)
                                        <option value="{{ $dentista->id }}"
                                            {{ old('dentista_id') == $dentista->id ? 'selected' : '' }}>
                                            {{ $dentista->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dentista_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Dente (FDI)</label>
                                <input type="text" name="dente"
                                    class="form-control form-control-sm @error('dente') is-invalid @enderror"
                                    placeholder="ex: 11, 36"
                                    value="{{ old('dente') }}">
                                @error('dente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Face</label>
                                <select name="face" class="form-select form-select-sm">
                                    <option value="">—</option>
                                    <option value="vestibular" {{ old('face') == 'vestibular' ? 'selected' : '' }}>Vestibular</option>
                                    <option value="lingual" {{ old('face') == 'lingual' ? 'selected' : '' }}>Lingual</option>
                                    <option value="oclusal" {{ old('face') == 'oclusal' ? 'selected' : '' }}>Oclusal</option>
                                    <option value="mesial" {{ old('face') == 'mesial' ? 'selected' : '' }}>Mesial</option>
                                    <option value="distal" {{ old('face') == 'distal' ? 'selected' : '' }}>Distal</option>
                                    <option value="cervical" {{ old('face') == 'cervical' ? 'selected' : '' }}>Cervical</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label required">Descrição</label>
                                <textarea name="descricao" rows="3"
                                    class="form-control form-control-sm @error('descricao') is-invalid @enderror"
                                    placeholder="Descreva o atendimento...">{{ old('descricao') }}</textarea>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Arquivos (fotos, radiografias, vídeos)</label>
                                <input type="file" name="arquivos[]" multiple
                                    class="form-control form-control-sm @error('arquivos.*') is-invalid @enderror"
                                    accept="image/*,video/*,.pdf">
                                <small class="text-secondary">Máximo 100MB por arquivo. Aceita imagens, vídeos e PDF.</small>
                                @error('arquivos.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-sm mt-3">Registrar Evolução</button>
                    </form>
                </div>
            </div>

            {{-- Histórico de Evoluções --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Histórico de Evoluções</h3>
                    <span class="ms-auto text-secondary">{{ $prontuario->evolucoes->count() }} registros</span>
                </div>
                <div class="card-body p-0">
                    @forelse($prontuario->evolucoes as $evolucao)
                        <div class="border-bottom p-3">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="d-flex gap-2 align-items-center mb-1">
                                        <strong>{{ $evolucao->created_at->format('d/m/Y H:i') }}</strong>
                                        <span class="text-secondary">{{ $evolucao->dentista->name }}</span>
                                        @if($evolucao->dente)
                                            <span class="badge bg-blue text-white">Dente {{ $evolucao->dente }}</span>
                                        @endif
                                        @if($evolucao->face)
                                            <span class="badge bg-secondary text-white">{{ ucfirst($evolucao->face) }}</span>
                                        @endif
                                    </div>
                                    <p class="mb-2">{{ $evolucao->descricao }}</p>

                                    {{-- Arquivos --}}
                                    @if($evolucao->arquivos->count() > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($evolucao->arquivos as $arquivo)
                                                <div class="position-relative">
                                                    @if($arquivo->tipo === 'imagem')
                                                        <a href="{{ $arquivo->url }}" target="_blank">
                                                            <img src="{{ $arquivo->url }}"
                                                                style="width:80px;height:80px;object-fit:cover;border-radius:4px;">
                                                        </a>
                                                    @elseif($arquivo->tipo === 'video')
                                                        <a href="{{ $arquivo->url }}" target="_blank"
                                                            class="btn btn-sm btn-secondary">
                                                            🎥 {{ $arquivo->nome_original }}
                                                        </a>
                                                    @else
                                                        <a href="{{ $arquivo->url }}" target="_blank"
                                                            class="btn btn-sm btn-secondary">
                                                            📄 {{ $arquivo->nome_original }}
                                                        </a>
                                                    @endif

                                                    <form action="{{ route('prontuarios.arquivos.destroy', $arquivo->id) }}"
                                                        method="POST"
                                                        data-confirm="Remover este arquivo?"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                            style="padding:1px 5px;font-size:10px">✕</button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <form action="{{ route('prontuarios.evolucoes.destroy', $evolucao->id) }}"
                                    method="POST"
                                    data-confirm="Deseja remover esta evolução?"
                                    class="ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-ghost-danger">Remover</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-secondary py-4">
                            Nenhuma evolução registrada ainda.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection