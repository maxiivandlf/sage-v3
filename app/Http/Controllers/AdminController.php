<?php

namespace App\Http\Controllers;

use App\Models\InstitucionExtensionModel;
use App\Models\LogsModel;
use App\Models\POFMH\PofmhModel;
use App\Models\POFMH\PofmhNovedades;
use App\Models\RelPofUsuariosModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\UsuarioModel;
use APP\Models\ReparticionModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
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
    public function nuevoUsuario(){
        //extras a enviar
        $TiposDeDocumentos = DB::table('tb_tiposdedocumento')->get();
        $TiposDeAgentes = DB::table('tb_tiposdeagente')->get();
        $Sexos = DB::table('tb_sexo')->get();
        $EstadosCiviles = DB::table('tb_estadosciviles')->get();
        $Nacionalidades = DB::table('tb_nacionalidad')->get();
        $TurnosUsuario = DB::table('tb_turnos_usuario')->get();
        $Modos = DB::table('tb_modos')->get();
        //se agrego el 18 abril
        /*$RelSubOrgAgente = DB::table('tb_suborg_agente')
        ->join('tb_agentes', 'tb_agentes.idAgente', '=', 'tb_suborg_agente.idAgente')
        ->join('tb_tiposdeagente', 'tb_tiposdeagente.idTipoAgente', '=', 'tb_agentes.TipoAgente')
        ->join('tb_suborganizaciones', 'tb_suborganizaciones.idSubOrganizacion', '=', 'tb_suborg_agente.idSubOrg')
        ->where('tb_suborg_agente.idSubOrg', session('idSubOrganizacion'))
        ->select(
            'tb_agentes.*',
            'tb_suborganizaciones.*',
            'tb_tiposdeagente.*',
            'tb_suborg_agente.*'
        )
        ->get();*/

        //dd($RelSubOrgAgente);
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Creación de Usuarios',
            'TurnosUsuario'=>$TurnosUsuario,
            'Modos'=>$Modos
            //'RelSubOrgAgente'=>$RelSubOrgAgente
        );
        //dd($infoPlaza);
        return view('bandeja.ADMIN.nuevo_usuario',$datos);
    }

    public function editarUsuario($idUsuario){
        //extras a enviar
        $TiposDeDocumentos = DB::table('tb_tiposdedocumento')->get();
        $TiposDeAgentes = DB::table('tb_tiposdeagente')->get();
        $Sexos = DB::table('tb_sexo')->get();
        $EstadosCiviles = DB::table('tb_estadosciviles')->get();
        $Nacionalidades = DB::table('tb_nacionalidad')->get();
        $TurnosUsuario = DB::table('tb_turnos_usuario')->get();
        $Modos = DB::table('tb_modos')->get();

        $Usuario = DB::table('tb_usuarios')
       // ->where('tb_usuarios.Modo','!=',2)
        ->join('tb_modos','tb_modos.idModo', '=', 'tb_usuarios.Modo')
        ->where('tb_usuarios.idusuario',$idUsuario) //es and
        ->get();
        //dd($RelSubOrgAgente);
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Creación de Usuarios',
            'Usuario'=>$Usuario,
            'Modos'=>$Modos,
            'TurnosUsuario'=>$TurnosUsuario,
            
        );
        //dd($infoPlaza);
        return view('bandeja.ADMIN.editar_usuario',$datos);
    }
    public function FormNuevoUsuario(Request $request){
             
        //voy a omitir por ahora la comprobacion de agentes por DNI
        $consultarEmail = DB::table('tb_usuarios')
        ->where('email',$request->Correo)
        ->get();

          $cantidadEncontrados=count($consultarEmail);
          //dd($cantidadEncontrados);
          if($cantidadEncontrados == 0){ 
            //dd($request);
                /*
                "_token" => "1EehVZtq97RHiL5w8cuPeD92FS4uvhY7LUarbVnP"
                "Apellido" => "Loyola"
                "Nombre" => "Leo Martin"
                "Activo" => "S"
                "Usuario" => "2"
                "Clave" => "2"
                "Correo" => "admin@admin.com"
                "Turno" => "1"
                se agrego modo el 16 de abril
                */
       
              $o = new UsuarioModel();
                $o->Nombre = strtoupper($request->Apellido)." ".strtoupper($request->Nombre);
                $o->Clave = $request->Clave;
                $o->Usuario = $request->Usuario;
                $o->Activo = $request->Activo;
                $o->email = $request->Correo;
                $o->idReparticion = 1;
                $o->Nivel = 119;
                //$o->Modo = 3;     //3 es menos que admin, 2 es para las escuelas  y 1 para admin
                $o->Dependencia = 1;
                $o->Turno = $request->Turno;
                $o->Modo = $request->TipoRol; //ahora activo y creo con un rol especifico
                $o->avatar="img_profile.svg";
              $o->save();
          
          return redirect("/nuevoUsuario")->with('ConfirmarNuevoUsuario','OK');
         //LuiController::PlazaNueva($request->idSurOrg);
        }else{
          return redirect("/nuevoUsuario")->with('ConfirmarNuevoUsuarioError','OK');
        }
      

    }

    public function FormNuevoUsuario_CUE(Request $request){
        //controlo si existen los datos
        $Usuario = DB::table('tb_usuarios')
        ->where('tb_usuarios.CUECOMPLETO',$request->CUE.$request->CUEa)
        ->where('tb_usuarios.Turno',$request->Turno) //es and
        ->get();

        //dd($Usuario);
        //voy a omitir por ahora la comprobacion de agentes por DNI

        
        //dd($request);
        /*
        "_token" => "G5AVlsqRW6m7v9FRCDo5mrKgYy6o5Fef3Oh5XhPY"
      "Nombre" => "Leo Loyola"              listo
      "Activo" => "S"                       listo
      "Usuario" => "Jardin Semillita"       listo
      "Clave" => "semillita"                listo
      "Correo" => "semillita@gmail.com"     listo
      "CUE" => "4600233"
      se agrego CUEa como cue con extension 14-02-24 y turno
        */
        
       if(count($Usuario)==0){
          $o = new UsuarioModel();
          $o->Nombre = strtoupper($request->Nombre);
          $o->Clave = $request->Clave;
          $o->Usuario = $request->Usuario;
          $o->Activo = $request->Activo;
          $o->Email = $request->Correo;
          $o->idReparticion = 1;
          $o->Nivel = 119;
          $o->Modo = 2;     //3 es menos que admin, 2 es para las escuelas  y 1 para admin
          $o->Dependencia = 1;
          $o->CUE = $request->CUE;
          $o->Turno = $request->Turno;
          $o->CUEa = $request->CUEa;
          $o->CUECOMPLETO = $request->CUE.$request->CUEa;
          $o->avatar="img_profile.svg";
        $o->save();
    
          //aprovecho y traigo la info de la institucion a copiar, pero solo 1 registro, asi evito la duplicidad de datos
        $institucion=DB::table('tb_institucion')
          ->where('tb_institucion.CUE',$request->CUE)
          ->first();  
    
        //creo una copia en la institucion extension
        /*
        +"idInstitucion": 85    ----------
          +"InstitucionNumeroProvisorio": "ECS"
          +"Unidad_Liquidacion": "ECS"                                  //no usado en extension
          +"Nivel": "Inicial"     ----------
          +"Categoria": "1°"      -----------
          +"CUE": "4600874"     ---------
          +"CUECOMPLETO": "460087400"   ----------
          +"Nombre_Institucion": "Ce.S.S.E.R. SEMILLITA"
          +"Domicilio_Institucion": "RUTA N° 5 - KM 10 - LAS PARCELAS"
          +"Turno": "M-T"
          +"Localidad": "LA RIOJA"
          +"Porcentaje_Zona": "40"
          +"Zona": "B"
          +"F12": null
          +"F11": null
          +"Estado": "0"
          +"TipoInstitucion": "J"
          +"cue_confirmada": 1
          +"Telefono": "380466666"
          +"EsPrivada": "N"
          +"Jornada": "Simple"
          +"Observaciones": "ninguna prueba"
          +"CorreoElectronico": "semillita@gmail.com"
          +"FechaAlta": "2024-02-14 04:09:56"
          +"created_at": null
          +"updated_at": "2024-02-14 04:23:46"
          +"Latitud": "124442"
          +"Longitud": "12122"
          +"imagen_escuela": "escuela.jpg"
          +"imagen_logo": "logo.png"
        */
        $ie = new InstitucionExtensionModel();
          $ie->idInstitucion = $institucion->idInstitucion;
          $ie->Nivel = $institucion->Nivel;
          $ie->Categoria = $institucion->Categoria;
          $ie->CUE = $institucion->CUE;
          $ie->CUECOMPLETO = $request->CUE.$request->CUEa;
          $ie->Nombre_Institucion = $institucion->Nombre_Institucion;
          $ie->Domicilio_Institucion = $institucion->Domicilio_Institucion;
          $ie->Turno = $institucion->Turno;
          $ie->Localidad = $institucion->Localidad;
          $ie->Porcentaje_Zona = $institucion->Porcentaje_Zona;
          $ie->F12 = $institucion->F12;
          $ie->F11 = $institucion->F11;
          $ie->Estado = $institucion->Estado;
          $ie->TipoInstitucion = $institucion->TipoInstitucion;
          $ie->cue_confirmada = $institucion->cue_confirmada;
          $ie->Telefono = $institucion->Telefono;
          $ie->EsPrivada = $institucion->EsPrivada;
          $ie->Jornada = $institucion->Jornada;
          $ie->Observaciones = $institucion->Observaciones;
          $ie->FechaAlta = $institucion->FechaAlta;
          $ie->Latitud = $institucion->Latitud;
          $ie->Longitud = $institucion->Longitud;
          $ie->imagen_escuela = $institucion->imagen_escuela;
          $ie->imagen_logo = $institucion->imagen_logo;
          $ie->idTurnoUsuario = $request->Turno;
          $ie->CUEa = $request->CUEa;
          $ie->PermiteBorrarTodo = 0;     //se agrego para poder usar el borrar todo de pof y novedades
        $ie->save();

        $CUE=$request->CUE;
          //cargarInfoUsuario/4600233
        return redirect("/cargarInfoUsuario/$CUE/")->with('ConfirmarNuevoUsuario','OK');
      }else{
        $CUE=$request->CUE;
        //cargarInfoUsuario/4600233
        return redirect("/cargarInfoUsuario/$CUE/")->with('ConfirmarNuevoUsuarioError','OK');
       }
        
         //LuiController::PlazaNueva($request->idSurOrg);

    }
    public function usuariosLista(){
        //extras a enviar
        $Usuarios = DB::table('tb_usuarios')
        ->join('tb_modos','tb_modos.idModo', '=', 'tb_usuarios.Modo')
        ->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','tb_usuarios.Turno')
        //->wherein('tb_usuarios.Modo',[1,3]) voy a traer todas los usuarios
        //->whereIn('tb_novedades.Motivo', [4, 6, 7])
        ->select('tb_usuarios.*','tb_modos.Descripcion as Modo','tb_turnos_usuario.Descripcion as Turno') 
        ->get();
        $Modos = DB::table('tb_modos')->get();
        //dd($RelSubOrgAgente);
        $datos=array(
            'mensajeError'=>"",
            'UsuariosLista'=>$Usuarios,
            'Modos'=>$Modos,
            'mensajeNAV'=>'Panel de Configuración de Usuarios',
        );
        //dd($infoPlaza);
        return view('bandeja.ADMIN.usuariosLista',$datos);
    }
    public function usuariosListaTec(){
      //extras a enviar
      $Usuarios = DB::table('tb_usuarios')
      ->join('tb_modos','tb_modos.idModo', '=', 'tb_usuarios.Modo')
      ->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','tb_usuarios.Turno')
      ->whereBetween('tb_usuarios.Modo', [2, 41])
      //->whereIn('tb_novedades.Motivo', [4, 6, 7]) 
      ->get();
      $Modos = DB::table('tb_modos')->get();
      //dd($RelSubOrgAgente);
      $datos=array(
          'mensajeError'=>"",
          'UsuariosLista'=>$Usuarios,
          'Modos'=>$Modos,
          'mensajeNAV'=>'Panel de Configuración de Usuarios',
      );
      //dd($infoPlaza);
      return view('bandeja.ADMIN.usuariosLista',$datos);
  }
    public function reiniciarCUE(){
      //extras a enviar
      $Instituciones = DB::table('tb_institucion_extension')
      ->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','=','tb_institucion_extension.idTurnoUsuario')
      ->select(
        'tb_institucion_extension.*',
        'tb_turnos_usuario.idTurnoUsuario',
        'tb_turnos_usuario.Descripcion as TurnoEscuela',
        )
      ->get();
      $Turnos = DB::table('tb_turnos_usuario')->get();
      //dd($RelSubOrgAgente);
      $datos=array(
          'mensajeError'=>"",
          'Instituciones'=>$Instituciones,
          'Turnos'=>$Turnos,
          'mensajeNAV'=>'Panel de Configuración de Instituciones',
      );
      //dd($infoPlaza);
      return view('bandeja.ADMIN.reiniciarCUE',$datos);
  }

  public function resetPof(){
    //extras a enviar
    $Instituciones = DB::table('tb_institucion_extension')
    ->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','=','tb_institucion_extension.idTurnoUsuario')
    ->select(
      'tb_institucion_extension.*',
      'tb_turnos_usuario.idTurnoUsuario',
      'tb_turnos_usuario.Descripcion as TurnoEscuela',
      )
    ->get();
    $Turnos = DB::table('tb_turnos_usuario')->get();
    //dd($RelSubOrgAgente);
    $datos=array(
        'mensajeError'=>"",
        'Instituciones'=>$Instituciones,
        'Turnos'=>$Turnos,
        'mensajeNAV'=>'Panel de Configuración de Instituciones',
    );
    //dd($infoPlaza);
    return view('bandeja.ADMIN.resetPof',$datos);
}

    public function FormActualizarUsuario(Request $request){
        //voy a omitir por ahora la comprobacion de agentes por DNI

        
        //dd($request);
        /*
       "_token" => "cCCSqEM9WUgc0Homrv4kAJgvZe9MpQCyJuMh7ure"
      "Apellido" => "loyola"            listo
      "Nombre" => "leo"                 listo
      "Activo" => "S"                   listo
      "Usuario" => "Leo Loyola"         listo
      "Clave" => "123"                  listo
      "Correo" => "djmov@gmail.com"     listo
      Turno     se agrego el 2802/24
      modo se aplico el 17 de abril 2024
        */
        
        $o = UsuarioModel::where('idUsuario', $request->us)->first();

        //proceso de guardar datos de respaldo
        $turnoViejo=$o->Turno;

          $o->Nombre = strtoupper($request->Nombre);
          $o->Clave = $request->Clave;
          $o->Usuario = $request->Usuario;
          $o->Activo = $request->Activo;
          $o->Email = $request->Correo;
          $o->Turno = $request->Turno;
          $o->Modo = $request->TipoRol;
        $o->save();

        session(['idTurnoUsuario'=>$request->Turno]); //actualizo su turno

        if($o->CUECOMPLETO != "" || $o->CUECOMPLETO != null){
            //traigo su institucion asociada para cambiar el turno
            $institucion = InstitucionExtensionModel::where('CUECOMPLETO',$o->CUECOMPLETO)
              ->where('idTurnoUsuario',$turnoViejo)
              ->first();
              $institucion->idTurnoUsuario = $request->Turno;
            $institucion->save();

            //busco a sus pofmh

          // Actualizar todos los registros de PofmhModel con el CUECOMPLETO y Turno anterior
          PofmhModel::where('CUECOMPLETO', $o->CUECOMPLETO)
              ->where('Turno', $turnoViejo)
              ->update(['Turno' => $request->Turno]);

          // Actualizar todos los registros de PofmhNovedades de la misma manera
          PofmhNovedades::where('CUECOMPLETO', $o->CUECOMPLETO)
              ->where('Turno', $turnoViejo)
              ->update(['Turno' => $request->Turno]);
        }


        $idUs=$request->us;
         return redirect("/editarUsuario/$idUs")->with('ConfirmarActualizarUsuario','OK');
         //LuiController::PlazaNueva($request->idSurOrg);

    }

    public function agregarCUEUsuario($idUsuario){
      //extras a enviar
      $TiposDeDocumentos = DB::table('tb_tiposdedocumento')->get();
      $TiposDeAgentes = DB::table('tb_tiposdeagente')->get();
      $Sexos = DB::table('tb_sexo')->get();
      $EstadosCiviles = DB::table('tb_estadosciviles')->get();
      $Nacionalidades = DB::table('tb_nacionalidad')->get();
      $TurnosUsuario = DB::table('tb_turnos_usuario')->get();
      $EstadoPOF = DB::table('tb_estado_pof')->get();
      $Usuario = DB::table('tb_usuarios')
      ->where('tb_usuarios.Modo','!=',2)
      ->where('tb_usuarios.idusuario',$idUsuario) //es and
      ->get();

      $infoCUEAgregadas = DB::table('tb_rel_admines_instituciones_extensiones')
      ->where('tb_rel_admines_instituciones_extensiones.idUsuario',$idUsuario)
      ->get();
      //dd($RelSubOrgAgente);
      $datos=array(
          'mensajeError'=>"",
          'mensajeNAV'=>'Panel de Creación de Usuarios',
          'Usuario'=>$Usuario,
          'EstadoPOF'=>$EstadoPOF,
          'infoCUEAgregada'=> $infoCUEAgregadas
      );
      //dd($infoPlaza);
      return view('bandeja.ADMIN.cue_usuario',$datos);
  }

  public function FormInsertarCUE(Request $request){
    //voy a omitir por ahora la comprobacion de agentes por DNI

    
    //dd($request);
    /*
      "_token" => "JvWbtJzdVXNP9d93TmF1KiWytXg1Y0TNziBhQ2vD"
        "CUECOMPLETO" => "4600614"
        "CantidadPersonas" => "35"
        "usuario" => "10"
    */
    
    $o = new RelPofUsuariosModel();
      $o->CUECOMPLETO = $request->CUECOMPLETO;
      $o->idUsuario = $request->usuario;
      $o->CantidadSubidos = $request->CantidadPersonas;
      $o->EstadoPOF = 1;
      $o->FechaInicio = Carbon::parse(Carbon::now())->format('Y-m-d');
    $o->save();
    
    $idUs=$request->usuario;
     return redirect("/agregarCUEUsuario/$idUs")->with('ConfirmarCUEusuario','OK');
     //LuiController::PlazaNueva($request->idSurOrg);

}


  public function ver_lista_agentes(){
    $infoNodos = DB::table('tb_nodos')
      ->whereNotNull('tb_nodos.Agente')
      //->limit(10)
      ->get();

        //dd($infoAgentes);
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Creación de Usuarios',
            'infoNodos'=>$infoNodos,
        );
        //dd($infoPlaza);
        return view('bandeja.ADMIN.ver_lista_agentes',$datos);
  }

  public function escuelasCargadas(){
            //extras a enviar
            $escuelas = InstitucionExtensionModel::whereNull('Nivel')
            ->orWhere('Nivel', '')
            ->orWhereNull('Categoria')
            ->orWhere('Categoria', '')
            ->orWhereNull('Nombre_Institucion')
            ->orWhere('Nombre_Institucion', '')
            ->orWhereNull('Localidad')
            ->orWhere('Localidad', '')
            ->orWhereNull('Zona')
            ->orWhere('Zona', '')
            ->orWhereNull('ZonaSupervision')
            ->orWhere('ZonaSupervision', '')
            ->orWhereNull('Jornada')
            ->orWhere('Jornada', '')
            ->orWhereNull('Ambito')
            ->orWhere('Ambito', '')
            ->orWhereNull('Departamento')
            ->orWhere('Departamento', '')
            ->leftJoin('tb_ambitos', 'tb_ambitos.idAmbito', '=', 'tb_institucion_extension.Ambito')
            ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
            ->select('tb_institucion_extension.*','tb_ambitos.*','tb_turnos_usuario.*')
            ->get();

           
            $datos=array(
                'mensajeError'=>"",
                'Escuelas'=>$escuelas,
                
                'mensajeNAV'=>'Panel de Configuración de Usuarios',
            );
            //dd($infoPlaza);
            return view('bandeja.ADMIN.escuelasCargadas',$datos);
  }

  public function asignarCUETecnico(){
           //extras a enviar
          $escuelasSinUsuario = DB::table('tb_institucion_extension')
          ->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','tb_institucion_extension.idTurnoUsuario')
          ->whereNull('idTecnicoSage')
          ->get();

          $escuelasConUsuario = DB::table('tb_institucion_extension')
          ->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','tb_institucion_extension.idTurnoUsuario')
          ->whereNotNull('idTecnicoSage')
          ->get();

          $tecnicos = DB::table('tb_usuarios')->where('Modo',3)->get();
          
           $datos=array(
               'mensajeError'=>"",
               'EscuelasS'=>$escuelasSinUsuario,
               'EscuelasC'=>$escuelasConUsuario,
               'Tecnicos'=>$tecnicos,
               'mensajeNAV'=>'Panel de Configuración de Usuarios',
           );
           //dd($infoPlaza);
           return view('bandeja.ADMIN.asignarCUETecnico',$datos);
  }

  public function formAsignarTecnico(Request $request){
    //dd($request);
    /*
    "_token" => "qeBy2RY9qs4UKV9kq3uuFqxcKam6CTUqBwY28sfN"
      "usuario" => "15"
      "institucion" => "1"
    */
    $institucion = InstitucionExtensionModel::where('idInstitucionExtension',$request->institucion)->first();
      $institucion->idTecnicoSage = $request->usuario;
    $institucion->save();

    return redirect()->back()->with('ConfirmarUbicacionUsuario','OK');

  }

  public function FormQuitarAsignacion(Request $request){
    //dd($request);
    /*
    "_token" => "qeBy2RY9qs4UKV9kq3uuFqxcKam6CTUqBwY28sfN"
      "usuario" => "15"
      "institucion" => "1"
    */
    $institucion = InstitucionExtensionModel::where('idInstitucionExtension',$request->institucion)->first();
      $institucion->idTecnicoSage = NULL;
    $institucion->save();

    return redirect()->back()->with('ConfirmarUbicacionQuitarUsuario','OK');

  }

  public function escuelasCargadasTecnico(){
    //extras a enviar
    
    $escuelas = DB::table('tb_institucion_extension')
    ->where('idTecnicoSage',session('idUsuario'))
    ->leftJoin('tb_ambitos', 'tb_ambitos.idAmbito', '=', 'tb_institucion_extension.Ambito')
    ->leftJoin('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
    ->select('tb_institucion_extension.*','tb_ambitos.*','tb_turnos_usuario.*')
    ->get();

    $niveles = DB::table('tb_nivelesensenanza')->get();
    $categorias = DB::table('tb_categorias')->get();
    $localidades = DB::table('tb_localidades')->get();
    $departamentos = DB::table('tb_departamentos')->get();
    $zonas = DB::table('tb_zonas_liq')->get();
    $zonasSup = DB::table('tb_zonasupervision')->get();
    $jornadas = DB::table('tb_jornadas')->get();
    $ambitos = DB::table('tb_ambitos')->get();
    $datos=array(
        'mensajeError'=>"",
        'Escuelas'=>$escuelas,
        'Niveles'=>$niveles,
        'Categorias'=>$categorias,
        'Localidades'=>$localidades,
        'Departamentos'=>$departamentos,
        'Zonas'=>$zonas,
        'ZonasSup'=>$zonasSup,
        'Jornadas'=>$jornadas,
        'Ambitos'=>$ambitos,
        'mensajeNAV'=>'Panel de Configuración de Usuarios',
    );
    //dd($infoPlaza);
    return view('bandeja.ADMIN.escuelasCargadasTecnico',$datos);
}


public function formActualizarEscTec(Request $request){
  //dd($request);
  /*
  // Extraer los datos del objeto Request
    $idInstitucionExtension = $request->input('idInstitucionExtension');
    $nombreInstitucion = $request->input('Nombre_Institucion'); ---------
    $nivel = $request->input('Nivel'); ---------------
    $categoria = $request->input('Categoria'); ------------
    $localidad = $request->input('Localidad');
    $departamento = $request->input('Departamento');
    $zona = $request->input('Zona');
    $zonaSupervision = $request->input('ZonaSupervision');
    $jornada = $request->input('Jornada');
    $ambito = $request->input('Ambito');
  */
 $inst = InstitucionExtensionModel::where('idInstitucionExtension',$request->input('idInstitucionExtension'))->first();
    $inst->Nombre_Institucion = $request->input('Nombre_Institucion');
    $inst->Nivel = $request->input('Nivel');
    $inst->Categoria = $request->input('Categoria');
    $inst->Localidad = $request->input('Localidad'); 
    $inst->Departamento = $request->input('Departamento');
    $inst->Zona = $request->input('Zona');
    $inst->ZonaSupervision = $request->input('ZonaSupervision');
    $inst->Jornada = $request->input('Jornada');
    $inst->Ambito = $request->input('Ambito');
  $inst->save();

  return response()->json(array('status' => 200, 'msg' => $request->input('idInstitucionExtension')), 200);
}


public function escuelasCargadasIncompletasTec(){
  //extras a enviar
  $escuelas = InstitucionExtensionModel::whereNull('Nivel')
  ->orWhere('Nivel', '')
  ->orWhereNull('Categoria')
  ->orWhere('Categoria', '')
  ->orWhereNull('Nombre_Institucion')
  ->orWhere('Nombre_Institucion', '')
  ->orWhereNull('Localidad')
  ->orWhere('Localidad', '')
  ->orWhereNull('Zona')
  ->orWhere('Zona', '')
  ->orWhereNull('ZonaSupervision')
  ->orWhere('ZonaSupervision', '')
  ->orWhereNull('Jornada')
  ->orWhere('Jornada', '')
  ->orWhereNull('Ambito')
  ->orWhere('Ambito', '')
  ->orWhereNull('Departamento')
  ->orWhere('Departamento', '')
  ->leftJoin('tb_ambitos', 'tb_ambitos.idAmbito', '=', 'tb_institucion_extension.Ambito')
  ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
  ->select('tb_institucion_extension.*','tb_ambitos.*','tb_turnos_usuario.*')
  ->get();

 
  $datos=array(
      'mensajeError'=>"",
      'Escuelas'=>$escuelas,
      
      'mensajeNAV'=>'Panel de Configuración de Usuarios',
  );
  //dd($infoPlaza);
  return view('bandeja.ADMIN.escuelasCargadasTecIn',$datos);
}

  public function logs(){
    set_time_limit(0);
    ini_set('memory_limit', '2028M');
      $logs = DB::table('tb_logs')
      ->join('tb_usuarios', 'tb_usuarios.idUsuario', '=', 'tb_logs.idUsuario')
      ->join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_usuarios.Turno')
      ->join('tb_modos', 'tb_modos.idModo', '=', 'tb_usuarios.Modo')
      ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', '=', 'tb_usuarios.CUECOMPLETO')
      ->select(
        'tb_logs.*',
        'tb_usuarios.idUsuario as UsuarioInfo',
        'tb_usuarios.Nombre',
        'tb_usuarios.email',
        'tb_institucion_extension.CUECOMPLETO',
        'tb_institucion_extension.Nombre_Institucion',
        'tb_institucion_extension.Nivel',
        DB::raw('COALESCE(tb_usuarios.CUECOMPLETO, "Sin Dato") as CUECOMPLETO'),
        'tb_turnos_usuario.Descripcion',
        'tb_modos.Descripcion as Modo',
        DB::raw('COALESCE(tb_usuarios.created_at, "Sin Dato") as FechaCreacion'),
        'tb_logs.updated_at as FechaUltimoAcceso'
      )
      ->limit(100)
      ->orderBy('idLog','DESC')
      ->get();

      $datos=array(
        'mensajeError'=>"",
        'Logs'=>$logs,
        
        'mensajeNAV'=>'Panel de Configuración de Usuarios',
    );
    //dd($infoPlaza);
    return view('bandeja.ADMIN.tabla_logs',$datos);
  }



}
