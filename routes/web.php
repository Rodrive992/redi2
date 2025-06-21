<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\HerramientasController;
use App\Http\Controllers\ConsultarBasesController;
use App\Http\Controllers\CruceCompatibilidadController;
use App\Http\Controllers\CertificadosController;

// Rutas públicas
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

// Rutas protegidas
Route::middleware(['auth', 'dependencia'])->group(function () {
    Route::get('/', function () {
        return view('redidgp');
    })->name('redidgp');

    Route::get('/redi-externo', function () {
        return view('rediexterno');
    })->name('redi.externo');

    // Herramientas
    Route::prefix('herramientas')->group(function () {
        Route::get('/mesa-entrada', [MesaController::class, 'mesaEntrada'])->name('herramientas.mesa_entrada');
        Route::get('/compatibilidad', [CruceCompatibilidadController::class, 'compatibilidad'])->name('herramientas.compatibilidad');
        Route::get('/exportar-compatibilidad', [CruceCompatibilidadController::class, 'exportarCompatibilidad'])->name('herramientas.exportar_compatibilidad');
        Route::get('/certificados', [CertificadosController::class, 'certificados'])->name('herramientas.certificados');
        Route::post('/exportar-certificados', [CertificadosController::class, 'exportarCertificados'])->name('herramientas.exportar_certificados');
        Route::get('/procedimientos', [HerramientasController::class, 'procedimientos'])->name('herramientas.procedimientos');
        Route::get('/asistencia', [HerramientasController::class, 'asistencia'])->name('herramientas.asistencia');
        Route::get('/vencimientos', [HerramientasController::class, 'vencimientos'])->name('herramientas.vencimientos');
        Route::get('/real-prestacion', [HerramientasController::class, 'realPrestacion'])->name('herramientas.real_prestacion');
        Route::get('/consultar-bases', [ConsultarBasesController::class, 'index'])->name('herramientas.consultar_bases');
        Route::get('/exportar-bases', [ConsultarBasesController::class, 'exportar'])->name('herramientas.exportar_bases');
        Route::get('/carga-bases', [HerramientasController::class, 'cargaBases'])->name('herramientas.carga_bases');
        Route::get('/suma-horarios', [HerramientasController::class, 'sumaHorarios'])->name('herramientas.suma_horarios');
    });

    // Mesa de Entrada
    Route::prefix('mesa')->group(function () {
        Route::post('/registrar', [MesaController::class, 'registrar'])->name('mesa.registrar');
        Route::get('/buscar', [MesaController::class, 'buscar'])->name('mesa.buscar');
        Route::get('/editar/{id}', [MesaController::class, 'editar'])->name('mesa.editar');
        Route::put('/actualizar/{id}', [MesaController::class, 'actualizar'])->name('mesa.actualizar');
        Route::delete('/eliminar/{id}', [MesaController::class, 'eliminar'])->name('mesa.eliminar');
    });
});

// Rutas para documentos
Route::middleware('auth')->group(function () {
    Route::post('/mesa/recibir/{id}', [MesaController::class, 'recibir'])->name('mesa.recibir');
    Route::post('/mesa/reenviar/{id}', [MesaController::class, 'reenviar'])->name('mesa.reenviar');
});