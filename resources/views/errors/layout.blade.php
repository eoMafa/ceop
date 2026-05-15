<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>CEOP — {{ $titulo }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --ceop-red: #c0392b; }

        body {
            background:
                radial-gradient(ellipse at 80% 50%, rgba(192,57,43,0.12) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 50%, rgba(192,57,43,0.06) 0%, transparent 60%),
                #1a0a0a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: sans-serif;
        }

        .error-wrapper {
            width: 100%;
            max-width: 480px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .error-brand {
            margin-bottom: 32px;
        }

        .error-brand h1 {
            font-size: 2rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -1px;
            margin: 0;
        }

        .error-brand p {
            font-size: 0.7rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--ceop-red);
            margin: 4px 0 0;
        }

        .error-card {
            background: #1e1e1e;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 48px 36px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        .error-code {
            font-size: 6rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, var(--ceop-red), #e74c3c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .error-icon {
            font-size: 3.5rem;
            margin-bottom: 16px;
            display: block;
        }

        .error-title {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .error-message {
            color: #888;
            font-size: 0.9rem;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .error-divider {
            width: 40px;
            height: 3px;
            background: var(--ceop-red);
            border-radius: 2px;
            margin: 16px auto 24px;
        }

        .btn-ceop {
            background: var(--ceop-red);
            color: #fff;
            border: none;
            padding: 12px 28px;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
        }

        .btn-ceop:hover {
            background: #a93226;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(192,57,43,0.4);
        }

        .btn-outline {
            background: transparent;
            color: #888;
            border: 1px solid #333;
            padding: 12px 28px;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
        }

        .btn-outline:hover {
            border-color: var(--ceop-red);
            color: var(--ceop-red);
            transform: translateY(-2px);
        }

        .footer-text {
            color: #444;
            font-size: 0.75rem;
            margin-top: 24px;
        }

        .footer-text span { color: var(--ceop-red); }

        /* Partículas */
        .particles {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 2px; height: 2px;
            background: var(--ceop-red);
            border-radius: 50%;
            animation: float linear infinite;
        }

        @keyframes float {
            0%   { transform: translateY(100vh); opacity: 0; }
            10%  { opacity: 0.15; }
            90%  { opacity: 0.15; }
            100% { transform: translateY(-100px); opacity: 0; }
        }
    </style>
</head>
<body>

    <div class="particles" id="particles"></div>

    <div class="error-wrapper">

        {{-- Brand --}}
        <div class="error-brand">
            <h1>CEOP</h1>
            <p>Centro Especializado em Odontologia e Prótese</p>
        </div>

        {{-- Card --}}
        <div class="error-card">
            <div class="error-code">{{ $codigo }}</div>
            <span class="error-icon">{{ $icone }}</span>
            <div class="error-title">{{ $titulo }}</div>
            <div class="error-divider"></div>
            <div class="error-message">{{ $mensagem }}</div>

            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="javascript:history.back()" class="btn-outline">← Voltar</a>
                <a href="{{ url('/dashboard') }}" class="btn-ceop">🏠 Ir para o Dashboard</a>
            </div>
        </div>

        <div class="footer-text">
            Erro {{ $codigo }} — <span>CEOP</span> &copy; {{ date('Y') }}
        </div>

    </div>

    <script>
        const container = document.getElementById('particles');
        for (let i = 0; i < 12; i++) {
            const p = document.createElement('div');
            p.classList.add('particle');
            p.style.left              = Math.random() * 100 + '%';
            p.style.animationDuration = Math.random() * 15 + 10 + 's';
            p.style.animationDelay    = Math.random() * 10 + 's';
            container.appendChild(p);
        }
    </script>

</body>
</html>