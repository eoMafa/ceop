<?php

use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ProcedimentoController;
use App\Http\Controllers\ProfileController;
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
    Route::get('pacientes/{id}', function (int $id) {
        $paciente = \App\Models\Paciente::withTrashed()->findOrFail($id);
        return view('pacientes.show', compact('paciente'));
    })->name('pacientes.show');
    Route::get('pacientes/{id}/edit', function (int $id) {
        $paciente = \App\Models\Paciente::withTrashed()->findOrFail($id);
        return view('pacientes.edit', compact('paciente'));
    })->name('pacientes.edit');
    Route::resource('pacientes', PacienteController::class)->except(['show', 'edit']);
    

    Route::get('procedimentos/search', [ProcedimentoController::class, 'search'])->name('procedimentos.search');
    Route::patch('procedimentos/{id}/restore', [ProcedimentoController::class, 'restore'])->name('procedimentos.restore');
    Route::resource('procedimentos', ProcedimentoController::class)->except(['show', 'edit']);
    Route::get('procedimentos/{id}', [ProcedimentoController::class, 'show'])->name('procedimentos.show');
    Route::get('procedimentos/{id}/edit', [ProcedimentoController::class, 'edit'])->name('procedimentos.edit');
    
});

require __DIR__.'/auth.php';
