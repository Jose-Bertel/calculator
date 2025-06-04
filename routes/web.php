<?php

use App\Livewire\CalculadoraImc;
use App\Livewire\MiProgreso;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::middleware(['auth'])->group(function () {

    Route::get('/calculadora', CalculadoraImc::class)->name('calculadora');
    Route::get('/progreso', MiProgreso::class)->name('progreso');
    // Rutas para Administradores
    Route::middleware(['role:ADMINISTRADOR'])->group(function () {
    });
    // Rutas para Usuairos
    Route::middleware(['role:USUARIO'])->group(function () {
    });
});
