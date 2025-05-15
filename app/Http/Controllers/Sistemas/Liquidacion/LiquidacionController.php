<?php

namespace App\Http\Controllers\Sistemas\Liquidacion;

use App\Http\Controllers\Controller;

use App\Models\SAGE2_1\instarealiq;
use App\Models\Sage\PofIpeModel;
use App\Models\InstitucionExtensionModel;
use App\Models\PadronModel;
use App\Models\POFMH\PofmhModel;
use App\Models\POFMH\CondicionModel;
use App\Models\POFMH\PofmhAulas;
use App\Models\POFMH\PofmhDivisiones;
use App\Models\POFMH\PofmhNovedadesExtras;
use App\Models\POFMH\PofmhTurnos;
use App\Models\POFMH\PofmhActivosModel;
use App\Models\POFMH\PofmhOrigenCargoModel;
use App\Models\POFMH\PofMhSitRev;
use App\Models\POFMH\PofmhNovedades;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LiquidacionImport;
use App\Models\Liquidacion\LiquidacionTempExcelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Concerns\ToArray;

use App\Exports\InconsistenciasExport;
use Illuminate\Support\Facades\Cache;


class LiquidacionController extends Controller
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
        //Estadisticas de liquidacion 
        $totalAgentesCobroIPE = PofIpeModel::where('IPE', 'SI')->count();
        $totalAgentesSinCobroIPE = PofIpeModel::where('IPE', 'NO')->count();
        $totalAgentesSinIPE = PofIpeModel::where('IPE', null)->count();
        $totalAgentes = PofIpeModel::count();

        //Cantidad de escuelas por zonas
        $totalEscuelasPorZonas = DB::table('tb_zonas_liq')
            ->join('tb_institucion_extension', 'tb_zonas_liq.codigo_letra', '=', 'tb_institucion_extension.Zona')
            ->select('tb_zonas_liq.nombre_loc_zona', DB::raw('COUNT(tb_institucion_extension.CUECOMPLETO) as total_escuelas'))
            ->groupBy('tb_zonas_liq.nombre_loc_zona')
            ->get();







        return view('liquidacion.dashboardLiquidacion', compact('totalAgentesCobroIPE', 'totalAgentesSinCobroIPE', 'totalAgentesSinIPE', 'totalAgentes', 'totalEscuelasPorZonas'));
    }

    public function buscar_dni_liq(Request $request)
    {
        //cargo en memoruia el cue a trabajar
        $institucionExtension = InstitucionExtensionModel::where('CUECOMPLETO', 1)
            ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
            ->Join('tb_nivelesensenanza', 'tb_nivelesensenanza.NivelEnsenanza', '=', 'tb_institucion_extension.Nivel')
            ->first();
        $pofmh = PofmhModel::where('CUECOMPLETO', 1)
            //->where('Turno',$institucionExtension->idTurnoUsuario)
            ->orderBy('orden', 'ASC')
            ->get();
        //dd($pofmh);
        //cargo los anexos
        $CargosSalariales =   DB::table('tb_cargossalariales')->get();
        $Condiciones =   CondicionModel::all();
        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $NovedadesExtras = PofmhNovedadesExtras::all();
        $Turnos =   PofmhTurnos::all();
        $Activos =   PofmhActivosModel::all();
        $OrigenesDeCargos = PofmhOrigenCargoModel::all();
        $CargosCreados = PofmhOrigenCargoModel::where('CUECOMPLETO', session('CUECOMPLETO'))->get();
        //$EspCur =   DB::table('tb_turnos_usuario')->get();

        $SitRev =   PofMhSitRev::all(); //DB::table('tb_situacionrevista')->get();
        $Motivos =   DB::table('tb_motivos')->get();
        //dd($Divisiones);
        $datos = array(
            'estado' => "Sin Accion",
            //'CUECOMPLETO'=>$CUECOMPLETO,
            //'institucionExtension'=>$institucionExtension,
            'infoPofMH' => $pofmh,
            'CargosSalariales' => $CargosSalariales,
            'Divisiones' => $Divisiones,
            'Turnos' => $Turnos,
            'SitRev' => $SitRev,
            'Motivos' => $Motivos,
            'Condiciones' => $Condiciones,
            'Aulas' => $Aulas,
            'NovedadesExtras' => $NovedadesExtras,
            'Activos' => $Activos,
            'OrigenesDeCargos' => $OrigenesDeCargos,
            'CargosCreados' => $CargosCreados,
        );

        return view('liquidacion.buscarAgenteDni', $datos);
    }
    public function buscar_cue_liq(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '2028M');

        $pofmh = PofmhModel::where('CUECOMPLETO', 1)
            ->orderBy('orden', 'ASC')
            ->get();

        //cargo los anexos
        $CargosSalariales =   DB::table('tb_cargossalariales')->get();
        $Condiciones =   CondicionModel::all();
        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $NovedadesExtras = PofmhNovedadesExtras::all();
        $Turnos =   PofmhTurnos::all();
        $Activos =   PofmhActivosModel::all();
        $OrigenesDeCargos = PofmhOrigenCargoModel::all();
        $CargosCreados = PofmhOrigenCargoModel::where('CUECOMPLETO', session('CUECOMPLETO'))->get();

        $instarealiq_escu = DB::connection('DB8')->table('instarealiq')
            ->whereNotNull('escu')
            ->select('escu')
            ->groupBy('escu')
            ->get();
        $instarealiq_area = DB::connection('DB8')->table('instarealiq')
            ->whereNotNull('area')
            ->select('area')
            ->groupBy('area')
            ->get();
        $SitRev =   PofMhSitRev::all(); //DB::table('tb_situacionrevista')->get();
        $Motivos =   DB::table('tb_motivos')->get();
        //dd($Divisiones);
        $datos = array(
            'estado' => "Sin Accion",
            //'CUECOMPLETO'=>$CUECOMPLETO,
            //'institucionExtension'=>$institucionExtension,
            'infoPofMH' => $pofmh,
            'CargosSalariales' => $CargosSalariales,
            'Divisiones' => $Divisiones,
            'Turnos' => $Turnos,
            'SitRev' => $SitRev,
            'Motivos' => $Motivos,
            'Condiciones' => $Condiciones,
            'Aulas' => $Aulas,
            'NovedadesExtras' => $NovedadesExtras,
            'instarealiq_escu' => $instarealiq_escu,
            'instarealiq_area' => $instarealiq_area,
            'Activos' => $Activos,
            'OrigenesDeCargos' => $OrigenesDeCargos,
            'CargosCreados' => $CargosCreados,
        );

        return view('liquidacion.buscarCUE', $datos);
    }

    public function buscar_zonas_consultas(Request $request)
    {

        if ($_POST) {
            $indoDesglose = 0;
            $indoDesglose2 = 0;

            if (isset($_POST['btnCUE'])) {
                $indoDesglose = DB::table('tb_desglose_agentes')
                    ->leftjoin('tb_institucion', 'tb_institucion.Unidad_Liquidacion', '=', 'tb_desglose_agentes.escu')
                    ->where(function ($query) use ($request) {
                        $query->where('tb_institucion.CUE', 'like', '%' . $request->cue . '%');
                    })
                    ->select(
                        'tb_institucion.*',
                        'tb_desglose_agentes.*',
                        'tb_desglose_agentes.area as desc_area'
                    )
                    ->get();
            }

            $datos = array(
                'estado' => "Agente Localizado",
                'indoDesglose' => $indoDesglose,

            );
            //dd($indoDesglose);
        } else {
            $indoDesglose = DB::table('tb_desglose_agentes')
                ->join('tb_institucion', 'tb_institucion.Unidad_Liquidacion', '=', 'tb_desglose_agentes.escu')
                // ->join('tb_institucion_extension', 'tb_institucion_extension.idInstitucion', '=', 'tb_institucion.idInstitucion')
                ->where('tb_desglose_agentes.docu', '1')
                ->select(
                    'tb_institucion.*',
                    //'tb_institucion_extension.*',
                    'tb_desglose_agentes.*'
                )
                ->get();


            $datos = array(
                'estado' => "Sin Accion",
                'indoDesglose' => $indoDesglose,
                'dniUsuario' => 1
            );
        }

        return view('bandeja.ADMIN.usuarios_zonas', $datos);
    }
    //funcion manual para ubicar ambito, sector y estado
    public function actualizarValoresInstituciones()
    {
        // Traer todos los padrones
        $padron = PadronModel::get();

        // Iterar sobre cada registro del padron
        foreach ($padron as $p) {
            // Buscar las extensiones correspondientes al CUECOMPLETO
            $inst_Ext = InstitucionExtensionModel::where('CUECOMPLETO', $p->CUECOMPLETO)->get();

            // Verificar si se encontraron extensiones
            if ($inst_Ext->isNotEmpty()) {
                // Iterar sobre cada extensión encontrada para actualizarla
                foreach ($inst_Ext as $extension) {
                    $extension->Ambito = $p->Ambito;
                    $extension->EsPrivada = $p->Sector;
                    $extension->Habilitado = $p->Estado;
                    $extension->Departamento = $p->Departamento;
                    $extension->Localidad = $p->Localidad;
                    $extension->Oferta_Tipo = $p->Oferta_Tipo;
                    $extension->Telefono = $p->Telefono;
                    $extension->save();
                }
            }
        }

        echo "Fin de modificaciones";
    }

    public function listarInstarealiq()
    {
        $datos = DB::connection('DB8')
            ->table('instarealiq')
            ->select(
                'ID_inst_area_liq',
                'ID_CUEA',
                'CUEA',
                'nombreInstitucion',
                'nivel',
                'modalidad',
                'zonaLiq',
                'codZonaLiq',
                'escu',
                'desc_escu',
                'area',
                'NoIPE',
                'ESTADO'
            )
            ->get();

        return view('bandeja.POF.editarInstaReaLiq', compact('datos'));
    }

    public function actualizarInstarealiq(Request $request)
    {
        $cambios = $request->input('cambios');

        if (empty($cambios)) {
            return response()->json(['message' => 'No se enviaron cambios.'], 400);
        }

        foreach ($cambios as $fila) {
            // Actualizar cada fila
            DB::connection('DB8')->table('instarealiq')
                ->where('ID_inst_area_liq', $fila['ID_inst_area_liq'])
                ->update([
                    'ID_CUEA' => $fila['ID_CUEA'],
                    'CUEA' => $fila['CUEA'],
                    'nombreInstitucion' => $fila['nombreInstitucion'],
                    'nivel' => $fila['nivel'],
                    'modalidad' => $fila['modalidad'],
                    'zonaLiq' => $fila['zonaLiq'],
                    'codZonaLiq' => $fila['codZonaLiq'],
                    'escu' => $fila['escu'],
                    'desc_escu' => $fila['desc_escu'],
                    'area' => $fila['area'],
                    'NoIPE' => $fila['NoIPE'],
                    'ESTADO' => $fila['ESTADO'],
                ]);
        }

        return response()->json(['message' => 'Cambios guardados exitosamente.']);
    }

    public function traerTodoAgenteLiq()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2028M');

        // Cargar todas las colecciones necesarias de una vez
        $institucionExtension = InstitucionExtensionModel::Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
            ->leftJoin('tb_nivelesensenanza', 'tb_institucion_extension.Nivel', '=', 'tb_nivelesensenanza.NivelEnsenanza')
            ->select('tb_institucion_extension.*', 'tb_turnos_usuario.*', 'tb_nivelesensenanza.*')
            ->first();

        $instituciones = DB::table('tb_institucion_extension')
            ->select('CUECOMPLETO', 'Nivel', 'Zona', 'ZonaSupervision', 'idTurnoUsuario')
            ->get()
            ->groupBy('CUECOMPLETO');

        $zonas = DB::table('tb_zonas_liq')
            ->pluck('nombre_loc_zona', 'codigo_letra')
            ->toArray();

        $zonasSupervision = DB::table('tb_zonasupervision')
            ->pluck('Codigo', 'idZonaSupervision')
            ->toArray();

        $dnInBase = DB::table('tb_agentes')
            ->pluck('Documento')
            ->toArray();

        $pofmh = PofmhModel::where('CUECOMPLETO', 'not like', '999%')
            ->where('CUECOMPLETO', 'not like', '950%')
            ->orderBy('CUECOMPLETO', 'ASC')
            ->get();

        $CargosSalariales = DB::table('tb_cargossalariales')
            ->pluck('Cargo', 'idCargo')
            ->toArray();

        $CargosSalarialesCodigo = DB::table('tb_cargossalariales')
            ->pluck('Codigo', 'idCargo')
            ->toArray();

        $Condiciones = CondicionModel::pluck('Descripcion', 'idCondicion')->toArray();
        $Aulas = PofmhAulas::pluck('nombre_aula', 'idAula')->toArray();
        $Divisiones = PofmhDivisiones::pluck('nombre_division', 'idDivision')->toArray();
        $Turnos = PofmhTurnos::pluck('nombre_turno', 'idTurno')->toArray();
        $Activos = PofmhActivosModel::pluck('nombre_activo', 'idActivo')->toArray();
        $OrigenesDeCargos = PofmhOrigenCargoModel::pluck('nombre_origen', 'idOrigenCargo')->toArray();

        $CargosCreados = DB::connection('DB7')->table('tb_padt')
            ->join('tb_origenes_cargos', 'tb_padt.idOrigenCargo', '=', 'tb_origenes_cargos.idOrigenCargo')
            ->join('tb_cargos_pof_origen', 'tb_origenes_cargos.nombre_origen', '=', 'tb_cargos_pof_origen.idCargos_Pof_Origen')
            ->pluck('tb_cargos_pof_origen.nombre_cargo_origen', 'tb_padt.idOrigenCargo')
            ->toArray();

        $SitRev = PofMhSitRev::pluck('Descripcion', 'idSituacionRevista')->toArray();
        $Motivos = DB::table('tb_motivos')->pluck('Nombre_Licencia', 'idMotivo')->toArray();
        $MotivosCodigo = DB::table('tb_motivos')->pluck('Codigo', 'idMotivo')->toArray();

        // Procesar los datos
        foreach ($pofmh as $row) {
            if (isset($instituciones[$row->CUECOMPLETO])) {
                $institucion = $instituciones[$row->CUECOMPLETO]->firstWhere('idTurnoUsuario', $row->Turno);
                if ($institucion) {
                    $row->Nivel = $institucion->Nivel ?? 'S/D';
                    $row->Zona = $zonas[$institucion->Zona] ?? 'S/D';
                    $row->ZonaSupervision = $zonasSupervision[$institucion->ZonaSupervision] ?? 'S/D';
                } else {
                    $row->Nivel = 'S/D';
                    $row->Zona = 'S/D';
                    $row->ZonaSupervision = 'S/D';
                }
            } else {
                $row->Nivel = 'S/D';
                $row->Zona = 'S/D';
                $row->ZonaSupervision = 'S/D';
            }

            $row->isDniLoaded = in_array($row->Agente, $dnInBase);
            $row->Origen = $CargosCreados[$row->Origen] ?? 'S/D';
            $row->SitRev = $SitRev[$row->SitRev] ?? 'S/D';
            $row->Cargo = isset($CargosSalariales[$row->Cargo]) ?
                $CargosSalariales[$row->Cargo] . ' (' . $CargosSalarialesCodigo[$row->Cargo] . ')' : 'S/D';
            $row->Aula = $Aulas[$row->Aula] ?? 'S/D';
            $row->Division = $Divisiones[$row->Division] ?? 'S/D';
            $row->Turno = $Turnos[$row->Turno] ?? 'S/D';
            $row->Condicion = $Condiciones[$row->Condicion] ?? 'S/D';
            $row->Activo = $Activos[$row->Activo] ?? 'S/D';
            $row->Motivo = isset($Motivos[$row->Motivo]) ?
                $Motivos[$row->Motivo] . ' (' . $MotivosCodigo[$row->Motivo] . ')' : 'S/D';
            $row->FechaAltaCargo = $row->FechaAltaCargo ? Carbon::parse($row->FechaAltaCargo)->format('Y-m-d') : 'S/D';
            $row->FechaDesignado = $row->FechaDesignado ? Carbon::parse($row->FechaDesignado)->format('Y-m-d') : 'S/D';
            $row->FechaDesde = $row->FechaDesde ? Carbon::parse($row->FechaDesde)->format('Y-m-d') : 'S/D';
            $row->FechaHasta = $row->FechaHasta ? Carbon::parse($row->FechaHasta)->format('Y-m-d') : 'S/D';
        }

        $datos = [
            'mensajeError' => "",
            'institucionExtension' => $institucionExtension,
            'infoPofMH' => $pofmh,
            'mensajeNAV' => 'Panel de Configuración de POF(Modalidad Horizontal)'
        ];

        return view('bandeja.POF.traerTodoAgenteLiq', $datos);
    }

    public function verNovedadesAgenteLiq(Request $request)
    {

        $dni = $request->input('dni');


        session(['AgenteDuplicadoBuscado' => $dni]);

        if (empty($dni)) {
            return response()->json(['error' => 'El DNI no puede estar vacío'], 400);
        }


        $novedadesAgente = PofmhNovedades::where('Agente', $dni)
            ->orderBy('FechaDesde', 'desc')
            ->join('tb_novedades_extras', 'tb_novedades.idNovedadExtra', '=', 'tb_novedades_extras.idNovedadExtra')
            ->get();


        if ($novedadesAgente->isEmpty()) {
            return response()->json(['message' => 'No se encontraron novedades para el agente'], 404);
        }

        $motivos = DB::table('tb_motivos')->pluck('Nombre_Licencia', 'idMotivo')->toArray();

        $motivosCodigo = DB::table('tb_motivos')->pluck('Codigo', 'idMotivo')->toArray();


        foreach ($novedadesAgente as $novedad) {
            $novedad->Motivo = isset($motivos[$novedad->Motivo]) ?
                $motivos[$novedad->Motivo] . ' (' . $motivosCodigo[$novedad->Motivo] . ')' : 'S/D';
        }

        return response()->json($novedadesAgente, 200);
    }

    public function verDocumentosNovedades(Request $request)
    {
        $idAgente = $request->query('idNovedad');

        // // Verificar si el ID del agente es válido
        if (empty($idAgente)) {
            return response()->json(['message' => 'ID de agente no válido'], 400);
        }



        //TODO: traer los documentos relacionados a la novedad del agente. no todos los documentos, solo los relacionados a la novedad


        // // Obtener los documentos del agente
        $documentos = DB::table('tb_documentos')
            ->where('Agente', $idAgente)
            ->get();
        // Verificar si se encontraron documentos
        if ($documentos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron documentos para la novedad'], 404);
        }

        // Retornar una vista parcial con los documentos
        return response()->json($documentos, 200);
    }

    public function cargarExcelLiquidacion()
    {

        $loading = false;

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

    public function compararDatosLiquidacion()
    {
        $datos = [];

        $temporales = LiquidacionTempExcelModel::all();

        $datosAgentesAgrupados  =




            // // Guardar las inconsistencias en caché para su posterior exportación
            // Cache::put('inconsistencias_detectadas', $inconsistencias, 3600); // Guardar por 1 hora en caché


            $datos = [
                'inconsistencias' => [],
                'temporales' => $temporales,
                'oficiales' => [],
                'agentes' => [],
            ];
        // $datos['oficiales'] = $oficiales;
        // $datos['agentes'] = $agentes;



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

    // public function verArbolLiquidacion($idSubOrg) {

    //     $liquidacionInstitucional = instarealiq::select('CUEA', 'escu', 'area', 'nombreInstitucion')
    //         ->where('CUEA', $idSubOrg)
    //         ->distinct()
    //         ->get()
    //         ->toArray();

    //     if (empty($liquidacionInstitucional)) {
    //         return response()->json([
    //             'message' => 'No se encontraron datos de liquidación institucional para el CUEA proporcionado.'
    //         ], 404);
    //     }

    //     $pofIpe = PofIpeModel::select('ApeNom', 'Trabajo', 'IPE', 'escu', 'Area', 'CUECOMPLETO')
    //         ->where('CUECOMPLETO', $idSubOrg)
    //         ->distinct()
    //         ->get()
    //         ->toArray();



    //     return response()->json($pofIpe, 200);
    // }



    // public function verAgentesLiquidacion($idDocumento)
    // {

    //     return response()->json([
    //         'message' => 'No se encontraron datos de liquidación institucional para el CUEA proporcionado.'
    //     ], 404);
    // }

    // public function nuevoAgenteLiquidacion()
    // {
    //     //TODO: Retornar vista o datos necesarios para crear un nuevo agente
    //     return view('liquidacion.nuevoAgente');
    // }

    // public function FormAltaAgenteLiquidacion(Request $request)
    // {
    //     return response()->json([
    //         'message' => 'No se encontraron datos de liquidación institucional para el CUEA proporcionado.'
    //     ], 404);
    // }

    // public function editarAgenteLiquidacion($idAgente)
    // {
    //     return response()->json([
    //         'message' => 'No se encontraron datos de liquidación institucional para el CUEA proporcionado.'
    //     ], 404);
    // }

    // public function FormActualizarAgenteLiquidacion(Request $request)
    // {
    //     return response()->json([
    //         'message' => 'No se encontraron datos de liquidación institucional para el CUEA proporcionado.'
    //     ], 404);
    // }

}
