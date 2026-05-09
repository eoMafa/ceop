<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>{{ config('app.name') }} - Página não encontrada</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased d-flex align-items-center min-vh-100 bg-secondary-lt">
    <div class="container-tight py-4">
        <div class="text-center mb-4">
            <h1 class="navbar-brand-text">🦷 {{ config('app.name') }}</h1>
        </div>

        <div class="card card-md">
            <div class="card-body text-center py-5">
                <div class="mb-3" style="font-size: 4rem;">🔍</div>

                <h2 class="h1 mb-2">Página não encontrada</h2>
                <p class="text-secondary mb-4">
                    A página que você está procurando não existe ou foi removida.
                </p>

                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        ← Voltar
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        🏠 Ir para o Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 text-secondary">
            <small>Erro 404 — Página não encontrada</small>
        </div>
    </div>
</body>
</html>