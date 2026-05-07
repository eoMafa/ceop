<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\CategoriaEstoqueController;
use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\ProcedimentoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProntuarioController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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


    Route::prefix('estoque')->name('estoque.')->group(function () {

        // Categorias
        Route::resource('categorias', CategoriaEstoqueController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        // Fornecedores
        Route::get('fornecedores/search', [FornecedorController::class, 'search'])->name('fornecedores.search');
        Route::patch('fornecedores/{id}/restore', [FornecedorController::class, 'restore'])->name('fornecedores.restore');
        Route::get('fornecedores/{id}/edit', [FornecedorController::class, 'edit'])->name('fornecedores.edit');
        Route::resource('fornecedores', FornecedorController::class)->parameters([
            'fornecedores' => 'fornecedor'
        ])->except(['edit']);

        // Produtos
        Route::get('produtos/search', [ProdutoController::class, 'search'])->name('produtos.search');
        Route::patch('produtos/{id}/restore', [ProdutoController::class, 'restore'])->name('produtos.restore');
        Route::get('produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
        Route::post('produtos/{produto}/movimentar', [ProdutoController::class, 'movimentar'])->name('produtos.movimentar');
        Route::resource('produtos', ProdutoController::class)->except(['edit']);
    });
});

require __DIR__.'/auth.php';
