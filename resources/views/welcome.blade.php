<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased d-flex align-items-center min-vh-100 bg-secondary-lt">
    <div class="container-tight py-4">
        <div class="text-center mb-4">
            <h1>🦷 {{ config('app.name') }}</h1>
            <p class="text-secondary mt-2">Sistema de Gestão Odontológica</p>
        </div>

        <div class="card card-md">
            <div class="card-body text-center">
                @if(Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary w-100 mb-2">
                            Ir para o Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">
                            Entrar
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</body>
</html>