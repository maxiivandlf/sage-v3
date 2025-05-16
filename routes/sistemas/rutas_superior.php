<?php

use App\Http\Controllers\Sistemas\Superior\LlamadosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sistemas\Superior\LlamadosAjaxController;

Route::get('/llamados', [LlamadosController::class, 'index'])->name('llamados.index'); // Mostrar tabla
Route::get('/ver_lom', [LlamadosController::class, 'lom'])->name('ver_lom'); // Mostrar lom
Route::get('/llamados/create', [LlamadosController::class, 'create'])->name('llamados.create'); // Formulario
Route::post('/llamados', [LlamadosController::class, 'store'])->name('llamados.store'); // Guardar datos
// Edición (para luego cargar cargos/espacios)
// Route::get('/llamados/{id}/edit', [LlamadosController::class, 'edit'])->name('llamados.edit');
// Route::post('/llamados/{id}', [LlamadosController::class, 'update'])->name('llamados.update'); // Actualizar datos
//Rutas para llamados
Route::post('/crear-llamado', [LlamadosController::class, 'crearllamado'])->name('llamado.crear');
Route::post('/actualizar-llamado', [LlamadosController::class, 'actualizarLlamado'])->name('llamado.actualizar');
Route::get('/llamado.editar/{id}', [LlamadosController::class, 'editarLlamado'])->name('llamado.editar');
//rutas para lom
Route::post('/agregarLom', [LlamadosController::class, 'agregarLom'])->name('llamado.agregarLom');
Route::post('/obtenerLom', [LlamadosController::class, 'obtenerLomPorLlamado'])->name('llamado.obtenerLom');    
Route::get('/editarLom/{id}', [LlamadosController::class, 'editarLom'])->name('llamado.editarLom');
Route::post('/eliminarLom', [LlamadosController::class, 'eliminarLom'])->name('llamado.eliminarLom');


//rutas para espacios
Route::post('/agregarEspacio', [LlamadosController::class, 'agregarEspacio'])->name('llamado.agregarEspacio');
Route::post('/obtenerEspacios', [LlamadosController::class, 'obtenerEspaciosPorLlamado'])->name('llamado.obtenerEspacios');
Route::post('/eliminarEspacio', [LlamadosController::class, 'eliminarEspacio'])->name('llamado.eliminarEspacio');
Route::post('/editarEspacio', [LlamadosController::class, 'editarEspacio'])->name('llamado.editarEspacio');
// rutas para cargos
Route::post('/agregarCargo', [LlamadosController::class, 'agregarCargo'])->name('llamado.agregarCargo');
Route::post('/obtenerCargos', [LlamadosController::class, 'obtenerCargosPorLlamado'])->name('llamado.obtenerCargos');   
Route::post('/eliminarCargo', [LlamadosController::class, 'eliminarCargo'])->name('llamado.eliminarCargo');
Route::post('/editarCargo', [LlamadosController::class, 'editarCargo'])->name('llamado.editarCargo');
// cambio de estado
Route::post('/llamado/estado', [LlamadosController::class, 'cambiarEstado'])->name('llamado.cambiar.estado');



//crear llamado AJAX
Route::prefix('ajax')->controller(LlamadosAjaxController::class)->group(function () {
    Route::get('/institutos/{zonaId}', 'getInstitutos');
    Route::get('/carreras/{institutoId}', 'getCarreras');
    Route::get('/cargos', 'getCargos');
    Route::get('/espacios', 'getEspacios');
});
