<?php

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


    Route::get('pacientes/search', [App\Http\Controllers\PacienteController::class, 'search'])->name('pacientes.search');
    Route::patch('pacientes/{id}/restore', [App\Http\Controllers\PacienteController::class, 'restore'])->name('pacientes.restore');
    Route::get('pacientes/{id}/edit', function (int $id) {
        $paciente = \App\Models\Paciente::withTrashed()->findOrFail($id);
        return view('pacientes.edit', compact('paciente'));
    })->name('pacientes.edit');
    Route::resource('pacientes', App\Http\Controllers\PacienteController::class)->except(['edit']);
    
    
});

require __DIR__.'/auth.php';
