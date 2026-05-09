<?php

namespace App\Console\Commands;

use App\Models\Parcela;
use App\Services\NotificacaoService;
use Illuminate\Console\Command;

class VerificarParcelasVencidas extends Command
{
    protected $signature   = 'notificacoes:parcelas-vencidas';
    protected $description = 'Verifica parcelas vencidas e envia notificações';

    public function handle(): void
    {
        $parcelas = Parcela::where('status', 'pendente')
            ->whereDate('data_vencimento', now()->toDateString())
            ->with('pagamento.paciente')
            ->get();

        foreach ($parcelas as $parcela) {
            NotificacaoService::parcelaVencida($parcela);
        }

        $this->info("Verificadas {$parcelas->count()} parcelas vencidas.");
    }
}