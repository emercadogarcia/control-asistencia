<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\ConfiguracionController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rutas públicas de autenticación
Route::get('/login', function () {
    return view('login');
})->name('login');

// Rutas protegidas (verificación con tokens en el cliente)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ASISTENCIA
Route::prefix('asistencia')->group(function () {
    Route::get('/', [AsistenciaController::class, 'index'])->name('asistencia.index');
    Route::get('/crear', [AsistenciaController::class, 'crear'])->name('asistencia.crear');
    Route::get('/buscar-personal', [AsistenciaController::class, 'buscarPersonal'])->name('asistencia.buscar');
    Route::post('/marcar', [AsistenciaController::class, 'marcar'])->name('asistencia.marcar');
    Route::get('/reporte', [AsistenciaController::class, 'reporteDiario'])->name('asistencia.reporte');
    Route::get('/exportar', [AsistenciaController::class, 'exportarExcel'])->name('asistencia.exportar');
});

// PERSONAL
Route::prefix('personal')->group(function () {
    Route::get('/', [PersonalController::class, 'index'])->name('personal.index');
    Route::get('/crear', [PersonalController::class, 'crear'])->name('personal.crear');
    Route::post('/guardar', [PersonalController::class, 'guardar'])->name('personal.guardar');
    Route::get('/{id}/editar', [PersonalController::class, 'editar'])->name('personal.editar');
    Route::post('/{id}', [PersonalController::class, 'actualizar'])->name('personal.actualizar');
    Route::get('/{id}/eliminar', [PersonalController::class, 'eliminar'])->name('personal.eliminar');
});

// CONFIGURACIÓN
Route::prefix('configuracion')->group(function () {
    Route::get('/', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    
    // Sucursales
    Route::get('/sucursales', [ConfiguracionController::class, 'sucursales'])->name('configuracion.sucursales');
    Route::post('/sucursales', [ConfiguracionController::class, 'guardarSucursal'])->name('configuracion.sucursales.crear');
    Route::get('/sucursales/{id}/editar', [ConfiguracionController::class, 'editarSucursal'])->name('configuracion.sucursales.editar');
    Route::post('/sucursales/{id}', [ConfiguracionController::class, 'guardarSucursal'])->name('configuracion.sucursales.actualizar');
    Route::get('/sucursales/{id}/eliminar', [ConfiguracionController::class, 'eliminarSucursal'])->name('configuracion.sucursales.eliminar');
    
    // Turnos
    Route::get('/turnos', [ConfiguracionController::class, 'turnos'])->name('configuracion.turnos');
    Route::post('/turnos', [ConfiguracionController::class, 'guardarTurno'])->name('configuracion.turnos.crear');
    Route::get('/turnos/{id}/editar', [ConfiguracionController::class, 'editarTurno'])->name('configuracion.turnos.editar');
    Route::post('/turnos/{id}', [ConfiguracionController::class, 'guardarTurno'])->name('configuracion.turnos.actualizar');
    Route::get('/turnos/{id}/eliminar', [ConfiguracionController::class, 'eliminarTurno'])->name('configuracion.turnos.eliminar');
    
    // Calendario Laboral
    Route::get('/calendario', [ConfiguracionController::class, 'calendario'])->name('configuracion.calendario');
    Route::post('/calendario', [ConfiguracionController::class, 'guardarEvento'])->name('configuracion.calendario.crear');
    Route::get('/calendario/{id}/editar', [ConfiguracionController::class, 'editarEvento'])->name('configuracion.calendario.editar');
    Route::post('/calendario/{id}', [ConfiguracionController::class, 'guardarEvento'])->name('configuracion.calendario.actualizar');
    Route::get('/calendario/{id}/eliminar', [ConfiguracionController::class, 'eliminarEvento'])->name('configuracion.calendario.eliminar');
    
    // Reset Base de Datos
    Route::post('/reset', [ConfiguracionController::class, 'reset'])->name('configuracion.reset');
});
