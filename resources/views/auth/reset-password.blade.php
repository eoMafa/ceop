<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>CEOP — Redefinir Senha</title>
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
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
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
            margin-bottom: 8px;
            text-align: center;
        }

        .login-card p {
            color: #777;
            font-size: 0.85rem;
            text-align: center;
            margin-bottom: 24px;
            line-height: 1.6;
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
        }

        .form-control::placeholder { color: #555 !important; }

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

        .back-link {
            display: block;
            text-align: center;
            color: #666;
            font-size: 0.8rem;
            text-decoration: none;
            margin-top: 20px;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--ceop-red); }

        .invalid-feedback {
            color: #e74c3c;
            font-size: 0.8rem;
        }

        .is-invalid { border-color: #e74c3c !important; }

        .footer-text {
            text-align: center;
            color: #444;
            font-size: 0.75rem;
            margin-top: 24px;
        }

        .footer-text span { color: var(--ceop-red); }

        /* Indicador de força de senha */
        .progress {
            height: 6px;
            background: #333;
            border-radius: 3px;
            overflow: hidden;
        }

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
    </style>
</head>
<body>

    <div class="particles" id="particles"></div>

    <div class="login-wrapper">

        <div class="login-logo">
            <h1>CEOP</h1>
            <br>
            <p>Centro Especializado em Odontologia e Prótese</p>
            <div class="divider"></div>
        </div>

        <div class="login-card">
            <h2>Redefinir senha</h2>
            <p>Digite sua nova senha abaixo.</p>

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
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    {{-- Indicador de força --}}
                    <div class="mt-2" id="forca-senha" style="display:none">
                        <div class="progress">
                            <div id="barra-forca" class="progress-bar" style="width:0%;transition:width 0.3s"></div>
                        </div>
                        <small id="texto-forca" class="d-block mt-1" style="color:#888"></small>
                        <ul class="mt-1 mb-0 ps-3" style="font-size:11px;color:#666">
                            <li id="req-tamanho">Mínimo 8 caracteres</li>
                            <li id="req-maiuscula">Uma letra maiúscula</li>
                            <li id="req-minuscula">Uma letra minúscula</li>
                            <li id="req-numero">Um número</li>
                            <li id="req-especial">Um caractere especial</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label required">Confirmar Nova Senha</label>
                    <input type="password" name="password_confirmation"
                        class="form-control"
                        placeholder="••••••••">
                </div>

                <button type="submit" class="btn-login">Redefinir Senha</button>
            </form>

            <a href="{{ route('login') }}" class="back-link">← Voltar ao login</a>
        </div>

        <div class="footer-text">
            <span>CEOP</span> &copy; {{ date('Y') }} — Sistema de Gestão Odontológica
        </div>

    </div>

    <script>
        // Partículas
        const container = document.getElementById('particles');
        for (let i = 0; i < 15; i++) {
            const p = document.createElement('div');
            p.classList.add('particle');
            p.style.left              = Math.random() * 100 + '%';
            p.style.animationDuration = Math.random() * 15 + 10 + 's';
            p.style.animationDelay    = Math.random() * 10 + 's';
            container.appendChild(p);
        }

        // Indicador de força de senha
        document.getElementById('password').addEventListener('input', function () {
            const val      = this.value;
            const forcaDiv = document.getElementById('forca-senha');
            const barra    = document.getElementById('barra-forca');
            const texto    = document.getElementById('texto-forca');

            if (!val) { forcaDiv.style.display = 'none'; return; }
            forcaDiv.style.display = 'block';

            const requisitos = {
                tamanho:   val.length >= 8,
                maiuscula: /[A-Z]/.test(val),
                minuscula: /[a-z]/.test(val),
                numero:    /[0-9]/.test(val),
                especial:  /[\W_]/.test(val),
            };

            Object.entries(requisitos).forEach(([req, ok]) => {
                const el = document.getElementById(`req-${req}`);
                if (el) {
                    el.style.color = ok ? '#48bb78' : '#666';
                }
            });

            const pontos = Object.values(requisitos).filter(Boolean).length;
            const niveis = [
                { cor: '#f56565', texto: 'Muito fraca',  largura: '20%' },
                { cor: '#ed8936', texto: 'Fraca',        largura: '40%' },
                { cor: '#ecc94b', texto: 'Moderada',     largura: '60%' },
                { cor: '#48bb78', texto: 'Forte',        largura: '80%' },
                { cor: '#38a169', texto: 'Muito forte',  largura: '100%' },
            ];

            const nivel = niveis[pontos - 1] || niveis[0];
            barra.style.width           = nivel.largura;
            barra.style.backgroundColor = nivel.cor;
            texto.textContent           = nivel.texto;
            texto.style.color           = nivel.cor;
        });
    </script>

</body>
</html>