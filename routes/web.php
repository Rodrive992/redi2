<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\HerramientasController;
use App\Http\Controllers\HerramientasExternoController;
use App\Http\Controllers\HerramientasAsistenciaController;
use App\Http\Controllers\AsistenciaDgpController;
use App\Http\Controllers\ExportarAsistenciaDgpController;
use App\Http\Controllers\ConsultarBasesController;
use App\Http\Controllers\CruceCompatibilidadController;
use App\Http\Controllers\CertificadosController;
use App\Http\Controllers\CargarBaseUncaController;
use App\Http\Controllers\CargarBaseRelojesController;
use App\Http\Controllers\CargarBaseEduProvController;
use App\Http\Controllers\RealPrestacionController;
use App\Http\Controllers\RealPrestacionControlController;
use App\Http\Controllers\RealPrestacionHistorialController;

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
        Route::get('/real-prestacion-historial', [HerramientasController::class, 'realPrestacionHistorial'])->name('herramientas.real_prestacion_historial');
         Route::get('/real-prestacion-control', [RealPrestacionControlController::class, 'index'])->name('herramientas.real_prestacion_control');
        Route::get('/real-prestacion-historial', [RealPrestacionHistorialController::class, 'index_dgp'])->name('herramientas.real_prestacion_historial');
        Route::get('/real-prestacion', [HerramientasController::class, 'realPrestacion'])->name('herramientas.real_prestacion');
        Route::get('/consultar-bases', [ConsultarBasesController::class, 'index'])->name('herramientas.consultar_bases');
        Route::get('/exportar-bases', [ConsultarBasesController::class, 'exportar'])->name('herramientas.exportar_bases');
        Route::get('/carga-bases', [HerramientasController::class, 'cargaBases'])->name('herramientas.carga_bases');
        Route::get('/suma-horarios', [HerramientasController::class, 'sumaHorarios'])->name('herramientas.suma_horarios');
    });
    // Herramientas-Externo
        Route::prefix('herramientas_externo')->group(function () {
        Route::get('redi-externo/asistencia_externo', [HerramientasExternoController::class, 'asistenciaExterno'])->name('herramientas.asistencia_externo');
        Route::get('redi-externo/real-prestacion', [HerramientasExternoController::class, 'realPrestacion'])->name('herramientas.real_prestacion_externo');
        Route::get('real_prestacion_historial_externo', [RealPrestacionHistorialController::class, 'index_externo'])->name('herramientas.real_prestacion_historial_externo');
        Route::get('real_prestacion_externo', [RealPrestacionController::class, 'index'])->name('herramientas.real_prestacion_externo');
        Route::get('real_prestacion_externo/descargar-plantilla', [RealprestacionController::class, 'descargarPlantilla'])->name('real_prestacion_externo.descargar_plantilla');
        Route::post('real_prestacion_externo/subir-archivo', [RealPrestacionController::class, 'subirArchivo'])->name('real_prestacion_externo.subir_archivo');
        Route::delete('real_prestacion/borrar/{id}', [RealPrestacionHistorialController::class, 'borrar'])->name('real_prestacion.borrar');        
        Route::post('real_prestacion/autorizar/{id}', [RealPrestacionHistorialController::class, 'autorizar'])->name('real_prestacion.autorizar');
    });
        // Herramientas-asistencia
        Route::prefix('herramientas_asistencia')->group(function () {
        Route::get('/panel_control', [HerramientasAsistenciaController::class, 'panelControl'])->name('herramientas.panel_control');
        Route::get('/calcular_horas', [HerramientasAsistenciaController::class, 'calcularHoras'])->name('herramientas.calcular_horas');
        Route::post('/guardar_control', [HerramientasAsistenciaController::class, 'guardarControl'])->name('herramientas_asistencia.guardar_control');
        Route::post('/guardar_legajo', [HerramientasAsistenciaController::class, 'guardarLegajo'])->name('herramientas_asistencia.guardar_legajo');
        Route::post('/guardar_reloj', [HerramientasAsistenciaController::class, 'guardarReloj'])->name('herramientas_asistencia.guardar_reloj');
        
        // Nuevas rutas para editar y eliminar
        Route::post('/actualizar_control/{id}', [HerramientasAsistenciaController::class, 'actualizarControl'])->name('herramientas_asistencia.actualizar_control');
        Route::delete('/eliminar_control/{id}', [HerramientasAsistenciaController::class, 'eliminarControl'])->name('herramientas_asistencia.eliminar_control');
        
        Route::post('/actualizar_legajo/{id}', [HerramientasAsistenciaController::class, 'actualizarLegajo'])->name('herramientas_asistencia.actualizar_legajo');
        Route::delete('/eliminar_legajo/{id}', [HerramientasAsistenciaController::class, 'eliminarLegajo'])->name('herramientas_asistencia.eliminar_legajo');
        
        Route::post('/actualizar_reloj/{id}', [HerramientasAsistenciaController::class, 'actualizarReloj'])->name('herramientas_asistencia.actualizar_reloj');
        Route::delete('/eliminar_reloj/{id}', [HerramientasAsistenciaController::class, 'eliminarReloj'])->name('herramientas_asistencia.eliminar_reloj');
    });
    // Informe Asistencia
        Route::get('/asistencia', [AsistenciaDgpController::class, 'index'])->name('asistencia.index');
        Route::post('/asistencia/consultar', [AsistenciaDgpController::class, 'consultarInforme'])->name('asistencia.consultar');
    // Exportar Asistencia
        Route::post('/asistencia/exportar', [ExportarAsistenciaDgpController::class, 'exportar'])->name('asistencia.exportar');
    
    
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
// Cargar bases
Route::post('/cargar-bases/unca', [CargarBaseUncaController::class, 'cargar'])->name('cargar.base.unca');
Route::post('/cargar-bases/administracion', [CargarBaseAdmProvController::class, 'cargar'])->name('cargar.base.administracion');
Route::post('/cargar-bases/educacion', [CargarBaseEduProvController::class, 'cargar'])->name('cargar.base.educacion');
Route::post('/cargar-bases/relojes', [CargarBaseRelojesController::class, 'cargar'])->name('cargar.base.relojes');
