<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserAtivo
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check() && !Auth::user()->ativo) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Sua conta foi desativada. Entre em contato com o administrador.');
        }

        return $next($request);
    }
}