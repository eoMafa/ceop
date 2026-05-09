<?php

namespace App\Console\Commands;

use App\Models\Paciente;
use App\Services\NotificacaoService;
use Illuminate\Console\Command;

class VerificarAniversarios extends Command
{
    protected $signature   = 'notificacoes:aniversarios';
    protected $description = 'Verifica aniversários do dia e envia notificações';

    public function handle(): void
    {
        $pacientes = Paciente::whereMonth('data_nascimento', now()->month)
            ->whereDay('data_nascimento', now()->day)
            ->whereNotNull('data_nascimento')
            ->get();

        foreach ($pacientes as $paciente) {
            NotificacaoService::aniversario($paciente);
        }

        $this->info("Verificados {$pacientes->count()} aniversários.");
    }
}