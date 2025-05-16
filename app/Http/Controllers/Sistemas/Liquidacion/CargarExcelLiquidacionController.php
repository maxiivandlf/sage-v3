<?php

namespace App\Http\Controllers\Sistemas\Liquidacion;

use App\Http\Controllers\Controller;
use App\Imports\LiquidacionImport;
use App\Models\Liquidacion\LiquidacionTempExcelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class CargarExcelLiquidacionController extends Controller
{
    public function __construct()
    {
        // Verificar la sesión en cada llamada al controlador
        $this->middleware(function ($request, $next) {
            // Verificar si la sesión 'Usuario' está activa
            if (!Session::has('Usuario')) {
                // Redirigir a la raíz si la sesión no está activa
                return Redirect::to('/');
            }

            return $next($request);
        });
    }

    public function index()
    {

        return view('liquidacion.cargarExcelLiq');
    }

    public function importarExcelLiquidacion(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '2028M');

        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls'
        ]);

        LiquidacionTempExcelModel::truncate();

        Excel::import(new LiquidacionImport, $request->file('archivo'));

        return redirect()->route('cargarExcelLiquidacion')->with('success', 'Archivo importado correctamente.');
    }
}
