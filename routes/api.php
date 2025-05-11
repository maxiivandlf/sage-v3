<?php

use App\Http\Controllers\Sistemas\Sage\ConsultasController;
use App\Http\Controllers\Sistemas\Liquidacion\LiquidacionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/consulta', [ConsultasController::class, 'consulta'])->name('consulta');

Route::controller(LiquidacionController::class)->group(function () {
    $baseUri = '/liquidacion';
    Route::get($baseUri, 'index')->name('liquidacion');
    Route::get($baseUri . '/verArbolLiquidacion/{idSubOrg}', 'verArbolLiquidacion')->name('verArbolLiquidacion');
    Route::get($baseUri . '/verAgentesLiquidacion/{idPlaza}', 'verAgentesLiquidacion')->name('verAgentesLiquidacion');
    Route::get($baseUri . '/nuevoAgenteLiquidacion', 'nuevoAgenteLiquidacion')->name('nuevoAgenteLiquidacion');
    Route::post($baseUri . '/formNuevoAgenteLiquidacion', 'FormNuevoAgenteLiquidacion')->name('formNuevoAgenteLiquidacion');
    Route::get($baseUri . '/editarAgenteLiquidacion/{idAgente}', 'editarAgenteLiquidacion')->name('editarAgenteLiquidacion');
    Route::post($baseUri . '/formActualizarAgenteLiquidacion', 'FormActualizarAgenteLiquidacion')->name('formActualizarAgenteLiquidacion');
    Route::post($baseUri . '/verNovedadesAgente', 'verNovedadesAgenteLiq')->name('verNovedadesAgente');
});

//Route::get('/liquidacion', [LiquidacionController::class, 'liquidacion'])->name('liquidacion');
