<?php

namespace App\Console\Commands;

use App\Models\Produto;
use App\Services\NotificacaoService;
use Illuminate\Console\Command;

class VerificarEstoqueBaixo extends Command
{
    protected $signature   = 'notificacoes:estoque-baixo';
    protected $description = 'Verifica produtos com estoque baixo e envia notificações';

    public function handle(): void
    {
        $produtos = Produto::whereColumn('estoque_atual', '<=', 'estoque_minimo')
            ->whereNull('deleted_at')
            ->get();

        foreach ($produtos as $produto) {
            NotificacaoService::estoqueBaixo($produto);
        }

        $this->info("Verificados {$produtos->count()} produtos com estoque baixo.");
    }
}