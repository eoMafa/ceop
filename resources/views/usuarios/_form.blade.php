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

            @if(!isset($usuario->id))
                <div class="col-md-6">
                    <label class="form-label required">Senha</label>
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
</script>
@endpush