<?php

namespace App\Http\Controllers;

use App\Models\AsignaturaModel;
use App\Models\DivisionesModel;
use App\Models\EspacioCurricularModel;
use Illuminate\Http\Request;
use App\Models\OrganizacionesModel;
use App\Models\PlazasModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
class LuiController extends Controller
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
    //sin funcion por ahora
    public function edificio(){
        /*//una alternativa como objet
        //$organizaciones = OrganizacionesModel::get();
        //org->CUE los llamo de esta forma con foreach

        //obtengo el usuario que es la escuela a trabajar
        $idReparticion = session('idReparticion');
        //consulto a reparticiones
        $reparticion = DB::table('tb_reparticiones')
        ->where('tb_reparticiones.idReparticion',$idReparticion)
        ->get();


        $edificio = DB::table('tb_edificios')
        ->join('tb_localidades', 'tb_organizaciones.localidad', '=', 'tb_localidades.idlocalidad')
        ->select(
            'tb_organizaciones.idorganizaciones',
            'tb_organizaciones.cue',
            'tb_organizaciones.nombre',
            'tb_organizaciones.domicilio',
            'tb_organizaciones.esprivada',
            'tb_localidades.localidad'
        )
        ->orderBy('tb_organizaciones.cue','ASC')
        ->get();

        $datos=array(
            'mensajeError'=>"",
            'Organizaciones'=>$organizaciones
        );
        return view('bandeja.LUI.edificio');*/
    }

    public function getOrg(){ 
        //una alternativa como objet
        //$organizaciones = OrganizacionesModel::get();
        //org->CUE los llamo de esta forma con foreach

        //obtengo el usuario que es la escuela a trabajar
        $idReparticion = session('idReparticion');
        //consulto a reparticiones
        $reparticion = DB::table('tb_reparticiones')
        ->where('tb_reparticiones.idReparticion',$idReparticion)
        ->get();
        //dd($reparticion[0]->Organizacion);

        $organizaciones = DB::table('tb_organizaciones')
        ->where('tb_organizaciones.idOrganizaciones',$reparticion[0]->Organizacion)
        ->join('tb_localidades', 'tb_organizaciones.localidad', '=', 'tb_localidades.idlocalidad')
        ->select(
            'tb_organizaciones.idorganizaciones',
            'tb_organizaciones.cue',
            'tb_organizaciones.nombre',
            'tb_organizaciones.domicilio',
            'tb_organizaciones.esprivada',
            'tb_localidades.localidad'
        )
        ->orderBy('tb_organizaciones.cue','ASC')
        ->get();

        $datos=array(
            'mensajeError'=>"",
            'Organizaciones'=>$organizaciones
        );
        return view('bandeja.LUI.organizaciones',$datos);
    }

    public function getOpcionesOrg(){
         //obtengo el usuario que es la escuela a trabajar
        // $idReparticion = session('idReparticion');
         //consulto a reparticiones
         /*$reparticion = DB::table('tb_reparticiones')
         ->where('tb_reparticiones.idReparticion',$idReparticion)
         ->get();*/
         //dd($reparticion[0]->Organizacion);
 
         //traigo el edificio de una suborg
         /*$SubOrg = DB::table('tb_suborganizaciones')
         ->where('tb_suborganizaciones.idSubOrganizacion',$reparticion[0]->subOrganizacion)
         ->get();*/

        //traigo info de la institucion en su extension por su ide unico
        $institucionExtension=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
                ->get();

         //$FechaAlta = Carbon::parse($SubOrg[0]->FechaAlta)->format('Y-m-d');

         //traigo el edificio de una suborg
         /*$Edificio = DB::table('tb_edificios')
         ->where('tb_edificios.idEdificio',$SubOrg[0]->Edificio)
         ->get();*/
 
         //al panel le pasamos todo lo necesario para las opciones
         $Niveles = DB::table('tb_nivelesensenanza')->take(8)->get();
         $Turnos = DB::table('tb_turnos')->get();
         $Zonas = DB::table('tb_zonas_liq')->get();
         $ZonasSupervision = DB::table('tb_zonasupervision')->get();
         //$Modalidades = DB::table('tb_modalidadesensenanza')->get();
         $Jornadas = DB::table('tb_jornadas')->get();
         $Categorias = DB::table('tb_categorias')->get();
        
         //agregados el 03 de abril
         $Ambitos = DB::table('tb_ambitos')->get();
         $EstadoHabilitaciones = DB::table('tb_habilitaciones')->get();

         $datos=array(
             'mensajeError'=>"",
             'Niveles'=>$Niveles,
             'Zonas'=>$Zonas,
             'ZonasSupervision'=>$ZonasSupervision,
             //'Edificio'=>$Edificio,
             'Turnos'=>$Turnos,
             'infoInstitucion'=>$institucionExtension,
             //'Modalidades'=>$Modalidades,
             'Jornadas'=>$Jornadas,
             //'FechaAlta'=>$FechaAlta,
             'Categorias'=>$Categorias,
             'Ambitos'=>$Ambitos,
             'EstadoHabilitaciones'=>$EstadoHabilitaciones,
             'mensajeNAV'=>'Panel de Administración / Configuración'

         );
         $ruta ='
            <li class="breadcrumb-item active"><a href="#">LEGAJO UNICO INSTITUCIONAL</a></li>
            <li class="breadcrumb-item active"><a href="'.route('getOpcionesOrg').'">DATOS INSTITUCIONALES</a></li>
            '; 
            session(['ruta' => $ruta]);
         return view('bandeja.LUI.informacion_escuela',$datos);       
    }

    public function adjuntar_novedad($idInstExt){
      
       $institucionExtension=DB::table('tb_institucion_extension')
               ->where('tb_institucion_extension.idInstitucionExtension',$idInstExt)
               ->get();

        
        $datos=array(
            'mensajeError'=>"",
            'infoInstitucion'=>$institucionExtension,
            'mensajeNAV'=>'Panel de Administración'

        );
        $ruta ='
           <li class="breadcrumb-item active"><a href="#">LEGAJO UNICO INSTITUCIONAL</a></li>
           <li class="breadcrumb-item active"><a href="'.route('adjuntar_novedad', ['idInstExt' => $idInstExt]).'">ADJUNTO NOVEDADES</a></li>
           '; 
           session(['ruta' => $ruta]);
        return view('bandeja.LUI.informacion_escuela_adjuntos',$datos);       
   }
    public function verSubOrg(){

        //obtengo el usuario que es la escuela a trabajar
       // $idReparticion = session('idReparticion');
        //consulto a reparticiones
        /*$reparticion = DB::table('tb_reparticiones')
        ->where('tb_reparticiones.idReparticion',$idReparticion)
        ->get();
        //dd($reparticion[0]->Organizacion);*/

        /*$Suborganizaciones = DB::table('tb_suborganizaciones')
        ->where('tb_suborganizaciones.idSubOrganizacion',$reparticion[0]->subOrganizacion)
        ->join('tb_edificios', 'tb_edificios.idEdificio', '=', 'tb_suborganizaciones.Edificio')
        ->select(
            'tb_suborganizaciones.*',
            'tb_edificios.*'
        )*/
        //->orderBy('tb_suborganizaciones.cue','ASC')
        //->get();
        $institucionExtension=DB::table('tb_institucion_extension')
        ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
        ->get();

        
        $datos=array(
            'mensajeError'=>"",
            'institucionExtension'=>$institucionExtension,
            'Nombre_Institucion'=>$institucionExtension[0]->Nombre_Institucion,
            'mensajeNAV'=>'Datos Institucionales'
           
        );
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">LEGAJO UNICO INSTITUCIONAL</a></li>
            <li class="breadcrumb-item active"><a href="'.route('verSubOrg').'">INSTITUCIÓN</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.LUI.suborganizaciones',$datos);
    }

    public function Reestructura(){ 
        //una alternativa como objet
        //$organizaciones = OrganizacionesModel::get();
        //org->CUE los llamo de esta forma con foreach

        $organizaciones = DB::table('tb_organizaciones')
        ->join('tb_localidades', 'tb_organizaciones.localidad', '=', 'tb_localidades.idlocalidad')
        ->select(
            'tb_organizaciones.idorganizaciones',
            'tb_organizaciones.cue',
            'tb_organizaciones.nombre',
            'tb_organizaciones.domicilio',
            'tb_organizaciones.esprivada',
            'tb_localidades.localidad'
        )
        ->orderBy('tb_organizaciones.cue','ASC')
        ->get();

        $datos=array(
            'mensajeError'=>"",
            'Organizaciones'=>$organizaciones
        );
        return view('bandeja.LUI.POF.reestructura',$datos);
    }

    public function PlazaNueva($idSubOrg){ 
        $infoSubOrg=DB::table('tb_suborganizaciones')
        ->where('tb_suborganizaciones.idsuborganizacion',$idSubOrg)
        ->select(
            'tb_suborganizaciones.Descripcion',
            'tb_suborganizaciones.idSubOrganizacion',
            'tb_suborganizaciones.Org',
            'tb_suborganizaciones.CUE',
        )
        ->get();

        $plazas = DB::table('tb_plazas')
        ->where('tb_plazas.SubOrganizacion',$idSubOrg)


        ->select(
            'tb_plazas.idPlaza',
            'tb_plazas.CUISE',
            'tb_plazas.CUPOF',
            'tb_plazas.FechaAlta',
            'tb_plazas.DuenoActual',
            'tb_plazas.Asignatura',
            'tb_plazas.TipoDeFuncion',
            'tb_plazas.CargoSalarialDefault',

        )
        ->orderBy('tb_plazas.idPlaza','DESC')
        ->get();
        //dd($plazas);
        //combos
        $turnos = DB::table('tb_turnos')->get();
        $regimen_laboral = DB::table('tb_regimenlaboral')->get();
        $fuentesDelFinanciamiento = DB::table('tb_fuentesdefinanciamiento')->get();
        $tiposDeFuncion = DB::table('tb_tiposdefuncion')->get();
        $regimgneSalarial = DB::table('tb_regimensalarial')->get();

        $datos=array(
            'mensajeError'=>"",
            'infoPlazas'=>$plazas,
            'infoSubOrg'=>$infoSubOrg,
            'infoTurnos'=>$turnos,
            'infoRegimenLaboral'=>$regimen_laboral,
            'infoFuentesDelFinanciamiento'=>$fuentesDelFinanciamiento,
            'infoTiposDeFuncion'=>$tiposDeFuncion,
            'infoRegimenSalarial'=>$regimgneSalarial,
            'idOrg'=>$infoSubOrg[0]->Org,
        );
        return view('bandeja.LUI.POF.plaza',$datos);
    }

    public function getCarrerasPlanes(){
        //obtengo el usuario que es la escuela a trabajar
       // $idReparticion = session('idReparticion');
        //consulto a reparticiones
       /* $reparticion = DB::table('tb_reparticiones')
        ->where('tb_reparticiones.idReparticion',$idReparticion)
        ->get();*/
        //dd($reparticion[0]->Organizacion);

        /*$Carreras = DB::table('tb_carreras')
        ->where('tb_suborganizaciones.idSubOrganizacion',$reparticion[0]->subOrganizacion)
        ->join('tb_planesestudio', 'tb_planesestudio.Carrera', '=', 'tb_carreras.idCarrera')
        ->join('tb_pof_relsuborganizacionplanesestudio','tb_pof_relsuborganizacionplanesestudio.PlanEstudio', '=', 'tb_planesestudio.idPlanEstudio')
        ->join('tb_suborganizaciones', 'tb_suborganizaciones.idSubOrganizacion', '=', 'tb_pof_relsuborganizacionplanesestudio.SubOrg')
        ->select(
            'tb_carreras.*',
        )
        ->orderBy('tb_carreras.idCarrera','ASC')
        ->get();

       /* $Planes = DB::table('tb_planesestudio')
        ->where('tb_suborganizaciones.idSubOrganizacion',$reparticion[0]->subOrganizacion)
        ->join('tb_pof_relsuborganizacionplanesestudio','tb_pof_relsuborganizacionplanesestudio.PlanEstudio', '=', 'tb_planesestudio.idPlanEstudio')
        ->join('tb_suborganizaciones', 'tb_suborganizaciones.idSubOrganizacion', '=', 'tb_pof_relsuborganizacionplanesestudio.SubOrg')
        ->join('tb_pof_estadosdeplanes', 'tb_pof_estadosdeplanes.idEstadodePlan', '=', 'tb_planesestudio.Estado')

        ->select(
            'tb_planesestudio.idPlanEstudio',
            'tb_planesestudio.Descripcion as DescripcionPlan',
            'tb_pof_estadosdeplanes.Descripcion as DescripcionEstado',
        )
        ->orderBy('tb_planesestudio.idPlanEstudio','ASC')
        ->get();
*/
        $CarrerasTodas = DB::table('tb_carreras')->get();

        $CarrerasRelSubOrg = DB::table('tb_carreras_suborg')
        ->join('tb_carreras','tb_carreras.idCarrera', '=', 'tb_carreras_suborg.idCarrera')
        ->where('tb_carreras_suborg.idSubOrganizacion',session('idSubOrganizacion'))
        ->get();

        $PlanesDeEstudio =  DB::table('tb_planesestudio')->get();

        $PlanesRelSubOrg = DB::table('tb_pof_relsuborganizacionplanesestudio')
        ->join('tb_carreras','tb_carreras.idCarrera', '=', 'tb_pof_relsuborganizacionplanesestudio.Carrera')
        ->join('tb_planesestudio','tb_planesestudio.idPlanEstudio', '=', 'tb_pof_relsuborganizacionplanesestudio.PlanEstudio')
        ->where('tb_pof_relsuborganizacionplanesestudio.SubOrg',session('idSubOrganizacion'))
        ->get();
        $datos=array(
            'mensajeError'=>"",
            'CarrerasTodas'=>$CarrerasTodas,
            'CarrerasRelSubOrg'=>$CarrerasRelSubOrg,
            'PlanesDeEstudio'=>$PlanesDeEstudio,
            'PlanesRelSubOrg'=>$PlanesRelSubOrg,
            'mensajeNAV'=>'Panel de Configuración de Carreras y Planes'
        );
        return view('bandeja.LUI.carreras_planes',$datos);

    }
    public function getCarreras($idSubOrg){
        //traigo las relaciones Suborg->planes->carrera
        $carreras = DB::table('tb_carreras')
        ->where('tb_suborganizaciones.idSubOrganizacion',$idSubOrg)
        ->join('tb_planesestudio', 'tb_planesestudio.Carrera', '=', 'tb_carreras.idCarrera')
        ->join('tb_pof_relsuborganizacionplanesestudio','tb_pof_relsuborganizacionplanesestudio.PlanEstudio', '=', 'tb_planesestudio.idPlanEstudio')
        ->join('tb_suborganizaciones', 'tb_suborganizaciones.idSubOrganizacion', '=', 'tb_pof_relsuborganizacionplanesestudio.SubOrg')
        ->select(
            'tb_carreras.*',
        )
        ->orderBy('tb_carreras.idCarrera','ASC')
        ->get();

       
        $respuesta="";
       
        foreach($carreras as $carrera){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$carrera->idCarrera.'</td>
                <td>'.$carrera->Descripcion.'<input type="hidden" id="nomCarreraModal'.$carrera->idCarrera.'" value="'.$carrera->Descripcion.'"</td>
                <td>'.$carrera->Titulo.'</td>
                <td>'.$carrera->Duracion.'</td>
                <td>
                    <button type="button" onclick="seleccionarCarrera('.$carrera->idCarrera.')">Seleccionar</button>
                </td>
            </tr>';
            
            
        }
       
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }

    public function getCarrerasTodas($nombre){
        //traigo las relaciones Suborg->planes->carrera
        $carreras = DB::table('tb_carreras')
        ->orWhere('Descripcion', 'like', '%' . $nombre . '%')
        ->get();

       
        $respuesta="";
       
        foreach($carreras as $carrera){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$carrera->idCarrera.'</td>
                <td>'.$carrera->Descripcion.'<input type="hidden" id="nomCarreraModal'.$carrera->idCarrera.'" value="'.$carrera->Descripcion.'"</td>
                <td>'.$carrera->Titulo.'</td>
                <td>'.$carrera->Duracion.'</td>
                <td>
                    <button type="button" onclick="seleccionarCarreraTodas('.$carrera->idCarrera.')">Seleccionar</button>
                </td>
            </tr>';
            
            
        }
       
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }

    public function getEspCurPlan($idPlan){
        //traigo las relaciones Suborg->planes->carrera
        $EspacioCurriculares = DB::table('tb_espacioscurriculares')
        ->join('tb_asignaturas', 'tb_asignaturas.idAsignatura', 'tb_espacioscurriculares.Asignatura')
        ->join('tb_planesestudio', 'tb_planesestudio.idPlanEstudio', 'tb_espacioscurriculares.PlanEstudio')
        ->where('tb_espacioscurriculares.SubOrg',session('idSubOrganizacion'))
        ->where('tb_espacioscurriculares.PlanEstudio',$idPlan)
        ->select(
            'tb_planesestudio.idPlanEstudio',
            'tb_planesestudio.Descripcion as NomPlan',
            'tb_asignaturas.idAsignatura',
            'tb_asignaturas.Descripcion as NomAsignatura',
            'tb_asignaturas.idAsignatura',
            'tb_espacioscurriculares.idEspacioCurricular',
        )
        ->get();

       
        $respuesta="";
       
        foreach($EspacioCurriculares as $EspacioCurricular){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$EspacioCurricular->idEspacioCurricular.'</td>
                <td>'.$EspacioCurricular->NomAsignatura.'</td>
                <td>'.$EspacioCurricular->NomPlan.'</td>
                <td>
                    <a href="/desvincularEspCur/'.$EspacioCurricular->idEspacioCurricular.'">
                    <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>';
            
            
        }
       
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }
    //funcion creada para esp curricular
    public function getAsignatura($nombre){
        //traigo las relaciones Suborg->planes->carrera
        $Asignaturas = DB::table('tb_asignaturas')
        ->orWhere('Descripcion', 'like', '%' . $nombre . '%')
        ->get();

       
        $respuesta="";
       
        foreach($Asignaturas as $Asignatura){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$Asignatura->idAsignatura.'</td>
                <td>'.$Asignatura->Descripcion.'<input type="hidden" id="nomAsignaturaModal'.$Asignatura->idAsignatura.'" value="'.$Asignatura->Descripcion.'"</td>
                <td>
                    <button type="button" onclick="seleccionarAsignatura('.$Asignatura->idAsignatura.')">Seleccionar</button>
                </td>
            </tr>';
            
            
        }
       
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }

    public function getPlanes($idSubOrg){
        //traigo las relaciones Suborg->planes->carrera
        $Planes = DB::table('tb_planesestudio')
        ->where('tb_suborganizaciones.idSubOrganizacion',$idSubOrg)
        ->join('tb_pof_relsuborganizacionplanesestudio','tb_pof_relsuborganizacionplanesestudio.PlanEstudio', '=', 'tb_planesestudio.idPlanEstudio')
        ->join('tb_suborganizaciones', 'tb_suborganizaciones.idSubOrganizacion', '=', 'tb_pof_relsuborganizacionplanesestudio.SubOrg')
        ->join('tb_pof_estadosdeplanes', 'tb_pof_estadosdeplanes.idEstadodePlan', '=', 'tb_planesestudio.Estado')

        ->select(
            'tb_planesestudio.idPlanEstudio',
            'tb_planesestudio.Descripcion as DescripcionPlan',
            'tb_pof_estadosdeplanes.Descripcion as DescripcionEstado',
        )
        ->orderBy('tb_planesestudio.idPlanEstudio','ASC')
        ->get();

       
        $respuesta="";
       
        foreach($Planes as $obj){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$obj->idPlanEstudio.'</td>
                <td>'.$obj->DescripcionPlan.'<input type="hidden" id="nomPlanModal'.$obj->idPlanEstudio.'" value="'.$obj->DescripcionPlan.'"</td>
                <td>'.$obj->DescripcionEstado.'</td>
                <td>
                    <button type="button" onclick="seleccionarPlan('.$obj->idPlanEstudio.')">Seleccionar</button>
                </td>
            </tr>';
            
            
        }
       
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }
    public function verDivisiones(){
                //obtengo el usuario que es la escuela a trabajar
                /*$idReparticion = session('idReparticion');
                //consulto a reparticiones
                $reparticion = DB::table('tb_reparticiones')
                ->where('tb_reparticiones.idReparticion',$idReparticion)
                ->get();*/
                //dd($reparticion[0]->Organizacion);
        
                //traigo el edificio de una suborg
                $institucion=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
                ->first();
        
                
                //al panel le pasamos todo lo necesario para las opciones
                $Turnos = DB::table('tb_turnos')->get();
                $Cursos = DB::table('tb_cursos')->get();
                $Division = DB::table('tb_division')->get();
                

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
                ->orderBy('tb_cursos.DescripcionCurso','ASC')
                //->orderBy('tb_cursos.DescripcionDivision','ASC')
                ->get();

                $datos=array(
                    'mensajeError'=>"",
                    'Turnos'=>$Turnos,
                    'Cursos'=>$Cursos,
                    'Division'=>$Division,
                    'Divisiones'=>$Divisiones,
                    'Institucion'=>$institucion,
                    'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
                    'mensajeNAV'=>'Panel de Configuración de Cursos y Divisiones'
        
                );
                $ruta ='
                <li class="breadcrumb-item active"><a href="#">LEGAJO UNICO INSTITUCIONAL</a></li>
                <li class="breadcrumb-item active"><a href="'.route('verDivisiones').'">CURSOS Y DIVISIONES</a></li>
                '; 
                session(['ruta' => $ruta]);
                return view('bandeja.LUI.divisiones',$datos); 
    }

    public function verAsigEspCur(){
        //obtengo el usuario que es la escuela a trabajar
       /* $idReparticion = session('idReparticion');
        //consulto a reparticiones
        $reparticion = DB::table('tb_reparticiones')
        ->where('tb_reparticiones.idReparticion',$idReparticion)
        ->get();
        //dd($reparticion[0]->Organizacion);

        //traigo el edificio de una suborg
        $SubOrg = DB::table('tb_suborganizaciones')
        ->where('tb_suborganizaciones.idSubOrganizacion',$reparticion[0]->subOrganizacion)
        ->get();
*/
        $institucionExtension=DB::table('tb_institucion_extension')
        ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
        ->get();

        //al panel le pasamos todo lo necesario para las opciones
        $Asignaturas = DB::table('tb_asignaturas')
        ->get();
        $CarrerasRelSubOrg = DB::table('tb_carreras_suborg')
        ->join('tb_carreras','tb_carreras.idCarrera', '=', 'tb_carreras_suborg.idCarrera')
        ->where('tb_carreras_suborg.idSubOrganizacion',session('idSubOrganizacion'))
        ->get();
        // $Planes = DB::table('tb_planesestudio')
        // ->where('tb_suborganizaciones.idSubOrganizacion',session('idSubOrganizacion'))
        // ->join('tb_pof_relsuborganizacionplanesestudio','tb_pof_relsuborganizacionplanesestudio.PlanEstudio', '=', 'tb_planesestudio.idPlanEstudio')
        // ->join('tb_suborganizaciones', 'tb_suborganizaciones.idSubOrganizacion', '=', 'tb_pof_relsuborganizacionplanesestudio.SubOrg')
        // ->join('tb_pof_estadosdeplanes', 'tb_pof_estadosdeplanes.idEstadodePlan', '=', 'tb_planesestudio.Estado')

        // ->select(
        //     'tb_planesestudio.idPlanEstudio',
        //     'tb_planesestudio.Descripcion as DescripcionPlan',
        //     'tb_pof_estadosdeplanes.Descripcion as DescripcionEstado',
        // )
        // ->orderBy('tb_planesestudio.idPlanEstudio','ASC')
        // ->get();
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
            'tb_divisiones.Descripcion'
            //'tb_cursos.*',
            //'tb_division.*',
            //'tb_turnos.Descripcion as DescripcionTurno',
           // 'tb_turnos.idTurno',
        )
        //->orderBy('tb_cursos.DescripcionCurso','ASC')
        ->groupBy('tb_divisiones.Curso', 'tb_divisiones.Descripcion')
        ->get();
        $datos=array(
            'mensajeError'=>"",
            'Asignaturas'=>$Asignaturas,
            'Cursos'=>$Cursos,
            'Division'=>$Division,
            'Divisiones'=>$Divisiones,
            'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
            'TiposDeEspacioCurricular'=>$TiposDeEspacioCurricular,
            'CarrerasRelSubOrg'=>$CarrerasRelSubOrg,
            'Planes'=>null,
            'TiposHora'=>$TiposHora,
            'RegimenDictado'=>$RegimenDictado,
            'mensajeNAV'=>'Panel de Configuración de Asignaturas / Planes y Modalidades'


        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">LEGAJO UNICO INSTITUCIONAL</a></li>
        <li class="breadcrumb-item active"><a href="'.route('verAsigEspCur').'">Asignaturas y Esp. Curriculares</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.LUI.asignatura_espaciocurricular',$datos); 
}
    public function getDivision($idSubOrg,$idPlanEstudio){
        //traigo las relaciones Suborg->planes->carrera
        $Divisiones = DB::table('tb_divisiones')
        ->where('tb_suborganizaciones.idSubOrganizacion',$idSubOrg)
        ->where('tb_planesestudio.idPlanEstudio',$idPlanEstudio)
        ->join('tb_turnos','tb_turnos.idTurno', '=', 'tb_divisiones.Turno')
        ->join('tb_suborganizaciones', 'tb_suborganizaciones.idSubOrganizacion', '=', 'tb_divisiones.idSubOrg')
        ->join('tb_planesestudio', 'tb_planesestudio.idPlanEstudio', '=', 'tb_divisiones.PlanEstudio')

        ->select(
            'tb_divisiones.idDivision',
            'tb_divisiones.Curso',
            'tb_divisiones.Division',
            'tb_turnos.Descripcion as Turno',
        )
        ->orderBy('tb_divisiones.idDivision','ASC')
        ->get();

       //dd($Divisiones);
        $respuesta="";
       
        foreach($Divisiones as $obj){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$obj->idDivision.'</td>
                <td>'.$obj->Curso." - ".$obj->Division.'<input type="hidden" id="nomDivisionModal'.$obj->idDivision.'" value="'.$obj->Curso." - ".$obj->Division.'"</td>
                <td>'.$obj->Turno.'</td>
                <td>
                    <button type="button" onclick="seleccionarDivision('.$obj->idDivision.')">Seleccionar</button>
                </td>
            </tr>';
            
            
        }
       
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }

    public function getEspacioCurricular($idNodo, Request $request) {
        // Obtener el término de búsqueda del request
        $search = $request->input('search');
    
        // Traer las relaciones Suborg->planes->carrera y filtrar según el término de búsqueda
        $EspacioCurricular = DB::table('tb_espacioscurriculares')
            ->where('tb_espacioscurriculares.CUE', session('CUE'))
            ->join('tb_pof_regimendictado', 'tb_pof_regimendictado.idRegimenDictado', '=', 'tb_espacioscurriculares.RegimenDictado')
            ->select(
                'tb_espacioscurriculares.idEspacioCurricular',
                'tb_espacioscurriculares.Horas',
                'tb_espacioscurriculares.Descripcion as EspacioCurricular',
                'tb_pof_regimendictado.Descripcion_regimen as RegimenDictado'
            )
            ->when($search, function ($query, $search) {
                return $query->where('tb_espacioscurriculares.Descripcion', 'like', '%' . $search . '%');
            })
            ->orderBy('tb_espacioscurriculares.idEspacioCurricular', 'ASC')
            ->get();
    
        $respuesta = "";
    
        foreach ($EspacioCurricular as $obj) {
            $respuesta .= '
                <tr class="gradeX">
                    <td>' . $obj->idEspacioCurricular . '</td>
                    <td>' . $obj->EspacioCurricular . '<input type="hidden" id="nomEspacioCurricularModal' . $obj->idEspacioCurricular . '" value="' . $obj->EspacioCurricular . '"></td>
                    <td>' . $obj->Horas . '</td>
                    <td>' . $obj->RegimenDictado . '</td>
                    <td>
                        <form method="POST" action="' . route('formularioInsertarEspCur') . '" class="formularioInsertarEspCur">
                        ' . csrf_field() . '
                            <input type="hidden" name="idEspCur" value="' . $obj->idEspacioCurricular . '">
                            <input type="hidden" name="idNodoEspCur" value="' . $idNodo . '">
                            <button type="submit" name="InsertarEspCur">Agregar</button>
                        </form>
                    </td>
                </tr>';
        }
    
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }
    

    //esta funcion devuelve desde la web
    public function getEspacioCurricularWeb($idPlanEstudio){
        //traigo las relaciones Suborg->planes->carrera
        $EspacioCurricular = DB::table('tb_espacioscurriculares')
        ->where('tb_espacioscurriculares.PlanEstudio',$idPlanEstudio)
        ->join('tb_pof_regimendictado','tb_pof_regimendictado.idRegimenDictado', '=', 'tb_espacioscurriculares.RegimenDictado')
        ->select(
            'tb_espacioscurriculares.idEspacioCurricular',
            'tb_espacioscurriculares.Curso',
            'tb_espacioscurriculares.Horas',
            'tb_espacioscurriculares.Descripcion as EspacioCurricular',
            'tb_pof_regimendictado.Descripcion as RegimenDictado',
        )
        ->orderBy('tb_espacioscurriculares.idEspacioCurricular','ASC')
        ->get();

       //dd($Divisiones);
       $datos=array(
        'mensajeError'=>"",
        'EspaciosCurriculares'=>$EspacioCurricular,
   
    );
    return view('bandeja.LUI.espacios_curriculares',$datos);
    }

    public function getCargosSalariales($idRegimenSalarial){
        //traigo las relaciones Suborg->planes->carrera
        $CargosSalariales = DB::table('tb_cargossalariales')
        ->where('tb_cargossalariales.RegimenSalarial',$idRegimenSalarial)
        ->select(
            'tb_cargossalariales.Codigo',
            'tb_cargossalariales.Cargo',
            'tb_cargossalariales.Puntos',
            'tb_cargossalariales.SueldoBasico',   
            'tb_cargossalariales.idCargo',         
        )
        ->orderBy('tb_cargossalariales.idCargo','ASC')
        ->get();

      //dd($CargosSalariales);
        $respuesta="";
       
        foreach($CargosSalariales as $obj){
            $respuesta=$respuesta.'
            <tr class="gradeX">
                <td>'.$obj->Codigo.'</td>
                <td>'.$obj->Cargo.'<input type="hidden" id="nomCargosSalarialesModal'.$obj->idCargo.'" value="'.$obj->Cargo.'"</td>
                <td>'.$obj->Puntos.'</td>
                <td>'.$obj->SueldoBasico.'</td>
                <td>
                    <button type="button" onclick="seleccionarCargoSalarial('.$obj->idCargo.')">Seleccionar</button>
                </td>
            </tr>';
            
            
        }
       
        return response()->json(array('status' => 200, 'msg' => $respuesta), 200);
    }

    public function AltaPlaza(Request $request){
        //consultas previas
        $EspacioCurricular = DB::table('tb_espacioscurriculares')
        ->where('tb_espacioscurriculares.idEspacioCurricular',$request->idEspacioCurricular)
        ->select('*')
        ->get();
        $Turnos = DB::table('tb_turnos')
        ->where('tb_turnos.idTurno',$request->Turno)
        ->select('*')
        ->get();
        // dd($request);
          $plaza = new PlazasModel();
          $plaza->SubOrganizacion=$request->idSubOrg;
          $plaza->CUISE=$request->CUE;
          $plaza->Division=$request->idDivision;
          $plaza->EspacioCurricular=$request->idEspacioCurricular;
          $plaza->Turno=$request->Turno;
          $plaza->FechaAlta=$request->FechaAlta;
          $plaza->Horas=$request->Horas;
          $plaza->NormaCreacion=$request->NormaDeCreacion;
          $plaza->RegimenLaboral=$request->RegimenLaboral;
          $plaza->TipoDeFuncion=$request->TipoDeFuncion;
          $plaza->RegimenSalarial=$request->RegimenSalarial;
          $plaza->CargoSalarialDefault=$request->idCargoSalarial;
          $plaza->Horario=$request->Horarios;
          $plaza->Observaciones=$request->Observacion;
          $plaza->Asignatura=$EspacioCurricular[0]->Asignatura; //completar luego con la verdadera asignatura
          //cargo automatico
          $plaza->Sistema=1;//educativo
          $plaza->Temporalidad=17;//a termino
          $plaza->CUPOF=$request->CUE.'-n/n-_______-'.$request->DescripcionDivision."-".$Turnos[0]->Mnemo;
          $plaza->save();
          
          $id=$request->idSubOrg;
         return redirect("/PlazaNueva/$id")->with('AltaPlaza','OK');
         //LuiController::PlazaNueva($request->idSurOrg);
      }
  
      public function editarEspCur($idEspCur){
        $institucionExtension=DB::table('tb_institucion_extension')
        ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
        ->get();

        //al panel le pasamos todo lo necesario para las opciones
        $Asignaturas = DB::table('tb_asignaturas')
        ->get();
        $CarrerasRelSubOrg = DB::table('tb_carreras_suborg')
        ->join('tb_carreras','tb_carreras.idCarrera', '=', 'tb_carreras_suborg.idCarrera')
        ->where('tb_carreras_suborg.idSubOrganizacion',session('idSubOrganizacion'))
        ->get();
        // $Planes = DB::table('tb_planesestudio')
        // ->where('tb_suborganizaciones.idSubOrganizacion',session('idSubOrganizacion'))
        // ->join('tb_pof_relsuborganizacionplanesestudio','tb_pof_relsuborganizacionplanesestudio.PlanEstudio', '=', 'tb_planesestudio.idPlanEstudio')
        // ->join('tb_suborganizaciones', 'tb_suborganizaciones.idSubOrganizacion', '=', 'tb_pof_relsuborganizacionplanesestudio.SubOrg')
        // ->join('tb_pof_estadosdeplanes', 'tb_pof_estadosdeplanes.idEstadodePlan', '=', 'tb_planesestudio.Estado')

        // ->select(
        //     'tb_planesestudio.idPlanEstudio',
        //     'tb_planesestudio.Descripcion as DescripcionPlan',
        //     'tb_pof_estadosdeplanes.Descripcion as DescripcionEstado',
        // )
        // ->orderBy('tb_planesestudio.idPlanEstudio','ASC')
        // ->get();
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
            'tb_divisiones.Descripcion'
            //'tb_cursos.*',
            //'tb_division.*',
            //'tb_turnos.Descripcion as DescripcionTurno',
           // 'tb_turnos.idTurno',
        )
        //->orderBy('tb_cursos.DescripcionCurso','ASC')
        ->groupBy('tb_divisiones.Curso', 'tb_divisiones.Descripcion')
        ->get();
        $datos=array(
            'mensajeError'=>"",
            'Asignaturas'=>$Asignaturas,
            'Cursos'=>$Cursos,
            'Division'=>$Division,
            'Divisiones'=>$Divisiones,
            'idEspCur' => $idEspCur,
            'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d'),
            'TiposDeEspacioCurricular'=>$TiposDeEspacioCurricular,
            'CarrerasRelSubOrg'=>$CarrerasRelSubOrg,
            'Planes'=>null,
            'TiposHora'=>$TiposHora,
            'RegimenDictado'=>$RegimenDictado,
            'mensajeNAV'=>'Panel de Configuración de Asignaturas / Planes y Modalidades'


        );
        return view('bandeja.LUI.editarEspCur',$datos); 
      }

      public function editarDivision($idDivision){
        //obtengo el usuario que es la escuela a trabajar
        /*$idReparticion = session('idReparticion');
        //consulto a reparticiones
        $reparticion = DB::table('tb_reparticiones')
        ->where('tb_reparticiones.idReparticion',$idReparticion)
        ->get();*/
        //dd($reparticion[0]->Organizacion);

        //traigo el edificio de una suborg
        $institucion=DB::table('tb_institucion_extension')
        ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
        ->get();

        
        //al panel le pasamos todo lo necesario para las opciones
        $Turnos = DB::table('tb_turnos')->get();
        $Cursos = DB::table('tb_cursos')->get();
        $Division = DB::table('tb_division')->get();
        

        $Divisiones = DB::table('tb_divisiones')
        ->where('tb_divisiones.idInstitucionExtension',session('idInstitucionExtension'))
        ->where('tb_divisiones.idDivision',$idDivision)
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
        ->orderBy('tb_cursos.DescripcionCurso','ASC')
        //->orderBy('tb_cursos.DescripcionDivision','ASC')
        ->first();

        $datos=array(
            'mensajeError'=>"",
            'Turnos'=>$Turnos,
            'Cursos'=>$Cursos,
            'Division'=>$Division,
            'Divisiones'=>$Divisiones,
            'Institucion'=>$institucion,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Cursos y Divisiones'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">LEGAJO UNICO INSTITUCIONAL</a></li>
        <li class="breadcrumb-item active"><a href="'.route('verDivisiones').'">CURSOS Y DIVISIONES</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.LUI.editarDivision',$datos); 
}


    public function formularioActualizarDivisiones(Request $request){
        //dd($request);
        /*
        "_token" => "LaT9WiCoic7Bl4kwccuIbNK8L7gS7ChWEf0dmUAz"
        "Descripcion" => "1ER GRADO"
        "Curso" => "2"
        "Division" => "17"
        "Turno" => "19"
        */
        $Divisiones = DivisionesModel::where('idDivision',$request->idDiv)->first();
            $Divisiones->Descripcion = $request->Descripcion;
            $Divisiones->Curso = $request->Curso;
            $Divisiones->Division = $request->Division;
            $Divisiones->Turno = $request->Turno;
            //$Divisiones->FechaAlta = Carbon::now();
            //$Divisiones->idInstitucionExtension = session('idInstitucionExtension');
        $Divisiones->save();

    return redirect("/verDivisiones")->with('ConfirmarActualizarDivisiones','OK');
    }




      
}
