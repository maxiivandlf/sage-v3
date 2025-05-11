<?php

namespace App\Http\Controllers;

use App\Models\AgenteModel;
use App\Models\AgenteRespaldoModel;
use App\Models\PofGeneradaModel;
use Illuminate\Http\Request;
use App\Models\OrganizacionesModel;
use Illuminate\Support\Facades\DB;
use App\Models\AsignaturaModel;
use App\Models\CarrerasRelSubOrgModel;
use App\Models\DivisionesModel;
use App\Models\EdificioModel;
use App\Models\EspacioCurricularModel;
use App\Models\InstitucionExtensionModel;
use App\Models\InstitucionModel;
use App\Models\InstRelAgenteModel;
use App\Models\NivelesEnsenanzaRelSubOrgModel;
use App\Models\Nodo;
use App\Models\NovedadesModel;
use App\Models\PlanesRelSubOrgModel;
use App\Models\PlazasModel;
use App\Models\POFMH\PofmhModel;
use App\Models\RelNodoEspCur;
use App\Models\SubOrgAgenteModel;
use App\Models\SubOrganizacionesModel;
use App\Models\TurnosRelInstModel;
use App\Models\TurnosRelSubOrgModel;
use App\Models\UsuarioModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;
use Dompdf\Options;
use LDAP\Result;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
class LupController extends Controller
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
    public function verArbol($idSubOrg){
        //traigo todo de suborganizacion pasada
        $organizacion=DB::table('tb_suborganizaciones')
        ->where('tb_suborganizaciones.idsuborganizacion',$idSubOrg)
        ->select('*')
        ->get();
        /*
            [
                {
                "org": 807
                }
            ]
                si lo llamo db:table me devuelve asi, leerlo como array primero objeto[0]->clave
        */
       
        //funcion previa, luego la desecho porque la idea es que use NODO en su lugar
        $suborganizaciones = DB::table('tb_suborganizaciones')
        ->where('tb_suborganizaciones.idSubOrganizacion',$idSubOrg)
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

        //por ahora traigo las plazas de una determina SubOrganizacion
        $plazas = DB::table('tb_plazas')
        ->where('tb_plazas.SubOrganizacion',$idSubOrg)
        ->leftJoin('tb_agentes', 'tb_agentes.idAgente', '=', 'tb_plazas.DuenoActual')
        ->select(
            'tb_plazas.*',
            'tb_agentes.Nombres',
            'tb_agentes.Documento',

        )
        ->orderBy('tb_plazas.idPlaza','DESC')
        ->get();

        $turnos = DB::table('tb_turnos')->get();
        $regimen_laboral = DB::table('tb_regimenlaboral')->get();
        $fuentesDelFinanciamiento = DB::table('tb_fuentesdefinanciamiento')->get();
        $tiposDeFuncion = DB::table('tb_tiposdefuncion')->get();
        $Asignaturas = DB::table('tb_asignaturas')->get();
        $CargosSalariales = DB::table('tb_cargossalariales')->get();
        $datos=array(
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
        
        //dd($plazas);
        return view('bandeja.LUP.arbol',$datos);
    }

    public function verArbolServicio($idSubOrg){
        //traigo todo de suborganizacion pasada
        $organizacion=DB::table('tb_suborganizaciones')
        ->where('tb_suborganizaciones.idsuborganizacion',$idSubOrg)
        ->select('*')
        ->get();
        /*
            [
                {
                "org": 807
                }
            ]
                si lo llamo db:table me devuelve asi, leerlo como array primero objeto[0]->clave
        */
       
        //funcion previa, luego la desecho porque la idea es que use NODO en su lugar
        $suborganizaciones = DB::table('tb_suborganizaciones')
        ->where('tb_suborganizaciones.idSubOrganizacion',$idSubOrg)
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

        //por ahora traigo las plazas de una determina SubOrganizacion
        $plazas = DB::table('tb_plazas')
        ->where('tb_plazas.SubOrganizacion',$idSubOrg)
        ->leftJoin('tb_agentes', 'tb_agentes.idAgente', '=', 'tb_plazas.DuenoActual')
        ->select(
            'tb_plazas.*',
            'tb_agentes.Nombres',
            'tb_agentes.Documento',

        )
        ->orderBy('tb_plazas.idPlaza','DESC')
        ->get();

        $turnos = DB::table('tb_turnos')->get();
        $regimen_laboral = DB::table('tb_regimenlaboral')->get();
        $fuentesDelFinanciamiento = DB::table('tb_fuentesdefinanciamiento')->get();
        $tiposDeFuncion = DB::table('tb_tiposdefuncion')->get();
        $Asignaturas = DB::table('tb_asignaturas')->get();
        $CargosSalariales = DB::table('tb_cargossalariales')->get();
        $datos=array(
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
        
        //dd($plazas);
        return view('bandeja.AG.Servicios.arbol',$datos);
    }
    public function verAgentes($idPlaza){
        $infoPlaza = DB::table('tb_plazas')
        ->where('tb_plazas.idPlaza',$idPlaza)
        ->select(
            'tb_plazas.*'
        )
        ->get();

        $Agentes = DB::table('tb_agentes')
        ->join('tb_tiposdeagente', 'tb_tiposdeagente.idTipoAgente', '=', 'tb_agentes.TipoAgente')
        ->select(
            'tb_agentes.idAgente',
            'tb_agentes.Nombres',
            'tb_agentes.Documento',
            'tb_agentes.Vive',
            'tb_agentes.Baja',
            'tb_tiposdeagente.Descripcion'
        )
        ->get();

        //extras a enviar
        $CausaAltas = DB::table('tb_causasaltas')->get();
        $CausaBajas = DB::table('tb_causasbajas')->get();
        $SR = DB::table('tb_situacionrevista')->get();

        $datos=array(
            'mensajeError'=>"",
            'idSubOrg'=>$infoPlaza[0]->SubOrganizacion,
            'Agentes'=>$Agentes,
            'infoPlaza'=>$infoPlaza,
            'CausaAltas'=>$CausaAltas,
            'CausaBajas'=>$CausaBajas,
            'SituacionDeRevista'=>$SR,
        );
        //dd($infoPlaza);
        return view('bandeja.LUP.agentes',$datos);
    }

    public function nuevoAgente(){
        //extras a enviar
        $TiposDeDocumentos = DB::table('tb_tiposdedocumento')->get();
        $TiposDeAgentes = DB::table('tb_tiposdeagente')->get();
        $Sexos = DB::table('tb_sexo')->get();
        $EstadosCiviles = DB::table('tb_estadosciviles')->get();
        $Nacionalidades = DB::table('tb_nacionalidad')->get();
        //se agrego el 18 abril
        $RelInstAgente = DB::table('tb_institucion_rel_agente')
        ->join('tb_agentes', 'tb_agentes.idAgente', '=', 'tb_institucion_rel_agente.idAgente')
        ->join('tb_institucion_extension', 'tb_institucion_extension.idInstitucionExtension', '=', 'tb_institucion_rel_agente.idInstitucionExtension')
        ->where('tb_institucion_extension.idInstitucionExtension', session('idInstitucionExtension'))
        ->select(
            'tb_agentes.*',
            'tb_institucion_extension.*',
        )
        ->get();

        //dd($RelSubOrgAgente);
        $datos=array(
            'mensajeError'=>"",
            'TipoDeDocumento' => $TiposDeDocumentos,
            'TipoDeAgentes' => $TiposDeAgentes,
            'Sexos' => $Sexos,
            'EstadosCiviles' => $EstadosCiviles,
            'Nacionalidades' => $Nacionalidades,
            'mensajeNAV'=>'Panel de Configuración de Agentes / No Agentes',
            'RelInstAgente'=>$RelInstAgente
        );
        //dd($infoPlaza);
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">LEGAJO ÚNICO DE PERSONAL</a></li>
            <li class="breadcrumb-item active"><a href="'.route('nuevoAgente').'">CREAR AGENTE NUEVO</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.LUP.nuevo_agente',$datos);
    }

    public function FormNuevoAgente(Request $request){

        //dd($request);
        /*
      "Apellido" => "leo prueba"
      "Nombre" => "leo prueba2"
      "Documento" => "952225"
      "Sexo" => "M"
      "CUIL" => "952225"

        */
        //valido si existe o no
        $consultaDNI = DB::table('tb_agentes')
        ->where('Documento', $request->Documento)
        ->first();


        if ($consultaDNI === null || $request->Documento==null ||$request->Documento=="") {
            //voy a omitir por ahora la comprobacion de agentes por DNI

            $institucionExtension=DB::table('tb_institucion_extension')
            ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
            ->get();
            $institucionBase=DB::table('tb_institucion')
            ->where('tb_institucion.idInstitucion',$institucionExtension[0]->idInstitucion)
            ->get();

            //dd($request);
            /*
            "_token" => "CXxPRXwdpVUv0XBGLDF4mUTkiPap95bKWqRdB1lE"
            "Apellido" => "loyola"
            "Nombre" => "leo martin"
            "Documento" => "22"
            "Sexo" => "M"
            "CUIL" => "23267319529"
            "TipoDeAgente" => "1"
            */
            /*$o = new AgenteModel();
                $o->docu = $request->Documento;
                $o->nomb = strtoupper($request->Apellido).", ".strtoupper($request->Nombre);
                $o->Sexo = $request->Sexo;
                $o->cuil = $request->CUIL;
                $o->viejo = 1;
                //datos de la zona, los traigo desde la 
                $o->zona = $institucionExtension[0]->Zona;
                $o->desc_zona = $institucionExtension[0]->Localidad;
                $o->escu = $institucionBase[0]->Unidad_Liquidacion;
                $o->desc_escu = $institucionExtension[0]->Nombre_Institucion;
            $o->save();*/

            //tambien creo el respaldo que sera util para los otros trabajos
            $r = new AgenteRespaldoModel();
                $r->Documento = $request->Documento;
                $r->ApeNom = strtoupper($request->Apellido).", ".strtoupper($request->Nombre);
                $r->Cuil = $request->CUIL;
                $r->Sexo = $request->Sexo;

            $r->save();
            //agrego al docente en la tabla relacionada suborg y agente
            $ag = new InstRelAgenteModel();
                $ag->idInstitucionExtension = session('idInstitucionExtension');
                $ag->idAgente = $r->idAgente;
            $ag->save();
            return redirect("/nuevoAgente")->with('ConfirmarNuevoAgente','OK');
            //LuiController::PlazaNueva($request->idSurOrg);
        }else{
            return redirect("/nuevoAgente")->with('ConfirmarNuevoAgenteExiste','OK');
        }
        

      }

    public function formularioEdificio(Request $request){
        //dd($request);
        /*
         "_token" => "d5IadjAga7U1TQ1BuqZJjhjTsYbjBy8Er9K4F9hY"
      "Domicilio" => "Puerto Argentino 58 B° Hospital"
      "DescripcionLocalidad" => "ARAUCO"
      "idLocalidad" => "1"
      "Zona" => "P"
      "ZonaSup" => "60"
      "googleMaps" => null
      "id" => "1861"
        */
        //busco la zona y traigo su nombre
        $infoDpto = DB::table('tb_zonas_liq')->where('codigo_letra',$request->Zona)->first();

        $actualizar = InstitucionExtensionModel::where('idInstitucionExtension', session('idInstitucionExtension'))
        ->update([
            'Domicilio_Institucion'=>$request->Domicilio,
            'Localidad'=>$request->DescripcionLocalidad,
            'Zona'=>$request->Zona,
            'Departamento'=>$infoDpto->nombre_loc_zona,
            'ZonaSupervision'=>$request->ZonaSup,
            // 'Latitud'=>$request->Latitud,
            // 'Longitud'=>$request->Longitud,
            'googleMaps'=>$request->googleMaps
        ]);
        return redirect("/getOpcionesOrg")->with('ConfirmarActualizarEdificio','OK');
    }

    public function formularioInstitucion(Request $request){
        //dd($request);
        /*
            "_token" => "mReaB5BQldTPEI0WeHROqVfZbtFnt1PgEXHWigWo"
            "CUE" => "4600874"
            "CUEa" => "460087400"
            "Descripcion" => "Ce.S.S.E.R. SEMILLITA"
            "Telefono" => "123456789"
            "EsPrivada" => "N"
            "Categoria" => "1°"
            "Modalidad" => "Inicial"
            "Jornada" => "Simple"
            "CorreoElectronico" => "semillita@gmail.com"
            "Observaciones" => "observaciones"
            //se agrega el 04 de abril
            Ambito
            Oferta_Tipo
        */

        //manejo a nivel de session para traer el id de la extension
        $institucionExtension=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.idInstitucionExtension',session('idInstitucionExtension'))
                ->get();
        //dd($infoSub[0]->cue_confirmada);

        //valido la primera vez para evitar que me ingresen otro cue
        //tambien aqui pondremos seguimiento
        if($institucionExtension[0]->cue_confirmada == 0){
            $actualizar = InstitucionExtensionModel::where('idInstitucionExtension', session('idInstitucionExtension'))
            ->update([
                'CUE'=>$request->CUE,
                'CUECOMPLETO'=>$request->CUEa,
                'Nombre_Institucion'=>$request->Descripcion,
                'Telefono'=>$request->Telefono,
                'EsPrivada'=>$request->EsPrivada,
                'Categoria'=>$request->Categoria,
                'Nivel'=>$request->Modalidad,
                'Jornada'=>$request->Jornada,
                'Observaciones'=>$request->Observaciones,
                'CorreoElectronico'=>$request->CorreoElectronico,
                'FechaAlta'=>Carbon::now(),
                'cue_confirmada'=>1,
                'Ambito'=>$request->Ambito,
                'Oferta_Tipo'=>$request->Oferta_Tipo
            ]);
            //actualizo las cue por si cambiaron
            session(['CUE'=>$request->CUE]);
            session(['CUEa'=>$request->CUEa]);  //cuecompleto
            session(['UsuarioEmail'=>$request->CorreoElectronico]);
        }else{
            $actualizar = InstitucionExtensionModel::where('idInstitucionExtension', session('idInstitucionExtension'))
            ->update([
                'Nombre_Institucion'=>$request->Descripcion,
                'Telefono'=>$request->Telefono,
                'EsPrivada'=>$request->EsPrivada,
                'Categoria'=>$request->Categoria,
                'Nivel'=>$request->Modalidad,
                'Jornada'=>$request->Jornada,
                'Observaciones'=>$request->Observaciones,
                'CorreoElectronico'=>$request->CorreoElectronico,
                'Ambito'=>$request->Ambito,
                'Oferta_Tipo'=>$request->Oferta_Tipo,
                'FechaAlta'=>Carbon::now()
            ]);

            //antes logeaba con el correo del usuario que era el mismo de la institucion, ahora los colocare diferente, usuario usuario, colegio colegio
            //session(['UsuarioEmail'=>$request->CorreoElectronico]);
        }
        
        
        //actualizo el correo en el usuario - lo desactivo, porque el correo de la escuela no es el mismo del usuario
        //puede o no pero lo dejo anulado
        /*UsuarioModel::where('idUsuario', session('idUsuario'))
        ->update([
            'email'=>$request->CorreoElectronico,
        ]);*/
        return redirect("/getOpcionesOrg")->with('ConfirmarActualizarInstitucion','OK');
    }

    public function formularioDivisiones(Request $request){

        //dd($request);
        /*
            "Descripcion" => "Sala de 3 "A""
            "Curso" => "3"
            "Division" => "1"
            "Turno" => "2"
            "FA" => "2022-11-17"
        */
        //primero voy a borrar todos los datos de una suborg
       
        $Divisiones = new DivisionesModel();
            $Divisiones->Descripcion = $request->Descripcion;
            $Divisiones->Curso = $request->Curso;
            $Divisiones->Division = $request->Division;
            $Divisiones->Turno = $request->Turno;
            $Divisiones->FechaAlta = Carbon::now();
            $Divisiones->idInstitucionExtension = session('idInstitucionExtension');
        $Divisiones->save();

        return redirect("/verDivisiones")->with('ConfirmarActualizarDivisiones','OK');
    }

    public function desvincularDivision($idDivision){
        //verifico si hay o no division activa en nodo
        $hayDivisiones=DB::table('tb_divisiones')
        ->join('tb_nodos', 'tb_nodos.Division', '=', 'tb_divisiones.idDivision')
        ->where('idDivision', $idDivision)
        ->get();

        if(count($hayDivisiones)>0){
            return redirect("/verDivisiones")->with('ConfirmarEliminarDivisionFallida','OK');
        }else{
            DB::table('tb_divisiones')
            ->where('idDivision', $idDivision)
            ->delete();
            return redirect("/verDivisiones")->with('ConfirmarEliminarDivision','OK');
        }
        
        
    }

    public function desvincularEspCur($idEspCur){
        //elimino la carrera seleccionada
        DB::table('tb_espacioscurriculares')
        ->where('idEspacioCurricular', $idEspCur)
        ->delete();
        return redirect("/verAsigEspCur")->with('ConfirmarEliminarEspCur','OK');
    }
    public function formularioCarreras(Request $request){
        //dd($request);
        /*
        "_token" => "cIBNdObN9KAjHSbmpPLyViviCJQPqmsy3S34hSV6"
        "Carrera" => "1"
        */
        //primero voy a borrar todos los datos de una suborg
       
        $carrera = new CarrerasRelSubOrgModel();
        $carrera->idCarrera = $request->Carreras;
        $carrera->idSubOrganizacion = session('idSubOrganizacion');
        $carrera->save();

        return redirect("/getCarrerasPlanes")->with('ConfirmarActualizarCarrera','OK');
    }

    public function desvincularCarrera($idCarreraSubOrg){
        //elimino la carrera seleccionada
        DB::table('tb_carreras_suborg')
            ->where('idCarrera_SubOrg', $idCarreraSubOrg)
            ->delete();
        return redirect("/getCarrerasPlanes")->with('ConfirmarEliminarCarrera','OK');
    }

    public function formularioAsignaturas(Request $request){
        //dd($request);
        /*
        "_token" => "cIBNdObN9KAjHSbmpPLyViviCJQPqmsy3S34hSV6"
        "Carrera" => "1"
        */
        //primero voy a borrar todos los datos de una suborg
       
        $Asignaturas = new AsignaturaModel();
        $Asignaturas->Descripcion = $request->Descripcion;
        $Asignaturas->UsuarioCreador = session('idUsuario');
        $Asignaturas->save();

        return redirect("/verAsigEspCur")->with('ConfirmarActualizarAsignatura','OK');
    }

    public function formularioEspCur(Request $request){
        //dd($request);
        /*
        "_token" => "bFPedfTNpHPrlD3SsqcP81c3wF5prQrG6OnKo2ZU"
        "DescripcionAsignatura" => "prueba historia2"   --
        "Asignatura" => "654"                           --
        "Carrera" => "Carrera Genérica"                 --
        "Planes" => "Plan Genérico"                     --
        "TipoHora" => "2"                               --
        "CantHoras" => "12"                             --
        "RegimenDictado" => "2"                         --
        "TiposDeEspacioCurricular" => "12"              --
        "Observaciones" => null
        */
        //primero voy a borrar todos los datos de una suborg
       
        $Ep = new EspacioCurricularModel();
            $Ep->Descripcion = $request->DescripcionAsignatura;     //
            $Ep->Carrera = 1;// $request->Carrera que es generica;  //
            //$Ep->Curso = $request->CursoDivision;
            $Ep->Tipo = $request->TiposDeEspacioCurricular;         //
            $Ep->Asignatura = $request->Asignatura;                 //
            $Ep->Horas = $request->CantHoras;                       //
            $Ep->PlanEstudio = 1;   //$request->Planes;             //
            $Ep->RegimenDictado = $request->RegimenDictado;         //
            $Ep->TipoHora = $request->TipoHora;  
            $Ep->Observaciones = $request->Observaciones;                    //
            $Ep->CUE = session('CUE');                              //son para toda la escuela
        $Ep->save();

        return redirect("/verAsigEspCur")->with('ConfirmarActualizarEspCur','OK');
    }
    public function formularioEspCurAct(Request $request){
        //dd($request);
        /*
        "_token" => "bFPedfTNpHPrlD3SsqcP81c3wF5prQrG6OnKo2ZU"
        "DescripcionAsignatura" => "prueba historia2"   --
        "Asignatura" => "654"                           --
        "Carrera" => "Carrera Genérica"                 --
        "Planes" => "Plan Genérico"                     --
        "TipoHora" => "2"                               --
        "CantHoras" => "12"                             --
        "RegimenDictado" => "2"                         --
        "TiposDeEspacioCurricular" => "12"              --
        "Observaciones" => null
        */
        //primero voy a borrar todos los datos de una suborg
       
        $Ep = EspacioCurricularModel::where('idEspacioCurricular',$request->iec)->first();
            $Ep->Descripcion = $request->DescripcionAsignatura;     //
            $Ep->Carrera = 1;// $request->Carrera que es generica;  //
            //$Ep->Curso = $request->CursoDivision;
            $Ep->Tipo = $request->TiposDeEspacioCurricular;         //
            $Ep->Asignatura = $request->Asignatura;                 //
            $Ep->Horas = $request->CantHoras;                       //
            $Ep->PlanEstudio = 1;   //$request->Planes;             //
            $Ep->RegimenDictado = $request->RegimenDictado;         //
            $Ep->TipoHora = $request->TipoHora;  
            $Ep->Observaciones = $request->Observaciones;                    //
            $Ep->CUE = session('CUE');                              //son para toda la escuela
        $Ep->save();

        $iec = $request->iec;
        return redirect("/editarEspCur/$iec")->with('ConfirmarActualizarEspCur','OK');
    }
    public function formularioPlanes(Request $request){
        //dd($request);
        /*
        "_token" => "cIBNdObN9KAjHSbmpPLyViviCJQPqmsy3S34hSV6"
        "Carrera" => "1"
        */
        //primero voy a borrar todos los datos de una suborg
       
        $planes = new PlanesRelSubOrgModel();
        $planes->Carrera = $request->Carrera;
        $planes->PlanEstudio = $request->Plan;
        $planes->SubOrg = session('idSubOrganizacion');
        $planes->save();

        return redirect("/getCarrerasPlanes")->with('ConfirmarActualizarPlanes','OK');
        
    }

    public function desvincularPlan($idPlanSubOrg){
        //elimino la carrera seleccionada
        DB::table('tb_pof_relsuborganizacionplanesestudio')
            ->where('idRelSuborganizacionPlan', $idPlanSubOrg)
            ->delete();
        return redirect("/getCarrerasPlanes")->with('ConfirmarEliminarCarrera','OK');
    }
    public function formularioNiveles(Request $request){
        $idSubOrg =session('idSubOrganizacion');
        //dd($request);
        /*
        "_token" => "cIBNdObN9KAjHSbmpPLyViviCJQPqmsy3S34hSV6"
        "r1" => "SI"
        "r2" => "SI"
        "r3" => "NO"
        "r4" => "NO"
        "r5" => "NO"
        "r6" => "NO"
        "r7" => "NO"
        "r8" => "NO"
        "r101" => "NO"
        "r119" => "NO"
        */
        //primero voy a borrar todos los datos de una suborg
        DB::table('tb_niveles_suborg')
            ->where('idSubOrganizacion', $idSubOrg)
            ->delete();
        //ahora los cargo a uno, por ahora uso este metodo simple
        if($request->r1=="SI"){
            $radio = new NivelesEnsenanzaRelSubOrgModel();
            $radio->idNivelEnsenanza = 1;
            $radio->idSubOrganizacion = $idSubOrg;
            $radio->save();
           
        }
        if($request->r2=="SI"){
            $radio = new NivelesEnsenanzaRelSubOrgModel();
            $radio->idNivelEnsenanza = 2;
            $radio->idSubOrganizacion = $idSubOrg;
            $radio->save();
           
        }
        if($request->r3=="SI"){
            $radio = new NivelesEnsenanzaRelSubOrgModel();
            $radio->idNivelEnsenanza = 3;
            $radio->idSubOrganizacion = $idSubOrg;
            $radio->save();
           
        }
        if($request->r4=="SI"){
            $radio = new NivelesEnsenanzaRelSubOrgModel();
            $radio->idNivelEnsenanza = 4;
            $radio->idSubOrganizacion = $idSubOrg;
            $radio->save();
           
        }
        if($request->r5=="SI"){
            $radio = new NivelesEnsenanzaRelSubOrgModel();
            $radio->idNivelEnsenanza = 5;
            $radio->idSubOrganizacion = $idSubOrg;
            $radio->save();
           
        }
        if($request->r6=="SI"){
            $radio = new NivelesEnsenanzaRelSubOrgModel();
            $radio->idNivelEnsenanza = 6;
            $radio->idSubOrganizacion = $idSubOrg;
            $radio->save();
           
        }
        if($request->r7=="SI"){
            $radio = new NivelesEnsenanzaRelSubOrgModel();
            $radio->idNivelEnsenanza = 7;
            $radio->idSubOrganizacion = $idSubOrg;
            $radio->save();
           
        }
        if($request->r8=="SI"){
            $radio = new NivelesEnsenanzaRelSubOrgModel();
            $radio->idNivelEnsenanza = 8;
            $radio->idSubOrganizacion = $idSubOrg;
            $radio->save();
           
        }
        if($request->r101=="SI"){
            $radio = new NivelesEnsenanzaRelSubOrgModel();
            $radio->idNivelEnsenanza = 101;
            $radio->idSubOrganizacion = $idSubOrg;
            $radio->save();
           
        }
        if($request->r119=="SI"){
            $radio = new NivelesEnsenanzaRelSubOrgModel();
            $radio->idNivelEnsenanza = 119;
            $radio->idSubOrganizacion = $idSubOrg;
            $radio->save();
           
        }
        
        return redirect("/getOpcionesOrg")->with('ConfirmarActualizarNiveles','OK');
    }

    public function formularioTurnos(Request $request){
        $idInstitucion =session('idInstitucionExtension');
        //dd($request);
        /*
        "_token" => "cIBNdObN9KAjHSbmpPLyViviCJQPqmsy3S34hSV6"
        "r1" => "SI"
        "r2" => "SI"
        "r3" => "NO"
        "r4" => "NO"
        "r5" => "NO"
        "r6" => "NO"
        "r7" => "NO"
        "r8" => "NO"
        "r101" => "NO"
        "r119" => "NO"
        */
        //primero voy a borrar todos los datos de una suborg
        DB::table('tb_turnos_inst')
            ->where('idInstitucionExtension', $idInstitucion)
            ->delete();
        //ahora los cargo a uno, por ahora uso este metodo simple
        if($request->r1=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 1;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r2=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 2;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r3=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 3;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r4=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 4;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r5=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 5;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r6=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 6;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r7=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 7;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r8=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 8;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r9=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 9;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r10=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 10;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r11=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 11;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r13=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 13;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r15=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 15;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r18=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 18;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r19=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 19;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        if($request->r20=="SI"){
            $radio = new TurnosRelInstModel();
            $radio->idTurno = 20;
            $radio->idInstitucionExtension = $idInstitucion;
            $radio->save();
           
        }
        
        return redirect("/getOpcionesOrg")->with('ConfirmarActualizarTurnos','OK');
    }

    public function formularioLogo(Request $request){
        //dd($request);
        //if ($request->logoimg != "") {
            

            $logoimg = $request->file('logoimg');
            $cuecompleto=session('CUECOMPLETO');    //
            $turno=session('idTurnoUsuario'); 
            //dd($logoimg->getClientOriginalName());
            //guardo en disco para pdfs
            $cueconturno=$cuecompleto.$turno;
            $path2 = $logoimg->storeAs("public/CUE/$cueconturno/", ('logo.'.$logoimg->extension()));

            //inserto la foto en el server
            $idSubOrg =session('idInstitucionExtension');
            $actualizar = InstitucionExtensionModel::where('idInstitucionExtension', session('idInstitucionExtension'))
            ->update([
                'imagen_logo'=>'logo.'.$logoimg->extension(),
            ]);
            return redirect("/getOpcionesOrg")->with('ConfirmarLogoSubido','OK');
        //} else {
            //return redirect("/getOpcionesOrg")->with('ConfirmarLogoNoSubido','OK');
        //}
    }

    public function formularioImgEscuela(Request $request){
        //dd($request);
        //if ($request->logoimg != "") {
            

            $img = $request->file('escuelaimg');
            $cuecompleto=session('CUECOMPLETO');        //ver si usamos cuea
            $turno=session('idTurnoUsuario'); 
            //dd($logoimg->getClientOriginalName());
            //guardo en disco para pdfs
            $cueconturno=$cuecompleto.$turno;
            $path2 = $img->storeAs("public/CUE/$cueconturno/", ('escuela.'.$img->extension()));

            //inserto la foto en el server
            $idSubOrg =session('idInstitucionExtension');
            $actualizar = InstitucionExtensionModel::where('idInstitucionExtension', session('idInstitucionExtension'))
            ->update([
                'imagen_escuela'=>'escuela.'.$img->extension(),
            ]);
            return redirect("/getOpcionesOrg")->with('ConfirmarImagenEscuelaSubido','OK');
        //} else {
            //return redirect("/getOpcionesOrg")->with('ConfirmarLogoNoSubido','OK');
        //}
    }

    //funcion para el control de asistencia en nodo
   public function controlAsistencia(Request $request){
    $nuevaCantidad = $request->input('nuevaCantidad');
   // Log::info('Valor recibido en controlAsistencia: ' . $nuevaCantidad); // Agrega esta línea para verificar el valor recibido

   //busco el nodo y lo actualizo
        $nodo = Nodo::where('idNodo', $request->input('idn'))->first();
            $nodo->CantidadAsistencia = $nuevaCantidad; //aqui aplico asistencia al nodo
        $nodo->save();
    //de paso actualizo en novedades si el nodo esta en servicio alta y le actualizo su cantidad de dias trabajados
    
        $novedad = NovedadesModel::where('Nodo', $request->input('idn'))
        //->where('Agente', $nodo->Agente)
        ->where('CUECOMPLETO', $nodo->CUECOMPLETO)
        ->where('idTurnoUsuario', $nodo->idTurnoUsuario)
        //->where('Motivo','=', 1)    //pregunto si esta activo con ALTA, son los unicos que tendran asistencia
        ->whereNotNull('Nodo') // Verifica si el campo 'Nodo' no es null
        ->first();

        if($novedad){
            $novedad->CantidadDiasTrabajados = $nodo->CantidadAsistencia; //aqui aplico asistencia al nodo
            $novedad->save();
        }else{
            $nuevaCantidad = 0;
        }
            
    return response()->json(['success' => true, 'message' => $nuevaCantidad]);

   }


   public function confirmarPOF(){
    $pofGeneradas = DB::table('tb_pof_generada')
        ->where('idInstitucionExtension',session('idInstitucionExtension'))
        ->orderBy('idPofGenerada','DESC')
        ->get();

        $datos=array(
            'mensajeError'=>"",
            'pofGeneradas'=>$pofGeneradas
            // 'NombreOrg'=>$organizacion[0]->Descripcion,
            // 'CueOrg'=>$organizacion[0]->CUE,
            // 'infoSubOrganizaciones'=>$suborganizaciones,
            // 'idSubOrg'=>$idSubOrg,  //la roto para pasarla a otras ventanas y saber donde volver
            // 'infoPlazas'=>$plazas,
            // 'CargosSalariales'=>$CargosSalariales,
            // 'Asignaturas'=>$Asignaturas,
            // 'tiposDeFuncion'=>$tiposDeFuncion,
        );
        
        //dd($plazas);
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">LEGAJO ÚNICO DE PERSONAL</a></li>
            <li class="breadcrumb-item active"><a href="'.route('confirmarPOF').'">CONFIRMAR POF</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.LUP.confirmarPOF',$datos);
   }

   public function generarPOF(Request $request){
    //dd($request);
   
    //alta de la pof
    $pof = new PofGeneradaModel();
        $pof->idInstitucionExtension = session('idInstitucionExtension');
        $pof->FechaAlta = Carbon::parse(Carbon::now())->format('Y-m-d');

        //genero el pdf y guardarlo en storage
        // Cargar el HTML que quieres convertir en PDF
        $infoNodos=DB::table('tb_nodos')
        ->where('CUECOMPLETO',session('CUECOMPLETO'))
        ->where('idTurnoUsuario',session('idTurnoUsuario'))
        ->orderBy('tb_nodos.idNodo','ASC')
        ->get();

            //dd($Novedades);
         $datos=array(
             'mensajeError'=>"",
             'infoNodos'=>$infoNodos,
             'FechaActual'=>$FechaAlta = Carbon::parse(Carbon::now())->format('d-m-Y'),
             'mensajeNAV'=>'Panel de Novedades - POF'
 
 
         );
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

        // Generar el nombre del archivo en MD5 sin la extensión
        $md5Name = md5(session('CUECOMPLETO') . date('Y-m-d H:i:s'));
            
        // Concatenar el nombre MD5 con la extensión original
        $newFileName = $md5Name . '.pdf';

        // Guardar el PDF en la carpeta de almacenamiento
        $pdf_path = storage_path('app/public/POF/') . $newFileName; // Ruta donde se guardará el archivo PDF

        // Escribir el contenido del PDF en el archivo
        file_put_contents($pdf_path, $pdf_content);

        // Guardar la URL del PDF en la base de datos
        $pof->URL = $newFileName; // URL relativa al archivo guardado

    $pof->save();
    
    //una vez terminado el proceso de pof, setea sus asistencias a cero
    $agentesEnNodo = DB::table('tb_nodos')
    ->where('tb_nodos.CUECOMPLETO',session('CUECOMPLETO'))
    ->where('tb_nodos.idTurnoUsuario',session('idTurnoUsuario'))
    ->update(['CantidadAsistencia' => 0]);

    // Devolver el PDF al navegador para descargar
    return response($pdf_content, 200)
    ->header('Content-Type', 'application/pdf')
    ->header('Content-Disposition', 'attachment; filename="Novedades.pdf"');
    }




    public function lista_de_agentes_inst(){
        /*
        $ListaAgentes = DB::table('tb_agentes')
        ->join('tb_nodos','tb_nodos.Agente','=','tb_agentes.Documento')
        ->where('tb_nodos.CUECOMPLETO',session('CUECOMPLETO'))
        ->where('tb_nodos.idTurnoUsuario',session('idTurnoUsuario'))
        ->select('tb_agentes.*')
        ->distinct()
        ->get();*/
        /*
        $ListaAgentes = DB::table('tb_agentes')
        ->join('tb_institucion_rel_agente','tb_institucion_rel_agente.idAgente','tb_agentes.idAgente')
        ->join('tb_institucion_extension','tb_institucion_extension.idInstitucionExtension','=','tb_institucion_rel_agente.idInstitucionExtension')
        */
        $ListaAgentes = DB::connection('DB7') // Conexión a la base de datos pofmh
        ->table('tb_pofmh') // Seleccionar la tabla tb_pofmh desde DB7
        ->join(DB::connection('mysql')->getDatabaseName().'.tb_agentes', 'tb_agentes.Documento', '=', 'tb_pofmh.Agente') // Unir con tb_agentes desde sage2
        ->where('tb_pofmh.CUECOMPLETO', session('CUECOMPLETO'))
        ->select('tb_agentes.*')
        //->limit(10)
        ->distinct()
        ->get();

        //dd($ListaAgentes);
         //dd($RelSubOrgAgente);
         $datos=array(
             'mensajeError'=>"",
             'ListaAgentes' => $ListaAgentes,
             'mensajeNAV'=>'Panel de Configuración de Agentes / No Agentes',
         );
         //dd($infoPlaza);
         $ruta ='
         <li class="breadcrumb-item active"><a href="#">LEGAJO ÚNICO DE PERSONAL</a></li>
         <li class="breadcrumb-item active"><a href="'.route('lista_de_agentes_inst').'">Agentes en La Institución</a></li>
         '; 
         session(['ruta' => $ruta]);
         return view('bandeja.LUP.lista_agentes_inst',$datos);
    }


    public function editarAgente($idAgente){
        //extras a enviar
        $TiposDeDocumentos = DB::table('tb_tiposdedocumento')->get();
        $TiposDeAgentes = DB::table('tb_tiposdeagente')->get();
        $Sexos = DB::table('tb_sexo')->get();
        $EstadosCiviles = DB::table('tb_estadosciviles')->get();
        $Nacionalidades = DB::table('tb_nacionalidad')->get();
        $TurnosUsuario = DB::table('tb_turnos_usuario')->get();

        $Localidades = DB::table('tb_localidades') ->orderBy('tb_localidades.localidad','ASC')->get();
        $Departamentos = DB::table('tb_departamentos') ->orderBy('tb_departamentos.nombre_dpto','ASC')->get();
        $Provincias = DB::table('tb_provincias')->get();

        $Agente = DB::table('tb_agentes')
        ->where('tb_agentes.idAgente',$idAgente) //es and
        ->first();
        //dd($RelSubOrgAgente);
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Creación de Usuarios',
            'Agente'=>$Agente,
            'Sexos'=>$Sexos,
            'Localidades'=>$Localidades,
            'Departamentos'=>$Departamentos,
            'Provincias'=>$Provincias
            
        );
        //dd($infoPlaza);
        return view('bandeja.LUP.editar_agente',$datos);
    }


    public function traerLocalidades($idDepto){
        //traigo las relaciones Suborg->planes->carrera
        $Localidades = DB::table('tb_localidades')
        ->where('tb_localidades.Departamento',$idDepto)
        ->orderBy('tb_localidades.localidad','ASC')
        ->get();

        return response()->json(array('status' => 200, 'msg' => $Localidades), 200);
    }

    
    public function FormActualizarAgente_ind(Request $request){
        //dd($request);
        /*
                "_token" => "oeE2YYcg3uCw5a1RQHlHswWe8xrI10edNeZwTWmH"
            "Agente" => "LOYOLA, LEO"
            "DNI" => "26731952"                 
            "CUIL" => "23267319529"
            "Sexo" => "M"
            "Barrio" => "Parque Sud"
            "Calle" => "Las Heras"
            "NumCasa" => "1586"
            --piso y numero_piso
            "Provincia" => "100"
            "Departamento" => "1"
            "Localidad" => "38"
            "ag" => "10122"
        */
        //una vez modificado los datos bases del agente
        $Agente = AgenteRespaldoModel::where('idAgente',$request->ag)->first();
            $Agente->ApeNom = $request->Agente;
            //$Agente->Documento = $request->DNI;                       //por ahora no lo dejare modificar
            $Agente->Cuil = $request->CUIL; 
            $Agente->Sexo = $request->Sexo;
            $Agente->Barrio = $request->Barrio;
            $Agente->Calle = $request->Calle;
            $Agente->Numero_Casa = $request->NumCasa;
            $Agente->Piso = $request->Piso;
            $Agente->Numero_Dpto = $request->numero_piso;
            $Agente->Localidad = $request->Localidad;
        $Agente->save();

        //debo modificar en su copia que es el desglose
        //$agecop = AgenteModel::where('docu',$request->DNI)->get();
        /*
            if($agecop){
                foreach($agecop as $ag){
                    //$ag = AgenteModel::where('idDesgloseAgente',$agecop->idDesgloseAgente)->first();
                        $ag->nomb = $request->Agente;            //por ahora no lo dejare modificar
                        $ag->cuil = $request->CUIL; 
                    $ag->save();
                }
            }
        */
            $idAg = $request->ag;       
            return redirect("/editarAgente/$idAg")->with('ConfirmarActualizarAgente','OK');
        
    }

    public function formularioInsertarEspCur(Request $request){
        //dd($request);
        /*
                "_token" => "oeE2YYcg3uCw5a1RQHlHswWe8xrI10edNeZwTWmH"
           dos datos idnodo y id espcur
        */
        //una vez modificado los datos bases del agente
        $relEspCur = new RelNodoEspCur();
            $relEspCur->idNodo = $request->idNodoEspCur;
            $relEspCur->idEspacioCurricular =$request->idEspCur;
        $relEspCur->save();

            $idNodo = $request->idNodoEspCur;
            return redirect("/ActualizarNodoAgente/$idNodo")->with('ConfirmarActualizarAgente','OK');
    }


    public function borrarRelNodoEspCur($idRelNodoEspCur,$idNodo){
         //elimino la carrera seleccionada
         DB::table('tb_rel_nodo_espcur')
         ->where('idRelNodoEspCur', $idRelNodoEspCur)
         ->delete();

         return redirect("/ActualizarNodoAgente/$idNodo")->with('ConfirmarEliminarEspCur','OK');
    }



    public function confirmarPofOriginal()
    {
        // Recuperar el registro usando Eloquent
        $institucionExtension = InstitucionExtensionModel::where('idInstitucionExtension', session('idInstitucionExtension'))->first();
    
        if ($institucionExtension) {
            $CUE = $institucionExtension->CUE;
    
            // Actualizar todas las instituciones que tienen el mismo CUE
            InstitucionExtensionModel::where('CUE', $CUE)->update(['PermiteEditarTodo' => 1]);
    
            // Redireccionar
            return redirect("/verCargosCreados/" . session('idInstitucionExtension'));
        }
    
        // En caso de no encontrar el registro, redirigir con un mensaje de error
        return redirect()->back()->withErrors('Institución no encontrada.');
    }
    
    












}
