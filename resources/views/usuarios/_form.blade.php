<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dados do Usuário</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label required">Nome</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $usuario->name ?? '') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label required">E-mail</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $usuario->email ?? '') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label required">Perfil</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror"
                    id="role">
                    <option value="">Selecione...</option>
                    <option value="admin" {{ old('role', $usuario->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="dentista" {{ old('role', $usuario->role ?? '') == 'dentista' ? 'selected' : '' }}>Dentista</option>
                    <option value="recepcionista" {{ old('role', $usuario->role ?? '') == 'recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4" id="cro-field" style="{{ old('role', $usuario->role ?? '') == 'dentista' ? '' : 'display:none' }}">
                <label class="form-label">CRO</label>
                <input type="text" name="cro" class="form-control @error('cro') is-invalid @enderror"
                    value="{{ old('cro', $usuario->cro ?? '') }}" placeholder="CRO-GO 12345">
                @error('cro')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" id="telefone"
                    class="form-control @error('telefone') is-invalid @enderror"
                    value="{{ old('telefone', $usuario->telefone ?? '') }}"
                    placeholder="(00) 00000-0000">
                @error('telefone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Permissões --}}
            @if(isset($usuario->id))
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Permissões de Acesso</h3>
                    <span class="ms-auto text-secondary">Admin tem acesso total automaticamente</span>
                </div>
                <div class="card-body">
                    @php
                        $modulos = \App\Models\Permission::modulos();
                        $acoes   = \App\Models\Permission::acoes();
                        $permissoes = $permissions->groupBy('modulo');
                        $userPermissions = $usuario->permissions->pluck('id')->toArray();
                    @endphp

                    @foreach($modulos as $modulo => $labelModulo)
                        <div class="mb-3">
                            <div class="fw-bold mb-2">{{ $labelModulo }}</div>
                            <div class="d-flex gap-3 flex-wrap">
                                @foreach($acoes as $acao => $labelAcao)
                                    @php
                                        $permission = $permissoes->get($modulo)?->firstWhere('acao', $acao);
                                    @endphp
                                    @if($permission)
                                        <label class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="permissions[]"
                                                value="{{ $permission->id }}"
                                                {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}>
                                            <span class="form-check-label">{{ $labelAcao }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @if(!$loop->last) <hr> @endif
                    @endforeach
                </div>
            </div>
            @endif

            @if(!isset($usuario->id))
                <div class="col-md-6">
                    <label class="form-label required">Senha</label>
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

                <div class="col-md-6">
                    <label class="form-label required">Confirmar Senha</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            @endif

        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

@push('scripts')
<script>
    // Mostrar/ocultar campo CRO conforme perfil selecionado
    document.getElementById('role').addEventListener('change', function () {
        const croField = document.getElementById('cro-field');
        croField.style.display = this.value === 'dentista' ? '' : 'none';
    });

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