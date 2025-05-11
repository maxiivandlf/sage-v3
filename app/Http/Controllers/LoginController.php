<?php

namespace App\Http\Controllers;

use App\Models\ExtensionModel;
use App\Models\InstitucionExtensionModel;
use App\Models\InstitucionModel;
use App\Models\LogsModel;
use App\Models\UsuarioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LoginController extends Controller
{


    public function index(Request $request)
    {

        if ($request->session()->has('Usuario') == false) {
            //dd($request->session()->has('Usuario'));
            $datos = array(
                'mensajeError' => "Bloqueado"
            );
            return view('login.index', $datos);
        } else {
            session(['Validar' => '']);
            $datos = array(
                'mensajeError' => "Bloqueado"
            );
            return view('login.index', $datos);
        }
    }


    public function validar(Request $request)
    {
        //dd($request);
        if ($request->email != "" && $request->clave != "") {
            $usuario = UsuarioModel::where('email', $request->email)
                ->where('Clave', $request->clave)
                ->join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', 'tb_usuarios.Turno')        //no olvidar dar turno
                ->get();
            //dd($usuario);
            $cantidadEncontrados = count($usuario);

            setlocale(LC_TIME, 'es_ES.UTF-8'); 
            Carbon::setLocale('es');

            $mesActual = Carbon::now()->locale('es')->translatedFormat('F');
            $mesActual = Str::ucfirst($mesActual);
            session(['mesActual' => $mesActual]);

            $mesAnterior = Carbon::now()->subMonth()->translatedFormat('F');
            $mesAnterior = Str::ucfirst($mesAnterior);
            session(['mesAnterior' => $mesAnterior]);

            //dd($mesActual);
            if ($cantidadEncontrados) {
                //creo la session para que cargue el menu
                session(['Usuario' => $usuario[0]->Nombre]);
                session(['NombreInstitucion' => $usuario[0]->Usuario]);
                session(['idUsuario' => $usuario[0]->idUsuario]);
                //session(['idReparticion'=>$usuario[0]->idReparticion]); //ya no lo uso, ahora el cue esta en la misma tabla usuario
                session(['UsuarioEmail' => $usuario[0]->email]);
                session(['UsuarioCUE' => $usuario[0]->CUE]);
                session(['Modo' => $usuario[0]->Modo]);
                session(['TurnoDescripcion' => $usuario[0]->Descripcion]);
                session(['idTurnoUsuario' => $usuario[0]->Turno]);
                //obtengo el usuario que es la escuela a trabajar
                // $idReparticion = session('idReparticion');
                //consulto a reparticiones
                //$reparticion = DB::table('tb_reparticiones')
                //->where('tb_reparticiones.idReparticion',$idReparticion)
                //->get();
                //dd($reparticion[0]->Organizacion);

                /*$subOrganizacion=DB::table('tb_suborganizaciones')
                ->where('tb_suborganizaciones.idsuborganizacion',$reparticion[0]->subOrganizacion)
                ->select('*')
                ->get();
                */
                $institucionExtension = DB::table('tb_institucion_extension')
                    ->where('tb_institucion_extension.CUECOMPLETO', $usuario[0]->CUECOMPLETO)
                    ->where('tb_institucion_extension.idTurnoUsuario', $usuario[0]->Turno)
                    ->get();
                //dd($institucionExtension);
                //crear un log
                if ($usuario->isNotEmpty()) {
                    $log = new LogsModel();
                    $log->idUsuario = session('idUsuario');
                    $log->save();
                }

                //dd($institucionExtension);
                if ($institucionExtension->isNotEmpty()) {
                    session(['CUE' => $institucionExtension[0]->CUE ?? null]);
                    session(['CUECOMPLETO' => $institucionExtension[0]->CUECOMPLETO ?? null]);
                    $cuebase = substr(session('CUECOMPLETO'), 0, 9);
                    session(['CUECOMPLETOBASE' => $cuebase]);

                    session(['idInstitucionExtension' => $institucionExtension[0]->idInstitucionExtension ?? null]);
                    session(['Nombre_Institucion' => $institucionExtension[0]->Nombre_Institucion ?? null]);
                    session(['Validar' => 'ok']);
                    session(['PermiteBorrarTodo' => $institucionExtension[0]->PermiteBorrarTodo ?? null]);
                    session(['PermiteEditarTodo' => $institucionExtension[0]->PermiteEditarTodo ?? null]);
                    session(['EstadoHabilitado' => $institucionExtension[0]->Habilitado ?? null]);
                    session(['Zona' => $institucionExtension[0]->Zona ?? null]);
                    session(['ZonaSupervision' => $institucionExtension[0]->ZonaSupervision ?? null]);
                    session(['InfoUsuario' => $usuario[0]]);
                } else {
                    session(['CUE' => '0000000']);
                    session(['CUECOMPLETO' => '000000000']);
                    session(['CUECOMPLETOBASE' => '000000000']);
                    session(['idInstitucionExtension' => '00']);
                    session(['Validar' => 'ok']);
                    session(['PermiteBorrarTodo' => 0]);
                    session(['PermiteEditarTodo' => 0]);
                    session(['EstadoHabilitado' => 13]);
                    session(['InfoUsuario' => $usuario[0]]);
                }
                //dd($usuario[0]->avatar);
                //armo ruta

                $ruta = '
                    <li class="breadcrumb-item active"><a href="' . route('Bandeja') . '">BANDEJA</a></li>
                    
                    ';
                session(['ruta' => $ruta]);

                //proceso nuevo, controlo si hay mantenimiento para no dejar que entren escuelas y sus manejadores
                $mantenimiento = DB::table('tb_mantenimiento')->first();
                //dd($mantenimiento); 43 es supervisionadultos@gmail.com
                $usuariosModosPermitidos = [1, 3, 7, 43];
                $existeCUE = $institucionExtension[0]->CUECOMPLETO ?? null;
                if ($existeCUE != null) {
                    $primerosCuatroDigitos = substr($institucionExtension[0]->CUECOMPLETO, 0, 4) ?? null;
                } else {
                    $primerosCuatroDigitos = null;
                }
                //dd($primerosCuatroDigitos);

                if (
                    $mantenimiento->Estado == "SI"
                    && !in_array(session('Modo'), $usuariosModosPermitidos) && $primerosCuatroDigitos != "9999"
                ) {
                    //dd($mantenimiento);
                    $datos = array(
                        'mensajeError' => "Sistema en Mantenimiento",
                        'mensajeNAV' => 'Bandeja Principal',
                    );
                    return redirect('/')->with('mensajeError', 'El Sistema se en encuentra en estos momentos en Mantenimiento');
                } else {
                    $datos = array(
                        'mensajeError' => "Usuario Correcto",
                        'mensajeNAV' => 'Bandeja Principal',
                    );
                    //session(['ActivarSplashInicial'=>"OK"]);
                    return redirect()->route('dashboardRedirect'); //->with('ActivarSplashInicial','OK');
                }
            } else {
                $datos = array(
                    'mensajeError' => "No se encontro el usuario en el Sistema",
                    'mensajeNAV' => 'Bandeja Principal'
                );
                return view('login.index', $datos);
            }
        } else {
            $datos = array(
                'mensajeError' => "Los campos estan vacios",
                'mensajeNAV' => 'Bandeja Principal'
            );
            return view('login.index', $datos);
        }
    }

    public function pedirUsuario()
    {
        $infoCue = InstitucionModel::where('CUE', 99999999999)
            ->get();
        $datos = array(
            'mensajeError' => "",
            'mensajeNAV' => 'Bandeja Principal',
            'infoCue' => $infoCue
        );
        return view('login.solicitarUsuario', $datos);
    }
    public function buscarCUE(Request $request)
    {
        if ($request->cue == "") {
            $infoCue = InstitucionModel::where('CUE', 9999999999)
                ->get();
            //dd($infoCue);
            $datos = array(
                'mensajeError' => "Debe escribir una CUE validad",
                'mensajeNAV' => 'Bandeja Principal',
                'infoCue' => $infoCue
            );
            return view('login.solicitarUsuario', $datos);
        } else {
            $infoCue = InstitucionModel::where('CUE', $request->cue)
                ->get();
            //dd($infoCue);
            $datos = array(
                'mensajeError' => "",
                'mensajeNAV' => 'Bandeja Principal',
                'infoCue' => $infoCue
            );
            return view('login.solicitarUsuario', $datos);
        }
    }

    public function cargarInfoUsuario($CUE)
    {
        $infoInstitucion = InstitucionModel::where('CUE', $CUE)
            ->get();
        $Extensiones = DB::table('tb_extensiones')->get();
        $TurnosUsuario = DB::table('tb_turnos_usuario')->get();
        $infoCUECreadas = UsuarioModel::where('CUE', $CUE)
            ->get();

        //traigo todos los usuarios que tienen cue buscada

        //yodd($infoInstitucion);
        $datos = array(
            'mensajeError' => "",
            'mensajeNAV' => 'Bandeja Principal',
            'infoInstitucion' => $infoInstitucion,
            'Extensiones' => $Extensiones,
            'infoCUECreadas' => $infoCUECreadas,
            'TurnosUsuario' => $TurnosUsuario
        );
        return view('login.cargaInfoPedido', $datos);
    }

    public function FormNuevoUsuario_CUE(Request $request)
    {
        //controlo si existen los datos
        $Usuario = DB::table('tb_usuarios')
            ->where('tb_usuarios.CUECOMPLETO', $request->CUE . $request->CUEa)
            ->where('tb_usuarios.Turno', $request->Turno) //es and
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

        if (count($Usuario) == 0) {
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
            $o->CUEa = substr($request->CUEa, 0, 2);
            $o->CUECOMPLETO = $request->CUE . $request->CUEa;
            $o->avatar = "img_profile.svg";
            $o->save();

            //aprovecho y traigo la info de la institucion a copiar, pero solo 1 registro, asi evito la duplicidad de datos
            $institucion = DB::table('tb_institucion')
                ->where('tb_institucion.CUE', $request->CUE)
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
            $ie->CUECOMPLETO = $request->CUE . $request->CUEa;
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
            $ie->PermiteEditarTodo = 0;     //se agrego para poder usar el editar en algunos campos
            $ie->save();

            $CUE = $request->CUE;
            //cargarInfoUsuario/4600233
            return redirect("/cargarInfoUsuario/$CUE/")->with('ConfirmarNuevoUsuario', 'OK');
        } else {
            $CUE = $request->CUE;
            //cargarInfoUsuario/4600233
            return redirect("/cargarInfoUsuario/$CUE/")->with('ConfirmarNuevoUsuarioError', 'OK');
        }

        //LuiController::PlazaNueva($request->idSurOrg);

    }
}
