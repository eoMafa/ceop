<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased layout-fluid">
    <div class="wrapper">

        {{-- Sidebar --}}
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark" data-bs-theme="dark">
            <div class="container-fluid">

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#sidebar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="{{ route('dashboard') }}">
                        🦷 {{ config('app.name') }}
                    </a>
                </h1>

                <div class="collapse navbar-collapse show" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <polyline points="5 12 3 12 12 3 21 12 19 12" />
                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                        <rect x="9" y="12" width="6" height="7" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}"
                                href="{{ route('pacientes.index') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="12" cy="7" r="4" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Pacientes</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('procedimentos.*') ? 'active' : '' }}"
                                href="{{ route('procedimentos.index') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 12h6" />
                                        <path d="M11 8h2" />
                                        <path d="M9 16h6" />
                                        <rect x="4" y="4" width="16" height="16" rx="2" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Procedimentos</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"
                                href="{{ route('usuarios.index') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        <path d="M16 11l2 2l4 -4" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Usuários</span>
                            </a>
                        </li>

                    </ul>

                    <div class="mt-auto pb-3">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="7" r="4" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">{{ Auth::user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('profile.edit') }}" class="dropdown-item">Perfil</a>
                                    <div class="dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Sair</button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Conteúdo principal --}}
        <div class="page-wrapper">

            {{-- Header --}}
            <div class="page-header d-print-none">
                <div class="container-fluid">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">@yield('title', 'Dashboard')</h2>
                            {{-- Breadcrumb --}}
                            @hasSection('breadcrumb')
                                <ol class="breadcrumb mt-1">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}">Início</a>
                                    </li>
                                    @yield('breadcrumb')
                                </ol>
                            @endif
                        </div>
                        {{-- Botões do header --}}
                        @hasSection('actions')
                            <div class="col-auto ms-auto">
                                @yield('actions')
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Alertas --}}
            <div class="container-fluid mt-3">
                
            </div>

            {{-- Conteúdo da página --}}
            <div class="page-body">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            {{-- Footer --}}
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center">
                        <div class="col-12 col-lg-auto">
                            <p class="mb-0 text-secondary">
                                {{ config('app.name') }} &copy; {{ date('Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>
    <script>
        window.sessionSuccess = @json(session('success'));
        window.sessionError = @json(session('error'));
    </script>
    @stack('scripts')
</body>

</html>