<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\HerramientasController;
use App\Http\Controllers\HerramientasExternoController;
use App\Http\Controllers\ConsultarBasesController;
use App\Http\Controllers\CruceCompatibilidadController;
use App\Http\Controllers\CertificadosController;
use App\Http\Controllers\CargarBaseUncaController;
use App\Http\Controllers\CargarBaseRelojesController;

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
    // Herramientas
    Route::prefix('herramientas_externo')->group(function () {       
        Route::get('redi-externo/asistencia', [HerramientasExternoController::class, 'asistencia'])->name('herramientas.asistencia_externo');       
        Route::get('redi-externo/real-prestacion', [HerramientasExternoController::class, 'realPrestacion'])->name('herramientas.real_prestacion_externo');
        
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
Route::post('/cargar-bases/unca', [CargarBaseUncaController::class, 'cargar'])->name('cargar.base.unca');
Route::post('/cargar-bases/administracion', [CargarBaseAdmProvController::class, 'cargar'])->name('cargar.base.administracion');
Route::post('/cargar-bases/educacion', [CargarBaseEduProvController::class, 'cargar'])->name('cargar.base.educacion');
Route::post('/cargar-bases/relojes', [CargarBaseRelojesController::class, 'cargar'])->name('cargar.base.relojes');