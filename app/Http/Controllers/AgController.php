<?php

namespace App\Http\Controllers;

use App\Models\AgenteModel;
use App\Models\AsignaturaModel;
use App\Models\EspacioCurricularModel;
use App\Models\HorariosModel;
use App\Models\InstitucionExtensionModel;
use App\Models\Nodo;
use App\Models\NovedadesModel;
use Illuminate\Http\Request;
use App\Models\OrganizacionesModel;
use App\Models\PlazasModel;
use App\Models\POFMH\PofmhModel;
use App\Models\POFMH\PofmhNovedades;
use App\Models\Sage\AlertaNovedadModel;
use App\Models\Sage\SuperRelacionCUEModel;
use App\Models\SitRevModel;
use App\Models\UsuarioModel;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AgController extends Controller
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
    public function verArbolServicio(){

        //obtengo el usuario que es la escuela a trabajar
        /*$idReparticion = session('idReparticion');
        //consulto a reparticiones
        $reparticion = DB::table('tb_reparticiones')
        ->where('tb_reparticiones.idReparticion',$idReparticion)
        ->get();*/
        //dd($reparticion[0]->Organizacion);
        
        //traigo todo de suborganizacion pasada
        /*$subOrganizacion=DB::table('tb_suborganizaciones')
        ->where('tb_suborganizaciones.idsuborganizacion',$reparticion[0]->subOrganizacion)
        ->select('*')
        ->get();*/

        $institucionExtension=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
                ->get();
        
        //dd(session('idInstitucionExtension'));
        /*
            [
                {
                "org": 807
                }
            ]
                si lo llamo db:table me devuelve asi, leerlo como array primero objeto[0]->clave
        */
       
        //funcion previa, luego la desecho porque la idea es que use NODO en su lugar
        /*$suborganizaciones = DB::table('tb_suborganizaciones')
        ->where('tb_suborganizaciones.idSubOrganizacion',session('idSubOrg'))
        ->join('tb_plazas', 'tb_plazas.Suborganizacion', '=', 'tb_suborganizaciones.idSubOrganizacion')
        ->join('tb_agentes', 'tb_agentes.idAgente', '=', 'tb_plazas.DuenoActual')  
        ->join('tb_asignaturas', 'tb_asignaturas.idAsignatura', '=', 'tb_plazas.Asignatura')
        ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_plazas.SitRevDuenoActual')
        ->join('tb_espacioscurriculares', 'tb_espacioscurriculares.idEspacioCurricular', '=', 'tb_plazas.EspacioCurricular')
        ->select(
            'tb_suborganizaciones.*',
            'tb_plazas.*',
            'tb_agentes.*',
            'tb_asignaturas.Descripcion as DesAsc',
            'tb_situacionrevista.Descripcion as SR',
            'tb_espacioscurriculares.Horas as CargaHoraria',
        )
        ->orderBy('tb_agentes.idAgente','ASC')
        ->get();
        */
        //por ahora traigo las plazas de una determina SubOrganizacion
       /* $plazas = DB::table('tb_plazas')
        ->where('tb_plazas.SubOrganizacion',$idSubOrg)
        ->leftJoin('tb_agentes', 'tb_agentes.idAgente', '=', 'tb_plazas.DuenoActual')
        ->select(
            'tb_plazas.*',
            'tb_agentes.Nombres',
            'tb_agentes.Documento',

        )
        ->orderBy('tb_plazas.idPlaza','DESC')
        ->get();
        */
        /*       $turnos = DB::table('tb_turnos')->get();
        $regimen_laboral = DB::table('tb_regimenlaboral')->get();
        $fuentesDelFinanciamiento = DB::table('tb_fuentesdefinanciamiento')->get();
        $tiposDeFuncion = DB::table('tb_tiposdefuncion')->get();
        $Asignaturas = DB::table('tb_asignaturas')->get();
        $CargosSalariales = DB::table('tb_cargossalariales')->get();
        */
       /* $datos=array(
            'mensajeError'=>"",
            'idOrg'=>$organizacion[0]->Org,
            'NombreOrg'=>$organizacion[0]->Descripcion,
            'CueOrg'=>$organizacion[0]->CUE,
            'infoSubOrganizaciones'=>$suborganizaciones,
            'idSubOrg'=>$idSubOrg,  //la roto para pasarla a otras ventanas y saber donde volver
            'infoPlazas'=>$plazas,
            'CargosSalariales'=>$CargosSalariales,
            'Asignaturas'=>$Asignaturas,
            'tiposDeFuncion'=>$tiposDeFuncion,
        );

        //respaldo de infonodos julio 2023
        $infoNodos=DB::table('tb_nodos')
        ->where('tb_suborganizaciones.idSubOrganizacion',$reparticion[0]->subOrganizacion)
        // ->whereNotNull('tb_nodos.PosicionAnterior')
        ->join('tb_suborganizaciones', 'tb_suborganizaciones.cuecompleto', 'tb_nodos.CUE')
        ->leftjoin('tb_agentes', 'tb_agentes.idAgente', 'tb_nodos.Agente')
        ->leftjoin('tb_asignaturas', 'tb_asignaturas.idAsignatura', 'tb_nodos.Asignatura')
        ->leftjoin('tb_cargossalariales', 'tb_cargossalariales.idCargo', 'tb_nodos.CargoSalarial')
        ->leftjoin('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', 'tb_nodos.SitRev')
        ->leftjoin('tb_divisiones', 'tb_divisiones.idDivision', 'tb_nodos.Division')
        ->select(
            'tb_agentes.*',
            'tb_nodos.*',
            'tb_asignaturas.idAsignatura',
            'tb_asignaturas.Descripcion as nomAsignatura',
            'tb_cargossalariales.idCargo',
            'tb_cargossalariales.Cargo as nomCargo',
            'tb_cargossalariales.Codigo as nomCodigo',
            'tb_situacionrevista.idSituacionRevista',
            'tb_situacionrevista.Descripcion as nomSitRev',
            'tb_divisiones.idDivision',
            'tb_divisiones.Descripcion as nomDivision',
        )
        ->orderBy('PosicionAnterior','ASC')
        ->get();
        */
        //traigo los nodos
        $infoNodos=DB::table('tb_nodos')
        ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
        // ->whereNotNull('tb_nodos.PosicionAnterior')
        ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', 'tb_nodos.CUECOMPLETO')
        ->select(
            'tb_nodos.*'
        )
        ->orderBy('tb_nodos.idNodo','ASC')
        ->get();
        //dd($infoNodos);

        //traemos otros array
        $SituacionRevista = DB::table('tb_situacionrevista')->get();
       /* $CargosInicial=DB::table('tb_asignaturas')
        ->orWhere('Descripcion', 'like', '%Cargo -%')->get();*/
        
        $Divisiones = DB::table('tb_divisiones')
                ->where('tb_divisiones.idInstitucionExtension',session('idInstitucionExtension'))
                ->join('tb_cursos','tb_cursos.idCurso', '=', 'tb_divisiones.Curso')
                ->join('tb_division','tb_division.idDivisionU', '=', 'tb_divisiones.Division')
                ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_divisiones.Turno')
                ->select(
                    'tb_divisiones.*',
                    'tb_divisiones.Descripcion as DescripcionDivi',
                    'tb_cursos.*',
                    'tb_division.*',
                    'tb_turnos.Descripcion as DescripcionTurno',
                    'tb_turnos.idTurno',
                )
                ->orderBy('tb_cursos.idCurso','ASC')
                ->get();

         /*   $EspaciosCurriculares = DB::table('tb_espacioscurriculares')
                ->where('tb_espacioscurriculares.SubOrg',session('idSubOrganizacion'))
                ->join('tb_asignaturas','tb_asignaturas.idAsignatura', 'tb_espacioscurriculares.Asignatura')
                ->select(
                    'tb_espacioscurriculares.*',
                    'tb_asignaturas.*'
                )
                //->orderBy('tb_asignaturas.DescripcionCurso','ASC')
                ->get();*/
        $datos=array(
            'mensajeError'=>"",
            'CUECOMPLETO'=>$institucionExtension[0]->CUECOMPLETO,
            'CUE'=>$institucionExtension[0]->CUE,
            'nombreInst'=>$institucionExtension[0]->Nombre_Institucion,
            'infoInstitucion'=>$institucionExtension,
            'idInstitucion'=>$institucionExtension[0]->idInstitucion, 
            'infoNodos'=>$infoNodos,
            //'CargosInicial'=>$CargosInicial,
            'SituacionDeRevista'=>$SituacionRevista,
            'Divisiones'=>$Divisiones,
            //'EspaciosCurriculares'=>$EspaciosCurriculares,
            'mensajeNAV'=>'Panel de Configuración de POF(Planta Orgánica Funcional)'
        );
        //lo guardo para controlar a las personas de una determinada cue/suborg
        //session(['CUE'=>$institucion[0]->CUE]);
        
        //session(['idInstitucion'=>$institucion[0]->idInstitucion]);
        //dd($plazas);
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">LEGAJO ÚNICO DE PERSONAL</a></li>
            <li class="breadcrumb-item active"><a href="'.route('verArbolServicio').'">CONFIGURAR AGENTE</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.AG.Servicios.arbol',$datos);
    }
    public function verArbolServicio2(){

        //obtengo el usuario que es la escuela a trabajar
        //$idReparticion = session('idReparticion');
        //consulto a reparticiones
        /*$reparticion = DB::table('tb_reparticiones')
        ->where('tb_reparticiones.idReparticion',$idReparticion)
        ->get();*/
        //dd($reparticion[0]->Organizacion);
        
        //traigo todo de suborganizacion pasada
        $institucionExtension=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
                ->get();
       // dd($institucionExtension);
        /*
            [
                {
                "org": 807
                }
            ]
                si lo llamo db:table me devuelve asi, leerlo como array primero objeto[0]->clave
        */
       
        //funcion previa, luego la desecho porque la idea es que use NODO en su lugar
        /*$suborganizaciones = DB::table('tb_suborganizaciones')
        ->where('tb_suborganizaciones.idSubOrganizacion',session('idSubOrg'))
        ->join('tb_plazas', 'tb_plazas.Suborganizacion', '=', 'tb_suborganizaciones.idSubOrganizacion')
        ->join('tb_agentes', 'tb_agentes.idAgente', '=', 'tb_plazas.DuenoActual')  
        ->join('tb_asignaturas', 'tb_asignaturas.idAsignatura', '=', 'tb_plazas.Asignatura')
        ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_plazas.SitRevDuenoActual')
        ->join('tb_espacioscurriculares', 'tb_espacioscurriculares.idEspacioCurricular', '=', 'tb_plazas.EspacioCurricular')
        ->select(
            'tb_suborganizaciones.*',
            'tb_plazas.*',
            'tb_agentes.*',
            'tb_asignaturas.Descripcion as DesAsc',
            'tb_situacionrevista.Descripcion as SR',
            'tb_espacioscurriculares.Horas as CargaHoraria',
        )
        ->orderBy('tb_agentes.idAgente','ASC')
        ->get();
        */
        //por ahora traigo las plazas de una determina SubOrganizacion
       /* $plazas = DB::table('tb_plazas')
        ->where('tb_plazas.SubOrganizacion',$idSubOrg)
        ->leftJoin('tb_agentes', 'tb_agentes.idAgente', '=', 'tb_plazas.DuenoActual')
        ->select(
            'tb_plazas.*',
            'tb_agentes.Nombres',
            'tb_agentes.Documento',

        )
        ->orderBy('tb_plazas.idPlaza','DESC')
        ->get();
        */
        /*       $turnos = DB::table('tb_turnos')->get();
        $regimen_laboral = DB::table('tb_regimenlaboral')->get();
        $fuentesDelFinanciamiento = DB::table('tb_fuentesdefinanciamiento')->get();
        $tiposDeFuncion = DB::table('tb_tiposdefuncion')->get();
        $Asignaturas = DB::table('tb_asignaturas')->get();
        $CargosSalariales = DB::table('tb_cargossalariales')->get();
        */
       /* $datos=array(
            'mensajeError'=>"",
            'idOrg'=>$organizacion[0]->Org,
            'NombreOrg'=>$organizacion[0]->Descripcion,
            'CueOrg'=>$organizacion[0]->CUE,
            'infoSubOrganizaciones'=>$suborganizaciones,
            'idSubOrg'=>$idSubOrg,  //la roto para pasarla a otras ventanas y saber donde volver
            'infoPlazas'=>$plazas,
            'CargosSalariales'=>$CargosSalariales,
            'Asignaturas'=>$Asignaturas,
            'tiposDeFuncion'=>$tiposDeFuncion,
        );

        //respaldo de infonodos julio 2023
        $infoNodos=DB::table('tb_nodos')
        ->where('tb_suborganizaciones.idSubOrganizacion',$reparticion[0]->subOrganizacion)
        // ->whereNotNull('tb_nodos.PosicionAnterior')
        ->join('tb_suborganizaciones', 'tb_suborganizaciones.cuecompleto', 'tb_nodos.CUE')
        ->leftjoin('tb_agentes', 'tb_agentes.idAgente', 'tb_nodos.Agente')
        ->leftjoin('tb_asignaturas', 'tb_asignaturas.idAsignatura', 'tb_nodos.Asignatura')
        ->leftjoin('tb_cargossalariales', 'tb_cargossalariales.idCargo', 'tb_nodos.CargoSalarial')
        ->leftjoin('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', 'tb_nodos.SitRev')
        ->leftjoin('tb_divisiones', 'tb_divisiones.idDivision', 'tb_nodos.Division')
        ->select(
            'tb_agentes.*',
            'tb_nodos.*',
            'tb_asignaturas.idAsignatura',
            'tb_asignaturas.Descripcion as nomAsignatura',
            'tb_cargossalariales.idCargo',
            'tb_cargossalariales.Cargo as nomCargo',
            'tb_cargossalariales.Codigo as nomCodigo',
            'tb_situacionrevista.idSituacionRevista',
            'tb_situacionrevista.Descripcion as nomSitRev',
            'tb_divisiones.idDivision',
            'tb_divisiones.Descripcion as nomDivision',
        )
        ->orderBy('PosicionAnterior','ASC')
        ->get();
        */
        //verifico si viene algo en la session
        if (session()->has('filtroDivision') && session('filtroDivision') != "") {
            $infoNodos=DB::table('tb_nodos')
            //->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
            ->where('tb_nodos.idTurnoUsuario',session('idTurnoUsuario'))
            ->where('tb_nodos.CUECOMPLETO',session('CUECOMPLETO'))
            ->where('tb_nodos.Division',session('filtroDivision'))
            // ->whereNotNull('tb_nodos.PosicionAnterior')
           // ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', 'tb_nodos.CUECOMPLETO')
            ->select(
                'tb_nodos.*'
            )
            ->orderBy('tb_nodos.idNodo','ASC')
            ->get();
        } else {
           //traigo los nodos de un CUECOMPLETO especifico + TURNO
            $infoNodos=DB::table('tb_nodos')
            //->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
            ->where('tb_nodos.idTurnoUsuario',session('idTurnoUsuario'))
            ->where('tb_nodos.CUECOMPLETO',session('CUECOMPLETO'))
            // ->whereNotNull('tb_nodos.PosicionAnterior')
        // ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', 'tb_nodos.CUECOMPLETO')
            ->select(
                'tb_nodos.*'
            )
            ->orderBy('tb_nodos.idNodo','ASC')
            ->get();
        }
        
        //dd($infoNodos);

        //traemos otros array
        $SituacionRevista = DB::table('tb_situacionrevista')->get();
        /*$CargosInicial=DB::table('tb_asignaturas')
        ->orWhere('Descripcion', 'like', '%Cargo -%')->get();*/
        
        $Divisiones = DB::table('tb_divisiones')
                ->where('tb_divisiones.idInstitucionExtension',session('idInstitucionExtension'))
                ->join('tb_cursos','tb_cursos.idCurso', '=', 'tb_divisiones.Curso')
                ->join('tb_division','tb_division.idDivisionU', '=', 'tb_divisiones.Division')
                ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_divisiones.Turno')
                ->select(
                    'tb_divisiones.*',
                    'tb_divisiones.Descripcion as DescripcionDivi',
                    'tb_cursos.*',
                    'tb_division.*',
                    'tb_turnos.Descripcion as DescripcionTurno',
                    'tb_turnos.idTurno',
                )
                ->orderBy('tb_cursos.idCurso','ASC')
                ->get();

            /*$EspaciosCurriculares = DB::table('tb_espacioscurriculares')
                ->where('tb_espacioscurriculares.SubOrg',session('idSubOrganizacion'))
                ->join('tb_asignaturas','tb_asignaturas.idAsignatura', 'tb_espacioscurriculares.Asignatura')
                ->select(
                    'tb_espacioscurriculares.*',
                    'tb_asignaturas.*'
                )
                //->orderBy('tb_asignaturas.DescripcionCurso','ASC')
                ->get();*/
        $datos=array(
            'mensajeError'=>"",
            'CUECOMPLETO'=>$institucionExtension[0]->CUECOMPLETO,
            'Nombre_Institucion'=>$institucionExtension[0]->Nombre_Institucion,
            'institucionExtension'=>$institucionExtension,
            'idInstitucionExtension'=>$institucionExtension[0]->idInstitucionExtension, 
            'infoNodos'=>$infoNodos,
            //'CargosInicial'=>$CargosInicial,
            'SituacionDeRevista'=>$SituacionRevista,
            'Divisiones'=>$Divisiones,
            //'EspaciosCurriculares'=>$EspaciosCurriculares,
            'mensajeNAV'=>'Panel de Configuración de POF(Planta Orgánica Funcional)'
        );
        //lo guardo para controlar a las personas de una determinada cue/suborg
        //session(['CUE'=>$institucionExtension[0]->CUE]);
        
        //session(['idSubOrg'=>$institucionExtension[0]->subOrganizacion]);
        //dd($infoNodos);
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">LEGAJO ÚNICO DE PERSONAL</a></li>
            <li class="breadcrumb-item active"><a href="'.route('verArbolServicio2').'">LISTA DE AGENTE</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.AG.Servicios.arbol2',$datos);
    }
    public function getAgentes($DNI){
        //verifico primero si el DNI existe en la base de datos
        /*$infoDNI = DB::table('tb_desglose_agentes')
        ->where('tb_desglose_agentes.docu',  $DNI)
        ->join('tb_institucion', 'tb_institucion.Unidad_Liquidacion', '=', 'tb_desglose_agentes.escu')
        ->select('tb_desglose_agentes.*')
        ->orderBy('tb_desglose_agentes.idDesgloseAgente', 'ASC')
        ->get();*/

        //traigo todos los agentes que coincidan con su DNI
        // $Agentes = DB::table('tb_desglose_agentes')
        // ->where('tb_desglose_agentes.docu', '=', $DNI)
        // ->select('tb_desglose_agentes.docu','tb_desglose_agentes.nomb')
        // ->groupBy('tb_desglose_agentes.docu','tb_desglose_agentes.nomb')
        // ->get();

        $Agentes = DB::connection('DB7')->table('tb_pofmh')
        ->where('Agente',$DNI)
        ->where('CUECOMPLETO',session('CUECOMPLETO'))
        ->join(DB::connection('mysql')->getDatabaseName() . '.tb_agentes', 'tb_agentes.Documento', '=', 'tb_pofmh.Agente')
        /*->join(DB::connection('DB7')->getDatabaseName() . '.tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pofmh.SitRev')*/
        ->get();
       //print_r($Agentes);

       
        $respuesta="";
        $contador=0;
        if($Agentes->isNotEmpty()){
            $contador++;
            foreach($Agentes as $a){
                //busco datos
                // Validar si la consulta para la situación de revista devuelve datos
                $infoSiTRev = DB::table('tb_situacionrevista')->where('idSituacionRevista', $a->SitRev)->first();
                $SitRev = $infoSiTRev && $infoSiTRev->Descripcion ? $infoSiTRev->Descripcion : "S/D";
                
                // Validar si la consulta para el cargo salarial devuelve datos
                $infoCargoSalarial = DB::table('tb_cargossalariales')->where('idCargo', $a->Cargo)->first();
                $CargoSalarial = $infoCargoSalarial && $infoCargoSalarial->Cargo ? $infoCargoSalarial->Codigo . " - " . $infoCargoSalarial->Cargo : "S/D";

                $infoCondicion = DB::connection('DB7')->table('tb_condiciones')->where('idCondicion', $a->Condicion)->first();
                $Condicion = $infoCondicion && $infoCondicion->Descripcion ? $infoCondicion->Descripcion : "S/D";

                $infoActivo = DB::connection('DB7')->table('tb_activos')->where('idActivo', $a->Activo)->first();
                $Activo = $infoActivo && $infoActivo->nombre_activo ? $infoActivo->nombre_activo : "S/D";

                //otros datos
                $horas = $a->Horas ? $a->Horas: 'S/D';
                $EspCur = $a->EspCur ? $a->EspCur: 'S/D';
                
                $respuesta=$respuesta.'
                <tr class="gradeX">
                    <td>'.$a->idPofmh.'</td>
                    <td>
                        '.$a->ApeNom.'
                        <input type="hidden" id="dniAgenteModal'.$a->idPofmh.'" value="'.$a->Agente.'">
                        <input type="hidden" id="nomAgenteModal'.$a->idPofmh.'" value="'.$a->ApeNom.'">
                    </td>
                    <td>'.$a->Agente.'</td>
                    <td>'.$horas .'</td>
                    <td>'.$SitRev.'</td>
                    <td>'.$CargoSalarial.'</td>
                    <td>'.$EspCur.'</td>
                    <td>'.$Condicion.'</td>
                    <td>'.$Activo.'</td>
                    
                    <td>
                        <input type="hidden" name="Agente" value="'.$a->Agente.'">
                        <button type="button" name="btnAgregar" onclick="seleccionarAgentes('.$a->idPofmh.')">Agregar Agente</button>
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
    public function getAgentesActualizar($DNI){
        //traigo todos los agentes que coincidan con su DNI
        $Agentes = DB::table('tb_desglose_agentes')
        ->where('tb_desglose_agentes.docu',$DNI)
        ->select('tb_desglose_agentes.docu','tb_desglose_agentes.nomb')
        ->groupBy('tb_desglose_agentes.docu','tb_desglose_agentes.nomb')
        ->get();

       //print_r($Agentes);
        $respuesta="";
        $contador=1;
        foreach($Agentes as $a){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                    <td>'.$contador.'</td>
                    <td>'.$a->nomb.'<input type="hidden" id="nomAgenteModal'.$a->docu.'" value="'.$a->nomb.'"</td>
                    <td>'.$a->docu.'</td>
                    
                    <td>
                        <input type="hidden" name="Agente" value="'.$a->docu.'">
                    <button type="button" name="btnAgregar" onclick="seleccionarAgentesActualizar('.$a->docu.')">Agregar Agente</button>
                </td>
            </tr>';
            $contador++;
            
        }

        
        //<button type="submit" onclick="seleccionarAgente('.$a->idAgente.')">Agregar Agente</button>
        //echo $respuesta;
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }
    public function getAgentesRel($DNI){
        //traigo todos los agentes que coincidan con su DNI
        $Agentes = DB::table('tb_agentes')
        ->where('tb_agentes.Documento',$DNI)
        ->select(
            'tb_agentes.*',
        )
        ->orderBy('tb_agentes.idAgente','ASC')
        ->get();

       //print_r($Agentes);
        $respuesta="";
       
        foreach($Agentes as $a){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$a->idAgente.'</td>
                <td>'.$a->Nombres.'<input type="hidden" id="nomAgenteModal'.$a->idAgente.'" value="'.$a->Nombres.'"</td>
                <td>'.$a->Documento.'</td>
                <td>
                    <input type="hidden" name="Agente" value="'.$a->idAgente.'">
                    <button type="submit" name="btnAgregar">Agregar Agente</button>
                </td>
            </tr>';
            
            
        }
        //<button type="submit" onclick="seleccionarAgente('.$a->idAgente.')">Agregar Agente</button>
        //echo $respuesta;
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }
    
    public function agregarAgenteEscuela(Request $request){
        //echo $request->Agente." ".session('CUE') ;
       // dd($request);
        /*
            "_token" => "GlwXEhtxgnCKdflU0laeMRsVb2YsU6uLrrIbC1JE"
            "idAgenteNuevoNodo" => "26731952"
            "CargoSal" => "1"
            "SituacionDeRevista" => "1"
            "idDivision" => "658"
            "cant_horas" => "20"
            "FechaAltaN" => "2024-02-10"
            se agrego la observacion para poner resoluciones
            se agrego fecha2 para el manejo de fecha en juetaeno


        */
       /* if($request->idEspCur != ""){
            $EspCur=DB::table('tb_espacioscurriculares')
            ->where('idEspacioCurricular',$request->idEspCur)
            ->get();
    
            //dd($EspCur[0]->Asignatura);
            $idAsig=$EspCur[0]->Asignatura;
        }else{
            $idAsig=1;
        }*/
        
        $nodo = new Nodo;
            $nodo->Agente = $request->idAgenteNuevoNodo;    //DNI
           // $nodo->EspacioCurricular = $request->idEspCur;
            $nodo->Division = $request->idDivision;
            $nodo->CargoSalarial = $request->CargoSal;
            $nodo->CantidadHoras = $request->cant_horas;
            $nodo->FechaDeAlta = $request->FechaAltaN1;     //toma en escuela
            $nodo->FechaDeAltaJuetaeno = $request->FechaAltaN2;     //toma en juetaeo
            $nodo->SitRev = $request->SituacionDeRevista;
           // $nodo->Asignatura = $idAsig;
            $nodo->Activo = 1;  //es nuevo y esta activo
            $nodo->Usuario = session('idUsuario');  //el que administra la escuela
            $nodo->CUECOMPLETO = session('CUECOMPLETO');
            $nodo->idTurnoUsuario = session('idTurnoUsuario');
            //datos extra
            $nodo->LicenciaActiva = "NO";   //es nodo nuevo, no tiene una licencia
            $nodo->CantidadAsistencia = 1;  //lo seteo a 1 cuando es nuevo
            $nodo->Observaciones = $request->Observaciones;
        $nodo->save();
        
        $SituacionDeRevista = SitRevModel::where('idSituacionRevista',$request->SituacionDeRevista)->get();
        //dd($SituacionDeRevista);
        //cargo la novedad de ingreso nuevo
        $novedad = new NovedadesModel();
            $novedad->Agente = $nodo->Agente;
            $novedad->CUECOMPLETO = session('CUECOMPLETO');
            $novedad->idTurnoUsuario = session('idTurnoUsuario');
            $novedad->CargoSalarial = $request->CargoSal;
            $novedad->Caracter = $request->SituacionDeRevista;
            $novedad->Division = $request->idDivision;
            $novedad->FechaDesde = Carbon::parse(Carbon::now())->format('Y-m-d');
            $novedad->TotalDias = 1;
            $novedad->Mes = date('m');
            $novedad->Anio = date('Y');
            $novedad->Motivo = 46;   //en este caso es vacante
            $novedad->Observaciones = "Alta de Servicio: ".$SituacionDeRevista[0]->Descripcion;
            $novedad->Estado = 1;   //activo tiene novedad sin fecha hasta
            $novedad->Nodo = $nodo->idNodo; //por ahora lo hago asi, tengo dudas
            $novedad->CantidadDiasTrabajados = $nodo->CantidadAsistencia;
        $novedad->save();
        
        return redirect()->back()->with('ConfirmarNuevoAgente','OK');
      
    }

    public function getBuscarAgente($DNI){
        
        //traigo todos los agentes que coincidan con su DNI
        $Agentes = DB::table('tb_desglose_agentes')
        ->where('tb_desglose_agentes.docu',$DNI)
        ->select(
            'tb_desglose_agentes.*',
        )
        ->orderBy('tb_desglose_agentes.idDesgloseAgente','ASC')
        ->get();
        if($Agentes->count()>0)
        {
            $respuesta=true;
        }else{
            $respuesta=false;
        }
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }

    public function getLocalidades($localidad){
        //traigo las relaciones Suborg->planes->carrera
        $Localidades = DB::table('tb_localidades')
        ->leftjoin('tb_provincias', 'tb_provincias.idProvincia', '=', 'tb_localidades.IdProvincia')
        ->orWhere('localidad', 'like', '%' . $localidad . '%')->get();

       //dd($Divisiones);
        $respuesta="";
       
        foreach($Localidades as $obj){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$obj->idLocalidad.'</td>
                <td>'.$obj->localidad.'<input type="hidden" id="nomLocalidadModal'.$obj->idLocalidad.'" value="'.$obj->localidad.'"</td>
                <td>'.$obj->nombre.'</td>
                <td>
                    <button type="button" onclick="seleccionarLocalidad('.$obj->idLocalidad.')">Seleccionar</button>
                </td>
            </tr>';
            
            
        }
       
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }
    public function getLocalidadesInstitucion($localidad){
        //traigo las relaciones Suborg->planes->carrera
        
        $Localidades = DB::table('tb_localidades')
        ->join('tb_departamentos', 'tb_departamentos.idDepartamento', '=', 'tb_localidades.Departamento')
        ->Where('localidad', 'like', '%' . $localidad . '%')->get();
        
       //dd($Divisiones);
        $respuesta="";
       
        foreach($Localidades as $obj){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$obj->idLocalidad.'</td>
                <td>'.$obj->localidad.'<input type="hidden" id="nomLocalidadModal'.$obj->idLocalidad.'" value="'.$obj->localidad.'"</td>
                <td>'.$obj->nombre_dpto.'</td>
                <td>
                    <button type="button" onclick="seleccionarLocalidadInstitucion('.$obj->idLocalidad.')">Seleccionar</button>
                </td>
            </tr>';
            
            
        }
       
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }
    public function getDepartamentos($departamento){
        //traigo las relaciones Suborg->planes->carrera
        //por alguna razon esta ligado y cargo en localiddes los dpto

        $Departamentos = DB::table('tb_localidades')
        //->join('tb_provincias', 'tb_provincias.idProvincia', '=', 'tb_departamentos.Provincia')
        ->orWhere('localidad', 'like', '%' . $departamento . '%')
    
        ->get();

       //dd($Divisiones);
        $respuesta="";
       
        foreach($Departamentos as $obj){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$obj->idLocalidad.'</td>
                <td>'.$obj->localidad.'<input type="hidden" id="nomDepartamentoModal'.$obj->idLocalidad.'" value="'.$obj->localidad.'"</td>
                <td>
                    <button type="button" onclick="seleccionarDepartamento('.$obj->idLocalidad.')">Seleccionar</button>
                </td>
                
            </tr>';
            
            
        }
       
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }


    public function agregaNodo($nodoActual){
        //aqui voy a verificar si es titular/interino u otra clase que requiera nodo anterior

            $nodo = Nodo::where('idNodo', $nodoActual)->first();

            //valido si no tiene nadie a su derecha, no existe nodo y puede crear
            if($nodo->PosicionSiguiente == null || $nodo->PosicionSiguiente == "" ){
                    $Nuevo = new Nodo;
                    $Nuevo->Agente = null;
                // $Nuevo->EspacioCurricular = null;
                    $Nuevo->CargoSalarial = $nodo->CargoSalarial;
                    $Nuevo->CantidadHoras = $nodo->CantidadHoras;
                    $Nuevo->FechaDeAlta = $nodo->FechaDeAlta;
                    $Nuevo->Division = $nodo->Division;
                    $Nuevo->SitRev = $nodo->SitRev;
                    //$Nuevo->Asignatura = null;
                    $Nuevo->Usuario = session('idUsuario');
                    $Nuevo->CUECOMPLETO = session('CUECOMPLETO');
                    $Nuevo->idTurnoUsuario = session('idTurnoUsuario');
                    $Nuevo->PosicionAnterior = $nodoActual;
                    $Nuevo->Activo = 0;
                    $Nuevo->CantidadAsistencia = 0;
                    $Nuevo->LicenciaActiva = "SI";
                $Nuevo->save();

                $SituacionDeRevista = SitRevModel::where('idSituacionRevista',$nodo->SitRev)->get();
                //le doy de alta 
                $novedad = new NovedadesModel();
                    $novedad->Agente = null;
                    $novedad->CUECOMPLETO = session('CUECOMPLETO');
                    $novedad->idTurnoUsuario = session('idTurnoUsuario');
                    $novedad->CargoSalarial = $nodo->CargoSalarial;
                    $novedad->Caracter = $nodo->SitRev;
                    $novedad->Division = $nodo->Division;
                    $novedad->FechaDesde = Carbon::parse(Carbon::now())->format('Y-m-d');
                    $novedad->TotalDias = 1;
                    $novedad->Mes = date('m');
                    $novedad->Anio = date('Y');
                    $novedad->Motivo = 46;   //en este caso es vacante
                    $novedad->Observaciones = "Alta de Servicio: ".$SituacionDeRevista[0]->Descripcion;
                    $novedad->Estado = 1;   //activo tiene novedad sin fecha hasta
                    $novedad->Nodo = $Nuevo->idNodo; //por ahora lo hago asi, tengo dudas
                    $novedad->CantidadDiasTrabajados = $nodo->CantidadAsistencia;
                $novedad->save();

                //si viene de izquierda a dererecha, le agrego una novedad de licencia
                //cargo la novedad de ingreso nuevo
                $novedad = new NovedadesModel();
                    $novedad->Agente = $Nuevo->Agente;
                    $novedad->CUECOMPLETO = session('CUECOMPLETO');
                    $novedad->idTurnoUsuario = session('idTurnoUsuario');
                    $novedad->CargoSalarial = $Nuevo->CargoSalarial;
                    $novedad->Caracter = $Nuevo->SitRev;
                    $novedad->Division = $Nuevo->Division;
                    $novedad->FechaDesde = Carbon::parse(Carbon::now())->format('Y-m-d');
                    $novedad->TotalDias = 1;
                    $novedad->Mes = date('m');
                    $novedad->Anio = date('Y');
                    $novedad->Motivo = 48;   //activo un suplencia
                    $novedad->ObservacionesLicencia = $Nuevo->Observaciones;
                    $novedad->Estado = 1;   //activo tiene novedad sin fecha hasta
                    $novedad->Nodo = $Nuevo->idNodo; //por ahora lo hago asi, tengo dudas
                    $novedad->CantidadDiasTrabajados = $Nuevo->CantidadAsistencia;

                    //calculo fecha desde-hasta
                    // Fecha inicial y final en formato YYYY-MM-DD
                    $fechaInicialObj = '2023-07-15';
                    $fechaFinalObj = '2023-08-02';

                    // Crear objetos DateTime
                    $fechaInicialObj = new DateTime(Carbon::parse(Carbon::now())->format('Y-m-d'));
                    $fechaFinalObj = new DateTime(Carbon::parse(Carbon::now())->format('Y-m-d'));

                    // Calcular la diferencia entre las dos fechas
                    $intervalo = $fechaInicialObj->diff($fechaFinalObj);

                    // Obtener la cantidad de días
                    $cantidadDias = $intervalo->days;

                    $novedad->FechaInicioLicencia = Carbon::parse(Carbon::now())->format('Y-m-d');
                    $novedad->FechaHastaLicencia = Carbon::parse(Carbon::now())->format('Y-m-d');
                    $novedad->TotalDiasLicencia = $cantidadDias ;
                    $novedad->EstaActivaLicencia = "SI" ;
                $novedad->save();
            
                //obtengo el id y lo guardo relacionando al anterior que recibo por parametro
                $Nuevo->idNodo;
                    $nodo = Nodo::where('idNodo', $nodoActual)->first();
                    $nodo->PosicionSiguiente = $Nuevo->idNodo;
                $nodo->save();

                return redirect()->back()->with('ConfirmarNuevoNodo','OK');
        // }
            }else{
                return redirect()->back()->with('ConfirmarNuevoNodoDerechoFallo','OK');
            }
           
        
    }

    public function agregaLic(Request $request){
        //aqui voy a verificar si es titular/interino u otra clase que requiera nodo anterior
        //por ahora no verificar a volante, tenerlo en cuenta luego
        //obtengo el agente actual(nodo actual)
        $nodoActual = Nodo::where('idNodo', $request->idNodo)->first();
       
        //1 si es raiz
        if($nodoActual->PosicionAnterior==null || $nodoActual->PosicionAnterior==""){
            //creo un nodo vacio, no le agrego novedad, dejo para usar el vincular
            $Nuevo = new Nodo;
                $Nuevo->Agente = null;
                //$Nuevo->EspacioCurricular = $nodoActual->EspacioCurricular;
                $Nuevo->CargoSalarial = null;
                $Nuevo->CantidadHoras = $nodoActual->CantidadHoras;
                $Nuevo->FechaDeAlta = null; //cuando se cargue el agente nuevo  antes era null
                $Nuevo->Division = $nodoActual->Division;
                $Nuevo->SitRev = null;
            // $Nuevo->Asignatura = $nodoActual->Asignatura;
                $Nuevo->Usuario = session('idUsuario');
                $Nuevo->CUECOMPLETO = session('CUECOMPLETO');
                $Nuevo->idTurnoUsuario = session('idTurnoUsuario');
                $Nuevo->PosicionAnterior = null;
                $Nuevo->PosicionSiguiente = $nodoActual->idNodo;            //aqui lo vinculo con mi actual, el que saca la lic
                $Nuevo->Activo = 0; //vacio vacante
                $Nuevo->CantidadAsistencia = 0;
                $Nuevo->LicenciaActiva = "NO";
            $Nuevo->save();

            $nodoActual->PosicionAnterior=$Nuevo->idNodo;
            $nodoActual->LicenciaActiva="SI";
            $nodoActual->save();

            //le agrego la novedad de Lic, no borro la activa
            //cargo la novedad de ingreso nuevo
            $novedad = new NovedadesModel();
                $novedad->Agente = $nodoActual->Agente;
                $novedad->CUECOMPLETO = session('CUECOMPLETO');
                $novedad->idTurnoUsuario = session('idTurnoUsuario');
                $novedad->CargoSalarial = $nodoActual->CargoSalarial;
                $novedad->Caracter = $nodoActual->SitRev;
                $novedad->Division = $nodoActual->Division;
                $novedad->FechaDesde = Carbon::parse(Carbon::now())->format('Y-m-d');
                $novedad->TotalDias = 1;
                $novedad->Mes = date('m');
                $novedad->Anio = date('Y');
                $novedad->Motivo = $request->TipoLicencia;   //en este caso es suplencia, etc
                $novedad->ObservacionesLicencia = $request->Observaciones;
                $novedad->Estado = 1;   //activo tiene novedad sin fecha hasta
                $novedad->Nodo = $nodoActual->idNodo; //por ahora lo hago asi, tengo dudas
                $novedad->CantidadDiasTrabajados = $nodoActual->CantidadAsistencia;

                //calculo fecha desde-hasta
                // Fecha inicial y final en formato YYYY-MM-DD
                $fechaInicialObj = '2023-07-15';
                $fechaFinalObj = '2023-08-02';

                // Crear objetos DateTime
                $fechaInicialObj = new DateTime($request->FechaInicioLic);//new DateTime(Carbon::parse(Carbon::now())->format('Y-m-d'));
                $fechaFinalObj = new DateTime($request->FechaHastaLic);
                //sumo un dia al dia base
                $fechaFinalObj->modify('+1 day'); 
                // Calcular la diferencia entre las dos fechas
                $intervalo = $fechaInicialObj->diff($fechaFinalObj);

                // Obtener la cantidad de días
                $cantidadDias = $intervalo->days;

                $novedad->FechaInicioLicencia = $request->FechaInicioLic;//Carbon::parse(Carbon::now())->format('Y-m-d');
                $novedad->FechaHastaLicencia = $request->FechaHastaLic;
                $novedad->TotalDiasLicencia = $cantidadDias ;
                $novedad->EstaActivaLicencia = "SI" ;
            $novedad->save();
        }else{
            //2-valor entre nodos
            $nodoAnterior =Nodo::where('idNodo', $nodoActual->PosicionAnterior)->first();
            
            //nuevo nodo intermedio
            $Nuevo = new Nodo;
            $Nuevo->Agente = null;
            //$Nuevo->EspacioCurricular = $nodoActual->EspacioCurricular;
            $Nuevo->CargoSalarial = null;
            $Nuevo->CantidadHoras = $nodoActual->CantidadHoras;
            $Nuevo->FechaDeAlta = null; //cuando se agregue el nuevo agente
            $Nuevo->Division = $nodoActual->Division;
            $Nuevo->SitRev = null;
            //$Nuevo->Asignatura = $nodoActual->Asignatura;
            $Nuevo->Usuario = session('idUsuario');
            $Nuevo->CUECOMPLETO = session('CUECOMPLETO');
            $Nuevo->idTurnoUsuario = session('idTurnoUsuario');
            $Nuevo->PosicionAnterior = $nodoAnterior->idNodo;
            $Nuevo->PosicionSiguiente = $nodoActual->idNodo;
            $Nuevo->Activo = 0; //vacio vacante
            $Nuevo->save();

            //modifico anterior y actual apuntando a nuevo nodo
            $nodoActual->PosicionAnterior=$Nuevo->idNodo;
            $nodoActual->save(); 

            $nodoAnterior->PosicionSiguiente=$Nuevo->idNodo;
            $nodoAnterior->save();           
        }
        

        
        

        return redirect()->back()->with('ConfirmarNuevoNodo','OK');
    }

    public function ampliarLic(Request $request){
        //aqui voy a verificar si es titular/interino u otra clase que requiera nodo anterior
        //por ahora no verificar a volante, tenerlo en cuenta luego
        //obtengo el agente actual(nodo actual)
        $nodoActual = Nodo::where('idNodo', $request->idNodo)->first();

        //buscao la novedad actual para darla de baja
        $novedadActual = NovedadesModel::where('idNovedad',$request->inov)->first();
            $novedadActual->EstaActivaLicencia = "NO" ;
            $novedadActual->Nodo = null;  //le quito para que no se accesible otra vez
        $novedadActual->save();

     //le agrego la novedad de Lic, no borro la activa
     //cargo la novedad de ingreso nuevo
     $novedad = new NovedadesModel();
         $novedad->Agente = $nodoActual->Agente;
         $novedad->CUECOMPLETO = session('CUECOMPLETO');
         $novedad->idTurnoUsuario = session('idTurnoUsuario');
         $novedad->CargoSalarial = $nodoActual->CargoSalarial;
         $novedad->Caracter = $nodoActual->SitRev;
         $novedad->Division = $nodoActual->Division;
         $novedad->FechaDesde = Carbon::parse(Carbon::now())->format('Y-m-d');
         $novedad->TotalDias = 1;
         $novedad->Mes = date('m');
         $novedad->Anio = date('Y');
         $novedad->Motivo = $request->TipoLicencia;   //en este caso es suplencia, etc
         $novedad->ObservacionesLicencia = $request->Observaciones;
         $novedad->Estado = 1;   //activo tiene novedad sin fecha hasta
         $novedad->Nodo = $nodoActual->idNodo; //por ahora lo hago asi, tengo dudas
         $novedad->CantidadDiasTrabajados = $nodoActual->CantidadAsistencia;

         //calculo fecha desde-hasta
         // Fecha inicial y final en formato YYYY-MM-DD
         $fechaInicialObj = '2023-07-15';
         $fechaFinalObj = '2023-08-02';

         // Crear objetos DateTime
         $fechaInicialObj = new DateTime($request->FechaInicioLic);//new DateTime(Carbon::parse(Carbon::now())->format('Y-m-d'));
         $fechaFinalObj = new DateTime($request->FechaHastaLic);
         //sumo un dia al dia base
        $fechaFinalObj->modify('+1 day');           
         // Calcular la diferencia entre las dos fechas
         $intervalo = $fechaInicialObj->diff($fechaFinalObj);

         // Obtener la cantidad de días
         $cantidadDias = $intervalo->days;

         $novedad->FechaInicioLicencia = $request->FechaInicioLic;//Carbon::parse(Carbon::now())->format('Y-m-d');
         $novedad->FechaHastaLicencia = $request->FechaHastaLic;
         $novedad->TotalDiasLicencia = $cantidadDias ;
         $novedad->EstaActivaLicencia = "SI" ;
     $novedad->save();
        
        return redirect()->back()->with('ConfirmarAmpliarlic','OK');
    }
    public function quitaLic($idNodo){
        //aqui voy a verificar si es titular/interino u otra clase que requiera nodo anterior
        //por ahora no verificar a volante, tenerlo en cuenta luego
       
        //busco la novedad y la actualizo
        $novedad = NovedadesModel::where('Nodo',$idNodo)
        ->where('tb_novedades.CUECOMPLETO', session('CUECOMPLETO'))
        ->where('tb_novedades.idTurnoUsuario', session('idTurnoUsuario'))
        ->where('tb_novedades.EstaActivaLicencia', "SI")
        ->whereNotIn('tb_novedades.Motivo', [46,47])   //lo busco por su anexo
        ->first();
        

        if($novedad !== null) {
        // Cuando lo encuentra lo actualiza
            $novedad->EstaActivaLicencia = "NO" ;
            $novedad->Nodo = null;  // Le quito para que no sea accesible otra vez
            $novedad->save();

            return 1; // Indicar éxito
        } else {
            return 0; // Indicar que no se encontró ninguna novedad
    }

        return 1;
    }
    public function agregarDatoANodo(Request $request){
        //actualizar el nodo creado vacio por el dato del interino, titular, etc
        $nodo = Nodo::where('idNodo', $request->input('idNodo'))->first();
        $nodo->Agente = $request->input('idAgente');
        $nodo->save();
        
        return redirect()->back()->with('ConfirmarNuevoNodo','OK');
    }

    public function getInfoNodo($nodo){
                //traigo los nodos
                $infoNodos=DB::table('tb_nodos')
                ->leftjoin('tb_agentes', 'tb_agentes.idAgente', '=', 'tb_nodos.Agente')
                ->where('tb_nodos.idNodo',$nodo)
                ->select('*')
                ->get();
                //dd($infoNodos);
        
                //traemos otros array
                $datos=array(
                    'mensajeError'=>"",
                    'infoNodoSiguiente'=>$infoNodos,
                );
                //lo guardo para controlar a las personas de una determinada cue/suborg

                //dd($plazas);
                return view('bandeja.AG.Servicios.arbol',$datos);
    }

    public function getCargosFunciones($nomCargoFuncionCodigo){
        //traigo las relaciones Suborg->planes->carrera
        $Localidades = DB::table('tb_cargossalariales')
        ->orWhere('Cargo', 'like', '%' . $nomCargoFuncionCodigo . '%')
        ->orWhere('Codigo', 'like', '%' . $nomCargoFuncionCodigo . '%')
        ->get();

       //dd($Divisiones);
        $respuesta="";
       
        foreach($Localidades as $obj){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$obj->idCargo.'</td>
                <td>'.$obj->Codigo.'<input type="hidden" id="nomCodigoModal'.$obj->idCargo.'" value="'.$obj->Codigo.'"</td>
                <td>'.$obj->Cargo.'<input type="hidden" id="nomCargoModal'.$obj->idCargo.'" value="'.$obj->Cargo.'"</td>
                <td>
                    <button type="button" onclick="seleccionarCargo('.$obj->idCargo.')">Seleccionar</button>
                </td>
            </tr>';
            
            
        }
       
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }

    public function ActualizarNodoAgente($idNodo){
        //dd($idNodo);
        //obtengo el usuario que es la escuela a trabajar
        //$idReparticion = session('idReparticion');
        //consulto a reparticiones
        /*$reparticion = DB::table('tb_reparticiones')
        ->where('tb_reparticiones.idReparticion',$idReparticion)
        ->get();*/
        //dd($reparticion[0]->Organizacion);
        
        //traigo todo de suborganizacion pasada
        $institucionExtension=DB::table('tb_institucion_extension')
        ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
        ->get();
        
        //traigo los nodos
        $infoNodos=DB::table('tb_nodos')
        ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
        ->where('tb_nodos.idNodo',$idNodo)
        ->leftjoin('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', 'tb_nodos.CUECOMPLETO')
        ->leftjoin('tb_desglose_agentes', 'tb_desglose_agentes.docu', 'tb_nodos.Agente')
        //->leftjoin('tb_asignaturas', 'tb_asignaturas.idAsignatura', 'tb_nodos.Asignatura')
        ->leftjoin('tb_cargossalariales', 'tb_cargossalariales.idCargo', 'tb_nodos.CargoSalarial')
        ->leftjoin('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', 'tb_nodos.SitRev')
        ->leftjoin('tb_divisiones', 'tb_divisiones.idDivision', 'tb_nodos.Division')
        ->select(
            'tb_desglose_agentes.*',
            'tb_nodos.*',
           // 'tb_asignaturas.idAsignatura',
            //'tb_asignaturas.Descripcion as nomAsignatura',
            'tb_cargossalariales.idCargo',
            'tb_cargossalariales.Cargo as nomCargo',
            'tb_cargossalariales.Codigo as nomCodigo',
            'tb_situacionrevista.idSituacionRevista',
            'tb_situacionrevista.Descripcion as nomSitRev',
            'tb_divisiones.idDivision',
            'tb_divisiones.Descripcion as nomDivision',
        )
        ->get();
        //dd($infoNodos);

        //traemos otros array
        $SituacionRevista = DB::table('tb_situacionrevista')->get();
        $TipoMotivo = DB::table('tb_motivos')->get();   //->take(45)
        
        $Divisiones = DB::table('tb_divisiones')
                ->where('tb_divisiones.idInstitucionExtension',session('idInstitucionExtension'))
                ->join('tb_cursos','tb_cursos.idCurso', '=', 'tb_divisiones.Curso')
                ->join('tb_division','tb_division.idDivisionU', '=', 'tb_divisiones.Division')
                ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_divisiones.Turno')
                ->select(
                    'tb_divisiones.*',
                    'tb_cursos.*',
                    'tb_division.*',
                    'tb_turnos.Descripcion as DescripcionTurno',
                    'tb_turnos.idTurno',
                )
                ->orderBy('tb_divisiones.Descripcion','ASC')
                ->get();

                /*$EspaciosCurriculares = DB::table('tb_espacioscurriculares')
                ->where('tb_espacioscurriculares.SubOrg',session('idSubOrganizacion'))
                ->join('tb_asignaturas','tb_asignaturas.idAsignatura', 'tb_espacioscurriculares.Asignatura')
                ->select(
                    'tb_espacioscurriculares.*',
                    'tb_asignaturas.*'
                )
                //->orderBy('tb_asignaturas.DescripcionCurso','ASC')
                ->get();*/

                $CargosSalariales = DB::table('tb_cargossalariales')->get();
                $DiasSemana = DB::table('tb_diassemana')->get();


                //le busco la licencia de paso
                $Novedades = DB::table('tb_novedades')
                ->where('tb_novedades.CUECOMPLETO', session('CUECOMPLETO'))
                ->where('tb_novedades.idTurnoUsuario', session('idTurnoUsuario'))
                ->where('tb_novedades.Nodo', $idNodo)
                ->whereIn('tb_novedades.Motivo', [4, 6, 7])   //lo busco por su anexo
                // ->where(function($query) {
                //     $query->orWhereNull('Nodo');
                // })
                ->join('tb_cargossalariales','tb_cargossalariales.idCargo', 'tb_novedades.CargoSalarial')
                ->join('tb_situacionrevista','tb_situacionrevista.idSituacionRevista', 'tb_novedades.Caracter')
                ->join('tb_divisiones','tb_divisiones.idDivision', 'tb_novedades.Division')
                ->join('tb_turnos', 'tb_turnos.idTurno', 'tb_divisiones.Turno')
                ->join('tb_motivos', 'tb_motivos.idMotivo', 'tb_novedades.Motivo')
                ->select(
                'tb_novedades.*',
                'tb_novedades.Observaciones as novObservaciones',
                'tb_cargossalariales.*',
                'tb_motivos.*',
                'tb_situacionrevista.Descripcion as SitRev',
                'tb_divisiones.Descripcion as nomDivision',
                'tb_turnos.Descripcion as DescripcionTurno',
                )
                ->get();


                $datos=array(
                    'mensajeError'=>"",
                    'CUECOMPLETO'=>$institucionExtension[0]->CUECOMPLETO,
                    'Nombre_Institucion'=>$institucionExtension[0]->Nombre_Institucion,
                    'institucionExtension'=>$institucionExtension,
                    'idInstitucionExtension'=>$institucionExtension[0]->idInstitucionExtension, 
                    'infoNodos'=>$infoNodos,
                    'SituacionDeRevista'=>$SituacionRevista,
                    'Divisiones'=>$Divisiones,
                    //'EspaciosCurriculares'=>$EspaciosCurriculares,
                    'CargosSalariales'=>$CargosSalariales,
                    'DiasSemana'=>$DiasSemana,
                    'Nodo'=>$idNodo,
                    'mensajeNAV'=>'Panel de Configuración de Agente',
                    'idBack'=>$infoNodos[0]->PosicionAnterior,
                    'TipoMotivos'=>$TipoMotivo,
                    'Novedades' => $Novedades
                );
       
                $ruta ='
                <li class="breadcrumb-item active"><a href="#">LEGAJO ÚNICO DE PERSONAL</a></li>
                <li class="breadcrumb-item active"><a href="#">LISTA DE AGENTES</a></li>
                <li class="breadcrumb-item active"><a href="'.route('ActualizarNodoAgente',$idNodo).'">ACTUALIZAR AGENTE</a></li>
                '; 
                session(['ruta' => $ruta]);
        return view('bandeja.AG.Servicios.actualizar_nodo',$datos);       
    }
    // public function ActualizarNodoAgenteSiguiente($idNodo){
    //     //dd($idBack);
    //     //obtengo el usuario que es la escuela a trabajar
    //     $idReparticion = session('idReparticion');
    //     //consulto a reparticiones
    //     $reparticion = DB::table('tb_reparticiones')
    //     ->where('tb_reparticiones.idReparticion',$idReparticion)
    //     ->get();
    //     //dd($reparticion[0]->Organizacion);
        
    //     //traigo todo de suborganizacion pasada
    //     $subOrganizacion=DB::table('tb_suborganizaciones')
    //     ->where('tb_suborganizaciones.idsuborganizacion',$reparticion[0]->subOrganizacion)
    //     ->select('*')
    //     ->get();
        
    //     //traigo los nodos
    //     $infoNodos=DB::table('tb_nodos')
    //     ->where('tb_suborganizaciones.idSubOrganizacion',$reparticion[0]->subOrganizacion)
    //     ->where('tb_nodos.idNodo',$idNodo)
    //     ->leftjoin('tb_suborganizaciones', 'tb_suborganizaciones.cuecompleto', 'tb_nodos.CUE')
    //     ->leftjoin('tb_agentes', 'tb_agentes.idAgente', 'tb_nodos.Agente')
    //     ->leftjoin('tb_asignaturas', 'tb_asignaturas.idAsignatura', 'tb_nodos.Asignatura')
    //     ->leftjoin('tb_cargossalariales', 'tb_cargossalariales.idCargo', 'tb_nodos.CargoSalarial')
    //     ->leftjoin('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', 'tb_nodos.SitRev')
    //     ->leftjoin('tb_divisiones', 'tb_divisiones.idDivision', 'tb_nodos.Division')
    //     ->select(
    //         'tb_agentes.*',
    //         'tb_nodos.*',
    //         'tb_asignaturas.idAsignatura',
    //         'tb_asignaturas.Descripcion as nomAsignatura',
    //         'tb_cargossalariales.idCargo',
    //         'tb_cargossalariales.Cargo as nomCargo',
    //         'tb_cargossalariales.Codigo as nomCodigo',
    //         'tb_situacionrevista.idSituacionRevista',
    //         'tb_situacionrevista.Descripcion as nomSitRev',
    //         'tb_divisiones.idDivision',
    //         'tb_divisiones.Descripcion as nomDivision'
    //     )
    //     ->get();
    //     //dd($infoNodos);
    //     //dd($infoNodos[0]->PosicionAnterior);
    //     //traemos otros array
    //     $SituacionRevista = DB::table('tb_situacionrevista')->get();
        
        
    //     $Divisiones = DB::table('tb_divisiones')
    //             ->where('tb_divisiones.idSubOrg',session('idSubOrganizacion'))
    //             ->join('tb_cursos','tb_cursos.idCurso', '=', 'tb_divisiones.Curso')
    //             ->join('tb_division','tb_division.idDivisionU', '=', 'tb_divisiones.Division')
    //             ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_divisiones.Turno')
    //             ->select(
    //                 'tb_divisiones.*',
    //                 'tb_cursos.*',
    //                 'tb_division.*',
    //                 'tb_turnos.Descripcion as DescripcionTurno',
    //                 'tb_turnos.idTurno',
    //             )
    //             ->orderBy('tb_cursos.DescripcionCurso','ASC')
    //             ->get();

    //             $EspaciosCurriculares = DB::table('tb_espacioscurriculares')
    //             ->where('tb_espacioscurriculares.SubOrg',session('idSubOrganizacion'))
    //             ->join('tb_asignaturas','tb_asignaturas.idAsignatura', 'tb_espacioscurriculares.Asignatura')
    //             ->select(
    //                 'tb_espacioscurriculares.*',
    //                 'tb_asignaturas.*'
    //             )
    //             //->orderBy('tb_asignaturas.DescripcionCurso','ASC')
    //             ->get();

    //             $CargosSalariales = DB::table('tb_cargossalariales')->get();
    //             $DiasSemana = DB::table('tb_diassemana')->get();
    //             $datos=array(
    //                 'mensajeError'=>"",
    //                 'CueOrg'=>$subOrganizacion[0]->cuecompleto,
    //                 'nombreSubOrg'=>$subOrganizacion[0]->Descripcion,
    //                 'infoSubOrganizaciones'=>$subOrganizacion,
    //                 'idSubOrg'=>$reparticion[0]->subOrganizacion, 
    //                 'infoNodos'=>$infoNodos,
    //                 'SituacionDeRevista'=>$SituacionRevista,
    //                 'Divisiones'=>$Divisiones,
    //                 'EspaciosCurriculares'=>$EspaciosCurriculares,
    //                 'CargosSalariales'=>$CargosSalariales,
    //                 'DiasSemana'=>$DiasSemana,
    //                 'Nodo'=>$idNodo,
    //                 'mensajeNAV'=>'Panel de Configuración de Agente',
    //                 'idBack'=>$infoNodos[0]->PosicionAnterior
                    
    //             );
       
    //     return view('bandeja.AG.Servicios.actualizar_nodo_siguiente',$datos);       
    // }

    public function formularioActualizarAgente(Request $request){
        //echo $request->Agente." ".session('CUE') ;
        //dd($request);
        /*
            "_token" => "GlwXEhtxgnCKdflU0laeMRsVb2YsU6uLrrIbC1JE"
            "DescripcionNombreAgenteActualizar" => "LOYOLA, LEO"
            "idAgente" => "26731952"
            "CargoSalarial" => "1"
            "SitRev" => "1"
            "Division" => "658"
            "CantidadHoras" => "20"
            "FA" => "2024-02-10"
            "nodo" => "109"
            "Observaciones" => "prueba en actualizar datos dentro de nodo"
            se agrego F2 pra juetaeno
        */
        /*$EspCur=DB::table('tb_espacioscurriculares')
        ->where('idEspacioCurricular',$request->EspCur)
        ->get();*/

        //dd($EspCur[0]->Asignatura);
        //$idAsig=$EspCur[0]->Asignatura;
        $nodo = Nodo::where('idNodo', $request->nodo)->first();
            
        
        //verifico si hay nueva entrada o si es simplemente actualizar al agente
        if($request->idAgente == $nodo->Agente){
            if($nodo->LicenciaActiva == "NO"){
                    //$nodo->Agente = $request->idAgente;                 //es el DNI
                    //$nodo->EspacioCurricular = $request->EspCur;
                    $nodo->Division = $request->Division;
                    $nodo->CargoSalarial = $request->CargoSalarial; //listo
                    $nodo->CantidadHoras = $request->CantidadHoras; //listo
                    $nodo->FechaDeAlta = $request->FA1;              //listo    escuela
                    $nodo->FechaDeAltaJuetaeno = $request->FA2;              //listo    Juetaeno
                    $nodo->SitRev = $request->SitRev;               //listo
                    // $nodo->Asignatura = $idAsig;
                    $nodo->Activo = 1;  //ingreso un agente
                    $nodo->Observaciones = $request->Observaciones; //listo 18 de abril
                    //$nodo->CantidadAsistencia = 0;
                    $nodo->Usuario = session('idUsuario');
                    $nodo->save();

                    //se trata de actualizar, por ende no cargo novedad, pero edito la activa
                    //como voy a liberar el nodo del actual docente, antes doy de baja en novedad
                    $novedad = NovedadesModel::where('Nodo', $nodo->idNodo)
                    ->where('Agente', $nodo->Agente)
                    ->where('CUECOMPLETO', $nodo->CUECOMPLETO)
                    ->where('idTurnoUsuario', $nodo->idTurnoUsuario)
                    ->where('Motivo','=', 46)    //pregunto si esta activo con ALTA de tipo vacante
                    ->whereNotNull('Nodo') // Verifica si el campo 'Nodo' no es null debido a que todavia esta activo
                    ->first();
            
                    if($novedad){
                        $novedad->CargoSalarial = $request->CargoSalarial;
                        $novedad->Caracter = $request->SitRev;
                        $novedad->Division = $nodo->Division;
                        $novedad->save();
                    }
            }else{
                //en caso de tener licencia, no lo dejo modificar nada de sus atributos
                    //agregado el 23 de agosto del 2024, verifico si permite editar
                    $infoInstitucion = InstitucionExtensionModel::where('CUECOMPLETO', $nodo->CUECOMPLETO)
                    ->where('idTurnoUsuario', $nodo->idTurnoUsuario)
                    ->first();

                    if($infoInstitucion->PermiteEditarTodo==1){
                        
                        $nodo->CargoSalarial = $request->CargoSalarial; //listo
                        $nodo->SitRev = $request->SitRev; 
                        $nodo->Division = $request->Division;
                        $nodo->CantidadHoras = $request->CantidadHoras; //listo
                        $nodo->FechaDeAlta = $request->FA1;              //listo    escuela
                        $nodo->FechaDeAltaJuetaeno = $request->FA2;              //listo    Juetaeno
                                      //listo
                        $nodo->Observaciones = $request->Observaciones; //listo 18 de abril
                        $nodo->Usuario = session('idUsuario');
                        $nodo->save();

                        //debo buscar la novedad y actualizar sus parametros
                        $novedad = NovedadesModel::where('Nodo', $nodo->idNodo)
                        ->where('Agente', $nodo->Agente)
                        ->where('CUECOMPLETO', $nodo->CUECOMPLETO)
                        ->where('idTurnoUsuario', $nodo->idTurnoUsuario)
                        ->where('EstaActivaLicencia','=', 'SI')    //pregunto si esta activo con ALTA de tipo vacante
                        ->whereNotNull('Nodo') // Verifica si el campo 'Nodo' no es null debido a que todavia esta activo
                        ->first();
                
                        if($novedad){
                            $novedad->CargoSalarial = $request->CargoSalarial;
                            $novedad->Caracter = $request->SitRev;
                            $novedad->Division = $nodo->Division;
                            $novedad->save();
                        }
                    }else{
                        $nodo->CantidadHoras = $request->CantidadHoras;
                        $nodo->Observaciones = $request->Observaciones; //listo 18 de abril
                        $nodo->FechaDeAlta = $request->FA1;              //listo    escuela
                        $nodo->FechaDeAltaJuetaeno = $request->FA2;              //listo    Juetaeno
                        $nodo->Usuario = session('idUsuario');
                        $nodo->save();
                    }
                   
            }
           
        }else{
            //nodo en blanco
            $nodo->Agente = $request->idAgente;                 //es el DNI
            //$nodo->EspacioCurricular = $request->EspCur;
            $nodo->Division = $request->Division;
            $nodo->CargoSalarial = $request->CargoSalarial; //listo
            $nodo->CantidadHoras = $request->CantidadHoras; //listo
            $nodo->FechaDeAlta = $request->FA1;  
            $nodo->FechaDeAltaJuetaeno = $request->FA2;            //listo
            $nodo->SitRev = $request->SitRev;               //listo
           // $nodo->Asignatura = $idAsig;
            $nodo->Activo = 1;  //ingreso un agente
            $nodo->Observaciones = $request->Observaciones; //listo 18 de abril
            //$nodo->CantidadAsistencia = 0;
            $nodo->Usuario = session('idUsuario');
            $nodo->save();

            //verifico si viene de izquierda a derecha      nuevo ingreso
            if($nodo->LicenciaActiva == "NO"){
                //busco el nombre de la sitre
                $SituacionDeRevista = SitRevModel::where('idSituacionRevista',$nodo->SitRev)->get();

                //busco la novedad porque actualizare al docente
                //$novedad = NovedadesModel::where('Nodo',$nodo->idNodo)->first();

                //agrego al docente en novedades de alta
                //cargo la novedad avisando que es baja
                $novedad = new NovedadesModel();
                    $novedad->Agente = $nodo->Agente;
                    $novedad->CUECOMPLETO = session('CUECOMPLETO');
                    $novedad->idTurnoUsuario = session('idTurnoUsuario');
                    $novedad->CargoSalarial = $nodo->CargoSalarial;
                    $novedad->Caracter = $nodo->SitRev;
                    $novedad->Division = $nodo->Division;
                    $novedad->FechaDesde = Carbon::parse(Carbon::now())->format('Y-m-d');
                    $novedad->FechaHasta = null;
                    $novedad->TotalDias = 1;
                    $novedad->Mes = date('m');
                    $novedad->Anio = date('Y');
                    $novedad->Motivo = 46;   //en este caso es vacante
                    $novedad->Observaciones = "Alta de Servicio: ".$SituacionDeRevista[0]->Descripcion;
                    $novedad->Estado = 1;   //activo tiene novedad sin fecha hasta
                    $novedad->Nodo = $nodo->idNodo; //por ahora lo hago asi, tengo dudas
                    $novedad->CantidadDiasTrabajados = $nodo->CantidadAsistencia;
                $novedad->save();
            }else{
                //actualizo la novedad que le faltan datos de alta
                $novedad = NovedadesModel::where('Nodo', $request->nodo)
                //->where('Agente', $nodo->Agente)
                ->where('CUECOMPLETO', $nodo->CUECOMPLETO)
                ->where('idTurnoUsuario', $nodo->idTurnoUsuario)
                ->where('Motivo','=', 46)    //pregunto si esta activo con ALTA
                ->whereNotNull('Nodo') // Verifica si el campo 'Nodo' no es null debido a que todavia esta activo
                ->first();

                if($novedad){
                    $novedad->Agente = $nodo->Agente;
                    $novedad->CargoSalarial = $nodo->CargoSalarial;
                    $novedad->Caracter = $nodo->SitRev;
                    $novedad->Division = $nodo->Division;
                    $novedad->save();
                }

                //de lic
                $novedad = NovedadesModel::where('Nodo', $request->nodo)
                //->where('Agente', $nodo->Agente)
                ->where('CUECOMPLETO', $nodo->CUECOMPLETO)
                ->where('idTurnoUsuario', $nodo->idTurnoUsuario)
                ->where('Motivo', 48)     //pregunto si esta activo con ALTA
                ->whereNotNull('Nodo') // Verifica si el campo 'Nodo' no es null debido a que todavia esta activo
                ->first();
        
                if($novedad){
                    $novedad->Agente = $nodo->Agente;
                    $novedad->CargoSalarial = $nodo->CargoSalarial;
                    $novedad->Caracter = $nodo->SitRev;
                    $novedad->Division = $nodo->Division;
                    $novedad->save();
                }
            }
        }
        
        /*
         //cargo la novedad de ingreso nuevo suplente
         $novedad = new NovedadesModel();
         $novedad->Agente = $nodo->Agente;
         $novedad->CUE = session('CUEa');
         $novedad->CargoSalarial = $nodo->CargoSalarial;
         $novedad->Caracter = $nodo->SitRev;
         $novedad->Division = $nodo->Division;
         $novedad->FechaDesde = Carbon::parse(Carbon::now())->format('Y-m-d');
         $novedad->TotalDias = 1;
         $novedad->Mes = date('m');
         $novedad->Anio = date('Y');
         $novedad->Motivo = 2;   //en este caso es suplente
         $novedad->Observaciones = "Cubre vacante";
         $novedad->Estado = 1;   //activo tiene novedad sin fecha hasta
         $novedad->save();
         */
        return redirect()->back()->with('ConfirmarActualizarAgente','OK');
    }

    public function desvincularDocente($idNodo){
        
        //dd($idAgente);
        //dd($idNodo);
        //traigo la info del nodo actual
        $nodo =  Nodo::where('idNodo', $idNodo)->first();
        
        //en caso de tener licencia el nodo, no permito desvincularlo
        if($nodo->LicenciaActiva == "NO"){
            //como voy a liberar el nodo del actual docente, antes doy de baja en novedad
            $novedad = NovedadesModel::where('Nodo', $nodo->idNodo)
            ->where('Agente', $nodo->Agente)
            ->where('CUECOMPLETO', $nodo->CUECOMPLETO)
            ->where('idTurnoUsuario', $nodo->idTurnoUsuario)
            ->where('Motivo','=', 1)    //pregunto si esta activo con ALTA y es vacante
            ->whereNotNull('Nodo') // Verifica si el campo 'Nodo' no es null debido a que todavia esta activo
            ->first();

            if($novedad){
                $novedad->Nodo = null; //le quito el valor del nodo a la antigua novedad de alta
                $novedad->save();
            }

            //agrego una novedad en baja
            //cargo la novedad avisando que es baja
            $novedad = new NovedadesModel();
                $novedad->Agente = $nodo->Agente;
                $novedad->CUECOMPLETO = session('CUECOMPLETO');
                $novedad->idTurnoUsuario = session('idTurnoUsuario');
                $novedad->CargoSalarial = $nodo->CargoSalarial;
                $novedad->Caracter = $nodo->SitRev;
                $novedad->Division = $nodo->Division;
                // Suponiendo que $nodo->FechaDeAlta es un string con la fecha y hora
                $fechaDeAlta = $nodo->FechaDeAlta;

                // Crear un objeto DateTime
                $fecha = new DateTime($fechaDeAlta);
                
                $novedad->FechaDesde = $fecha->format('Y-m-d'); // Esto te dará la fecha en formato 'YYYY-MM-DD'     //el nodo tiene la fecha de alta, no desde
                $novedad->FechaHasta = Carbon::parse(Carbon::now())->format('Y-m-d');
                //calculo fecha desde-hasta
                    // Fecha inicial y final en formato YYYY-MM-DD
                    $fechaInicialObj = '2023-07-15';
                    $fechaFinalObj = '2023-08-02';

                    // Crear objetos DateTime
                    $fechaInicialObj = new DateTime($nodo->FechaDeAlta);
                    $fechaFinalObj = new DateTime(Carbon::parse(Carbon::now())->format('Y-m-d'));
                    // Incluir el primer día en el cálculo
                    $fechaFinalObj->modify('+1 day');
                    // Calcular la diferencia entre las dos fechas
                    $intervalo = $fechaInicialObj->diff($fechaFinalObj);

                    // Obtener la cantidad de días
                    $cantidadDias = $intervalo->days;
                $novedad->TotalDias = 1;
                $novedad->Mes = date('m');
                $novedad->Anio = date('Y');
                $novedad->Motivo = 47;   //en este caso es vacante
                $novedad->Observaciones = "Se dio de baja al docente por desvinculacion";
                $novedad->Estado = 1;   //activo tiene novedad sin fecha hasta
                $novedad->Nodo = null; //por ahora lo hago asi, tengo dudas
                $novedad->CantidadDiasTrabajados = $cantidadDias;
            $novedad->save();


            //al nodo que esta en uso le saco el agente
            $nodo =  Nodo::where('idNodo', $idNodo)->first();;
                $nodo->Agente = null;
                $nodo->Activo = 0;  //quito un agente
                $nodo->CantidadAsistencia = 0;
                $nodo->Usuario = session('idUsuario');
            $nodo->save();
        }else{
            //tienen lic no permito desvincular
        }
        
        return redirect()->back()->with('ConfirmarDesvincularAgente','OK');
    }

    public function desvincularDocenteRetornoRaiz($idNodo){
        //dd($idAgente);
        //dd($idNodo);
        //traigo la info del nodo actual
        $nodo =  Nodo::where('idNodo', $idNodo)->first();
        if($nodo->Agente != null || $nodo->Agente != ""){   //si tiene agente
            //como voy a liberar el nodo del actual docente, antes doy de baja en novedad
            $novedad = NovedadesModel::where('Nodo', $nodo->idNodo)
            ->where('Agente', $nodo->Agente)
            ->where('CUECOMPLETO', $nodo->CUECOMPLETO)
            ->where('idTurnoUsuario', $nodo->idTurnoUsuario)
            ->where('Motivo','=', 46)    //pregunto si esta activo con ALTA
            ->whereNotNull('Nodo') // Verifica si el campo 'Nodo' no es null debido a que todavia esta activo
            ->first();

            if($novedad){
                $novedad->Nodo = null; //le quito el valor del nodo a la antigua novedad de alta
                $novedad->save();
            }

           
            //agrego una novedad en baja
            //cargo la novedad avisando que es baja
            $novedad = new NovedadesModel();
                $novedad->Agente = $nodo->Agente;
                $novedad->CUECOMPLETO = session('CUECOMPLETO');
                $novedad->idTurnoUsuario = session('idTurnoUsuario');
                $novedad->CargoSalarial = $nodo->CargoSalarial;
                $novedad->Caracter = $nodo->SitRev;
                $novedad->Division = $nodo->Division;
                // Suponiendo que $nodo->FechaDeAlta es un string con la fecha y hora
                $fechaDeAlta = $nodo->FechaDeAlta;

                // Crear un objeto DateTime
                $fecha = new DateTime($fechaDeAlta);

                $novedad->FechaDesde = $fecha->format('Y-m-d');//cambiando dia 30/05/24 de Fecha Desded a FechaDeAlta
                $novedad->FechaHasta = Carbon::parse(Carbon::now())->format('Y-m-d');
                $novedad->TotalDias = 1;
                $novedad->Mes = date('m');
                $novedad->Anio = date('Y');
                $novedad->Motivo = 47;   //en este caso es BAJA
                $novedad->Observaciones = "Se dio de baja al docente por Retorno de Agente";
                $novedad->Estado = 1;   //activo tiene novedad sin fecha hasta
                $novedad->Nodo = null; //por ahora lo hago asi, tengo dudas
                //calculo fecha desde-hasta
                    // Fecha inicial y final en formato YYYY-MM-DD
                    $fechaInicialObj = '2023-07-15';
                    $fechaFinalObj = '2023-08-02';

                    // Crear objetos DateTime
                    $fechaInicialObj = new DateTime($nodo->FechaDeAlta);
                    $fechaFinalObj = new DateTime(Carbon::parse(Carbon::now())->format('Y-m-d'));
                    // Incluir el primer día en el cálculo
                    $fechaFinalObj->modify('+1 day');
                    // Calcular la diferencia entre las dos fechas
                    $intervalo = $fechaInicialObj->diff($fechaFinalObj);

                    // Obtener la cantidad de días
                    $cantidadDias = $intervalo->days;
                $novedad->CantidadDiasTrabajados = $cantidadDias;//$nodo->CantidadAsistencia;
            $novedad->save();


            //al nodo que esta en uso le saco el agente
            $nodo =  Nodo::where('idNodo', $idNodo)->first();
                $nodo->Agente = null;
                $nodo->Activo = 0;  //quito un agente
                $nodo->CantidadAsistencia = 0;
                $nodo->Usuario = session('idUsuario');
            $nodo->save();
        }else{
            //no hace nada porque esta vacante
        }
        
        return 1;
    }
    public function desvincularDocenteRetornoRaiz_conLiC($idNodo){
        //dd($idAgente);
        //dd($idNodo);
        //traigo la info del nodo actual
        $nodo =  Nodo::where('idNodo', $idNodo)->first();
        if($nodo->Agente != null || $nodo->Agente != ""){
            //como voy a liberar el nodo del actual docente, antes doy de baja en novedad
            $novedad = NovedadesModel::where('Nodo', $nodo->idNodo)
            ->where('Agente', $nodo->Agente)
            ->where('CUECOMPLETO', $nodo->CUECOMPLETO)
            ->where('idTurnoUsuario', $nodo->idTurnoUsuario)
            ->where('Motivo','=', 46)    //pregunto si esta activo con ALTA
            ->whereNotNull('Nodo') // Verifica si el campo 'Nodo' no es null debido a que todavia esta activo
            ->first();

            if($novedad){
                $novedad->Nodo = null; //le quito el valor del nodo a la antigua novedad de alta
                $novedad->save();
            }

            //controlo si esta en triada y si tiene licencia que seria lo mas obvio
            if($nodo->LicenciaActiva == "SI"){
                //actualizo la novedad del nodo actual para poder regresar
                $actualizoEstado  = $this->quitaLic($nodo->idNodo);
            }
            //agrego una novedad en baja
            //cargo la novedad avisando que es baja
            $novedad = new NovedadesModel();
                $novedad->Agente = $nodo->Agente;
                $novedad->CUECOMPLETO = session('CUECOMPLETO');
                $novedad->idTurnoUsuario = session('idTurnoUsuario');
                $novedad->CargoSalarial = $nodo->CargoSalarial;
                $novedad->Caracter = $nodo->SitRev;
                $novedad->Division = $nodo->Division;
                $novedad->FechaDesde = $nodo->FechaDeAlta;
                $novedad->FechaHasta = Carbon::parse(Carbon::now())->format('Y-m-d');
                $novedad->TotalDias = 1;
                $novedad->Mes = date('m');
                $novedad->Anio = date('Y');
                $novedad->Motivo = 47;   //en este caso es BAJA
                $novedad->Observaciones = "Se dio de baja al docente por Retorno de Agente";
                $novedad->Estado = 1;   //activo tiene novedad sin fecha hasta
                $novedad->Nodo = null; //por ahora lo hago asi, tengo dudas
                $novedad->CantidadDiasTrabajados = $nodo->CantidadAsistencia;
            $novedad->save();


            //al nodo que esta en uso le saco el agente
            $nodo =  Nodo::where('idNodo', $idNodo)->first();
                $nodo->Agente = null;
                $nodo->Activo = 0;  //quito un agente
                $nodo->CantidadAsistencia = 0;
                $nodo->Usuario = session('idUsuario');
            $nodo->save();

        }else{
            //actualizo la novedad del nodo actual para poder regresar
            $actualizoEstado  = $this->quitaLic($idNodo);
        }
        
       

        
        return 1;
    }
    public function formularioActualizarHorario(Request $request){
        $idSubOrg =session('idSubOrganizacion');
        //dd($request);
        /*
        "_token" => "gdhTlL89APQI1WQJyXA2HsKjYmQ15mcx2z6ZLlED"
        "r1" => "NO"
        "Lunes" => "algoen lunes"
        "r2" => "NO"
        "Martes" => "alg en martes"
        "r3" => "NO"
        "Miercoles" => "algo en mierc"
        "r4" => "SI"
        "Jueves" => "algo en juev"
        "r5" => "SI"
        "Viernes" => "algo en vie"
        "r6" => "SI"
        "Sabado" => "algo en sab"
        "Agn" => "57"
        */
        //primero voy a borrar todos los datos de una suborg
        DB::table('tb_horarios')
            ->where('Nodo', $request->Agn)
            ->delete();
        //ahora los cargo a uno, por ahora uso este metodo simple
        if($request->r1=="SI"){
            $radio = new HorariosModel();
            $radio->Nodo = $request->Agn;
            $radio->DiaDeLaSemana = 1;
            $radio->Descripcion = $request->Lunes;
            $radio->save();
        }
        if($request->r2=="SI"){
            $radio = new HorariosModel();
            $radio->Nodo = $request->Agn;
            $radio->DiaDeLaSemana = 2;
            $radio->Descripcion = $request->Martes;
            $radio->save();
        }        
        if($request->r3=="SI"){
            $radio = new HorariosModel();
            $radio->Nodo = $request->Agn;
            $radio->DiaDeLaSemana = 3;
            $radio->Descripcion = $request->Miercoles;
            $radio->save();
        } 
        if($request->r4=="SI"){
            $radio = new HorariosModel();
            $radio->Nodo = $request->Agn;
            $radio->DiaDeLaSemana = 4;
            $radio->Descripcion = $request->Jueves;
            $radio->save();
        } 
        if($request->r5=="SI"){
            $radio = new HorariosModel();
            $radio->Nodo = $request->Agn;
            $radio->DiaDeLaSemana = 5;
            $radio->Descripcion = $request->Viernes;
            $radio->save();
        } 
        if($request->r6=="SI"){
            $radio = new HorariosModel();
            $radio->Nodo = $request->Agn;
            $radio->DiaDeLaSemana = 6;
            $radio->Descripcion = $request->Sabado;
            $radio->save();
        } 
        return redirect("/ActualizarNodoAgente/$request->Agn")->with('ConfirmarActualizarHorario','OK');
    }

    public function eliminarNodo($idNodo){
        //borro todos sus horarios
        DB::table('tb_horarios')
            ->where('Nodo', $idNodo)
            ->delete();
        
        //traigo la info del nodo
        $nodo =  Nodo::where('idNodo', $idNodo)->first();

        //obtengo su nodo anterior si existe y lo actualizo a null
        // $nodoAnteriorPos =$nodo->PosicionAnterior;
        
        //     $nodoAnterior =  Nodo::where('idNodo', $nodo->PosicionAnterior)->first();
        //     $nodoAnterior->PosicionSiguiente = null;
        //     $nodoAnterior->Usuario = session('idUsuario');
        //     $nodoAnterior->save();
        
        $nodoAnteriorPos = $nodo->PosicionAnterior;

        // Verificar si el nodo anterior existe
        if (!is_null($nodoAnteriorPos)) {
            $nodoAnterior = Nodo::where('idNodo', $nodoAnteriorPos)->first();
            
            // Verificar si se encontró el nodo anterior
            if (!is_null($nodoAnterior)) {
                // Actualizar el nodo anterior
                $nodoAnterior->PosicionSiguiente = null;
                $nodoAnterior->Usuario = session('idUsuario');
                $nodoAnterior->save();
            } else {
                // El nodo anterior no existe
                // Realizar alguna acción en caso de que el nodo anterior no se encuentre
            }
        }
            //dd($nodoAnterior);
        DB::table('tb_rel_nodo_espcur')
            ->where('idNodo', $idNodo)
            ->delete();

        //ahora puedo borrarlo al creado
        DB::table('tb_nodos')
            ->where('idNodo', $idNodo)
            ->delete();

        //tambien localizo el alta y la novedad y las borro
        DB::table('tb_novedades')
            ->where('Nodo', $idNodo)
            ->delete();

        
            //si tiene alguien lo llevo a seguir editando
        if(is_null($nodoAnteriorPos)){
            return redirect("/verArbolServicio")->with('ConfirmarBorradoNodo','OK');
        }else{
            
            return redirect()->route('ActualizarNodoAgente', $nodoAnteriorPos)->with('ConfirmarBorradoNodo','OK');
        }
        
    }

    public function regresarNodo($idNodo){
      
        //antes de borrar debo verificar su anterior
        $nodoActual =  Nodo::where('idNodo', $idNodo)->first();                                     //C
        $nodoAnterior =  Nodo::where('idNodo', $nodoActual->PosicionAnterior)->first();             //B

        
        ///dividimos en casos
        /*
            caso 1 donde solo tengo un retorno a vacante o a usuario solo
        */
        if($nodoAnterior->PosicionAnterior == null || $nodoAnterior->PosicionAnterior == ""){
             //dar de baja al nodo anterior y crear novedad
             $desvinculando = $this->desvincularDocenteRetornoRaiz($nodoAnterior->idNodo);
             //le indico que apunta a raiz, no hay nadie por debajo, le asigno valores como nuevo y creo novedad
             $nodoActual->PosicionAnterior = null;
             $nodoActual->LicenciaActiva = "NO";
             $nodoActual->CantidadAsistencia = 0;
 
             //actualizo la novedad del nodo actual para poder regresar
             $actualizoEstado  = $this->quitaLic($nodoActual->idNodo);
             
             //Eliminar nodo B con sus datos(todos + lic + horario)
             //ahora puedo borrarlo porque es raiz el retorno y nadie lo usara
             //limpio su horario por si le pusieron
             DB::table('tb_rel_nodo_espcur')
             ->where('idNodo', $nodoAnterior->idNodo)
             ->delete();

             DB::table('tb_horarios')
            ->where('Nodo', $nodoAnterior->idNodo)
            ->delete();

            DB::table('tb_nodos')
                 ->where('idNodo', $nodoAnterior->idNodo)
                 ->delete();
            
             $nodoActual->save();
        }else{
            /*
            caso 2 donde el retorno tiene un antecesor, triada
            */
            //pregunto por si acaso hay triada
           
            
                $nodoAnteriorAnterior =  Nodo::where('idNodo', $nodoAnterior->PosicionAnterior)->first();   //A
                //dar de baja al nodo anterior y crear novedad
                $desvinculando = $this->desvincularDocenteRetornoRaiz_conLiC($nodoAnterior->idNodo);
                //le indico que apunta a raiz, no hay nadie por debajo, le asigno valores como nuevo y creo novedad
                

                $nodoActual->PosicionAnterior = $nodoAnteriorAnterior->idNodo;
                $nodoAnteriorAnterior->PosicionSiguiente = $nodoActual->idNodo;
                //Eliminar nodo B con sus datos(todos + lic + horario)
                //ahora puedo borrarlo porque es raiz el retorno y nadie lo usara
                DB::table('tb_rel_nodo_espcur')
                ->where('idNodo', $nodoAnterior->idNodo)
                ->delete();
                
                DB::table('tb_horarios')
                ->where('Nodo', $nodoAnterior->idNodo)
                ->delete();
                DB::table('tb_nodos')
                    ->where('idNodo', $nodoAnterior->idNodo)
                    ->delete();
                    
                $nodoAnteriorAnterior->save();
                $nodoActual->save();
            
        }

        

        
      
        
        
            return redirect("/verArbolServicio2")->with('ConfirmarRegresoNodo','OK');

            
    } public function regresarNodo_respaldo($idNodo){
      
        //antes de borrar debo verificar su anterior
        $nodoActual =  Nodo::where('idNodo', $idNodo)->first();                                     //C
        $nodoAnterior =  Nodo::where('idNodo', $nodoActual->PosicionAnterior)->first();             //B

        //pregunto por si acaso hay triada
        if($nodoAnterior->PosicionAnterior != null || $nodoAnterior->PosicionAnterior != ""){
            $aqui="aqui";
           
            $nodoAnteriorAnterior =  Nodo::where('idNodo', $nodoAnterior->PosicionAnterior)->first();   //A
            //dar de baja al nodo anterior y crear novedad
            $desvinculando = $this->desvincularDocenteRetornoRaiz($nodoAnterior->idNodo);
            //le indico que apunta a raiz, no hay nadie por debajo, le asigno valores como nuevo y creo novedad
            $nodoActual->PosicionAnterior = $nodoAnteriorAnterior->idNodo;
            $nodoAnteriorAnterior->PosicionSiguiente = $nodoActual->idNodo;
            //Eliminar nodo B con sus datos(todos + lic + horario)
            //ahora puedo borrarlo porque es raiz el retorno y nadie lo usara
            DB::table('tb_nodos')
                ->where('idNodo', $nodoAnterior->idNodo)
                ->delete();
                
            $nodoAnteriorAnterior->save();
            $nodoActual->save();
        
        }else{
            //dar de baja al nodo anterior y crear novedad
            $desvinculando = $this->desvincularDocenteRetornoRaiz($nodoAnterior->idNodo);
            //le indico que apunta a raiz, no hay nadie por debajo, le asigno valores como nuevo y creo novedad
            $nodoActual->PosicionAnterior = null;
            $nodoActual->LicenciaActiva = "NO";
            $nodoActual->CantidadAsistencia = 0;

            //actualizo la novedad del nodo actual para poder regresar
            $actualizoEstado  = $this->quitaLic($nodoActual->idNodo);
            
            //Eliminar nodo B con sus datos(todos + lic + horario)
            //ahora puedo borrarlo porque es raiz el retorno y nadie lo usara
            DB::table('tb_nodos')
                ->where('idNodo', $nodoAnterior->idNodo)
                ->delete();
            
            $nodoActual->save();
        }

        
        /*$nodoSiguiente =  Nodo::where('idNodo', $nodoActual->PosicionSiguiente)->first();
        //1- actualizar la posicion de A <--C
        $nodoSiguiente->PosicionAnterior = $nodoAnterior->idNodo;
        $nodoSiguiente->save();

        //2- actualizar la posicion de A--> C
        $nodoAnterior->PosicionSiguiente = $nodoSiguiente->idNodo;  
        

        if($nodoAnterior->Activo == 1){
          
         
        }
            //solo con ativo cero entra
            //3- actualizar el agente de B--> A
            $nodoAnterior->Agente = $nodoActual->Agente;
            $nodoAnterior->FechaDeAlta = $nodoActual->FechaDeAlta;
            $nodoAnterior->EspacioCurricular = $nodoActual->EspacioCurricular;
            $nodoAnterior->SitRev = $nodoActual->SitRev;
            $nodoAnterior->CantidadHoras = $nodoActual->CantidadHoras;
            $nodoAnterior->CargoSalarial = $nodoActual->CargoSalarial;
            $nodoAnterior->Observaciones = $nodoActual->Observaciones;
            $nodoAnterior->FechaDeAlta = $nodoActual->FechaDeAlta;
            $nodoAnterior->Activo = $nodoActual->Activo;
            $nodoAnterior->Usuario = session('idUsuario');
            $nodoAnterior->save();
            
        */
        
        
            return redirect("/verArbolServicio2")->with('ConfirmarRegresoNodo','OK');

            
    }    public function getFiltrandoNodos($valorBuscado){
        $CargosInicial=DB::table('tb_asignaturas')
        ->get();
        //obtengo el usuario que es la escuela a trabajar
        $idReparticion = session('idReparticion');
        //consulto a reparticiones
        $reparticion = DB::table('tb_reparticiones')
        ->where('tb_reparticiones.idReparticion',$idReparticion)
        ->get();

        $infoNodos=DB::table('tb_nodos')
        ->where('tb_suborganizaciones.idSubOrganizacion',$reparticion[0]->subOrganizacion)
        ->join('tb_suborganizaciones', 'tb_suborganizaciones.cuecompleto', 'tb_nodos.CUE')
        ->leftjoin('tb_agentes', 'tb_agentes.idAgente', 'tb_nodos.Agente')
        ->join('tb_asignaturas', 'tb_asignaturas.idAsignatura', 'tb_nodos.Asignatura')
        ->join('tb_cargossalariales', 'tb_cargossalariales.idCargo', 'tb_nodos.CargoSalarial')
        ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', 'tb_nodos.SitRev')
        ->join('tb_divisiones', 'tb_divisiones.idDivision', 'tb_nodos.Division')
        ->orWhere('tb_agentes.Nombres', 'like', '%'.$valorBuscado.'%')
        ->orWhere('tb_agentes.Documento', 'like', '%'.$valorBuscado.'%')
        ->select(
            'tb_agentes.*',
            'tb_nodos.*',
            'tb_asignaturas.idAsignatura',
            'tb_asignaturas.Descripcion as nomAsignatura',
            'tb_cargossalariales.idCargo',
            'tb_cargossalariales.Cargo as nomCargo',
            'tb_cargossalariales.Codigo as nomCodigo',
            'tb_situacionrevista.idSituacionRevista',
            'tb_situacionrevista.Descripcion as nomSitRev',
            'tb_divisiones.idDivision',
            'tb_divisiones.Descripcion as nomDivision',
        )
        ->get();

        /*$datos=array(
            'infoNodos'=>$infoNodos,
        );*/
        session(['infoNodos'=>$infoNodos]);
    }


    public function ver_novedades($novedad, $id = null){
        $valor = [];
        $mensaje="";
        switch($novedad){
            case 'Alta': 
                $valor[] = 9; // Añade solo 9
                $mensaje="Novedades Altas";
                break; 
            case 'Baja': 
                $valor = [10,14]; // Añade solo 10
                $mensaje="Novedades Bajas";
                break; 
            case 'Licencia': 
                $valor[] = 11; // Añade solo 11
                $mensaje="Novedades Licencias";
                break; 
            case 'Otros': 
                $valor = [1, 4, 5, 6, 12]; // Añade varios valores
                $mensaje="Novedades Día Femenino / Enf / Cap /otros";
                break; 
            case 'Paros':
                $valor = [2, 3];    //paros
                $mensaje="Novedades Paro Nac. y Prov.";
                break;
            case 'Faltas':
                $valor = [7, 8];    //faltas
                $mensaje="Novedades Justificada e Injustificada";
                break;
            case 'Volantes':
                $valor = [13];    //Volantes
                $mensaje="Novedades Volantes";
                break;
            case 'Estandar':
                $valor = [1];    //Volantes
                $mensaje="Novedades Volantes";
                break;
            default:
                $valor = [1,2,3,4,5,6,7,8,9,10,11,12,13,14]; // Añade varios valores
                $mensaje = "Novedad Todas";
                break;
        }  
        
        
        if($id != null){
            //esta consulta la armo solo para traer en caso que venga de las super
            $infoInstitucion = DB::table('tb_institucion_extension')
            ->where('idInstitucionExtension',$id)
            ->first();

            $idTurnoExt = $infoInstitucion->idTurnoUsuario;
            $idInstExtECUE = $infoInstitucion->CUECOMPLETO;  //guardo el id de la extension
            //dd($infoInstitucion);
        }else{
            $idTurnoExt = session('idTurnoUsuario');
            $idInstExtECUE = session('CUECOMPLETO');  //guardo el id de la extension

        }
        $institucionExtension=DB::table('tb_institucion_extension')
        ->where('tb_institucion_extension.CUECOMPLETO', $idInstExtECUE)
        ->where('tb_institucion_extension.Turno', $idTurnoExt)
        ->get();

         $Novedades = DB::connection('DB7')->table('tb_novedades')
         ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_novedades.Turno')
         ->join('tb_novedades_extras', 'tb_novedades_extras.idNovedadExtra', '=', 'tb_novedades.idNovedadExtra')
         ->whereIn('tb_novedades.idNovedadExtra', $valor) //solo altas traigo
         ->where('tb_novedades.CUECOMPLETO', $idInstExtECUE)
         ->where('tb_novedades.Turno', $idTurnoExt)
         ->orderBy('FechaDesde','ASC')
         //->orderByRaw('YEAR(FechaDesde) ASC, FechaDesde ASC')
         ->get();
         
        $tipoNovedad = DB::connection('DB7')->table('tb_novedades_extras')->get();
            //dd($Novedades);
         $datos=array(
             'mensajeError'=>"",
             'Novedades'=>$Novedades,
             'mensaje'=>$mensaje,
             'TipoNovedades'=>$tipoNovedad,
             'InstitucionExtension'=>$idInstExtECUE,
             'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
             'mensajeNAV'=>'Panel de Novedades - '.$mensaje
 
 
         );
         //dd($datos);
         $ruta ='
        <li class="breadcrumb-item active"><a href="#">NOVEDADES</a></li>
        <li class="breadcrumb-item active"><a href="'.route('ver_novedades',"").'">VER ALTAS</a></li>
        '; 
        session(['ruta' => $ruta]);
    return view('bandeja.AG.ver_novedades',$datos);
}

// Dentro de tu controlador, por ejemplo, NovedadController.php
public function cambiarEstadoConfirmar(Request $request)
{
    // Valida el ID de la novedad
    $request->validate([
        'idNovedad' => 'required|integer',
    ]);

    // Obtiene la novedad por su ID
    $novedad = PofmhNovedades::find($request->idNovedad);

    if ($novedad) {
        $novedad->Supervisores = 1;  // Estado "visto"
        // Guarda los cambios en la base de datos
        $novedad->Estado = 'Aprobado';
        $novedad->ObservacionesSuper = $request->Observacion;
        $novedad->FechaObservacionSuper = Carbon::parse(Carbon::now())->format('Y-m-d');
        $novedad->save();

        //debo ir a cambiar el alerta
        $alerta  = AlertaNovedadModel::where('idNovedad',$novedad->idNovedad)->first();
        if ($alerta) {
            $alerta->Estado = "Aprobado";
            $alerta->save();
        }
        return response()->json([
            'success' => true,
            'novedad' => $novedad,  // Retorna la novedad actualizada
        ]);
    }

    return response()->json(['success' => true]);
}

public function cambiarEstadoRechazar(Request $request)
{
    // Valida el ID de la novedad
    /*$request->validate([
        'idNovedad' => 'required|integer',
    ]);*/

    // Obtiene la novedad por su ID
    $novedad = PofmhNovedades::find($request->idNovedad);

    if ($novedad) {
        $novedad->Supervisores = 0;  // Estado "rechazo"
        // Guarda los cambios en la base de datos
        $novedad->Estado = 'Pendiente';
        $novedad->ObservacionesSuper = $request->Observacion;
        $novedad->FechaObservacionSuper = Carbon::parse(Carbon::now())->format('Y-m-d');
        $novedad->save();

        //actualizo tambien si lo vuelven a colocar
        //debo ir a cambiar el alerta
        $alerta  = AlertaNovedadModel::where('idNovedad',$novedad->idNovedad)->first();
        if ($alerta) {
            $alerta->Estado = "Aprobado";
            $alerta->save();
        }
        return response()->json([
            'success' => true,
            'novedad' => $novedad,  // Retorna la novedad actualizada
        ]);
    }

    return response()->json(['success' => true]);
}

    public function ver_novedades_altas(){
           
           
            $institucionExtension=DB::table('tb_institucion_extension')
            ->where('tb_institucion_extension.CUECOMPLETO',session('CUECOMPLETO'))
            ->where('tb_institucion_extension.Turno',session('idTurnoUsuario'))
            ->get();

             $Novedades = DB::connection('DB7')->table('tb_novedades')
             ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_novedades.Turno')
             ->join('tb_novedades_extras', 'tb_novedades_extras.idNovedadExtra', '=', 'tb_novedades.idNovedadExtra')
             ->whereIn('tb_novedades.idNovedadExtra', [9]) //solo altas traigo
             ->where('tb_novedades.CUECOMPLETO',session('CUECOMPLETO'))
             ->where('tb_novedades.Turno',session('idTurnoUsuario'))
             ->get();
             

                //dd($Novedades);
             $datos=array(
                 'mensajeError'=>"",
                 'Novedades'=>$Novedades,
                 'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
                 'mensajeNAV'=>'Panel de Novedades - Altas'
     
     
             );
             $ruta ='
            <li class="breadcrumb-item active"><a href="#">NOVEDADES</a></li>
            <li class="breadcrumb-item active"><a href="'.route('ver_novedades_altas').'">VER ALTAS</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.AG.Servicios.novedades_altas',$datos);
    }

    public function ver_novedades_bajas(){
        $institucionExtension=DB::table('tb_institucion_extension')
        ->where('tb_institucion_extension.CUECOMPLETO',session('CUECOMPLETO'))
        ->where('tb_institucion_extension.Turno',session('idTurnoUsuario'))
        ->get();

         $Novedades = DB::connection('DB7')->table('tb_novedades')
         ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_novedades.Turno')
         ->join('tb_novedades_extras', 'tb_novedades_extras.idNovedadExtra', '=', 'tb_novedades.idNovedadExtra')
         ->whereIn('tb_novedades.idNovedadExtra', [10]) //solo baja traigo
         ->where('tb_novedades.CUECOMPLETO',session('CUECOMPLETO'))
         ->where('tb_novedades.Turno',session('idTurnoUsuario'))
         ->get();
         

            //dd($Novedades);
         $datos=array(
             'mensajeError'=>"",
             'Novedades'=>$Novedades,
             'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
             'mensajeNAV'=>'Panel de Novedades - Altas'
 
 
         );
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">NOVEDADES</a></li>
            <li class="breadcrumb-item active"><a href="'.route('ver_novedades_bajas').'">VER BAJAS</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.AG.Servicios.novedades_bajas',$datos);
    }
    public function ver_novedades_licencias(){
        $institucionExtension=DB::table('tb_institucion_extension')
        ->where('tb_institucion_extension.CUECOMPLETO',session('CUECOMPLETO'))
        ->where('tb_institucion_extension.Turno',session('idTurnoUsuario'))
        ->get();

         $Novedades = DB::connection('DB7')->table('tb_novedades')
         ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_novedades.Turno')
         ->join('tb_novedades_extras', 'tb_novedades_extras.idNovedadExtra', '=', 'tb_novedades.idNovedadExtra')
         ->whereIn('tb_novedades.idNovedadExtra', [11]) //solo altas traigo
         ->where('tb_novedades.CUECOMPLETO',session('CUECOMPLETO'))
         ->where('tb_novedades.Turno',session('idTurnoUsuario'))
         ->get();
         

            //dd($Novedades);
         $datos=array(
             'mensajeError'=>"",
             'Novedades'=>$Novedades,
             'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
             'mensajeNAV'=>'Panel de Novedades - Altas'
 
 
         );
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">NOVEDADES</a></li>
            <li class="breadcrumb-item active"><a href="'.route('ver_novedades_licencias').'">VER LICENCIAS</a></li>
            '; 
            session(['ruta' => $ruta]);
     return view('bandeja.AG.Servicios.novedades_licencias',$datos);
    }   

    
    public function activarFiltro(Request $request){
        //dd($request);
        /*
            "_token" => "sJZZLeTtMQBJvhjlbWHlEPYEXBsZDPtDw9d2c4HS"
            "idDivision" => "658"
            "btnEnviar" => null
        */
        //creo la session o las cambio
       session(['filtroDivision'=>$request->idDivision]);
       return redirect("/verArbolServicio2");
    }

    public function generar_pdf_novedades(){
        //traigo los datos para armar la tabla
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

        
         $Novedades = DB::table('tb_novedades')
            ->where('tb_novedades.CUECOMPLETO', session('CUECOMPLETO'))
            ->where('tb_novedades.idTurnoUsuario', session('idTurnoUsuario'))
            ->whereIn('tb_novedades.Motivo', [1]) //solo altas traigo
            ->whereNotNull('tb_novedades.Nodo') // Verifica si el campo 'Nodo' no es null
            ->whereNotNull('tb_novedades.Agente') // Verifica si el campo 'Nodo' no es null
            ->join('tb_cargossalariales', 'tb_cargossalariales.idCargo', '=', 'tb_novedades.CargoSalarial')
            ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_novedades.Caracter')
            ->join('tb_divisiones', 'tb_divisiones.idDivision', '=', 'tb_novedades.Division')
            ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_divisiones.Turno')
            ->join('tb_motivos', 'tb_motivos.idMotivo', '=', 'tb_novedades.Motivo')
            ->select(
                'tb_novedades.*',
                'tb_cargossalariales.*',
                'tb_motivos.*',
                'tb_situacionrevista.Descripcion as SitRev',
                'tb_divisiones.Descripcion as nomDivision',
                'tb_turnos.Descripcion as DescripcionTurno'
            )
            ->get();

            //dd($Novedades);
         $datos=array(
             'mensajeError'=>"",
             'Novedades'=>$Novedades,
             'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
             'mensajeNAV'=>'Panel de Novedades - Altas'
 
 
         );
        // Cargar el HTML que quieres convertir en PDF
        $html = view('bandeja.LUI.POF.generar_pdf',['datos' => $datos])->render();

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        // Crear una instancia de Dompdf con las opciones
        $dompdf = new Dompdf($options);

        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($html);

        // Establecer la orientación de la página (landscape o portrait)
        $dompdf->setPaper('A4', 'landscape'); // Aquí puedes cambiar 'landscape' a 'portrait' si prefieres la orientación vertical

        // Renderizar el PDF
        $dompdf->render();

        // Obtener el contenido del PDF generado
        $pdf_content = $dompdf->output();

        // Devolver el PDF al navegador para descargar
        return response($pdf_content, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Novedades.pdf"');
        
    }

    public function limpiar_carga()
    {
        //cargar el turno
        $CUECOMPLETO = session('CUECOMPLETO');
        $PermiteBorrarTodo = session('PermiteBorrarTodo');
        $idTurnoUsuario = session('idTurnoUsuario');
        // Verificar si el cuecompleto comienza con '9999999'
        //if (substr($CUECOMPLETO, 0, 7) === '9999999') {       //9999999-00 /  turno 1 o 2 o3
        if ($PermiteBorrarTodo == 1) {
            // Eliminar registros de la tabla tb_novedades
            NovedadesModel::where('cuecompleto', $CUECOMPLETO)
            ->where('idTurnoUsuario', $idTurnoUsuario)
            ->delete();
            
            //antes de borrar horario debo consultar los nodos del cue
            $nodos = Nodo::where('cuecompleto', $CUECOMPLETO)
            ->where('idTurnoUsuario', $idTurnoUsuario)
            ->get();

            foreach($nodos as $n){
                HorariosModel::where('Nodo', $n->idNodo)->delete();
                DB::table('tb_rel_nodo_espcur')
                ->where('idNodo', $n->idNodo)
                ->delete();
                

            }

            // Eliminar registros de la tabla tb_nodos
            Nodo::where('cuecompleto', $CUECOMPLETO)
            ->where('idTurnoUsuario', $idTurnoUsuario)
            ->delete();

            //antes de irme desactivo el borrar
            $desactivo = InstitucionExtensionModel::where('cuecompleto', $CUECOMPLETO)
                        ->where('idTurnoUsuario', $idTurnoUsuario)->first();
                $desactivo->PermiteBorrarTodo=0;
            $desactivo->save();
    
            return redirect("/verArbolServicio2")->with('ConfirmarLimpieza', 'OK');
        } else {
            return redirect()->back()->with('ConfirmarLimpiezaError', 'OK');
        }
    }


    public function agregarNovedadParticular($id = null){
       // dd($id);
        if($id == null){
            $idInst = session('idInstitucionExtension');
        }else{
            $idInst = $id;
        }
       $institucionExtension=DB::table('tb_institucion_extension')
       ->where('tb_institucion_extension.idInstitucionExtension',$idInst)
       ->first();
        //dd($institucionExtension);
        //$infoSage2 = DB::connection('DB8')->table('tb_institucion_extension')
       $Motivos =   DB::table('tb_motivos')->get();
       $Condicion =   DB::connection('DB7')->table('tb_condiciones')->get();
       $nodos = DB::table('tb_nodos')
        ->where('tb_nodos.CUECOMPLETO', $institucionExtension->CUECOMPLETO)
        ->where('tb_nodos.idTurnoUsuario', $institucionExtension->idTurnoUsuario)
        ->get();

        $novedadExtra = DB::connection('DB7')->table('tb_novedades_extras')->get();

        $Divisiones = DB::table('tb_divisiones')
        ->where('tb_divisiones.idInstitucionExtension',$idInst)
        ->join('tb_cursos','tb_cursos.idCurso', '=', 'tb_divisiones.Curso')
        ->join('tb_division','tb_division.idDivisionU', '=', 'tb_divisiones.Division')
        ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_divisiones.Turno')
        ->select(
            'tb_divisiones.*',
            'tb_divisiones.Descripcion as DescripcionDivi',
            'tb_cursos.*',
            'tb_division.*',
            'tb_turnos.Descripcion as DescripcionTurno',
            'tb_turnos.idTurno',
        )
        ->orderBy('tb_cursos.idCurso','ASC')
        ->get();
        
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        //novedad de pof
        /*$Novedades = DB::connection('DB7')->table('tb_novedades')
        ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_novedades.Turno')
        ->join('tb_novedades_extras', 'tb_novedades_extras.idNovedadExtra', '=', 'tb_novedades.idNovedadExtra')
        ->whereIn('tb_novedades.idNovedadExtra', $valor) //solo altas traigo
        ->where('tb_novedades.CUECOMPLETO', $idInstExtECUE)
        ->where('tb_novedades.Turno', $idTurnoExt)
        ->orderBy('FechaDesde','ASC')
        //->orderByRaw('YEAR(FechaDesde) ASC, FechaDesde ASC')
        ->get();*/

        /*$Novedades = PofmhNovedades::where('tb_novedades.CUECOMPLETO', $institucionExtension->CUECOMPLETO)
            ->where('tb_novedades.Turno', $institucionExtension->idTurnoUsuario)
            ->whereNotNull('tb_novedades.Agente') // Verifica si el campo 'Agente' no es null
            ->whereBetween('tb_novedades.created_at', [$startOfMonth, $endOfMonth]) // Filtra por el mes actual
            ->join('tb_novedades_extras', 'tb_novedades_extras.idNovedadExtra', '=', 'tb_novedades.idNovedadExtra')
            ->join('tb_motivos', 'tb_motivos.idMotivo', '=', 'tb_novedades.Motivo')
            ->select(
                'tb_novedades.*',
                'tb_novedades_extras.*',
                'tb_motivos.*'
            )
            ->get();*/
        
        $Novedades = DB::connection('DB7')->table('tb_novedades')->where('tb_novedades.CUECOMPLETO', $institucionExtension->CUECOMPLETO)
            ->where('tb_novedades.Turno', $institucionExtension->idTurnoUsuario)
            ->where('FormatoNovedadNuevo',1)
            ->whereNotNull('tb_novedades.Agente') // Verifica si el campo 'Agente' no es null
            ->whereBetween('tb_novedades.created_at', [$startOfMonth, $endOfMonth]) // Filtra por el mes actual
            ->join('tb_novedades_extras', 'tb_novedades_extras.idNovedadExtra', '=', 'tb_novedades.idNovedadExtra')
            //->join('tb_motivos', 'tb_motivos.idMotivo', '=', 'tb_novedades.Motivo')
            ->select(
                'tb_novedades.*',
                'tb_novedades_extras.*',
            //    'tb_motivos.*'
            )
            //->orderBy('Supervisores','ASC')
            ->orderByRaw('CAST(Supervisores AS UNSIGNED) ASC')
            ->get();    
        //dd($Novedades); 
        /*$Novedades = DB::table('tb_novedades')
        ->where('tb_novedades.CUECOMPLETO', session('CUECOMPLETO'))
        ->where('tb_novedades.idTurnoUsuario', session('idTurnoUsuario'))
        ->whereIn('tb_novedades.Motivo', [0]) //solo altas traigo
        ->whereNotNull('tb_novedades.Agente') // Verifica si el campo 'Nodo' no es null
        ->join('tb_novedades_extras', 'tb_novedades_extras.idNovedadExtra', '=', 'tb_novedades.idNovedadExtra')
        ->select(
            'tb_novedades.*',
            'tb_novedades_extras.*'
        )
        ->get();*/
        Carbon::setLocale('es');

        // Obtener el mes actual
        $currentMonth = Carbon::now()->locale('es')->format('F Y');
        $datos=array(
            'mensajeError'=>"",
            'nodos'=>$nodos,
            'Divisiones'=>1,
            'MesActual'=>$currentMonth,
            'NovedadesExtras'=>$novedadExtra,
            'Novedades'=>$Novedades,
            'Motivos'=>$Motivos,
            'Condiciones'=> $Condicion,
            'InstitucionExtension'=>$institucionExtension,
            'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Novedades - Extras'


        );
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">NOVEDADES</a></li>
            <li class="breadcrumb-item active"><a href="'.route('agregarNovedadParticular').'">Novedad Particular</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.AG.Servicios.agregarNovedadParticular',$datos);
    }

    public function ver_archivos(){
        $cue = session('CUE');
        $cuebase = substr($cue, 0, 7);
         $documentos = DB::table('tb_documentos')
         ->where('CUECOMPLETO', 'like','%'.$cuebase.'%')
         ->join('tb_agentes', 'tb_agentes.Documento', '=', 'tb_documentos.Agente') // referencia completa
         ->orderBy('FechaAlta','DESC')
         ->get();
         //dd($documentos);
            //dd($Novedades);
         $datos=array(
             'mensajeError'=>"",
             'Documentos'=>$documentos,
             'CUE'=>session('CUECOMPLETO'),
             'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
             'mensajeNAV'=>'Panel de Archivos'
 
 
         );
         $ruta ='
        <li class="breadcrumb-item active"><a href="#">ARCHIVOS</a></li>
        <li class="breadcrumb-item active"><a href="'.route('ver_archivos').'">VER ARCHIVOS</a></li>
        '; 
        session(['ruta' => $ruta]);
    return view('bandeja.AG.ver_archivos',$datos);
    }
    public function verNovedadesParticulares(Request $request){
        if($_POST){
            $cue = $request->CUE;
            $turno = $request->turno;
        }else{
            $cue = 0;
            $turno = 1;

        }
        $institucionExtension=DB::table('tb_institucion_extension')
        ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
        ->get();
 
         
        $nodos = DB::table('tb_nodos')
         ->where('tb_nodos.CUECOMPLETO', $cue)
         ->where('tb_nodos.idTurnoUsuario', $turno)
         ->get();
 
         $novedadExtra = DB::table('tb_novedades_extras')->get();
 
         $Divisiones = DB::table('tb_divisiones')
         ->where('tb_divisiones.idInstitucionExtension',session('idInstitucionExtension'))
         ->join('tb_cursos','tb_cursos.idCurso', '=', 'tb_divisiones.Curso')
         ->join('tb_division','tb_division.idDivisionU', '=', 'tb_divisiones.Division')
         ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'tb_divisiones.Turno')
         ->select(
             'tb_divisiones.*',
             'tb_divisiones.Descripcion as DescripcionDivi',
             'tb_cursos.*',
             'tb_division.*',
             'tb_turnos.Descripcion as DescripcionTurno',
             'tb_turnos.idTurno',
         )
         ->orderBy('tb_cursos.idCurso','ASC')
         ->get();
         
         $startOfMonth = Carbon::now()->startOfMonth();
         $endOfMonth = Carbon::now()->endOfMonth();
 
         $Novedades = DB::table('tb_novedades')
             ->where('tb_novedades.CUECOMPLETO', $cue)
             ->where('tb_novedades.idTurnoUsuario', $turno)
             ->whereIn('tb_novedades.Motivo', [0]) // Solo altas traigo
             ->whereNotNull('tb_novedades.Agente') // Verifica si el campo 'Agente' no es null
             //->whereBetween('tb_novedades.created_at', [$startOfMonth, $endOfMonth]) // Filtra por el mes actual
             ->join('tb_novedades_extras', 'tb_novedades_extras.idNovedadExtra', '=', 'tb_novedades.idNovedadExtra')
             ->select(
                 'tb_novedades.*',
                 'tb_novedades_extras.*'
             )
             ->get();
             
         /*$Novedades = DB::table('tb_novedades')
         ->where('tb_novedades.CUECOMPLETO', session('CUECOMPLETO'))
         ->where('tb_novedades.idTurnoUsuario', session('idTurnoUsuario'))
         ->whereIn('tb_novedades.Motivo', [0]) //solo altas traigo
         ->whereNotNull('tb_novedades.Agente') // Verifica si el campo 'Nodo' no es null
         ->join('tb_novedades_extras', 'tb_novedades_extras.idNovedadExtra', '=', 'tb_novedades.idNovedadExtra')
         ->select(
             'tb_novedades.*',
             'tb_novedades_extras.*'
         )
         ->get();*/
         Carbon::setLocale('es');
 
         // Obtener el mes actual
         $currentMonth = Carbon::now()->locale('es')->format('F Y');

         $turnosUsuario = DB::table('tb_turnos_usuario')->get();
         $datos=array(
             'mensajeError'=>"",
             'nodos'=>$nodos,
             'Divisiones'=>1,
             'MesActual'=>$currentMonth,
             'NovedadesExtras'=>$novedadExtra,
             'Novedades'=>$Novedades,
             'Turnos'=>$turnosUsuario,
             'cueUsado'=>$cue,
             'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
             'mensajeNAV'=>'Panel de Novedades - Extras'
 
 
         );
         $ruta ='
             <li class="breadcrumb-item active"><a href="#">NOVEDADES</a></li>
             <li class="breadcrumb-item active"><a href="'.route('verNovedadesParticulares').'">Novedad Particular</a></li>
             '; 
             session(['ruta' => $ruta]);
         return view('bandeja.AG.Servicios.verNovedadesParticulares',$datos);
     }
    public function buscar_agente(Request $request){
       

        $dni = $request->input('dni');

        // Realiza la búsqueda en la base de datos utilizando el modelo Agente
        $agente = AgenteModel::where('Documento', $dni)->first();

        if ($agente) {
            // Si se encuentra un agente con el DNI especificado, devuelve el Apellido y Nombre
            return response()->json(array('status' => 200, 'msg' => $agente->ApeNom), 200);
        } else {
            // Si no se encuentra ningún agente, devuelve un mensaje indicando que no se encontró ningún agente con ese DNI
            return response()->json(array('status' => 200, 'msg' => "--"), 200);
        }
    }
    
    

    public function formularioNovedadParticular(Request $request){
        //dd($request);
        /*
            "_token" => "WPX424Mt6Hwm5fOXVENBV3X7tVJpDQfbddJTV2sv"
            "FechaInicio" => "2024-04-23"
            "FechaHasta" => "2024-04-24"
            "DNI" => "26731952"
            "TipoNovedad" => "2"
            "Observaciones" => "algo en novedad"
            "obligaciones" => "on"
            "CUPOF" => "1"
            "TipoMotivo" => "0"
            llega apenmom
            tipocondicion se agrega
            *debo controlar si viene o no idpof
        */
        
        //hasta ver si funciona el proceso, no creare filas ni vinculare el idpof a la novedad

        if($request->idpof == null){
            // Capturar los datos enviados desde el usuario
            $cue = $request->input('datoCUE'); 
            $turno = $request->input('datoTurno'); 
        
            /*
            // Crear un nuevo registro vacío
            $registro = new PofmhModel();
                $registro->CUECOMPLETO = $cue; 
                $registro->Turno = $turno; 
                $registro->Agente = $request->DNI;
                $registro->ApeNom = $request->ApeNom;
                $registro->FechaDesde = $request->FechaInicio;
                $registro->FechaHasta = $request->FechaHasta; 
                $registro->Condicion = $request->TipoCondicion? $request->TipoCondicion : 1;   //como es nuevo le dejo actual por ahora
                $registro->Activo = 1;   //como es nuevo le dejo actual por ahora
                $registro->Observaciones = $request->Observaciones;
                $registro->Motivo = $request->TipoMotivo;

                //como volvemos a usar los campos de asistencia, los dejo en cero
                $registro->Asistencia = 0;
                $registro->Justificada = 0;
                $registro->Injustificada = 0;
            $registro->save(); // Guardar en la base de datos
        
            // Verificar que se guardó correctamente
            if ($registro->exists) {
                $idpof = $registro->idPofmh;
            } else {
                $idpof = 0;
            }*/
            $idpof = 0;   
        }else{
            $idpof = 0;//$request->idpof;
        }
        $salir=false;
        if($request->TipoNovedad == 11 || $request->TipoNovedad == 12){
            $DNI = 0;   //si no viene el tipo de novedad, le coloco la 1 que es la de alta
        }else{
            //ontrolo si existe o no infodocu
            $infoDocu = DB::table('tb_agentes')
                        ->where('Documento', $request->DNI)
                        ->first();
            if($infoDocu){
                $DNI = $request->DNI;
            }else{
                $salir=true;
            }
        }
        if($request->TipoNovedad == 16){
            $DNI = 1;   //para razones institucionales
            $salir=false;
        }
        if($salir){
           return redirect()->back()->with('errorMensaje','OK');
            //return redirect()->back()->with('errorMensaje', 'El DNI no existe en la base de datos.');
        }
        
        $novedad = new PofmhNovedades();

            $novedad->Agente = $DNI;   //para los feriados
            $novedad->CUECOMPLETO = session('CUECOMPLETO');
            $novedad->Turno = session('idTurnoUsuario');
            
            $novedad->FechaDesde = $request->FechaInicio;
            $novedad->FechaHasta = $request->FechaHasta;
            $novedad->TotalDias = 1;
            $novedad->Mes = date('m');
            $novedad->Anio = date('Y');
            $novedad->Dia = date('d');
            $novedad->Motivo = 0;   //le coloco cero para decir que no son motivos de la tabla motivos, sino generales de la escuela
            $novedad->Observaciones = $request->Observaciones;
            $novedad->idNovedadExtra = $request->TipoNovedad;
            $novedad->Obligaciones = $request->Obligaciones;
            $novedad->CUPOF = $request->CUPOF;
            $novedad->Motivo = $request->TipoMotivo;
            $novedad->Condicion = $request->TipoCondicion? $request->TipoCondicion : 1;   //como es nuevo le dejo actual por ahora
            $novedad->FormatoNovedadNuevo = 1;
            $novedad->Estado = "Pendiente"; 
            $novedad->idPofmh = $idpof;    // veo si es cero o no

            // Crear objetos DateTime
            $fechaInicialObj = new DateTime($request->FechaInicio);//new DateTime(Carbon::parse(Carbon::now())->format('Y-m-d'));
            $fechaFinalObj = new DateTime($request->FechaHasta);
            $fechaFinalObj->modify('+1 day');   //aplico fix para que sume bien 1 dia al total ejemplo, 24 al 27 = 4
            // Calcular la diferencia entre las dos fechas
            $intervalo = $fechaInicialObj->diff($fechaFinalObj);

            // Obtener la cantidad de días
            $cantidadDias = $intervalo->days;

            $novedad->TotalDiasLicencia = $cantidadDias;
            //para control
            $novedad->Supervisores = 0;
            $novedad->Liquidacion = 0;
        $novedad->save();
        
        //en caso que exista idpof debo actualizar la tabla pofhm
        /*
        $infoAgentePofmh = PofmhModel::where('idPofmh', $idpof)->first();
        if($infoAgentePofmh){

            $infoAgentePofmh->FechaDesde = $request->FechaInicio;
            $infoAgentePofmh->FechaHasta = $request->FechaHasta; 

            switch($request->modTipoNovedad){
                case '3':   //Paro nacional
                    $infoAgentePofmh->Justificada += $cantidadDias;
                    break;
                case '4':   //Paro Provincial
                    $infoAgentePofmh->Justificada += $cantidadDias;
                    break;
                case '5':   //Carpeta Medica
                    if($cantidadDias>3){
                        $infoAgentePofmh->Injustificada += $cantidadDias;
                    }else{
                        $infoAgentePofmh->Justificada += $cantidadDias;
                    }
                    break;
                case '6':   //Capacitacion
                    if($cantidadDias>3){
                        $infoAgentePofmh->Injustificada += $cantidadDias;
                    }else{
                        $infoAgentePofmh->Justificada += $cantidadDias;
                    }
                    break;
                case '7':   //falta Justificada
                    $infoAgentePofmh->Justificada += $cantidadDias;
                    break;
                case '8':   //falta injustificada
                    $infoAgentePofmh->Injustificada += $cantidadDias;
                    break;
                case '9':   //Otras Licencias para ver
                    if($cantidadDias>3){
                        $infoAgentePofmh->Injustificada += $cantidadDias;
                    }else{
                        $infoAgentePofmh->Justificada += $cantidadDias;
                    }
                    break;
                case '10':   //volantes
                    $infoAgentePofmh->Asistencia += $cantidadDias;
                    break;
            }
            $infoAgentePofmh->Condicion = $novedad->Condicion;   //es falta injustificada, lo dejo como ACTUAL
            //$infoAgentePofmh->Activo = 1; 
            $infoAgentePofmh->Observaciones = $request->Observaciones;
            $infoAgentePofmh->Motivo = $request->TipoMotivo;
        }
        $infoAgentePofmh->save();
        */

        //traigo el tipo de novedad
        $infoTipoNovedad = DB::table('tb_novedades_extras')
        ->where('tb_novedades_extras.idNovedadExtra', $request->TipoNovedad)
        ->first();

        if($request->DNI == null){
            $obs = "Se creo una nueva novedad para informar un Feriado desde el ".$request->FechaInicio." hasta el ".$request->FechaHasta." por el motivo: ".$infoTipoNovedad->tipo_novedad;
        }else{
            //armo la observacion a enviar
            $obs = "Se creo una nueva novedad para el AGENTE CON DNI: ".$request->DNI." - ".$request->ApeNom." desde el ".$request->FechaInicio." hasta el ".$request->FechaHasta." por el motivo: ".$infoTipoNovedad->tipo_novedad;
        }
        //inserto un acuse de novedad
        $acuseNovedad = new AlertaNovedadModel();
            $acuseNovedad->CUECOMPLETO = session('CUECOMPLETO');
            $acuseNovedad->Zona = session('Zona');
            $acuseNovedad->ZonaSupervision = session('ZonaSupervision');
            $acuseNovedad->Estado = "Pendiente";
            $acuseNovedad->Observaciones = $obs;
            $acuseNovedad->Eliminado = 0;
            $acuseNovedad->idInstitucionExtension = session('idInstitucionExtension');
            $acuseNovedad->idNovedad = $novedad->idNovedad;
        $acuseNovedad->save();
        return redirect()->back()->with('ConfirmarNuevaNovedadParticular','OK');
    }


    //algo que poner

    public function cambiarEstadoBorrado(Request $request)
    {
        // Obtener los datos directamente del request sin validación
        $idInstitucionExtension = $request->input('idInstitucionExtension');
        $valor = $request->input('valor');

        // Buscar el registro y actualizarlo
        $institucion = InstitucionExtensionModel::find($idInstitucionExtension);

        if ($institucion) {
            // Cambiar el estado
            $borrar = ($institucion->PermiteBorrarTodo == 0) ? 1 : 0;
            $institucion->PermiteBorrarTodo = $borrar;
            $institucion->save();

            // Responder con éxito
            return response()->json(['status' => 200, 'msg' => 'Estado actualizado'], 200);
        } else {
            // Responder con error si no se encuentra el registro
            return response()->json(['status' => 400, 'msg' => 'Registro no encontrado'], 400);
        }
    }

    public function cambiarEstadoEdicion(Request $request)
    {
        // Obtener el idInstitucionExtension del request
        $idInstitucionExtension = $request->input('idInstitucionExtension');
    
        // Buscar el registro inicial usando el idInstitucionExtension
        $institucion = InstitucionExtensionModel::find($idInstitucionExtension);
    
        if ($institucion) {
            // Obtener el valor actual de CUE de la institución
            $CUE = $institucion->CUE;
    
            // Determinar el nuevo valor para PermiteEditarTodo
            $nuevoValor = ($institucion->PermiteEditarTodo == 0) ? 1 : 0;
    
            // Actualizar todas las instituciones con el mismo CUE
            InstitucionExtensionModel::where('CUE', $CUE)
                ->update(['PermiteEditarTodo' => $nuevoValor]);
    
            // Responder con éxito
            return response()->json(['status' => 200, 'msg' => 'Estado actualizado en todas las instituciones con el mismo CUE'], 200);
        } else {
            // Responder con error si no se encuentra el registro
            return response()->json(['status' => 400, 'msg' => 'Registro no encontrado'], 400);
        }
    }
    

    //proceso editar novedad particular
    public function editarNovedadParticular(Request $request){
        //dd($request);
        /*
        "_token" => "pcYi3eHT5nSEKZJYL4fSOY5DWam9PGPDnK4io3T7"
      "idNovedad" => "114943"
      "CUPOF" => "4455-PRUEBAXXX22"
      "FechaInicio" => "2025-03-18"
      "FechaHasta" => "2025-03-27"
      "modTipoNovedad" => "6"
      "modTipoMotivo" => "6"
      "modObligaciones" => "3"
      "Observaciones" => "probando 2"
      "modIdNovedad" => null
      llega modTipoCondicion
      controlar 1,2,7,8,9 novedad
        */


        //dd($request);
        /*
            "_token" => "WPX424Mt6Hwm5fOXVENBV3X7tVJpDQfbddJTV2sv"
            "FechaInicio" => "2024-04-23"
            "FechaHasta" => "2024-04-24"
            "DNI" => "26731952"
            "TipoNovedad" => "2"
            "Observaciones" => "algo en novedad"
            "obligaciones" => "on"
            "CUPOF" => "1"
            "TipoMotivo" => "0"
        */
        

        $novedad =  PofmhNovedades::where('idNovedad', $request->modidNovedad)->first();
        //dd($novedad);
            $novedad->FechaDesde = $request->FechaInicio;
            $novedad->FechaHasta = $request->FechaHasta;
            $novedad->TotalDias = 1;
            $novedad->Mes = date('m');
            $novedad->Anio = date('Y');
            $novedad->Observaciones = $request->Observaciones;
            $novedad->idNovedadExtra = $request->modTipoNovedad;
            $novedad->Obligaciones = $request->modObligaciones;
            $novedad->CUPOF = $request->CUPOF;
            $novedad->Motivo = $request->modTipoMotivo;
            $novedad->Estado = "Pendiente";
            $novedad->Condicion = $request->modTipoCondicion ? $request->modTipoCondicion : 1;   //controlarlo

            // Crear objetos DateTime
            $fechaInicialObj = new DateTime($request->FechaInicio);//new DateTime(Carbon::parse(Carbon::now())->format('Y-m-d'));
            $fechaFinalObj = new DateTime($request->FechaHasta);
            $fechaFinalObj->modify('+1 day');   //aplico fix para que sume bien 1 dia al total ejemplo, 24 al 27 = 4
            // Calcular la diferencia entre las dos fechas
            $intervalo = $fechaInicialObj->diff($fechaFinalObj);

            // Obtener la cantidad de días
            $cantidadDias = $intervalo->days;

            $novedad->TotalDiasLicencia = $cantidadDias;
            //para control
            //aqui veremos si le escribo mensaje en el campo observaciones, falta crear en super y liq
            $novedad->Supervisores = 0;
            $novedad->Liquidacion = 0;
        $novedad->save();
        

        //una vez editado debo rectificar el pofnominal
/*
        $infoAgentePof = PofmhModel::where('idPofmh', $novedad->idPofmh)->first();
        //dd($infoAgentePof);
        $infoAgentePof->Agente = $novedad->Agente;
            
            $infoAgentePof->FechaDesde = $novedad->FechaDesde;
            $infoAgentePof->FechaHasta = $novedad->FechaHasta;

            if($novedad->Condicion != null){
                $infoAgentePof->Condicion = $novedad->Condicion;   //como es nuevo le dejo actual por ahora
            }
            $infoAgentePof->Activo = 1;   //como es nuevo le dejo actual por ahora
            $infoAgentePof->Observaciones = $novedad->Observaciones;
            $infoAgentePof->Motivo = $novedad->Motivo;

            //segun novedad vuelvo a calcular las fechas
            $fechaInicialObj = new DateTime($novedad->FechaInicio);//new DateTime(Carbon::parse(Carbon::now())->format('Y-m-d'));
            $fechaFinalObj = new DateTime($novedad->FechaHasta);
            $fechaFinalObj->modify('+1 day');   //aplico fix para que sume bien 1 dia al total ejemplo, 24 al 27 = 4
            // Calcular la diferencia entre las dos fechas
            $intervalo = $fechaInicialObj->diff($fechaFinalObj);

            // Obtener la cantidad de días
            $cantidadDias = $intervalo->days;

            switch($request->modTipoNovedad){
                case '3':   //Paro nacional
                    $infoAgentePof->Justificada += $cantidadDias;
                    break;
                case '4':   //Paro Provincial
                    $infoAgentePof->Justificada += $cantidadDias;
                    break;
                case '5':   //Carpeta Medica
                    if($cantidadDias>3){
                        $infoAgentePof->Injustificada += $cantidadDias;
                    }else{
                        $infoAgentePof->Justificada += $cantidadDias;
                    }
                    break;
                case '6':   //Capacitacion
                    if($cantidadDias>3){
                        $infoAgentePof->Injustificada += $cantidadDias;
                    }else{
                        $infoAgentePof->Justificada += $cantidadDias;
                    }
                    break;
                case '7':   //falta Justificada
                    $infoAgentePof->Justificada += $cantidadDias;
                    break;
                case '8':   //falta injustificada
                    $infoAgentePof->Injustificada += $cantidadDias;
                    break;
                case '9':   //Otras Licencias para ver
                    if($cantidadDias>3){
                        $infoAgentePof->Injustificada += $cantidadDias;
                    }else{
                        $infoAgentePof->Justificada += $cantidadDias;
                    }
                    break
                case '10':   //volantes
                    $infoAgentePof->Asistencia += $cantidadDias;
                    break;
            }
        $infoAgentePof->Condicion = 1;   //es falta injustificada, lo dejo como ACTUAL
        //$infoAgentePof->Activo = 1; 
        $infoAgentePof->save();
        */
        $obs = "Se edito la  novedad ". $novedad->idNovedad." para informar un cambio en el Feriado desde el ".$request->FechaInicio." hasta el ".$request->FechaHasta;
        //inserto un acuse de novedad - EDITANDO
        $acuseNovedad = new AlertaNovedadModel();
            $acuseNovedad->CUECOMPLETO = session('CUECOMPLETO');
            $acuseNovedad->Zona = session('Zona');
            $acuseNovedad->ZonaSupervision = session('ZonaSupervision');
            $acuseNovedad->Estado = "Pendiente";
            $acuseNovedad->Observaciones = $obs;
        $acuseNovedad->save();
        return redirect()->back()->with('ConfirmarEditarNovedadParticular','OK');

    
        }

        public function eliminarNovedadParticular($idNovedad){
           
            $novedad = PofmhNovedades::find($idNovedad);
            if ($novedad) {

                //controlo si es un alta para borra la fila completa
                if($novedad->idNovedadExtra == 1){   //agregar mas borrados
                    // Eliminar el registro de la tabla tb_pofmh
                    $pofmh = PofmhModel::where('idPofmh', $novedad->idPofmh)->first();
                    if ($pofmh) {
                        $pofmh->delete();
                    }
                }
                
                $obs = "Se elimino la  novedad ". $novedad->idNovedad." - Usuario: ".session('Usuario')."(id: ".session('idUsuario').")    - Turno: ".session("TurnoDescripcion");
                //inserto un acuse de novedad - EDITANDO
                $acuseNovedad = new AlertaNovedadModel();
                    $acuseNovedad->CUECOMPLETO = session('CUECOMPLETO');
                    $acuseNovedad->Zona = session('Zona');
                    $acuseNovedad->ZonaSupervision = session('ZonaSupervision');
                    $acuseNovedad->Estado = "Borrado";
                    $acuseNovedad->Eliminado = 1;
                    $acuseNovedad->Observaciones = $obs;
                $acuseNovedad->save();

                //borro tranquilo la novedad
                $novedad->delete();
                return redirect()->back()->with('ConfirmarEliminarNovedadParticular','OK');
            } else {
                return redirect()->back()->with('ErrorEliminarNovedadParticular','OK');
            }
        }

        public function marcarPendiente($id, Request $request)
        {
            // Validar que se haya pasado una observación
            $request->validate([
                'nota_super' => 'nullable|string|max:255',
            ]);

            // Obtener la novedad por su ID
            $novedad = PofmhNovedades::find($id);

            if ($novedad) {
                // Actualizar el estado de la novedad y la observación
                $novedad->Estado = 'Pendiente';  // O cualquier valor que determines
                $novedad->ObservacionesSuper = $request->nota_super;  // Asignar la observación
                $novedad->FechaObservacionSuper = Carbon::parse(Carbon::now())->format('Y-m-d');
                $novedad->save();

                // Responder con éxito
                return response()->json(['success' => true]);
            }

        return response()->json(['success' => false, 'message' => 'Novedad no encontrada']);
        }

        public function getCondiciones($idTipoNovedad)
        {
            // Obtener las condiciones filtradas por el tipo de novedad
            $condiciones = DB::connection('DB7')->table('tb_condiciones')->where('idTipoNovedad', $idTipoNovedad)->get();
    
            // Retornar los resultados como JSON
            return response()->json($condiciones);
        }



public function consultaPruebaNovedad(){
    $mesActual = Carbon::now()->month; // mayo = 5
    $anioActual = Carbon::now()->year; // 2025

    // Buscar relaciones del usuario en el mes y año actual
    $Misrelaciones = SuperRelacionCUEModel::where('idUsuarioSuper', session('idUsuario'))
        ->whereMonth('created_at', $mesActual)
        ->whereYear('created_at', $anioActual)
        ->get();

    $CUEs = $Misrelaciones->pluck('CUECOMPLETO')->toArray();

    $cantidad = DB::table('tb_alerta_novedades')
        ->whereIn('CUECOMPLETO', $CUEs)
        ->where('Estado', "Pendiente")
        ->whereMonth('created_at', $mesActual)
        ->count();

    session(['hayAlertas' => $cantidad]);

    return response()->json([
        'Cantidad' => $cantidad
    ]);
}




















}

