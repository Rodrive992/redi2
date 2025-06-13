<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MesaController;

// Rutas públicas
Route::controller(AuthController::class)->group(function() {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

// Rutas protegidas
Route::middleware(['auth', 'dependencia'])->group(function() {
    Route::get('/', function() {
        return view('redidgp');
    })->name('redidgp');
    
    Route::get('/redi-externo', function() {
        return view('rediexterno');
    })->name('redi.externo');
});

Route::middleware('auth')->group(function() {
    Route::post('/mesa/recibir', [MesaController::class, 'recibirTodos'])->name('mesa.recibir');
    Route::post('/mesa/reenviar', [MesaController::class, 'reenviarTodos'])->name('mesa.reenviar');
});