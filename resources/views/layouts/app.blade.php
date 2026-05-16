<!doctype html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        .navbar-vertical.navbar-expand-lg {
            overflow-y: auto !important;
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
        }

        .navbar-vertical.navbar-expand-lg:hover {
            scrollbar-color: rgba(192,57,43,0.3) transparent;
        }

        .navbar-vertical.navbar-expand-lg::-webkit-scrollbar {
            width: 3px;
        }

        .navbar-vertical.navbar-expand-lg::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 3px;
        }

        .navbar-vertical.navbar-expand-lg:hover::-webkit-scrollbar-thumb {
            background: rgba(192,57,43,0.3);
        }
        :root {
            --ceop-red:  #c0392b;
            --ceop-dark: #1a0a0a;
        }

        /* Sidebar */
        .navbar-vertical {
            background: linear-gradient(180deg, #1a0a0a 0%, #2c1010 100%) !important;
            border-right: 1px solid #3a1a1a !important;
        }

        .navbar-vertical .navbar-brand {
            border-bottom: 1px solid #3a1a1a;
            padding-bottom: 16px;
            margin-bottom: 8px;
        }

        .navbar-vertical .navbar-brand-text {
            color: #fff !important;
            font-weight: 900;
            font-size: 1.2rem;
            letter-spacing: -0.5px;
        }

        .navbar-vertical .nav-link {
            color: #aaa !important;
            border-radius: 6px;
            margin: 2px 8px;
            transition: all 0.2s ease;
        }

        .navbar-vertical .nav-link:hover {
            color: #fff !important;
            background: rgba(192,57,43,0.15) !important;
        }

        .navbar-vertical .nav-link.active {
            color: #fff !important;
            background: rgba(192,57,43,0.25) !important;
            border-left: 3px solid var(--ceop-red);
        }

        .navbar-vertical .nav-link-title {
            font-size: 0.88rem;
        }

        .navbar-vertical .nav-section {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #555 !important;
            padding: 12px 16px 4px;
        }

        /* Avatar do usuário */
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--ceop-red), #e74c3c);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        /* Dropdown da sidebar */
        .navbar-vertical .dropdown-menu {
            background: #1a1a1a;
            border: 1px solid #333;
        }

        .navbar-vertical .dropdown-item {
            color: #aaa;
            font-size: 0.85rem;
        }

        .navbar-vertical .dropdown-item:hover {
            background: rgba(192,57,43,0.15);
            color: #fff;
        }

        .navbar-vertical .dropdown-divider {
            border-color: #333;
        }

        /* Botões primários com cor CEOP */
        .btn-primary {
            background-color: var(--ceop-red) !important;
            border-color: var(--ceop-red) !important;
        }

        .btn-primary:hover {
            background-color: #a93226 !important;
            border-color: #a93226 !important;
        }

        /* Badge de notificação */
        .badge-notification {
            position: absolute;
            top: -4px;
            right: -4px;
            font-size: 10px;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            padding: 0 4px;
        }

        /* Links ativos com cor CEOP */
        a { color: var(--ceop-red); }
        a:hover { color: #a93226; }
        .btn-primary, .bg-primary { background-color: var(--ceop-red) !important; }
        .text-primary { color: var(--ceop-red) !important; }
        .border-primary { border-color: var(--ceop-red) !important; }

        /* ===== FORMULÁRIOS GLOBAIS ===== */

        /* Cards de formulário com borda CEOP no topo */
        .card {
            border-radius: 8px;
            border: 1px solid #e8e8e8;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        .card-header {
            border-bottom: 1px solid #f0f0f0;
            background: #fafafa;
            border-radius: 8px 8px 0 0 !important;
            padding: 14px 20px;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #c0392b, #e74c3c);
            border-radius: 8px 8px 0 0;
        }

        .card-header .card-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #333;
        }

        /* Inputs com foco CEOP */
        .form-control:focus,
        .form-select:focus {
            border-color: #c0392b !important;
            box-shadow: 0 0 0 3px rgba(192,57,43,0.1) !important;
        }

        /* Labels mais modernos */
        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        /* Required asterisk vermelho */
        .form-label.required::after,
        .required::after {
            content: ' *';
            color: #c0392b;
        }

        /* Inputs com borda mais suave */
        .form-control,
        .form-select {
            border: 1.5px solid #e0e0e0;
            border-radius: 6px;
            padding: 9px 12px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            color: #333;
        }

        .form-control:hover,
        .form-select:hover {
            border-color: #c0392b;
        }

        /* Placeholder */
        .form-control::placeholder {
            color: #bbb;
            font-size: 0.88rem;
        }

        /* Input group */
        .input-group-text {
            background: #f5f5f5;
            border: 1.5px solid #e0e0e0;
            color: #666;
            font-size: 0.88rem;
        }

        /* Textarea */
        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        /* Botão Salvar */
        .btn-primary {
            background: linear-gradient(135deg, #c0392b, #e74c3c) !important;
            border: none !important;
            border-radius: 6px !important;
            padding: 10px 28px !important;
            font-weight: 600 !important;
            letter-spacing: 0.5px !important;
            box-shadow: 0 2px 8px rgba(192,57,43,0.3) !important;
            transition: all 0.2s ease !important;
        }

        .btn-primary:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 15px rgba(192,57,43,0.4) !important;
            background: linear-gradient(135deg, #a93226, #c0392b) !important;
        }

        /* Botão Cancelar/Secundário */
        .btn-secondary {
            border-radius: 6px !important;
            padding: 10px 28px !important;
            font-weight: 600 !important;
        }

        /* Feedback de validação */
        .invalid-feedback {
            font-size: 0.78rem;
            color: #c0392b;
        }

        .is-invalid {
            border-color: #c0392b !important;
            background-image: none !important;
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(192,57,43,0.15) !important;
        }

        /* Form check / switch */
        .form-check-input:checked {
            background-color: #c0392b !important;
            border-color: #c0392b !important;
        }

        /* Badges */
        .badge {
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 4px 8px;
        }

        /* Tabelas */
        .table thead th {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            border-bottom: 2px solid #f0f0f0;
            padding: 10px 16px;
        }

        .table tbody td {
            padding: 12px 16px;
            font-size: 0.88rem;
            vertical-align: middle;
            color: #444;
        }

        .table-hover tbody tr:hover {
            background: rgba(192,57,43,0.03);
        }

        /* Paginação */
        .page-link {
            color: #c0392b;
            border-radius: 4px !important;
            margin: 0 2px;
        }

        .page-item.active .page-link {
            background-color: #c0392b !important;
            border-color: #c0392b !important;
        }

        .page-link:hover {
            color: #a93226;
            background: rgba(192,57,43,0.08);
        }

        /* Breadcrumb */
        .breadcrumb-item a {
            color: #c0392b;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #888;
        }

        /* Page title */
        .page-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #222;
        }

        /* Alertas */
        .alert-success {
            background: rgba(72,187,120,0.1);
            border: 1px solid rgba(72,187,120,0.3);
            color: #276749;
            border-radius: 6px;
        }

        .alert-danger {
            background: rgba(192,57,43,0.1);
            border: 1px solid rgba(192,57,43,0.3);
            color: #c0392b;
            border-radius: 6px;
        }

        .alert-warning {
            background: rgba(237,137,54,0.1);
            border: 1px solid rgba(237,137,54,0.3);
            color: #975a16;
            border-radius: 6px;
        }

        
    </style>
</head>

<body class="antialiased layout-fluid">
    <div class="wrapper">

        {{-- Sidebar --}}
        <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
            <div class="container-fluid">

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#sidebar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                {{-- Logo --}}
                <h1 class="navbar-brand navbar-brand-autodark w-100">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2">
                        <div>
                            <div style="font-size:1.4rem;font-weight:900;color:#fff;letter-spacing:-0.5px;line-height:1">
                                CEOP
                            </div>
                            <div style="font-size:0.6rem;color:#c0392b;letter-spacing:2px;text-transform:uppercase;line-height:1;margin-top:2px">
                                Odontologia
                            </div>
                        </div>
                    </a>
                </h1>

                <div class="collapse navbar-collapse show" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-2">

                        {{-- Dashboard --}}
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

                        {{-- Seção Clínica --}}
                        <li class="nav-section">Clínica</li>

                        @if(auth()->user()->podeVer('pacientes'))
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
                        @endif

                        @if(auth()->user()->podeVer('agendamentos'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('agendamentos.*') ? 'active' : '' }}"
                                    href="{{ route('agendamentos.index') }}">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <rect x="4" y="5" width="16" height="16" rx="2" />
                                            <line x1="16" y1="3" x2="16" y2="7" />
                                            <line x1="8" y1="3" x2="8" y2="7" />
                                            <line x1="4" y1="11" x2="20" y2="11" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Agendamentos</span>
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->podeVer('prontuario'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('evolucoes.*') ? 'active' : '' }}"
                                    href="{{ route('evolucoes.index') }}">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                            <rect x="9" y="3" width="6" height="4" rx="2" />
                                            <line x1="9" y1="12" x2="15" y2="12" />
                                            <line x1="9" y1="16" x2="11" y2="16" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Consultas</span>
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->podeVer('pacientes'))
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
                        @endif

                        {{-- Seção Financeiro --}}
                        @if(auth()->user()->podeVer('financeiro'))
                            <li class="nav-section">Financeiro</li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('orcamentos.*') || request()->routeIs('pagamentos.*') || request()->routeIs('convenios.*') ? 'active' : '' }}"
                                    href="#" data-bs-toggle="dropdown">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                            <rect x="9" y="3" width="6" height="4" rx="2" />
                                            <path d="M9 12h6" />
                                            <path d="M9 16h6" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Financeiro</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('orcamentos.index') }}" class="dropdown-item">💼 Orçamentos</a>
                                    <a href="{{ route('pagamentos.index') }}" class="dropdown-item">💳 Pagamentos</a>
                                    <a href="{{ route('convenios.index') }}" class="dropdown-item">🤝 Convênios</a>
                                </div>
                            </li>
                        @endif

                        {{-- Seção Estoque --}}
                        @if(auth()->user()->podeVer('estoque'))
                            <li class="nav-section">Estoque</li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('estoque.*') ? 'active' : '' }}"
                                    href="#" data-bs-toggle="dropdown">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5" />
                                            <path d="M12 12l8 -4.5" />
                                            <path d="M12 12v9" />
                                            <path d="M12 12l-8 -4.5" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Estoque</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('estoque.produtos.index') }}" class="dropdown-item">📦 Produtos</a>
                                    <a href="{{ route('estoque.fornecedores.index') }}" class="dropdown-item">🚚 Fornecedores</a>
                                    <a href="{{ route('estoque.categorias.index') }}" class="dropdown-item">🏷️ Categorias</a>
                                </div>
                            </li>
                        @endif

                        {{-- Seção Relatórios --}}
                        @if(auth()->user()->podeVer('relatorios'))
                            <li class="nav-section">Análises</li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('relatorios.*') ? 'active' : '' }}"
                                    href="#" data-bs-toggle="dropdown">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <line x1="8" y1="6" x2="21" y2="6" />
                                            <line x1="8" y1="12" x2="21" y2="12" />
                                            <line x1="8" y1="18" x2="21" y2="18" />
                                            <line x1="3" y1="6" x2="3.01" y2="6" />
                                            <line x1="3" y1="12" x2="3.01" y2="12" />
                                            <line x1="3" y1="18" x2="3.01" y2="18" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Relatórios</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('relatorios.financeiro') }}" class="dropdown-item">💰 Financeiro</a>
                                    <a href="{{ route('relatorios.atendimento') }}" class="dropdown-item">📅 Atendimento</a>
                                    <a href="{{ route('relatorios.pacientes') }}" class="dropdown-item">👥 Pacientes</a>
                                    <a href="{{ route('relatorios.estoque') }}" class="dropdown-item">📦 Estoque</a>
                                    <a href="{{ route('relatorios.lucratividade') }}" class="dropdown-item">📊 Lucratividade</a>
                                </div>
                            </li>
                        @endif

                        {{-- Seção Admin --}}
                        @if(auth()->user()->podeVer('usuarios'))
                            <li class="nav-section">Administração</li>

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

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}"
                                    href="{{ route('logs.index') }}">
                                    <span class="nav-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                            <rect x="9" y="3" width="6" height="4" rx="2" />
                                            <path d="M9 12h6" />
                                            <path d="M9 16h6" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Logs</span>
                                </a>
                            </li>
                        @endif

                    </ul>

                    {{-- Usuário logado --}}
                    <div class="mt-auto pb-3 pt-3" style="border-top:1px solid #3a1a1a">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                                data-bs-toggle="dropdown">
                                <div class="user-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="flex-fill" style="min-width:0">
                                    <div class="nav-link-title text-white" style="font-size:0.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div style="font-size:0.7rem;color:#666">
                                        {{ ucfirst(Auth::user()->role) }}
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    👤 Meu Perfil
                                </a>
                                <a href="{{ route('notificacoes.index') }}" class="dropdown-item">
                                    🔔 Notificações
                                    @php $naoLidas = auth()->user()->notificacoesNaoLidas()->count(); @endphp
                                    @if($naoLidas > 0)
                                        <span class="badge bg-danger ms-1">{{ $naoLidas }}</span>
                                    @endif
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        🚪 Sair
                                    </button>
                                </form>
                            </div>
                        </div>
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
                            @hasSection('breadcrumb')
                                <ol class="breadcrumb mt-1">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}">Início</a>
                                    </li>
                                    @yield('breadcrumb')
                                </ol>
                            @endif
                        </div>

                        {{-- Sino de notificações --}}
                        <div class="col-auto">
                            <div class="dropdown">
                                <a href="#" class="btn btn-icon position-relative" data-bs-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                        <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                    </svg>
                                    @php $totalNaoLidas = auth()->user()->notificacoesNaoLidas()->count(); @endphp
                                    @if($totalNaoLidas > 0)
                                        <span class="badge bg-danger badge-notification">
                                            {{ $totalNaoLidas > 99 ? '99+' : $totalNaoLidas }}
                                        </span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" style="width:350px;max-height:400px;overflow-y:auto">
                                    <div class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span>🔔 Notificações</span>
                                        @if($totalNaoLidas > 0)
                                            <form action="{{ route('notificacoes.ler-todas') }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-ghost-secondary">
                                                    Marcar todas como lidas
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    @php
                                        $notificacoesRecentes = auth()->user()->notificacoes()->limit(5)->get();
                                    @endphp

                                    @forelse($notificacoesRecentes as $notificacao)
                                        <a href="{{ route('notificacoes.ler', $notificacao->id) }}"
                                            class="dropdown-item d-flex align-items-start py-2 {{ !$notificacao->lida ? 'bg-light' : '' }}">
                                            <span class="me-2 mt-1">{{ $notificacao->icone_tipo }}</span>
                                            <div class="flex-fill">
                                                <div class="fw-bold" style="font-size:13px">{{ $notificacao->titulo }}</div>
                                                <div class="text-secondary" style="font-size:12px">{{ $notificacao->mensagem }}</div>
                                                <div class="text-secondary" style="font-size:11px">{{ $notificacao->created_at->diffForHumans() }}</div>
                                            </div>
                                            @if(!$notificacao->lida)
                                                <span class="badge bg-danger ms-1" style="width:8px;height:8px;border-radius:50%;padding:0"></span>
                                            @endif
                                        </a>
                                    @empty
                                        <div class="dropdown-item text-center text-secondary py-3">
                                            Nenhuma notificação
                                        </div>
                                    @endforelse

                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('notificacoes.index') }}" class="dropdown-item text-center" style="color:var(--ceop-red)">
                                        Ver todas as notificações
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Botões do header --}}
                        @hasSection('actions')
                            <div class="col-auto">
                                @yield('actions')
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Conteúdo da página --}}
            <div class="page-body">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            {{-- Footer --}}
            <footer class="footer footer-transparent d-print-none">
                <div class="container-fluid">
                    <div class="row text-center align-items-center">
                        <div class="col-12">
                            <p class="mb-0 text-secondary" style="font-size:0.8rem">
                                <span style="color:var(--ceop-red);font-weight:700">CEOP</span>
                                — Centro Especializado em Odontologia e Prótese &copy; {{ date('Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    {{-- Timeout de sessão --}}
    <div class="modal modal-blur fade" id="modal-timeout" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">⚠️ Sessão expirando</h5>
                </div>
                <div class="modal-body">
                    <p>Sua sessão vai expirar em <strong id="countdown">5:00</strong> minutos por inatividade.</p>
                    <p class="text-secondary mb-0">Clique em continuar para permanecer conectado.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" id="btn-continuar">
                        Continuar conectado
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.sessionSuccess = @json(session('success'));
        window.sessionError   = @json(session('error'));
    </script>

    @stack('scripts')

    <script>
    (function () {
        const TIMEOUT_MINUTOS = 120;
        const AVISO_MINUTOS   = 5;
        const AVISO_MS        = (TIMEOUT_MINUTOS - AVISO_MINUTOS) * 60 * 1000;

        let countdownInterval, timeoutTimer, avisoTimer;

        function resetTimers() {
            clearTimeout(avisoTimer);
            clearTimeout(timeoutTimer);
            clearInterval(countdownInterval);

            avisoTimer = setTimeout(mostrarAviso, AVISO_MS);
            timeoutTimer = setTimeout(function () {
                window.location.href = '{{ route("login") }}';
            }, TIMEOUT_MINUTOS * 60 * 1000);
        }

        function mostrarAviso() {
            const modal = bootstrap.Modal.getOrCreate(document.getElementById('modal-timeout'));
            modal.show();

            let segundosRestantes = AVISO_MINUTOS * 60;
            countdownInterval = setInterval(function () {
                segundosRestantes--;
                const mins = Math.floor(segundosRestantes / 60);
                const secs = segundosRestantes % 60;
                document.getElementById('countdown').textContent =
                    `${mins}:${secs.toString().padStart(2, '0')}`;
                if (segundosRestantes <= 0) clearInterval(countdownInterval);
            }, 1000);
        }

        document.getElementById('btn-continuar').addEventListener('click', function () {
            fetch('{{ route("dashboard") }}', { method: 'HEAD' });
            bootstrap.Modal.getInstance(document.getElementById('modal-timeout'))?.hide();
            resetTimers();
        });

        ['click', 'keypress', 'mousemove', 'scroll'].forEach(function (e) {
            document.addEventListener(e, resetTimers, { passive: true });
        });

        resetTimers();
    })();
    </script>

</body>
</html>