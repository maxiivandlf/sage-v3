<?php

namespace App\Http\Controllers\Sistemas\Sage;

use App\Http\Controllers\Controller;
use App\Models\Sage\HorasIpeModel;
use App\Models\Sage\PofIpeModel;
use App\Models\Sage\RelAgenteEliminadoPofIpe;
use App\Models\Sage\RelPofIpeModel;
use App\Models\TurnosModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControlIPEEscuelasController extends Controller
{
    public function controlDeIpe() {
        Carbon::setLocale('es');
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
        
        $cuebase = substr(session('CUECOMPLETO'), 0, 9);
        session(['CUECOMPLETOBASE' => $cuebase]);
    
        $infoUnidLiq = DB::connection('DB8')->table('instarealiq')
            ->where('instarealiq.CUEA', session('CUECOMPLETOBASE'))
            ->select('escu', 'area')
            ->groupBy('escu', 'area')
            ->get()
            ->toArray();
    
        $infoHoras = HorasIpeModel::all();
    
        // Armar string para vista
        $liqText = '';
        foreach ($infoUnidLiq as $unidliq) {
            if (!empty($unidliq->escu) && !empty($unidliq->area)) {
                $liqText .= $unidliq->escu . ' - ' . $unidliq->area;
            } else {
                $liqText .= 'S/D';
            }
            $liqText .= " / ";
        }
        $liqText = rtrim($liqText, ' / ');
        session(['UnidadLiquidacion' => $liqText]);
    
        $listaNoPermitidos = ['820'];
        $infoAgentesEliminados = RelAgenteEliminadoPofIpe::where('CUECOMPLETO', session('CUECOMPLETOBASE'))
            ->pluck('idPofIpe')
            ->toArray();
    
        // Consulta base
        $infoAgentesQuery = PofIpeModel::whereNotIn('idPofIpe', $infoAgentesEliminados)
            ->whereNotIn('Escu', $listaNoPermitidos)
            ->whereNull('Agregado');
    
        if (!empty($infoUnidLiq)) {
            $infoAgentesQuery->where(function($query) use ($infoUnidLiq) {
                foreach ($infoUnidLiq as $item) {
                    $query->orWhere(function($q) use ($item) {
                        $q->where('Escu', $item->escu)
                            ->where('Area', $item->area);
                    });
                }
            });
        } else {
            $infoAgentesQuery->whereRaw('0 = 1');
        }
    
        $infoAgentes = $infoAgentesQuery
            ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pof_ipe.Plan')
            ->join('tb_cargossalariales', function($join) {
                $join->on(DB::raw("CONCAT(tb_pof_ipe.lcat, tb_pof_ipe.ncat)"), '=', 'tb_cargossalariales.Codigo');
            })
            ->join('tb_areas_liquidacion', 'tb_areas_liquidacion.descripcion_area', '=', 'tb_pof_ipe.Area')
            ->get();
    
        // Agentes nuevos
        $AgentesNuevosQuery = PofIpeModel::where('Agregado', 1)
            ->where('CUECOMPLETO_AG', session('CUECOMPLETOBASE'));
    
        if (!empty($infoUnidLiq)) {
            $AgentesNuevosQuery->where(function($query) use ($infoUnidLiq) {
                foreach ($infoUnidLiq as $item) {
                    $query->orWhere(function($q) use ($item) {
                        $q->where('Escu', $item->escu)
                            ->where('Area', $item->area);
                    });
                }
            });
        } else {
            $AgentesNuevosQuery->whereRaw('0 = 1');
        }
    
        $AgentesNuevos = $AgentesNuevosQuery
            ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pof_ipe.Plan')
            ->join('tb_cargossalariales', function($join) {
                $join->on(DB::raw("CONCAT(tb_pof_ipe.lcat, tb_pof_ipe.ncat)"), '=', 'tb_cargossalariales.Codigo');
            })
            ->join('tb_areas_liquidacion', 'tb_areas_liquidacion.descripcion_area', '=', 'tb_pof_ipe.Area')
            ->get();
    
        // Agentes relacionados
        $infoAgentesRel = PofIpeModel::where('tb_rel_pof_ipe.CUECOMPLETO', session('CUECOMPLETOBASE'))
            ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pof_ipe.Plan')
            ->join('tb_cargossalariales', function($join) {
                $join->on(DB::raw("CONCAT(tb_pof_ipe.lcat, tb_pof_ipe.ncat)"), '=', 'tb_cargossalariales.Codigo');
            })
            ->join('tb_areas_liquidacion', 'tb_areas_liquidacion.descripcion_area', '=', 'tb_pof_ipe.Area')
            ->join('tb_rel_pof_ipe', 'tb_rel_pof_ipe.idPofIpe', '=', 'tb_pof_ipe.idPofIpe')
            ->get();
    
        $MesActual = Carbon::now()->locale('es')->format('F');
        session(['MesActual' => $MesActual]);
    
        $Turnos = DB::connection('DB7')->table('tb_turnos')->get();
        $sitRev = DB::connection('DB7')->table('tb_situacionrevista')->get();
        $Sexos = DB::table('tb_sexo')->get();
        $CargoSalarial = DB::connection('DB7')->table('tb_cargossalariales')->orderBy('Codigo', 'ASC')->get();
    
        $datos = [
            'mensajeError' => "",
            'infoUnidLiq' => $infoUnidLiq,
            'infoAgentes' => $infoAgentes,
            'MesActual' => $MesActual,
            'Turnos' => $Turnos,
            'infoHoras' => $infoHoras,
            'AgentesNuevos' => $AgentesNuevos,
            'infoAgentesCount' => $infoAgentes->count(),
            'infoAgentesRelacionados' => $infoAgentesRel,
            'CUECOMPLETOBASE' => session('CUECOMPLETOBASE'),
            'NombreInstitucion' => session('NombreInstitucion'),
            'liqText' => $liqText,
            'SitRev' => $sitRev,
            'Sexos' => $Sexos,
            'CargoSalarial' => $CargoSalarial,
            'FechaActual' => Carbon::now()->format('Y-m-d'),
            'mensajeNAV' => 'Panel de Control de IPE'
        ];
    
        $ruta = '
        <li class="breadcrumb-item active"><a href="#">Escuelas</a></li>
        <li class="breadcrumb-item active"><a href="'.route('controlDeIpe').'">Control de IPE</a></li>';
        session(['ruta' => $ruta]);
    
        return view('sage.ipe.controlIpeEscuela', $datos);
    }
    
    public function controlDeIpeSuper($idExtension) {
        Carbon::setLocale('es');
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
    
        $infoEscuela = DB::table('tb_institucion_extension')
            ->where('idInstitucionExtension', $idExtension)
            ->first();
    
        $cuebase = substr($infoEscuela->CUECOMPLETO, 0, 9);
        session(['CUECOMPLETOBASE' => $cuebase]);
    
        $infoUnidLiq = DB::connection('DB8')->table('instarealiq')
            ->where('instarealiq.CUEA', session('CUECOMPLETOBASE'))
            ->select('escu', 'area')
            ->groupBy('escu', 'area')
            ->get()
            ->toArray();
    
        $infoHoras = HorasIpeModel::all();
    
        $liqText = '';
        foreach ($infoUnidLiq as $unidliq) {
            if (!empty($unidliq->escu) && !empty($unidliq->area)) {
                $liqText .= "{$unidliq->escu} - {$unidliq->area}";
            } else {
                $liqText .= 'S/D';
            }
            $liqText .= " / ";
        }
        $liqText = rtrim($liqText, ' / ');
        session(['UnidadLiquidacion' => $liqText]);
    
        $listaNoPermitidos = ['820'];
    
        $infoAgentesEliminados = RelAgenteEliminadoPofIpe::where('CUECOMPLETO', session('CUECOMPLETOBASE'))
            ->pluck('idPofIpe')
            ->toArray();
    
        // === Agentes Existentes ===
        $infoAgentesQuery = PofIpeModel::whereNotIn('idPofIpe', $infoAgentesEliminados)
            ->whereNotIn('Escu', $listaNoPermitidos)
            ->whereNull('Agregado');
    
        if (!empty($infoUnidLiq)) {
            $infoAgentesQuery->where(function($query) use ($infoUnidLiq) {
                foreach ($infoUnidLiq as $item) {
                    $query->orWhere(function($q) use ($item) {
                        $q->where('Escu', $item->escu)
                            ->where('Area', $item->area);
                    });
                }
            });
        } else {
            $infoAgentesQuery->whereRaw('0 = 1');
        }
    
        $infoAgentes = $infoAgentesQuery
            ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pof_ipe.Plan')
            ->join('tb_cargossalariales', function($join) {
                $join->on(DB::raw("CONCAT(tb_pof_ipe.lcat, tb_pof_ipe.ncat)"), '=', 'tb_cargossalariales.Codigo');
            })
            ->join('tb_areas_liquidacion', 'tb_areas_liquidacion.descripcion_area', '=', 'tb_pof_ipe.Area')
            ->get();
    
        // === Agentes Nuevos ===
        $AgentesNuevosQuery = PofIpeModel::where('Agregado', 1)
            ->where('CUECOMPLETO_AG', session('CUECOMPLETOBASE'));
    
        if (!empty($infoUnidLiq)) {
            $AgentesNuevosQuery->where(function($query) use ($infoUnidLiq) {
                foreach ($infoUnidLiq as $item) {
                    $query->orWhere(function($q) use ($item) {
                        $q->where('Escu', $item->escu)
                            ->where('Area', $item->area);
                    });
                }
            });
        } else {
            $AgentesNuevosQuery->whereRaw('0 = 1');
        }
    
        $AgentesNuevos = $AgentesNuevosQuery
            ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pof_ipe.Plan')
            ->join('tb_cargossalariales', function($join) {
                $join->on(DB::raw("CONCAT(tb_pof_ipe.lcat, tb_pof_ipe.ncat)"), '=', 'tb_cargossalariales.Codigo');
            })
            ->join('tb_areas_liquidacion', 'tb_areas_liquidacion.descripcion_area', '=', 'tb_pof_ipe.Area')
            ->get();
    
        // === Agentes Relacionados ===
        $infoAgentesRel = PofIpeModel::where('tb_rel_pof_ipe.CUECOMPLETO', session('CUECOMPLETOBASE'))
            ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pof_ipe.Plan')
            ->join('tb_cargossalariales', function($join) {
                $join->on(DB::raw("CONCAT(tb_pof_ipe.lcat, tb_pof_ipe.ncat)"), '=', 'tb_cargossalariales.Codigo');
            })
            ->join('tb_areas_liquidacion', 'tb_areas_liquidacion.descripcion_area', '=', 'tb_pof_ipe.Area')
            ->join('tb_rel_pof_ipe', 'tb_rel_pof_ipe.idPofIpe', '=', 'tb_pof_ipe.idPofIpe')
            ->get();
    
        setlocale(LC_TIME, 'es_ES.UTF-8');
        Carbon::setLocale('es');
        $MesActual = Carbon::now()->translatedFormat('F');
        session(['MesActual' => $MesActual]);
    
        $Turnos = DB::connection('DB7')->table('tb_turnos')->get();
        $sitRev = DB::connection('DB7')->table('tb_situacionrevista')->get();
        $Sexos = DB::table('tb_sexo')->get();
        $CargoSalarial = DB::connection('DB7')->table('tb_cargossalariales')
            ->orderBy('Codigo', 'ASC')->get();
    
        $datos = [
            'mensajeError' => "",
            'infoUnidLiq' => $infoUnidLiq,
            'infoAgentes' => $infoAgentes,
            'MesActual' => $MesActual,
            'Turnos' => $Turnos,
            'infoHoras' => $infoHoras,
            'AgentesNuevos' => $AgentesNuevos,
            'infoAgentesCount' => $infoAgentes->count(),
            'infoAgentesRelacionados' => $infoAgentesRel,
            'CUECOMPLETOBASE' => session('CUECOMPLETOBASE'),
            'NombreInstitucion' => session('NombreInstitucion'),
            'liqText' => $liqText,
            'SitRev' => $sitRev,
            'Sexos' => $Sexos,
            'CargoSalarial' => $CargoSalarial,
            'FechaActual' => Carbon::now()->format('Y-m-d'),
            'mensajeNAV' => 'Panel de Control de IPE'
        ];
    
        $ruta = '
        <li class="breadcrumb-item active"><a href="#">Escuelas</a></li>
        <li class="breadcrumb-item active"><a href="'.route('controlDeIpeSuper', $idExtension).'">Control de IPE</a></li>';
        session(['ruta' => $ruta]);
    
        return view('sage.ipe.controlIpeEscuelaSuper', $datos);
    }
    
    public function controlDeIpeTec($idInstitucionExtension){
        Carbon::setLocale('es');
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
        $infoInstitucion = DB::table('tb_institucion_extension')->where('idInstitucionExtension', $idInstitucionExtension)->first();

        $cuebase = substr($infoInstitucion->CUECOMPLETO, 0, 9);
        session(['CUECOMPLETOBASE' => $cuebase]);

        //llevo el unidad en instarealiq
        $infoUnidLiq = DB::connection('DB8')->table('instarealiq')
        ->where('instarealiq.CUEA', session('CUECOMPLETOBASE'))
        ->groupBy('instarealiq.escu')
        ->pluck('instarealiq.escu') // obtenés directamente una colección con los valores
        ->toArray();

        $infoHoras = HorasIpeModel::all();
        $liqText = '';
        foreach ($infoUnidLiq as $unidliq) {
            // Ya es el valor directamente, así que no usás ->escu
            $liqText .= !empty($unidliq) ? $unidliq : 'S/D';
            $liqText .= " / ";
        }
        $liqText = rtrim($liqText, ' / '); 
        session(['UnidadLiquidacion' => $liqText]); //la resguardo para usarla al actualizar el IPE
        $listaNoPermitidos = ['820'];
        //preparo lista negra de usuarios eliminado que no quiero en las otras consultas
        $infoAgentesEliminados = RelAgenteEliminadoPofIpe::where('CUECOMPLETO', session('CUECOMPLETOBASE'))
        ->pluck('idPofIpe')
        ->toArray();
        //dd($infoAgentesEliminados);
        //preparo la consulta de agentes en la tabla para poder ir cotejando los resultados con infounidadliq
        /*
        $infoAgentes = PofIpeModel::whereIn('Escu', $infoUnidLiq)
        ->whereNotIn('idPofIpe', $infoAgentesEliminados)
        ->whereNotIn('Escu', $listaNoPermitidos)
        ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pof_ipe.Plan')
        ->join('tb_cargossalariales', function($join) {
            $join->on(DB::raw("CONCAT(tb_pof_ipe.lcat, tb_pof_ipe.ncat)"), '=', 'tb_cargossalariales.Codigo');
        })
        ->join('tb_areas_liquidacion', 'tb_areas_liquidacion.descripcion_area', '=', 'tb_pof_ipe.Area')
        ->get();
        */
        //dd($infoAgentes);
        $infoAgentesSoloExcluidos = PofIpeModel::where(function($query) use ($infoAgentesEliminados, $listaNoPermitidos) {
            $query->whereIn('idPofIpe', $infoAgentesEliminados)
                  ->orWhereIn('Escu', $listaNoPermitidos);
        })
        ->whereIn('Escu', $infoUnidLiq) // si querés seguir filtrando por las unidades liquidadas
        ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pof_ipe.Plan')
        ->join('tb_cargossalariales', function($join) {
            $join->on(DB::raw("CONCAT(tb_pof_ipe.lcat, tb_pof_ipe.ncat)"), '=', 'tb_cargossalariales.Codigo');
        })
        ->join('tb_areas_liquidacion', 'tb_areas_liquidacion.descripcion_area', '=', 'tb_pof_ipe.Area')
        ->get();

        //traigo los agentes relacionados solamente si existen
        $infoAgentesRel = PofIpeModel::where('tb_rel_pof_ipe.CUECOMPLETO', session('CUECOMPLETOBASE'))
        ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pof_ipe.Plan')
        ->join('tb_cargossalariales', function($join) {
            $join->on(DB::raw("CONCAT(tb_pof_ipe.lcat, tb_pof_ipe.ncat)"), '=', 'tb_cargossalariales.Codigo');
        })
        ->join('tb_areas_liquidacion', 'tb_areas_liquidacion.descripcion_area', '=', 'tb_pof_ipe.Area')
        ->join('tb_rel_pof_ipe', 'tb_rel_pof_ipe.idPofIpe', '=', 'tb_pof_ipe.idPofIpe')
        ->get();
        //dd($infoAgentesRel);

        // Obtener el mes actual
        $MesActual = Carbon::now()->locale('es')->format('F');
        session(['MesActual' => $MesActual]);

        $Turnos = DB::connection('DB7')->table('tb_turnos')->get();
        //dd($Turnos);
        $datos = array(
            'mensajeError' => "",
            'infoUnidLiq' => $infoUnidLiq,
            'infoAgentes' => $infoAgentesSoloExcluidos,
            'MesActual' => $MesActual,
            'Turnos' => $Turnos,
            'infoHoras' => $infoHoras,
            'infoAgentesCount' => $infoAgentesSoloExcluidos->count(),
            'infoAgentesRelacionados' => $infoAgentesRel,
            'CUECOMPLETO'=> $infoInstitucion->CUECOMPLETO,
            'NombreInstitucion'=> $infoInstitucion->Nombre_Institucion,
            'liqText' => $liqText,
            'FechaActual' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV' => 'Panel de Control de IPE'
        );
   
        // 6. Configuro la ruta de navegación
        $ruta = '
        <li class="breadcrumb-item active"><a href="#">Escuelas</a></li>
        <li class="breadcrumb-item active"><a href="'.route('controlDeIpeTec',$idInstitucionExtension).'">Control de IPE - Tecnico</a></li>
        '; 
        session(['ruta' => $ruta]);
    
        // Retorno la vista con los datos
        return view('sage.ipe.controlIpeTec', $datos);   
    }

    public function recuperarAgenteEliminado($idPofIpe, $cue)
    {
        try {
            $deleted = RelAgenteEliminadoPofIpe::where('idPofIpe', $idPofIpe)
                ->where('CUECOMPLETO', $cue)
                ->get();

            if (!$deleted) {
                return response()->json(['success' => false, 'message' => 'Agente no encontrado.'], 404);
            }

            // Elimina todos los registros encontrados
            $deleted->each->delete();

            return response()->json(['success' => true, 'message' => 'Agente recuperado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al recuperar el agente.'], 500);
        }
    }
    public function actualizarIPE(Request $request)
    {
        //consulto para saber si vieen SI o NO y segun eso borrar las horas
        if($request->IPE == "NO"){
                //borra las horas
                $agente = PofIpeModel::findOrFail($request->id);
                    $agente->IPE = $request->IPE;
                    $agente->Horas_Trabajadas = 0;
                    $agente->CUECOMPLETO = session('CUECOMPLETOBASE');
                    $agente->Mes = session('MesActual');
                $agente->save();
        }else{
            $agente = PofIpeModel::findOrFail($request->id);
                $agente->IPE = $request->IPE;
                $agente->CUECOMPLETO = session('CUECOMPLETOBASE');
                $agente->Mes = session('MesActual');
            $agente->save();
        }
       
    
        return response()->json(['status' => 'ok', 'message' => 'IPE actualizado']);
    }
    public function actualizarIPER1(Request $request)
    {
        $agente = RelPofIpeModel::findOrFail($request->idr1);
            $agente->IPE = $request->IPER1;
            $agente->Mes = session('MesActual');
            $agente->CUECOMPLETO = session('CUECOMPLETOBASE');
        $agente->save();
    
        return response()->json(['status' => 'ok', 'message' => 'IPE actualizado']);
    }
    
    public function actualizarPertenece(Request $request)
    {
        $agente = PofIpeModel::findOrFail($request->id);
            $agente->Pertenece = $request->Pertenece;
            if($request->Pertenece == 'SI'){
                $agente->Unidad_Liquidacion = session('UnidadLiquidacion');
                $agente->CUECOMPLETO = session('CUECOMPLETOBASE');
            }
            else{
                $agente->Unidad_Liquidacion = '';
                $agente->CUECOMPLETO = '';
            }
        $agente->save();
    
        return response()->json(['status' => 'ok', 'message' => 'IPE actualizado']);
    }
    public function actualizarPerteneceR1(Request $request)
    {
        $agente = PofIpeModel::findOrFail($request->id);
            $agente->Pertenece_R1 = $request->Pertenece_R1;
            if($request->Pertenece_R1 == 'SI'){
                $agente->Unidad_Liquidacion_R1 = session('UnidadLiquidacion');
                $agente->CUECOMPLETO_R1 = session('CUECOMPLETOBASE');
            }
            else{
                $agente->Unidad_Liquidacion_R1 = '';
                $agente->CUECOMPLETO_R1 = '';
            }
        $agente->save();
    
        return response()->json(['status' => 'ok', 'message' => 'IPE actualizado']);
    }
    public function actualizarTurno(Request $request)
    {
        if($request->filled('id')){
            $agente = PofIpeModel::findOrFail($request->id);
                $agente->Turno = $request->Turno;
                $agente->CUECOMPLETO = session('CUECOMPLETOBASE');
            $agente->save();
        }else{
            $agente = RelPofIpeModel::findOrFail($request->idr1);
                $agente->Turno = $request->TurnoR1;
                $agente->CUECOMPLETO = session('CUECOMPLETOBASE');
            $agente->save();
        }
        
        
    
        return response()->json(['status' => 'ok', 'message' => 'Turno actualizado']);
    }
    public function actualizarTurno_relacionado(Request $request)
    {
        $agente = RelPofIpeModel::findOrFail($request->idr1);
            $agente->Turno = $request->TurnoR1;
            $agente->CUECOMPLETO = session('CUECOMPLETOBASE');
        $agente->save();
    
        return response()->json(['status' => 'ok', 'message' => 'Turno actualizado']);
    }
    
    public function actualizarHora(Request $request)
    {
        if($request->filled('id')){
            $agente = PofIpeModel::findOrFail($request->id);
                $agente->Horas_Trabajadas= $request->Hora;
                $agente->CUECOMPLETO = session('CUECOMPLETOBASE');
            $agente->save();
        }else{
            $agente = PofIpeModel::findOrFail($request->idr1);
                $agente->Horas_Trabajadas_R1 = $request->HoraR1;
                $agente->CUECOMPLETO = session('CUECOMPLETOBASE');
            $agente->save();
        }
        
        
    
        return response()->json(['status' => 'ok', 'message' => 'Horas actualizado']);
    }

    public function actualizarHora_relacionado(Request $request)
    {
        $agente = RelPofIpeModel::findOrFail($request->idr1);
            $agente->Horas_Trabajadas = $request->HoraR1;
            $agente->CUECOMPLETO = session('CUECOMPLETOBASE');
        $agente->save();
        
        return response()->json(['status' => 'ok', 'message' => 'Horas actualizado']);
    }
    public function getAgentesIPE($DNI){
        

        $Agentes = DB::connection('DB7')->table('tb_pof_ipe')
        ->where('Documento',$DNI)
        ->get();
       
        $respuesta="";
        $contador=0;
        if($Agentes->isNotEmpty()){
            $contador++;
            foreach($Agentes as $a){     
                $infoEscuela = DB::connection('DB8')->table('instarealiq')
                ->where('instarealiq.escu', $a->Escu)->first();
                if($infoEscuela){
                    if($infoEscuela->desc_escu != null || $infoEscuela->desc_escu != ''){
                        $nombreEscuela = $infoEscuela->desc_escu;
                    }else{
                        $nombreEscuela = $infoEscuela->nombreInstitucion;
                    }
                }else{
                    $nombreEscuela = 'Escuela no encontrada';

                }

                $infoSitRev = DB::connection('DB7')->table('tb_situacionrevista')->where('tb_situacionrevista.idSituacionRevista', $a->Plan)->first();
                $infoCargoSal = DB::connection('DB7')->table('tb_cargossalariales')->where('tb_cargossalariales.Codigo', $a->lcat.$a->ncat)->first();
                $infoArea = DB::connection('DB7')->table('tb_areas_liquidacion')->where('tb_areas_liquidacion.descripcion_area', $a->Area)->first();
            
                $infoSitRev = $infoSitRev ? $infoSitRev->Descripcion : 'N/A';
                $infoCargoSal = $infoCargoSal ? $infoCargoSal->Codigo : 'N/A';
                $infoArea = $infoArea ? $infoArea->descripcion_area : 'N/A';
                $respuesta=$respuesta.'
                <tr class="gradeX">
                    <td>'.$contador.'</td>
                    <td>
                        '.$a->Documento.'
                        <input type="hidden" id="dniAgenteModal'.$a->idPofIpe.'" value="'.$a->Documento.'">
                        <input type="hidden" id="nomAgenteModal'.$a->idPofIpe.'" value="'.$a->ApeNom.'">
                    </td>
                    <td>'.$a->Cuil.'</td>
                    <td class="text-center">'.$a->Trabajo .'</td>
                    <td>'.$a->ApeNom.'</td>
                    <td class="text-center">'.$a->Sexo.'</td>
                    <td class="text-center">'.$a->Zona.'</td>
                    <td class="text-center">'.$a->Escu.' - ('.$nombreEscuela.')</td>
                    <td class="text-center">'.$infoSitRev.'</td>
                    <td class="text-center">'.$a->Antiguedad.'</td>
                    <td class="text-center">'.$a->Agrupacion.'</td>
                    <td class="text-center">'.$infoCargoSal.'</td>
                    <td class="text-center">'.$a->Area.'</td>
                    <td>
                        <input type="hidden" name="Agente" value="'.$a->Documento.'">
                        <button type="button" name="btnAgregar" class="btn-agregar-agente" data-id="'.$a->idPofIpe.'" data-dni="'.$a->Documento.'">
                            <i class="fa fa-plus"></i> Agregar Agente
                        </button>
                    </td>
                </tr>';
                $contador++;
                
            }
            //// <td>'.$a->desc_escu.'</td>
                    // <td>'.$a->desc_plan.'</td>
        }else{
            $respuesta=$respuesta.'
                <tr class="gradeX">
                    <td colspan="4">Agente no encontrado en SAGE</td>
                </tr>';
        }
        //<button type="submit" onclick="seleccionarAgente('.$a->idAgente.')">Agregar Agente</button>
        //echo $respuesta;
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }


    public function agregarAgente(Request $request)
    {
        // Evitar duplicados
        $existe = RelPofIpeModel::where('idPofIpe', $request->idPofIpe)
            ->where('CUECOMPLETO', $request->CUECOMPLETO)
            ->exists();

        if ($existe) {
            return response()->json(['message' => 'El agente ya fue agregado.'], 409);
        }

        $relacion = new RelPofIpeModel();
            $relacion->idPofIpe = $request->idPofIpe;
            $relacion->CUECOMPLETO = $request->CUECOMPLETO;
            $relacion->Mes = $request->Mes;
            $relacion->Unidad_Liquidacion = session('UnidadLiquidacion');
            $relacion->created_at = now();
            $relacion->updated_at = now();
        $relacion->save();

        return response()->json(['message' => 'Agente agregado correctamente.']);
    }

    //aqui borramos el agente y de paso limpiamos su relacion + los campos que se cargaron
    public function eliminarAgenteRelacionado(Request $request)
    {
        try {
            // Eliminar la relación en tb_rel_pof_ipe
            RelPofIpeModel::where('idRelPofIpe', $request->idRel)->delete();
    
    
            return response()->json(['status' => 'ok', 'message' => 'Agente relacionado eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }
    
    public function eliminarAgenteBase(Request $request)
    {
        try {
            $agente = new RelAgenteEliminadoPofIpe();
                $agente->idPofIpe = $request->idPofIpe;
                $agente->CUECOMPLETO = session('CUECOMPLETOBASE');
                $agente->created_at = now();
                $agente->updated_at = now();
            $agente->save();
    
            return response()->json(['status' => 'ok', 'message' => 'Agente relacionado eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }

    //funcion de unificacion entre la tabla tb_pof_ipe  y la tabla tb_rel_pof_ipe
    public function UnificarPofIpe(){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
    
        PofIpeModel::chunk(500, function ($AgentesPof) {
            foreach ($AgentesPof as $agente) {
                try {
                    // Buscar relaciones relacionadas a este POF
                    $relaciones = RelPofIpeModel::where('idPofIpe', $agente->idPofIpe)->get();
    
                    echo "Agente: ".$agente->idPofIpe ." con ".$relaciones->count()." relaciones <br>";
    
                    if ($relaciones->count() > 0) {
                        foreach ($relaciones as $index => $relacion) {
                            $rIndex = $index + 1;
                            if ($rIndex > 4) break;
    
                            $agente->{"IPE_R$rIndex"} = $relacion->IPE;
                            $agente->{"Horas_Trabajadas_R$rIndex"} = $relacion->Horas_Trabajadas;
                            $agente->{"CUECOMPLETO_R$rIndex"} = $relacion->CUECOMPLETO;
                            $agente->{"Unidad_Liquidacion_R$rIndex"} = $relacion->Unidad_Liquidacion;
                            $agente->{"Turno_R$rIndex"} = $relacion->Turno;
                            $agente->{"Mes_R$rIndex"} = $relacion->Mes;
                        }
    
                        // Guardar los cambios hechos en el agente (tb_pof_ipe)
                        $agente->save();
                    }
    
                } catch (\Exception $e) {
                    \Log::error("Error al procesar idPofIpe {$agente->idPofIpe}: " . $e->getMessage());
                }
            }
        });
    
        return response()->json(['mensaje' => 'Proceso de unificación completado correctamente.']);
    }
    
    
    public function FormNuevoAgenteAltaControlIpe(Request $request){
        //dd($request);
        /*
        #parameters: array:8 [▼
     "Apellido" => "Loyola"
      "Nombre" => "leo"
      "Sexo" => "M"
      "Documento" => "9858752"
      "CUIL" => "232323"
      "SitRev" => "2"
      "CargoSalarial" => "A01"
            ]
         */

        //desgloso el codgo
        // Validación segura
        $cargo = $request->CargoSalarial;

        // Si viene vacío o con menos de 2 caracteres, lo forzamos a 'Z00'
        if ($cargo == null) {
            $cargo = 'Z00';
        }

        // Luego extraés normalmente
        $lcat = substr($cargo, 0, 1);
        $ncat = substr($cargo, 1);
        // $lcat = substr($request->CargoSalarial, 0, 1);
        // $ncat = substr($request->CargoSalarial, 1);
        $infoEscuela = DB::connection('DB8')->table('instarealiq')
        ->where('instarealiq.CUEA', session('CUECOMPLETOBASE'))->first();
        $zona =  $infoEscuela->codZonaLiq ? $infoEscuela->codZonaLiq : 'S/D';
        $area = $infoEscuela->area ? $infoEscuela->area : 'S/D';
        $escu = $infoEscuela->escu ? $infoEscuela->escu : 'S/D';

        //listo podemos insertar
        $AgenteNuevo = new PofIpeModel();
            $AgenteNuevo->ApeNom = $request->Apellido . ', ' . $request->Nombre;
            $AgenteNuevo->Sexo = $request->Sexo;
            $AgenteNuevo->Documento = $request->Documento;
            $AgenteNuevo->Cuil = $request->CUIL;
            $AgenteNuevo->Trabajo = 'S';
            $AgenteNuevo->Zona = $zona;
            $AgenteNuevo->Escu = $escu;
            $AgenteNuevo->Area = $area;
            $AgenteNuevo->Plan = $request->SitRev;
            $AgenteNuevo->Agrupacion = 'S/D';
            $AgenteNuevo->Antiguedad = 'S/D';
            $AgenteNuevo->lcat = $lcat;
            $AgenteNuevo->ncat = $ncat;
            $AgenteNuevo->Agregado = 1; //para poder usarlo como bandera
            $AgenteNuevo->CUECOMPLETO_AG =session('CUECOMPLETOBASE');
        $AgenteNuevo->save();
        return redirect("/controlDeIpe")->with('ConfirmarNuevoUsuario','OK');


    }

    public function verificarDni(Request $request)
    {
        $existe = DB::connection('DB7') // o la conexión que uses
            ->table('tb_pof_ipe')
            ->where('Documento', $request->documento)
            ->exists();

        return response()->json(['existe' => $existe]);
    }


}
