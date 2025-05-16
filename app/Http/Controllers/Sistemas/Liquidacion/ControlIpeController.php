<?php

namespace App\Http\Controllers\Sistemas\Liquidacion;

use App\Exports\InconsistenciasExport;
use App\Http\Controllers\Controller;
use App\Models\AgenteModel;
use App\Models\Liquidacion\AgentesAgrupadosModel;
use App\Models\Liquidacion\LiquidacionTempExcelModel;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cache;
use App\Services\IpeAsignadorService;
use Maatwebsite\Excel\Facades\Excel;

class ControlIpeController extends Controller
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

    public function index(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $datos = [];
        $inconsistencias = [];
        $enInstituciones = [];
        $sinInstitucion = [];
        $agentesconIPE = [];
        $agentessinIPE = [];

        $search = $request->input('search');

        $datosCagadosDesdeLiquidacion = LiquidacionTempExcelModel::when($search, function ($query, $search) {
            return $query->where('docu', 'like', "%$search%")
                ->orWhere('nomb', 'like', "%$search%");
        })->paginate(50);
        // $datosCagadosDesdeLiquidacion = LiquidacionTempExcelModel::paginate(50);
        // dd($datosCagadosDesdeLiquidacion->docu);

        // $agentesAgrupados = AgentesAgrupadosModel::all();
        // dd($AgentesAgrupados);

        $asignador = new IpeAsignadorService();

        foreach ($datosCagadosDesdeLiquidacion as $agente) {


            $agrup = AgentesAgrupadosModel::where('docu', $agente->docu)->get();

            if ($agrup->isNotEmpty()) {
                $validacion = $asignador->asignar($agente->toArray(), $agrup);
                $enInstituciones[] = [
                    'docu' => $agente->docu,
                    'agente' => $agente->toArray(),
                    'agrup' => $agrup->toArray(),
                    'validacion' => $validacion
                ];
            } else {
                $sinInstitucion[] = [
                    'docu' => $agente->docu,
                    'agente' => $agente->toArray(),
                ];
            }
        }

        foreach ($enInstituciones as $agenteEnInst) {
            $validacion = $agenteEnInst['validacion'];

            foreach ($validacion['unidadesValidadas'] as $unidad) {

                if (!$unidad['coincide']) {
                    $inconsistencias[] = $agenteEnInst;
                    break;
                }
            }

            if ($validacion['ipe'] == 'SI') {

                $agentesconIPE[] = $agenteEnInst;
            } else {
                $agentessinIPE[] = $agenteEnInst;
            }
        }

        // // Guardar las inconsistencias en caché para su posterior exportación
        // Cache::put('inconsistencias_detectadas', $inconsistencias, 3600); // Guardar por 1 hora en caché


        $datos = [
            'inconsistencias' => $inconsistencias,
            'agentesConIpe' => $agentesconIPE,
            'agentesSinIpe' => $agentessinIPE,
            'sinInstitucion' => $sinInstitucion,
            'paginacion' => $datosCagadosDesdeLiquidacion,
        ];
        //  dd($inconsistencias);

        return view('liquidacion.controlLiquidacion', compact('datos'));
    }


    public function exportarInconsistencias()
    {
        $inconsistencias = Cache::get('inconsistencias_detectadas', []);

        if (empty($inconsistencias)) {
            return redirect()->route('compararDatosLiquidacion')->with('error', 'No hay inconsistencias para exportar.');
        }

        return Excel::download(new InconsistenciasExport($inconsistencias), 'inconsistencias_liquidacion.xlsx');
    }
}
