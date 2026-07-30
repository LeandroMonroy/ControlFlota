<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\HistorialMantencionController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\MantencionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('vehiculos.index');
    Route::post('/vehiculos', [VehiculoController::class, 'store'])->middleware('can:vehiculos.editar')->name('vehiculos.store');
    Route::put('/vehiculos/{vehiculo}', [VehiculoController::class, 'update'])->middleware('can:vehiculos.editar')->name('vehiculos.update');
    Route::delete('/vehiculos/{vehiculo}', [VehiculoController::class, 'destroy'])->middleware('can:administrador')->name('vehiculos.destroy');

    Route::get('/documentos', [DocumentoController::class, 'index'])->name('documentos.index');
    Route::post('/documentos', [DocumentoController::class, 'store'])->middleware('can:documentos.editar')->name('documentos.store');
    Route::put('/documentos/{documento}', [DocumentoController::class, 'update'])->middleware('can:documentos.editar')->name('documentos.update');
    Route::delete('/documentos/{documento}', [DocumentoController::class, 'destroy'])->middleware('can:administrador')->name('documentos.destroy');

    Route::get('/mantenciones', [MantencionController::class, 'index'])->name('mantenciones.index');
    Route::post('/mantenciones', [MantencionController::class, 'store'])->middleware('can:mantenciones.editar')->name('mantenciones.store');
    Route::put('/mantenciones/{mantencion}', [MantencionController::class, 'update'])->middleware('can:mantenciones.editar')->name('mantenciones.update');
    Route::delete('/mantenciones/{mantencion}', [MantencionController::class, 'destroy'])->middleware('can:administrador')->name('mantenciones.destroy');

    Route::get('/historial', [HistorialMantencionController::class, 'index'])->name('historial.index');
    Route::post('/historial', [HistorialMantencionController::class, 'store'])->middleware('can:mantenciones.editar')->name('historial.store');

    Route::get('/informes', [InformeController::class, 'index'])->name('informes.index');
    Route::get('/informes/vehiculos', [InformeController::class, 'vehiculos'])->name('informes.vehiculos');
    Route::get('/informes/documentos', [InformeController::class, 'documentos'])->name('informes.documentos');
    Route::get('/informes/mantenciones', [InformeController::class, 'mantenciones'])->name('informes.mantenciones');
    Route::get('/informes/historial', [InformeController::class, 'historial'])->name('informes.historial');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('can:administrador')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    });
});

require __DIR__.'/auth.php';
