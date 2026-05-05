<?php

use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Rotas customizadas ANTES do resource
    Route::get('pacientes/search', [PacienteController::class, 'search'])->name('pacientes.search');
    Route::patch('pacientes/{id}/restore', [PacienteController::class, 'restore'])->name('pacientes.restore');

    // Show e Edit customizados para aceitar withTrashed
    Route::get('pacientes/{id}', function (int $id) {
        $paciente = \App\Models\Paciente::withTrashed()->findOrFail($id);
        return view('pacientes.show', compact('paciente'));
    })->name('pacientes.show');

    Route::get('pacientes/{id}/edit', function (int $id) {
        $paciente = \App\Models\Paciente::withTrashed()->findOrFail($id);
        return view('pacientes.edit', compact('paciente'));
    })->name('pacientes.edit');

    // Resource sem show e edit padrão
    Route::resource('pacientes', PacienteController::class)->except(['show', 'edit']);
    
    
});

require __DIR__.'/auth.php';
