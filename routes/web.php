<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\HerramientasController;

// Rutas públicas
Route::controller(AuthController::class)->group(function() {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

// Rutas protegidas (requieren autenticación y middleware 'dependencia')
Route::middleware(['auth', 'dependencia'])->group(function() {
    // Panel principal
    Route::get('/', function() {
        return view('redidgp');
    })->name('redidgp');
    
    // Redi Externo
    Route::get('/redi-externo', function() {
        return view('rediexterno');
    })->name('redi.externo');

    // Herramientas
    Route::prefix('herramientas')->group(function() {
        Route::get('/mesa-entrada', [MesaController::class, 'mesaEntrada'])->name('herramientas.mesa_entrada');
        Route::get('/compatibilidad', [HerramientasController::class, 'compatibilidad'])->name('herramientas.compatibilidad');
        Route::get('/certificados', [HerramientasController::class, 'certificados'])->name('herramientas.certificados');
        Route::get('/procedimientos', [HerramientasController::class, 'procedimientos'])->name('herramientas.procedimientos');
        Route::get('/asistencia', [HerramientasController::class, 'asistencia'])->name('herramientas.asistencia');
        Route::get('/vencimientos', [HerramientasController::class, 'vencimientos'])->name('herramientas.vencimientos');
        Route::get('/real-prestacion', [HerramientasController::class, 'realPrestacion'])->name('herramientas.real_prestacion');
        Route::get('/plantas', [HerramientasController::class, 'plantas'])->name('herramientas.plantas');
        Route::get('/carga-bases', [HerramientasController::class, 'cargaBases'])->name('herramientas.carga_bases');
        Route::get('/suma-horarios', [HerramientasController::class, 'sumaHorarios'])->name('herramientas.suma_horarios');
    });

    // Rutas para Mesa de Entrada (protegidas por dependencia)
    Route::prefix('mesa')->group(function() {
        Route::post('/registrar', [MesaController::class, 'registrar'])->name('mesa.registrar');
        Route::get('/buscar', [MesaController::class, 'buscar'])->name('mesa.buscar');
    });
});

// Rutas para manejo de documentos (solo requieren autenticación)
Route::middleware('auth')->group(function() {
    Route::post('/mesa/recibir/{id}', [MesaController::class, 'recibir'])->name('mesa.recibir');
    Route::post('/mesa/reenviar/{id}', [MesaController::class, 'reenviar'])->name('mesa.reenviar');
});
    