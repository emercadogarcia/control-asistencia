<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReporteController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('reportes')->group(function () {
    Route::get('/asistencia-diaria', [ReporteController::class, 'asistenciaDiaria']);
    Route::get('/atrasos', [ReporteController::class, 'atrasos']);
    Route::get('/horas-trabajadas', [ReporteController::class, 'horasTrabajadas']);
    Route::get('/horas-extra', [ReporteController::class, 'horasExtra']);
    Route::get('/inasistencias', [ReporteController::class, 'inasistencias']);
});
