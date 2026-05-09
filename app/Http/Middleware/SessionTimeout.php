<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    // Tempo em minutos de inatividade
    protected int $timeout = 120;

    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $ultimaAtividade = session('ultima_atividade');

        if ($ultimaAtividade && now()->diffInMinutes($ultimaAtividade) >= $this->timeout) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Sua sessão expirou por inatividade. Faça login novamente.');
        }

        // Atualiza o timestamp de última atividade
        session(['ultima_atividade' => now()]);

        return $next($request);
    }
}