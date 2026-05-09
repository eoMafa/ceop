@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('breadcrumb')
    <li class="breadcrumb-item active">Perfil</li>
@endsection

@section('content')
    <div class="row g-3">

        {{-- Atualizar informações --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informações do Perfil</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label class="form-label required">Nome</label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">E-mail</label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Alterar senha --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Alterar Senha</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label class="form-label required">Senha Atual</label>
                            <input type="password" name="current_password"
                                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Nova Senha</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            {{-- Indicador de força --}}
                            <div class="mt-2" id="forca-senha" style="display:none">
                                <div class="progress" style="height:6px">
                                    <div id="barra-forca" class="progress-bar" style="width:0%;transition:width 0.3s"></div>
                                </div>
                                <small id="texto-forca" class="text-secondary mt-1 d-block"></small>
                                <ul class="mt-1 mb-0 ps-3" style="font-size:12px">
                                    <li id="req-tamanho" class="text-danger">Mínimo 8 caracteres</li>
                                    <li id="req-maiuscula" class="text-danger">Uma letra maiúscula</li>
                                    <li id="req-minuscula" class="text-danger">Uma letra minúscula</li>
                                    <li id="req-numero" class="text-danger">Um número</li>
                                    <li id="req-especial" class="text-danger">Um caractere especial (!@#$%&*)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Confirmar Nova Senha</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Alterar Senha</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
<script>
document.getElementById('password').addEventListener('input', function () {
    const val        = this.value;
    const forcaDiv   = document.getElementById('forca-senha');
    const barra      = document.getElementById('barra-forca');
    const texto      = document.getElementById('texto-forca');

    if (!val) {
        forcaDiv.style.display = 'none';
        return;
    }

    forcaDiv.style.display = 'block';

    const requisitos = {
        tamanho:   val.length >= 8,
        maiuscula: /[A-Z]/.test(val),
        minuscula: /[a-z]/.test(val),
        numero:    /[0-9]/.test(val),
        especial:  /[\W_]/.test(val),
    };

    // Atualiza itens
    Object.entries(requisitos).forEach(([req, ok]) => {
        const el = document.getElementById(`req-${req}`);
        if (el) {
            el.classList.toggle('text-success', ok);
            el.classList.toggle('text-danger', !ok);
        }
    });

    const pontos = Object.values(requisitos).filter(Boolean).length;

    const niveis = [
        { cor: '#f56565', texto: 'Muito fraca',  largura: '20%' },
        { cor: '#ed8936', texto: 'Fraca',        largura: '40%' },
        { cor: '#ecc94b', texto: 'Moderada',     largura: '60%' },
        { cor: '#48bb78', texto: 'Forte',        largura: '80%' },
        { cor: '#38a169', texto: 'Muito forte',  largura: '100%' },
    ];

    const nivel = niveis[pontos - 1] || niveis[0];
    barra.style.width           = nivel.largura;
    barra.style.backgroundColor = nivel.cor;
    texto.textContent           = nivel.texto;
    texto.style.color           = nivel.cor;
});
</script>
@endpush