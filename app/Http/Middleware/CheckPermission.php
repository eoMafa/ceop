<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permissao): mixed
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->temPermissao($permissao)) {
            abort(403, 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}