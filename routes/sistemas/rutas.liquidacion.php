<?php

use App\Http\Controllers\Sistemas\Liquidacion\LiquidacionController;
use App\Http\Controllers\Sistemas\Liquidacion\ControlIpeController;
use App\Http\Controllers\Sistemas\Liquidacion\InformacionInstitucionesController;
use App\Http\Controllers\Sistemas\Liquidacion\CargarExcelLiquidacionController;
use Illuminate\Support\Facades\Route;

$baseUri = '/liquidacion';

Route::controller(LiquidacionController::class)->prefix($baseUri)->group(function () {

    Route::get('/', 'index')->name('liquidacion');
    Route::get('/buscar_dni_liq',  'buscar_dni_liq')->name('buscar_dni_liq');
    Route::get('/buscar_cue_liq',  'buscar_cue_liq')->name('buscar_cue_liq');
    Route::post('/verNovedadesAgente', 'verNovedadesAgenteLiq')->name('verNovedadesAgente');
    Route::get('/verDocumentosNovedades', 'verDocumentosNovedades')->name('verDocumentosNovedades');
});

Route::controller(InformacionInstitucionesController::class)->prefix($baseUri)->group(
    function () {
        Route::get('/informacionInstituciones',  'index')->name('informacionInstituciones'); //TODO: Cambiar la vista a la de liquidacion
        Route::get('/traerTodoAgenteLiq',  'traerTodoAgenteLiq')->name('traerTodoAgenteLiq');
        Route::get('/actualizarValoresInstituciones',  'actualizarValoresInstituciones')->name('actualizarValoresInstituciones');
    }
);

Route::controller(CargarExcelLiquidacionController::class)->prefix($baseUri)->group(function () {
    Route::get('/cargarExcelLiquidacion',  'index')->name('cargarExcelLiquidacion');
    Route::post('/importarExcelLiquidacion', 'importarExcelLiquidacion')->name('importarExcelLiquidacion');
});

Route::controller(ControlIpeController::class)->prefix($baseUri)->group(function () {
    Route::get('/controlIpe',  'index')->name('controlIpe');
});
