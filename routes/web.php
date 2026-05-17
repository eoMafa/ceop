<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\CategoriaEstoqueController;
use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvolucaoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\ProcedimentoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProntuarioController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\RelatorioController;
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

Route::middleware('auth')->prefix('notificacoes')->name('notificacoes.')->group(function () {
    Route::get('/', [NotificacaoController::class, 'index'])->name('index');
    Route::get('/count', [NotificacaoController::class, 'count'])->name('count');
    Route::patch('/{notificacao}/ler', [NotificacaoController::class, 'marcarLida'])->name('ler');
    Route::patch('/ler-todas', [NotificacaoController::class, 'marcarTodasLidas'])->name('ler-todas');
    Route::delete('/{notificacao}', [NotificacaoController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', 'permission:usuarios.ver'])->prefix('logs')->name('logs.')->group(function () {
    Route::get('/', [ActivityLogController::class, 'index'])->name('index');
    Route::get('/{log}', [ActivityLogController::class, 'show'])->name('show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Pacientes
    Route::get('pacientes/search', [PacienteController::class, 'search'])->name('pacientes.search')->middleware('permission:pacientes.ver');
    Route::patch('pacientes/{id}/restore', [PacienteController::class, 'restore'])->name('pacientes.restore')->middleware('permission:pacientes.editar');
    Route::resource('pacientes', PacienteController::class)
        ->except(['show', 'edit'])
        ->middleware([
            'index'   => 'permission:pacientes.ver',
            'create'  => 'permission:pacientes.criar',
            'store'   => 'permission:pacientes.criar',
            'update'  => 'permission:pacientes.editar',
            'destroy' => 'permission:pacientes.deletar',
        ]);
        Route::get('pacientes/{id}', function (int $id) {
            $paciente = \App\Models\Paciente::withTrashed()->findOrFail($id);
            return view('pacientes.show', compact('paciente'));
        })->name('pacientes.show')->middleware('permission:pacientes.ver');
        Route::get('pacientes/{id}/edit', function (int $id) {
            $paciente = \App\Models\Paciente::withTrashed()->findOrFail($id);
            return view('pacientes.edit', compact('paciente'));
        })->name('pacientes.edit')->middleware('permission:pacientes.editar');


    // Procedimentos
    Route::get('procedimentos/search', [ProcedimentoController::class, 'search'])->name('procedimentos.search')->middleware('permission:pacientes.ver');
    Route::patch('procedimentos/{id}/restore', [ProcedimentoController::class, 'restore'])->name('procedimentos.restore')->middleware('permission:pacientes.editar');
    Route::resource('procedimentos', ProcedimentoController::class)
        ->except(['show', 'edit'])
        ->middleware([
            'index'   => 'permission:pacientes.ver',
            'create'  => 'permission:pacientes.criar',
            'store'   => 'permission:pacientes.criar',
            'update'  => 'permission:pacientes.editar',
            'destroy' => 'permission:pacientes.deletar',
        ]);
    Route::get('procedimentos/{id}', [ProcedimentoController::class, 'show'])->name('procedimentos.show')->middleware('permission:pacientes.ver');
    Route::get('procedimentos/{id}/edit', [ProcedimentoController::class, 'edit'])->name('procedimentos.edit')->middleware('permission:pacientes.editar');
    
    
    // Usuários
    Route::get('usuarios/search', [UsuarioController::class, 'search'])->name('usuarios.search')->middleware('permission:usuarios.ver');
    Route::patch('usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore')->middleware('permission:usuarios.editar');
    Route::patch('usuarios/{usuario}/password', [UsuarioController::class, 'updatePassword'])->name('usuarios.password')->middleware('permission:usuarios.editar');
    Route::resource('usuarios', UsuarioController::class)->middleware([
        'index'   => 'permission:usuarios.ver',
        'create'  => 'permission:usuarios.criar',
        'store'   => 'permission:usuarios.criar',
        'show'    => 'permission:usuarios.ver',
        'edit'    => 'permission:usuarios.editar',
        'update'  => 'permission:usuarios.editar',
        'destroy' => 'permission:usuarios.deletar',
    ]);


    // Agendamentos
    Route::get('agendamentos/eventos', [AgendamentoController::class, 'eventos'])->name('agendamentos.eventos')->middleware('permission:agendamentos.ver');
    Route::resource('agendamentos', AgendamentoController::class)->middleware([
        'index'   => 'permission:agendamentos.ver',
        'create'  => 'permission:agendamentos.criar',
        'store'   => 'permission:agendamentos.criar',
        'show'    => 'permission:agendamentos.ver',
        'edit'    => 'permission:agendamentos.editar',
        'update'  => 'permission:agendamentos.editar',
        'destroy' => 'permission:agendamentos.deletar',
    ]);


    // Prontuários
    Route::get('pacientes/{paciente}/prontuario', [ProntuarioController::class, 'show'])->name('prontuarios.show')->middleware('permission:prontuario.ver');
    Route::post('prontuarios/{prontuario}/anamnese', [ProntuarioController::class, 'salvarAnamnese'])->name('prontuarios.anamnese')->middleware('permission:prontuario.editar');
    Route::post('prontuarios/{prontuario}/evolucoes', [ProntuarioController::class, 'storeEvolucao'])->name('prontuarios.evolucoes.store')->middleware('permission:prontuario.criar');
    Route::delete('evolucoes/{evolucao}', [ProntuarioController::class, 'destroyEvolucao'])->name('prontuarios.evolucoes.destroy')->middleware('permission:prontuario.deletar');
    Route::delete('evolucao-arquivos/{arquivo}', [ProntuarioController::class, 'destroyArquivo'])->name('prontuarios.arquivos.destroy')->middleware('permission:prontuario.deletar');


    // Convênios
    Route::get('convenios/search', [ConvenioController::class, 'search'])->name('convenios.search')->middleware('permission:financeiro.ver');
    Route::patch('convenios/{id}/restore', [ConvenioController::class, 'restore'])->name('convenios.restore')->middleware('permission:financeiro.editar');
    Route::get('convenios/{id}/edit', [ConvenioController::class, 'edit'])->name('convenios.edit')->middleware('permission:financeiro.editar');
    Route::resource('convenios', ConvenioController::class)->except(['edit', 'show'])->middleware([
        'index'   => 'permission:financeiro.ver',
        'create'  => 'permission:financeiro.criar',
        'store'   => 'permission:financeiro.criar',
        'update'  => 'permission:financeiro.editar',
        'destroy' => 'permission:financeiro.deletar',
    ]);

    // Orçamentos
    Route::patch('orcamentos/{orcamento}/status', [OrcamentoController::class, 'atualizarStatus'])->name('orcamentos.status')->middleware('permission:financeiro.editar');
    Route::resource('orcamentos', OrcamentoController::class)->except(['show'])->middleware([
        'index'   => 'permission:financeiro.ver',
        'create'  => 'permission:financeiro.criar',
        'store'   => 'permission:financeiro.criar',
        'edit'    => 'permission:financeiro.editar',
        'update'  => 'permission:financeiro.editar',
        'destroy' => 'permission:financeiro.deletar',
    ]);
    Route::get('orcamentos/{orcamento}', [OrcamentoController::class, 'show'])->name('orcamentos.show')->middleware('permission:financeiro.ver');
    Route::delete('orcamentos/arquivos/{arquivo}', [OrcamentoController::class, 'destroyArquivo'])->name('orcamentos.arquivos.destroy')->middleware('permission:financeiro.deletar');

    //Evoluções
    Route::get('evolucoes/orcamentos-por-paciente', [EvolucaoController::class, 'orcamentosPorPaciente'])->name('evolucoes.orcamentos-por-paciente');
    Route::delete('evolucoes/arquivos/{arquivo}', [EvolucaoController::class, 'destroyArquivo'])->name('evolucoes.arquivos.destroy');
    Route::resource('evolucoes', EvolucaoController::class)->parameters([
            'evolucoes' => 'evolucao'
        ])->only(['index', 'show', 'create', 'store', 'edit', 'update']);

    // Pagamentos
    Route::patch('parcelas/{parcela}/baixar', [PagamentoController::class, 'baixarParcela'])->name('parcelas.baixar')->middleware('permission:financeiro.editar');
    Route::patch('parcelas/{parcela}/cancelar', [PagamentoController::class, 'cancelarParcela'])->name('parcelas.cancelar')->middleware('permission:financeiro.editar');
    Route::resource('pagamentos', PagamentoController::class)->only(['index', 'create', 'store', 'show', 'destroy'])->middleware([
        'index'   => 'permission:financeiro.ver',
        'create'  => 'permission:financeiro.criar',
        'store'   => 'permission:financeiro.criar',
        'show'    => 'permission:financeiro.ver',
        'destroy' => 'permission:financeiro.deletar',
    ]);
    Route::get('pagamentos/{pagamento}/recibo', [ReciboController::class, 'gerar'])->name('recibos.gerar')->middleware('permission:financeiro.ver');

    // Estoque
    Route::prefix('estoque')->name('estoque.')->group(function () {

        // Categorias
        Route::resource('categorias', CategoriaEstoqueController::class)->only(['index', 'store', 'update', 'destroy'])->middleware([
            'index'   => 'permission:estoque.ver',
            'store'   => 'permission:estoque.criar',
            'update'  => 'permission:estoque.editar',
            'destroy' => 'permission:estoque.deletar',
        ]);

        // Fornecedores
        Route::get('fornecedores/search', [FornecedorController::class, 'search'])->name('fornecedores.search')->middleware('permission:estoque.ver');
        Route::patch('fornecedores/{id}/restore', [FornecedorController::class, 'restore'])->name('fornecedores.restore')->middleware('permission:estoque.editar');
        Route::get('fornecedores/{id}/edit', [FornecedorController::class, 'edit'])->name('fornecedores.edit')->middleware('permission:estoque.editar');
        Route::resource('fornecedores', FornecedorController::class)->parameters([
            'fornecedores' => 'fornecedor'
        ])->except(['edit'])->middleware([
                'index'   => 'permission:estoque.ver',
                'create'  => 'permission:estoque.criar',
                'store'   => 'permission:estoque.criar',
                'show'    => 'permission:estoque.ver',
                'update'  => 'permission:estoque.editar',
                'destroy' => 'permission:estoque.deletar',
            ]);

        // Produtos
        Route::get('produtos/search', [ProdutoController::class, 'search'])->name('produtos.search')->middleware('permission:estoque.ver');
        Route::patch('produtos/{id}/restore', [ProdutoController::class, 'restore'])->name('produtos.restore')->middleware('permission:estoque.editar');
        Route::get('produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit')->middleware('permission:estoque.editar');
        Route::post('produtos/{produto}/movimentar', [ProdutoController::class, 'movimentar'])->name('produtos.movimentar')->middleware('permission:estoque.editar');
        Route::resource('produtos', ProdutoController::class)->except(['edit'])->middleware([
            'index'   => 'permission:estoque.ver',
            'create'  => 'permission:estoque.criar',
            'store'   => 'permission:estoque.criar',
            'show'    => 'permission:estoque.ver',
            'update'  => 'permission:estoque.editar',
            'destroy' => 'permission:estoque.deletar',
        ]);
    });

    //Relatórios
    Route::prefix('relatorios')->name('relatorios.')->middleware('permission:relatorios.ver')->group(function () {
        Route::get('financeiro', [RelatorioController::class, 'financeiro'])->name('financeiro');
        Route::get('lucratividade', [RelatorioController::class, 'lucratividade'])->name('lucratividade');
        Route::get('atendimento', [RelatorioController::class, 'atendimento'])->name('atendimento');
        Route::get('pacientes', [RelatorioController::class, 'pacientes'])->name('pacientes');
        Route::get('estoque', [RelatorioController::class, 'estoque'])->name('estoque');
        Route::get('exportar/{tipo}', [RelatorioController::class, 'exportarPdf'])->name('exportar');
    });
});

require __DIR__.'/auth.php';
