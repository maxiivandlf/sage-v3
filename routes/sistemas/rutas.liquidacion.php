<?php

use App\Http\Controllers\Sistemas\Liquidacion\LiquidacionController;
use Illuminate\Support\Facades\Route;

Route::controller(LiquidacionController::class)->group(function () {
    $baseUri = '/liquidacion';
    Route::get($baseUri, 'index')->name('liquidacion');
    Route::get($baseUri . '/buscar_dni_liq',  'buscar_dni_liq')->name('buscar_dni_liq');
    Route::get($baseUri . '/buscar_cue_liq',  'buscar_cue_liq')->name('buscar_cue_liq');

    Route::get($baseUri . '/areaLiqudacionInstitucional',  'listarInstarealiq')->name('areaLiqudacionInstitucional'); //TODO: Cambiar la vista a la de liquidacion
    Route::get($baseUri . '/traerTodoAgenteLiq',  'traerTodoAgenteLiq')->name('traerTodoAgenteLiq');

    // Route::get($baseUri . '/buscar_zonas_consultas',  'buscar_zonas_consultas')->name('buscar_zonas_consultas');
    // Route::post($baseUri . '/buscar_zonas_consultas',  'buscar_zonas_consultas')->name('buscar_zonas_consultas');
    // Route::get($baseUri . '/actualizarValoresInstituciones',  'actualizarValoresInstituciones')->name('actualizarValoresInstituciones');
    Route::post($baseUri . '/verNovedadesAgente', 'verNovedadesAgenteLiq')->name('verNovedadesAgente');
    Route::get('/liquidacion/verDocumentosNovedades', 'verDocumentosNovedades')->name('verDocumentosNovedades');

    Route::get($baseUri . '/cargarExcelLiquidacion',  'cargarExcelLiquidacion')->name('cargarExcelLiquidacion');
    Route::post($baseUri . '/importarExcelLiquidacion', 'importarExcelLiquidacion')->name('importarExcelLiquidacion');

    Route::get($baseUri . '/compararDatosLiquidacion', 'compararDatosLiquidacion')->name('compararDatosLiquidacion');
    Route::get($baseUri . '/exportarInconsistencias',  'exportarInconsistencias')->name('exportarInconsistencias');
});
