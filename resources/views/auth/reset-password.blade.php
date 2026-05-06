<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>{{ config('app.name') }} - Redefinir Senha</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased d-flex align-items-center min-vh-100 bg-secondary-lt">
    <div class="container-tight py-4">
        <div class="text-center mb-4">
            <h1 class="navbar-brand-text">🦷 {{ config('app.name') }}</h1>
        </div>

        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Redefinir senha</h2>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="mb-3">
                        <label class="form-label required">E-mail</label>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $request->email) }}"
                            placeholder="seu@email.com" autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Nova Senha</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Nova senha">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Confirmar Nova Senha</label>
                        <input type="password" name="password_confirmation"
                            class="form-control"
                            placeholder="Confirme a nova senha">
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Redefinir Senha</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>