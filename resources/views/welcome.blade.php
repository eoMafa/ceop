<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>CEOP — Centro Especializado em Odontologia e Prótese</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ceop-red:   #c0392b;
            --ceop-dark:  #1a0a0a;
            --ceop-gray:  #2c2c2c;
        }

        body {
            background: var(--ceop-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hero {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background:
                radial-gradient(ellipse at 80% 50%, rgba(192,57,43,0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 50%, rgba(192,57,43,0.08) 0%, transparent 60%),
                var(--ceop-dark);
        }

        .hero-content {
            text-align: center;
            max-width: 600px;
        }

        .logo-text {
            font-size: 5rem;
            font-weight: 900;
            letter-spacing: -2px;
            color: #ffffff;
            line-height: 1;
            margin-bottom: 0;
        }

        .logo-subtitle {
            font-size: 0.85rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #888;
            margin-top: 8px;
            margin-bottom: 40px;
        }

        .divider {
            width: 60px;
            height: 3px;
            background: var(--ceop-red);
            margin: 20px auto 30px;
            border-radius: 2px;
        }

        .hero-description {
            color: #aaa;
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 40px;
        }

        .btn-ceop {
            background: var(--ceop-red);
            color: #fff;
            border: none;
            padding: 14px 48px;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 1px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
            text-transform: uppercase;
        }

        .btn-ceop:hover {
            background: #a93226;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(192,57,43,0.4);
        }

        .btn-ceop-outline {
            background: transparent;
            color: #fff;
            border: 1px solid #444;
            padding: 14px 48px;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 1px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
            text-transform: uppercase;
        }

        .btn-ceop-outline:hover {
            border-color: var(--ceop-red);
            color: var(--ceop-red);
            transform: translateY(-2px);
        }

        .features {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-top: 60px;
            flex-wrap: wrap;
        }

        .feature-item {
            text-align: center;
            color: #666;
            font-size: 0.85rem;
        }

        .feature-icon {
            font-size: 1.8rem;
            display: block;
            margin-bottom: 8px;
        }

        .footer-bar {
            background: #111;
            border-top: 1px solid #222;
            padding: 16px;
            text-align: center;
            color: #555;
            font-size: 0.8rem;
        }

        .footer-bar span {
            color: var(--ceop-red);
        }

        /* Partículas decorativas */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: var(--ceop-red);
            border-radius: 50%;
            opacity: 0.3;
            animation: float linear infinite;
        }

        @keyframes float {
            0%   { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10%  { opacity: 0.3; }
            90%  { opacity: 0.3; }
            100% { transform: translateY(-100px) rotate(720deg); opacity: 0; }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>

    {{-- Partículas de fundo --}}
    <div class="particles" id="particles"></div>

    {{-- Hero --}}
    <div class="hero">
        <div class="hero-content">

            {{-- Logo --}}
            <h1 class="logo-text">
                CEOP
            </h1>
            <p class="logo-subtitle">Centro Especializado em Odontologia e Prótese</p>

            <div class="divider"></div>

            <p class="hero-description">
                Sistema de gestão completo para a clínica odontológica CEOP.<br>
                Pacientes, agendamentos, prontuários e muito mais.
            </p>

            {{-- Botões --}}
            @auth
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('dashboard') }}" class="btn-ceop">
                        Acessar o Sistema
                    </a>
                </div>
            @else
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('login') }}" class="btn-ceop">
                        Entrar
                    </a>
                </div>
            @endauth

            {{-- Features --}}
            <div class="features">
                <div class="feature-item">
                    <span class="feature-icon">👥</span>
                    Pacientes
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📅</span>
                    Agendamentos
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📋</span>
                    Prontuários
                </div>
                <div class="feature-item">
                    <span class="feature-icon">💰</span>
                    Financeiro
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📦</span>
                    Estoque
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📊</span>
                    Relatórios
                </div>
            </div>

        </div>
    </div>

    {{-- Footer --}}
    <div class="footer-bar">
        <span>CEOP</span> — Centro Especializado em Odontologia e Prótese &copy; {{ date('Y') }}
    </div>

    <script>
        // Gera partículas animadas
        const container = document.getElementById('particles');
        for (let i = 0; i < 20; i++) {
            const p = document.createElement('div');
            p.classList.add('particle');
            p.style.left            = Math.random() * 100 + '%';
            p.style.width           = Math.random() * 3 + 1 + 'px';
            p.style.height          = p.style.width;
            p.style.animationDuration = Math.random() * 15 + 10 + 's';
            p.style.animationDelay  = Math.random() * 10 + 's';
            p.style.opacity         = Math.random() * 0.3;
            container.appendChild(p);
        }
    </script>

</body>
</html>