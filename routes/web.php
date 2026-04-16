<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('asistencia')->group(function () {
        Route::get('/', function() { return view('asistencia.index'); })->name('asistencia.index');
        Route::get('/crear', function() { return view('asistencia.create'); })->name('asistencia.create');
        Route::get('/reportes', function() { return view('asistencia.reportes'); })->name('asistencia.reportes');
    });

    Route::prefix('personal')->group(function () {
        Route::get('/', function() { return view('personal.index'); })->name('personal.index');
        Route::get('/crear', function() { return view('personal.create'); })->name('personal.create');
    });

    Route::prefix('configuracion')->group(function () {
        Route::get('/', function() { return view('configuracion.index'); })->name('configuracion.index');
    });
});

Route::get('/login', function () {
    return response()->json(['error' => 'No autenticado'], 401);
})->name('login');
