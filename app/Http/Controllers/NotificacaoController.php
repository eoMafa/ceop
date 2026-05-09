<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public function index()
    {
        $notificacoes = auth()->user()->notificacoes()->paginate(20);
        
        return view('notificacoes.index', compact('notificacoes'));
    }

    public function marcarLida(Notificacao $notificacao)
    {
        if ($notificacao->user_id === auth()->id()) {
            $notificacao->update(['lida_em' => now()]);
        }

        if ($notificacao->url) {
            return redirect($notificacao->url);
        }

        return redirect()->route('notificacoes.index');
    }

    public function marcarTodasLidas()
    {
        auth()->user()->notificacoesNaoLidas()->update(['lida_em' => now()]);

        return redirect()->route('notificacoes.index')
            ->with('success', 'Todas as notificações foram marcadas como lidas!');
    }

    public function destroy(Notificacao $notificacao)
    {
        if ($notificacao->user_id === auth()->id()) {
            $notificacao->delete();
        }

        return back()->with('success', 'Notificação removida!');
    }

    // Retorna contagem para o sino via AJAX
    public function count()
    {
        return response()->json([
            'count' => auth()->user()->notificacoesNaoLidas()->count(),
        ]);
    }
}