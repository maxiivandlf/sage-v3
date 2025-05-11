<?php

namespace App\Http\Controllers;

use App\Models\InstitucionExtensionModel;
use App\Models\SitRevModel;
use App\Models\Nodo;
use App\Models\POFMH\CargoOrigenPofMHModel;
use App\Models\POFMH\PofmhModel;
use App\Models\ZonasLiqModel;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Models\POFMH\CondicionModel;
use App\Models\POFMH\PofmhActivosModel;
use App\Models\POFMH\PofmhAulas;
use App\Models\POFMH\PofmhDivisiones;
use App\Models\POFMH\PofmhOrigenCargoModel;
use App\Models\POFMH\PofMhSitRev;
use App\Models\POFMH\PofmhTurnos;


class EstadisticasController extends Controller
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
    public function ver_novedades_cues(){
       $institucionExtension=DB::table('tb_institucion_extension')
       ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
       ->get();

        
        $TiposDeEspacioCurricular = DB::table('tb_tiposespacioscurriculares')->get();
        $Cursos = DB::table('tb_cursos')->get();
        $Division = DB::table('tb_division')->get();
        $Cursos = DB::table('tb_cursos')->get();
        $TiposHora = DB::table('tb_tiposhora')->get();
        $RegimenDictado = DB::table('tb_pof_regimendictado')->get();
        $Divisiones = DB::table('tb_divisiones')
        ->where('tb_divisiones.idInstitucionExtension',session('idInstitucionExtension'))
        ->join('tb_cursos','tb_cursos.idCurso', '=', 'tb_divisiones.Curso')
        //->join('tb_division','tb_division.idDivisionU', '=', 'tb_divisiones.Division')
        //->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_divisiones.Turno')
        ->select(
            //'tb_divisiones.idDivision',
            'tb_divisiones.Curso',
            //'tb_cursos.*',
            //'tb_division.*',
            //'tb_turnos.Descripcion as DescripcionTurno',
           // 'tb_turnos.idTurno',
        )
        //->orderBy('tb_cursos.DescripcionCurso','ASC')
        ->groupBy('tb_divisiones.Curso')
        ->get();
            
        $Turnos = DB::table('tb_turnos_usuario')->get();
        $NovedadesCUE = DB::table('tb_institucion_extension')
           ->whereNotNull('CUE')->orWhere('CUE', '')
           ->select(
               'tb_institucion_extension.*'
           )
           ->get();

           //dd($Novedades);
        $datos=array(
            'mensajeError'=>"",
            'NovedadesCUE'=>$NovedadesCUE,
            'Turnos'=>$Turnos,
            'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Novedades - Altas'


        );
        return view('bandeja.AG.Servicios.ver_novedades_cues',$datos);
    }

    public function ver_info_por_Instituciones(){
        /*PROCESO DE NIVELES */
        $nivelCounts = DB::select('
    SELECT 
        COALESCE(nivel, "Sin nivel") AS nivel,
        COUNT(*) AS count
    FROM (
        SELECT 
            COALESCE(nivel, "Sin nivel") AS nivel
        FROM tb_institucion_extension
        GROUP BY CUECOMPLETO, nivel
    ) AS subquery
    GROUP BY nivel
');

    // Calcular el total de todos los registros
    $total = array_sum(array_map(function($item) {
        return $item->count;
    }, $nivelCounts));

    // Preparar los datos para Highcharts con porcentajes
    $puntos = [];
    foreach ($nivelCounts as $nivel) {
        $porcentaje = ($total > 0) ? ($nivel->count / $total) * 100 : 0;
        $puntos[] = [
            'name' => $nivel->nivel . " (" . $nivel->count . ")",
            'y' => floatval($nivel->count),
            'percentage' => $porcentaje
        ];
    }
        /** fin dew calculo de niveles */

         /*PROCESO DE jornadas */
        $jornadaCounts = DB::select('
            SELECT 
        COALESCE(Jornada, "Sin Jornada") AS Jornada,
        COUNT(*) AS count
    FROM (
        SELECT 
            COALESCE(Jornada, "Sin Jornada") AS Jornada
        FROM tb_institucion_extension
        GROUP BY CUECOMPLETO, Jornada
    ) AS subquery
    GROUP BY Jornada
');
        // Calcular el total de todos los registros
        $total2 = array_sum(array_map(function($item) {
            return $item->count;
        }, $jornadaCounts));

        // Preparar los datos para Highcharts con porcentajes
        $puntos2 = [];
        foreach ($jornadaCounts as $o) {
            $puntos2[] = [
                'name' => $o->Jornada . " (" . $o->count . ")",
                'y' => floatval($o->count),
                'percentage' => ($o->count / $total2) * 100
            ];
        }
         /** fin dew calculo de jornadas */
        
         /*PROCESO DE Zonas */
        $ZonasCounts = DB::select('
        select 
            COALESCE(zona, "Sin zona") AS zona,
            COALESCE(Departamento, "Sin Dpto") AS Departamento,
            count(*) as count 
        from tb_institucion_extension 
        GROUP BY zona,Departamento
        ');
    // Calcular el total de todos los registros
    $total3 = array_sum(array_map(function($item) {
        return $item->count;
    }, $ZonasCounts));

    // Preparar los datos para Highcharts con porcentajes
    $puntos3 = [];
    foreach ($ZonasCounts as $o) {
        $puntos3[] = [
            'name' => $o->zona . " (".$o->Departamento." -" . $o->count . ")",
            'y' => floatval($o->count),
            'percentage' => ($o->count / $total3) * 100
        ];
    }
     /** fin dew calculo de jornadas */

         /*PROCESO DE AMBITOS */
         $ambitoCounts = DB::select('
         select 
             COALESCE(nombreAmbito, "Sin Ambito") AS ambito,
	 
             count(*) as count 
         from tb_institucion_extension, tb_ambitos
         where tb_institucion_extension.Ambito = tb_ambitos.idAmbito
         GROUP BY nombreAmbito
         ');
     // Calcular el total de todos los registros
     $total4 = array_sum(array_map(function($item) {
         return $item->count;
     }, $ambitoCounts));
 
     // Preparar los datos para Highcharts con porcentajes
     $puntos4 = [];
     foreach ($ambitoCounts as $o) {
         $puntos4[] = [
             'name' => $o->ambito . " (". $o->count . ")",
             'y' => floatval($o->count),
             'percentage' => ($o->count / $total4) * 100
         ];
     }
      /** fin dew calculo de jornadas */
         $datos=array(
             'mensajeError'=>"",
             'NovedadesCUE'=>'',
             'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
             'mensajeNAV'=>'Panel de Estadísticas - Por Valores',
             'niveles'=>json_encode($puntos),
             'jornadas'=>json_encode($puntos2),
             'zonas'=>json_encode(($puntos3)),
             'ambitos'=>json_encode(($puntos4))
 
 
         );
         return view('bandeja.AG.Servicios.estadisticas.conteo',$datos);
     }

     public function ver_info_por_docentes(){
        /*PROCESO DE cargos */
        $cargoCounts = DB::select('
        select
        COALESCE(Cargo, "Sin info Cargo") AS cargo,
        count(*) as count 
         from tb_nodos
			 join tb_cargossalariales on tb_cargossalariales.idCargo = tb_nodos.CargoSalarial
         GROUP BY Cargo
');

    // Calcular el total de todos los registros
    $total = array_sum(array_map(function($item) {
        return $item->count;
    }, $cargoCounts));

    // Preparar los datos para Highcharts con porcentajes
    $puntos = [];
    foreach ($cargoCounts as $nivel) {
        $porcentaje = ($total > 0) ? ($nivel->count / $total) * 100 : 0;
        $puntos[] = [
            'name' => $nivel->cargo . " (" . $nivel->count . ")",
            'y' => floatval($nivel->count),
            'percentage' => $porcentaje
        ];
    }
        /** fin dew calculo de cargos */

         /*PROCESO DE sit rev */
        $sitrevCounts = DB::select('
             select
            COALESCE(Descripcion, "Sin Info Cargo") AS SitRev,
            count(*) as count 
         from tb_nodos
			 join tb_situacionrevista on tb_situacionrevista.idSituacionRevista = tb_nodos.SitRev
         GROUP BY Descripcion
');
        // Calcular el total de todos los registros
        $total2 = array_sum(array_map(function($item) {
            return $item->count;
        }, $sitrevCounts));

        // Preparar los datos para Highcharts con porcentajes
        $puntos2 = [];
        foreach ($sitrevCounts as $o) {
            $puntos2[] = [
                'name' => $o->SitRev . " (" . $o->count . ")",
                'y' => floatval($o->count),
                'percentage' => ($o->count / $total2) * 100
            ];
        }
         /** fin dew calculo de sitrev */
        
         /*PROCESO DE turnos */
        $turnosCounts = DB::select('
         select
            COALESCE(Descripcion, "Sin Info Turno") AS turno,
            count(*) as count 
         from tb_nodos
			 join tb_turnos_usuario on tb_turnos_usuario.idTurnoUsuario = tb_nodos.idTurnoUsuario
         GROUP BY Descripcion
        ');
    // Calcular el total de todos los registros
    $total3 = array_sum(array_map(function($item) {
        return $item->count;
    }, $turnosCounts));

    // Preparar los datos para Highcharts con porcentajes
    $puntos3 = [];
    foreach ($turnosCounts as $o) {
        $puntos3[] = [
            'name' => $o->turno . " (". $o->count . ")",
            'y' => floatval($o->count),
            'percentage' => ($o->count / $total3) * 100
        ];
    }
     /** fin dew calculo de turnos */

         /*PROCESO DE licencia */
         $op4Counts = DB::select('
         select
        COALESCE(LicenciaActiva, "Sin Info Licencia") AS licencia,
        count(*) as count 
         from tb_nodos

         GROUP BY LicenciaActiva
         ');
     // Calcular el total de todos los registros
     $total4 = array_sum(array_map(function($item) {
         return $item->count;
     }, $op4Counts));
 
     // Preparar los datos para Highcharts con porcentajes
     $puntos4 = [];
     foreach ($op4Counts as $o) {
         $puntos4[] = [
             'name' => $o->licencia . " (". $o->count . ")",
             'y' => floatval($o->count),
             'percentage' => ($o->count / $total4) * 100
         ];
     }
      /** fin dew calculo de jornadas */
         $datos=array(
             'mensajeError'=>"",
             'NovedadesCUE'=>'',
             'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
             'mensajeNAV'=>'Panel de Estadísticas - Por Valores',
             'cargos'=>json_encode($puntos),
             'SituacionRevistas'=>json_encode($puntos2),
             'turnos'=>json_encode(($puntos3)),
             'licencias'=>json_encode(($puntos4))
 
 
         );
         return view('bandeja.AG.Servicios.estadisticas.info_docente',$datos);
     }

     public function ver_info_por_Zonas(){
        
       
        $indoDesglose=DB::table('tb_desglose_agentes')
        ->join('tb_institucion', 'tb_institucion.Unidad_Liquidacion', '=', 'tb_desglose_agentes.escu')
        // ->join('tb_institucion_extension', 'tb_institucion_extension.idInstitucion', '=', 'tb_institucion.idInstitucion')
        ->where('tb_desglose_agentes.docu','1')
        ->select(
        'tb_institucion.*',
        //'tb_institucion_extension.*',
        'tb_desglose_agentes.*'
        )
        ->get();
        
        $SitRev = DB::table('tb_situacionrevista')->get();
        $datos=array(
            'estado'=>"Sin Accion",
            'indoDesglose'=>$indoDesglose,
            'dniUsuario'=>1,
            'SitRev'=>$SitRev
        );
      
       
        return view('dashboard.infoPorZonas',$datos);
    }

    public function ver_info_por_Zonas_Liq(){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
       
        $ZonasLiq = DB::table('tb_zonas_liq')->get();

        $Instituciones = DB::table('tb_institucion_extension')
        ->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','=','tb_institucion_extension.idTurnoUsuario')
        //->selectRaw('tb_institucion_extension.*, COALESCE("Zona", "Sin Zona") as Zona')
        //->orderBy('Zona', 'ASC')
        ->get();

        //dd($Instituciones);
        
        $SitRev = DB::table('tb_situacionrevista')->get();
        $datos=array(
            'estado'=>"Sin Accion",
            'ZonasLiq'=>$ZonasLiq,
            'Instituciones'=>$Instituciones
        );
      
       
        return view('dashboard.infoPorZonasLiq',$datos);
    }

    public function ver_info_por_Zonas_Liq_opt(){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
       
        $ZonasLiq = DB::table('tb_zonas_liq')->get();

        $Instituciones = DB::table('tb_institucion_extension')
        ->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','=','tb_institucion_extension.idTurnoUsuario')
        //->selectRaw('tb_institucion_extension.*, COALESCE("Zona", "Sin Zona") as Zona')
        //->orderBy('Zona', 'ASC')
        ->get();

        //dd($Instituciones);
        
        $SitRev = DB::table('tb_situacionrevista')->get();
        $datos=array(
            'estado'=>"Sin Accion",
            'ZonasLiq'=>$ZonasLiq,
            'Instituciones'=>$Instituciones
        );
      
       
        return view('dashboard.infoPorZonasLiqOpt',$datos);
    }

/*
    public function cargar_zona($idZona) {
        // Obtener la zona correspondiente
        $zona = ZonasLiqModel::where('idZona',$idZona)->first();
    
        // Obtener las instituciones y otros datos relacionados
        $instituciones = DB::table('tb_institucion_extension')->where('Zona', $zona->codigo_letra)
        ->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','=','tb_institucion_extension.idTurnoUsuario')
        ->get();
        $sitRev = SitRevModel::all(); // Situaciones de revista
    
        // Verifica si tienes los datos necesarios antes de devolver la vista
        if ($instituciones && $sitRev && $zona) {
            return view('bandeja.partials.tablaZona', compact('instituciones', 'sitRev', 'zona'));
        } else {
            return response()->json(['error' => 'Datos no encontrados'], 404);
        }
        //return response()->json(array('status' => 200, 'msg' => "-listo"), 200);
    }
*/
public function cargar_zona_liq($idZona) {
    set_time_limit(0);
    ini_set('memory_limit', '2028M');
    // Obtener la zona correspondiente
    $zona = ZonasLiqModel::where('idZona', $idZona)->first();

    // Obtener las instituciones y otros datos relacionados
    $instituciones = DB::table('tb_institucion_extension')
        ->where('Zona', $zona->codigo_letra)
        ->join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->select('tb_institucion_extension.*', 'tb_turnos_usuario.Descripcion as Turno')
        ->get();

    $sitRev = SitRevModel::all(); // Situaciones de revista

    // Agrupar las instituciones por nivel
    $institucionesPorNivel = $instituciones->groupBy('Nivel');
    $infoAula = PofmhAulas::all();
    $infoDivision = PofmhDivisiones::all();
    $infoTurno = PofmhTurnos::all();
    $infoCondicion = CondicionModel::all();
    $infoActivos = PofmhActivosModel::all();
    $infoSitRev = PofMhSitRev::all();
    $infoCargoSalarial = DB::table('tb_cargossalariales')->get();
    $infoMotivos = DB::table('tb_motivos')->get();
    $infoOrigen = PofmhOrigenCargoModel::all();
    $infoCargosOrigen = CargoOrigenPofMHModel::all();
    // Preparar los datos de usuarios para cada institución
    $usuariosPorInstitucion = [];
    foreach ($instituciones as $institucion) {
        $usuariosPorInstitucion[$institucion->CUECOMPLETO] = PofmhModel::where('tb_pofmh.CUECOMPLETO', $institucion->CUECOMPLETO)
            ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', '=', 'tb_pofmh.CUECOMPLETO')
            ->where('tb_pofmh.CUECOMPLETO', 'not like', '999999%') // Excluir CUECOMPLETO que comiencen con 999999
            ->select('tb_pofmh.*', 'tb_institucion_extension.Nombre_Institucion', 'tb_institucion_extension.CUE')
            ->orderBy('orden','ASC')
            ->distinct()
            ->get();
    }

    // Verifica si tienes los datos necesarios antes de devolver la vista
    if ($institucionesPorNivel && $sitRev && $zona) {
        return view('bandeja.partials.tablaZonaNivelLiq', compact(
            'institucionesPorNivel', 
    'sitRev', 
            'zona', 
            'usuariosPorInstitucion',
            'infoAula','infoDivision','infoTurno','infoCondicion','infoActivos','infoSitRev','infoCargoSalarial','infoOrigen','infoCargosOrigen','infoMotivos'));
    } else {
        return response()->json(['error' => 'Datos no encontrados'], 404);
    }
}
public function cargar_zona_liq_opt($idZona) {
    set_time_limit(0);
    ini_set('memory_limit', '2028M');

    // Obtener la zona correspondiente
    $zona = ZonasLiqModel::where('idZona', $idZona)->first();

    // Obtener las instituciones y otros datos relacionados
    $instituciones = DB::table('tb_institucion_extension')
        ->where('Zona', $zona->codigo_letra)
        ->join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->select('tb_institucion_extension.*', 'tb_turnos_usuario.Descripcion as Turno')
        ->get();

    // Agrupar las instituciones por nivel
    $institucionesPorNivel = $instituciones->groupBy('Nivel'); // Agrupar por campo 'Nivel'

    // Verifica si tienes los datos necesarios antes de devolver la vista
    return view('bandeja.partials.tablaZonaNivelLiqOpt', compact('institucionesPorNivel'));
}

public function verInfoInstitucion($inst)
{
    $institucion=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.idInstitucionExtension',$inst)
                ->first();
    //viene el cue y capturo todos los cargos creados en esa institucion
    $CargosCreados = PofmhOrigenCargoModel::where('CUECOMPLETO',$institucion->CUECOMPLETO)
                ->join('tb_cargos_pof_origen','tb_cargos_pof_origen.idCargos_Pof_Origen','tb_origenes_cargos.nombre_origen')
                ->where('tb_origenes_cargos.idTurno',$institucion->idTurnoUsuario)
                ->get()
                ->toArray();

    return response()->json([
        'success' => true,
        'data' => $CargosCreados
    ]);
}
public function cargarAgentes(Request $request) {
    $cue = $request->input('cue');
    $instituciones = DB::table('tb_institucion_extension')
        ->where('CUECOMPLETO', $cue)
        ->join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->select('tb_institucion_extension.*', 'tb_turnos_usuario.Descripcion as Turno')
        ->get();

    $agentes = PofmhModel::where('CUECOMPLETO', $cue)
    ->orderBy('orden','ASC')
    ->get();
    $sitRev = SitRevModel::all(); // Situaciones de revista
    
    $infoAula = PofmhAulas::all();
    $infoDivision = PofmhDivisiones::all();
    $infoTurno = PofmhTurnos::all();
    $infoCondicion = CondicionModel::all();
    $infoActivos = PofmhActivosModel::all();
    $infoSitRev = PofMhSitRev::all();
    $infoCargoSalarial = DB::table('tb_cargossalariales')->get();
    $infoMotivos = DB::table('tb_motivos')->get();
    $infoOrigen = PofmhOrigenCargoModel::all();
    $infoCargosOrigen = CargoOrigenPofMHModel::all();


    // Retornar una vista parcial con los datos de los agentes
    return view('bandeja.partials.agentes', compact('agentes',
    'sitRev','infoAula','infoDivision','infoTurno',
    'infoCondicion','infoActivos','infoSitRev',
    'infoCargoSalarial','infoOrigen','infoCargosOrigen','infoMotivos','instituciones'));
}



public function traerPersonasIdInstExt(Request $request) {
    // Obtén el id de la institución enviado por Ajax
    $idInstitucion = $request->input('id');
    
    // Asegúrate de obtener el primer registro que coincida
    $idInstExt = InstitucionExtensionModel::where('idInstitucionExtension', $idInstitucion)->first();

    // Verifica si $idInstExt no es null antes de continuar
    if (!$idInstExt) {
        // Si no hay resultados, regresa un mensaje de error o una vista vacía
        return response()->json(['error' => 'Institución no encontrada'], 404);
    }

    // Realiza la consulta para obtener las personas
    $personas = DB::table('tb_nodos')
                    ->where('CUECOMPLETO', $idInstExt->CUECOMPLETO)
                    ->where('idTurnoUsuario', $idInstExt->idTurnoUsuario)
                    ->join('tb_situacionrevista','tb_situacionrevista.idSituacionRevista','=','tb_nodos.SitRev')
                    ->join('tb_agentes','tb_agentes.Documento','=','tb_nodos.Agente')
                    ->join('tb_cargossalariales','tb_cargossalariales.idCargo','=','tb_nodos.CargoSalarial')
                    ->get();
    // Retorna una vista parcial con la lista de personas
    return view('bandeja.partials.lista_personas', compact('personas'));
}











}
