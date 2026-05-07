<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\ProcedimentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProntuarioController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/register', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('pacientes/search', [PacienteController::class, 'search'])->name('pacientes.search');
    Route::patch('pacientes/{id}/restore', [PacienteController::class, 'restore'])->name('pacientes.restore');
    Route::resource('pacientes', PacienteController::class)->except(['show', 'edit']);
    Route::get('pacientes/{id}', function (int $id) {
        $paciente = \App\Models\Paciente::withTrashed()->findOrFail($id);
        return view('pacientes.show', compact('paciente'));
    })->name('pacientes.show');
    Route::get('pacientes/{id}/edit', function (int $id) {
        $paciente = \App\Models\Paciente::withTrashed()->findOrFail($id);
        return view('pacientes.edit', compact('paciente'));
    })->name('pacientes.edit');


    Route::get('procedimentos/search', [ProcedimentoController::class, 'search'])->name('procedimentos.search');
    Route::patch('procedimentos/{id}/restore', [ProcedimentoController::class, 'restore'])->name('procedimentos.restore');
    Route::resource('procedimentos', ProcedimentoController::class)->except(['show', 'edit']);
    Route::get('procedimentos/{id}', [ProcedimentoController::class, 'show'])->name('procedimentos.show');
    Route::get('procedimentos/{id}/edit', [ProcedimentoController::class, 'edit'])->name('procedimentos.edit');
    

    Route::get('usuarios/search', [UsuarioController::class, 'search'])->name('usuarios.search');
    Route::patch('usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');
    Route::patch('usuarios/{usuario}/password', [UsuarioController::class, 'updatePassword'])->name('usuarios.password');
    Route::resource('usuarios', UsuarioController::class);


    Route::get('agendamentos/eventos', [AgendamentoController::class, 'eventos'])->name('agendamentos.eventos');
    Route::resource('agendamentos', AgendamentoController::class);


    Route::get('pacientes/{paciente}/prontuario', [ProntuarioController::class, 'show'])->name('prontuarios.show');
    Route::post('prontuarios/{prontuario}/anamnese', [ProntuarioController::class, 'salvarAnamnese'])->name('prontuarios.anamnese');
    Route::post('prontuarios/{prontuario}/evolucoes', [ProntuarioController::class, 'storeEvolucao'])->name('prontuarios.evolucoes.store');
    Route::delete('evolucoes/{evolucao}', [ProntuarioController::class, 'destroyEvolucao'])->name('prontuarios.evolucoes.destroy');
    Route::delete('evolucao-arquivos/{arquivo}', [ProntuarioController::class, 'destroyArquivo'])->name('prontuarios.arquivos.destroy');


    // Convênios
    Route::get('convenios/search', [ConvenioController::class, 'search'])->name('convenios.search');
    Route::patch('convenios/{id}/restore', [ConvenioController::class, 'restore'])->name('convenios.restore');
    Route::get('convenios/{id}/edit', [ConvenioController::class, 'edit'])->name('convenios.edit');
    Route::resource('convenios', ConvenioController::class)->except(['edit', 'show']);

    // Orçamentos
    Route::patch('orcamentos/{orcamento}/status', [OrcamentoController::class, 'atualizarStatus'])->name('orcamentos.status');
    Route::resource('orcamentos', OrcamentoController::class)->except(['show']);
    Route::get('orcamentos/{orcamento}', [OrcamentoController::class, 'show'])->name('orcamentos.show');

    // Pagamentos
    Route::patch('parcelas/{parcela}/baixar', [PagamentoController::class, 'baixarParcela'])->name('parcelas.baixar');
    Route::patch('parcelas/{parcela}/cancelar', [PagamentoController::class, 'cancelarParcela'])->name('parcelas.cancelar');
    Route::resource('pagamentos', PagamentoController::class)->only(['index', 'create', 'store', 'show', 'destroy']);


    Route::get('pagamentos/{pagamento}/recibo', [ReciboController::class, 'gerar'])->name('recibos.gerar');
});

require __DIR__.'/auth.php';
