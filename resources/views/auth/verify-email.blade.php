<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>{{ config('app.name') }} - Verificar E-mail</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased d-flex align-items-center min-vh-100 bg-secondary-lt">
    <div class="container-tight py-4">
        <div class="text-center mb-4">
            <h1 class="navbar-brand-text">🦷 {{ config('app.name') }}</h1>
        </div>

        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-2">Verifique seu e-mail</h2>
                <p class="text-secondary text-center mb-4">
                    Enviamos um link de verificação para o seu e-mail. Por favor, clique no link para continuar.
                </p>

                @if(session('status') == 'verification-link-sent')
                    <div class="alert alert-success mb-3">
                        Um novo link de verificação foi enviado para o seu e-mail.
                    </div>
                @endif

                <div class="form-footer">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            Reenviar e-mail de verificação
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary w-100">
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>