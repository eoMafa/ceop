<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>CEOP — Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ceop-red:  #c0392b;
            --ceop-dark: #1a0a0a;
        }

        body {
            background:
                radial-gradient(ellipse at 80% 50%, rgba(192,57,43,0.12) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 50%, rgba(192,57,43,0.06) 0%, transparent 60%),
                #1a0a0a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-logo h1 {
            font-size: 3rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -1px;
            margin-bottom: 4px;
        }

        .login-logo h1 span {
            color: var(--ceop-red);
        }

        .login-logo p {
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #666;
        }

        .divider {
            width: 40px;
            height: 3px;
            background: var(--ceop-red);
            margin: 12px auto 0;
            border-radius: 2px;
        }

        .login-card {
            background: #1e1e1e;
            border: 1px solid #2a2a2a;
            border-radius: 8px;
            padding: 36px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        .login-card h2 {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 24px;
            text-align: center;
        }

        .form-label {
            color: #aaa;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .form-control {
            background: #2a2a2a !important;
            border: 1px solid #333 !important;
            color: #fff !important;
            border-radius: 4px;
            padding: 10px 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--ceop-red) !important;
            box-shadow: 0 0 0 3px rgba(192,57,43,0.15) !important;
            outline: none;
        }

        .form-control::placeholder {
            color: #555 !important;
        }

        .form-check-label {
            color: #888;
            font-size: 0.85rem;
        }

        .form-check-input:checked {
            background-color: var(--ceop-red);
            border-color: var(--ceop-red);
        }

        .btn-login {
            background: var(--ceop-red);
            color: #fff;
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
        }

        .btn-login:hover {
            background: #a93226;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(192,57,43,0.4);
        }

        .forgot-link {
            color: #666;
            font-size: 0.8rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--ceop-red);
        }

        .alert-danger {
            background: rgba(192,57,43,0.15);
            border: 1px solid rgba(192,57,43,0.3);
            color: #e74c3c;
            border-radius: 4px;
            padding: 10px 14px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        .invalid-feedback {
            color: #e74c3c;
            font-size: 0.8rem;
        }

        .is-invalid {
            border-color: #e74c3c !important;
        }

        .footer-text {
            text-align: center;
            color: #444;
            font-size: 0.75rem;
            margin-top: 24px;
        }

        .footer-text span {
            color: var(--ceop-red);
        }

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
            10%  { opacity: 0.2; }
            90%  { opacity: 0.2; }
            100% { transform: translateY(-100px); opacity: 0; }
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>

    <div class="particles" id="particles"></div>

    <div class="login-wrapper">

        {{-- Logo --}}
        <div class="login-logo">
            <h1>CEOP</h1>
            <br>
            <p>Centro Especializado em Odontologia e Prótese</p>
            <div class="divider"></div>
        </div>

        {{-- Card de login --}}
        <div class="login-card">
            <h2>Acesse sua conta</h2>

            {{-- Alertas --}}
            @if(session('error'))
                <div class="alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="seu@email.com"
                        autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0">Senha</label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Esqueceu a senha?
                            </a>
                        @endif
                    </div>
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input">
                        <span class="form-check-label">Lembrar de mim</span>
                    </label>
                </div>

                <button type="submit" class="btn-login">Entrar</button>
            </form>
        </div>

        <div class="footer-text">
            <span>CEOP</span> &copy; {{ date('Y') }} — Sistema de Gestão Odontológica
        </div>

    </div>

    <script>
        const container = document.getElementById('particles');
        for (let i = 0; i < 15; i++) {
            const p = document.createElement('div');
            p.classList.add('particle');
            p.style.left              = Math.random() * 100 + '%';
            p.style.width             = Math.random() * 3 + 1 + 'px';
            p.style.height            = p.style.width;
            p.style.animationDuration = Math.random() * 15 + 10 + 's';
            p.style.animationDelay    = Math.random() * 10 + 's';
            container.appendChild(p);
        }
    </script>

</body>
</html>