<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>{{ config('app.name') }} - Confirmar Senha</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased d-flex align-items-center min-vh-100 bg-secondary-lt">
    <div class="container-tight py-4">
        <div class="text-center mb-4">
            <h1 class="navbar-brand-text"> {{ config('app.name') }}</h1>
        </div>

        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-2">Confirme sua senha</h2>
                <p class="text-secondary text-center mb-4">
                    Esta é uma área segura. Por favor, confirme sua senha antes de continuar.
                </p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label required">Senha</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Sua senha" autofocus>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>