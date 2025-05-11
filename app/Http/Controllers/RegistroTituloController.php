<?php

namespace App\Http\Controllers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\titulo\RegistroDocModel;
use App\Models\titulo\certificadosModelo;
use App\Models\titulo\datosPersonalesModelo;
use App\Models\titulo\establecimientosModelo;
use App\Models\titulo\EstadoModel;
use App\Models\titulo\registroDeCertificadosModelo;
use App\Models\titulo\registroTituloModelo;
use App\Models\titulo\tituloModelo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\EjemploMailConAdjunto;
use Illuminate\Support\Facades\Mail;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
class RegistroTituloController extends Controller
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
     //TITULOS SOLOS
    public function gestion_titulos(){
           $Titulos = tituloModelo::all();
           $Estados = EstadoModel::all();
            $datos=array(
                'mensajeError'=>"",
                'Titulos'=>$Titulos,
                'Estados'=>$Estados,
                'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
                'mensajeNAV'=>'Panel de Configuración de Títulos'

            );
            $ruta ='
            <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
            <li class="breadcrumb-item active"><a href="'.route('gestion_titulos').'">Gestión de Títulos</a></li>
            '; 
            session(['ruta' => $ruta]);
            return view('bandeja.RegistroTitulo.titulos',$datos); 
    }

    public function formularioTitulos(Request $request){
        //dd($request);
        /*
           "_token" => "txR8lywVhBA0SQn86RNwn7u25nMjwN3pg5IEr6PO"
        "DescripcionTitulo" => "prrueba cargando titulo"
         */
        $a = new tituloModelo();
            $a->nombre_titulo  = $request->DescripcionTitulo;
            $a->estado_titulo = 1;
        $a->save();

        return redirect()->back()->with('ConfirmarNuevoTitulo','OK');
    }

    public function formularioActualizarTitulos(Request $request){
       // dd($request);
        /*
            "_token" => "x9B6R9F5YoGf1WyuvHakjI9JrvC1IIzKDRVqcAh0"
            "tituloidEnv" => "299"
            "Descripcion" => "prueba"
            "Estado" => "1"
         */
        $a = tituloModelo::where('idTitulo',$request->tituloidEnv)->first();
            $a->nombre_titulo  = $request->Descripcion;
            $a->estado_titulo = $request->Estado;
        $a->save();

        return redirect()->back()->with('ConfirmarActualizarTitulo','OK');
    }

    public function eliminarTitulo($idTitulo){
        //dd($idTitulo);
        //borro un titulo
        DB::connection('DB2')->table('tb_titulos')
            ->where('idTitulo', $idTitulo)
            ->delete();
        
            return redirect()->route('gestion_titulos')->with('ConfirmarBorradoTitulo','OK');
        
        
    }
    
    
    
    //CERTIFICADOS SOLOS
    public function gestion_certificados(){
        $Certificado = certificadosModelo::all();
        $Estados = EstadoModel::all();
        $datos=array(
            'mensajeError'=>"",
            'Certificado'=>$Certificado,
            'Estados'=>$Estados,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Certificados'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
        <li class="breadcrumb-item active"><a href="'.route('gestion_certificados').'">Gestión de Certificados</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.RegistroTitulo.certificados',$datos); 
    }

    public function formularioCertificados(Request $request){
        //dd($request);
        /*
            "_token" => "x9B6R9F5YoGf1WyuvHakjI9JrvC1IIzKDRVqcAh0"
        "DescripcionCertificado" => "nuevo certificado"
         */
        $a = new certificadosModelo();
            $a->nombre_certificado  = $request->DescripcionCertificado;
            $a->estado_certificado = 1;
        $a->save();

        return redirect()->back()->with('ConfirmarNuevoCertificado','OK');
    }

    public function formularioActualizarCertificado(Request $request){
        //dd($request);
         /*
             "_token" => "x9B6R9F5YoGf1WyuvHakjI9JrvC1IIzKDRVqcAh0"
            "certificadoidEnv" => "30"
            "Descripcion" => "nuevo certificado"
            "Estado" => "2"
          */
         $a = certificadosModelo::where('idCertificado',$request->certificadoidEnv)->first();
             $a->nombre_certificado  = $request->Descripcion;
             $a->estado_certificado = $request->Estado;
         $a->save();
 
         return redirect()->back()->with('ConfirmarActualizarCertificado','OK');
     }

     public function eliminarCertificado($idCertificado){
        //dd($idTitulo);
        //borro un titulo
        DB::connection('DB2')->table('tb_certificados')
            ->where('idCertificado', $idCertificado)
            ->delete();
        
            return redirect()->route('gestion_certificados')->with('ConfirmarBorradoCertificado','OK');
        
        
    }


    //ESTABLECIMIENTOS SOLOS
    public function gestion_establecimientos(){
        $Establecimiento = establecimientosModelo::orderBy('idEstablecimiento','ASC')->get();
        $Estados = EstadoModel::all();
        $datos=array(
            'mensajeError'=>"",
            'Establecimiento'=>$Establecimiento,
            'Estados'=>$Estados,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Establecimientos'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
        <li class="breadcrumb-item active"><a href="'.route('gestion_establecimientos').'">Gestión de Establecimientos</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.RegistroTitulo.establecimientos',$datos); 
    }

    public function formularioEstablecimientos(Request $request){
        //dd($request);
        /*
            "_token" => "rf4gjLnRmaxtAepyWpO1xD7U4kf5UXCUJKmqzpDv"
            "DescripcionEstablecimiento" => "prueba establecimiento"
         */
        $a = new establecimientosModelo();
            $a->nombre_establecimiento  = $request->DescripcionEstablecimiento;
            $a->estado_establecimiento = 1;
        $a->save();

        return redirect()->back()->with('ConfirmarNuevoEstablecimiento','OK');
    }

    public function formularioActualizarEstablecimiento(Request $request){
        //dd($request);
         /*
            "_token" => "rf4gjLnRmaxtAepyWpO1xD7U4kf5UXCUJKmqzpDv"
            "establecimientoidEnv" => "163"
            "Descripcion" => "prueba establecimiento2"
            "Estado" => "2"
          */
         $a = establecimientosModelo::where('idEstablecimiento',$request->establecimientoidEnv)->first();
            $a->nombre_establecimiento  = $request->Descripcion;
            $a->estado_establecimiento = $request->Estado;
         $a->save();
 
         return redirect()->back()->with('ConfirmarActualizarEstablecimiento','OK');
     }

    public function eliminarEstablecimiento($idEstablecimiento){
        //dd($idTitulo);
        //borro un titulo
        DB::connection('DB2')->table('tb_establecimientos')
            ->where('idEstablecimiento', $idEstablecimiento)
            ->delete();
        
            return redirect()->route('gestion_establecimientos')->with('ConfirmarBorradoEstablecimiento','OK');
        
        
    }






    public function gestion_reg_titulo(){
        $Establecimiento = establecimientosModelo::all();
        $Agentes = datosPersonalesModelo::all();
        $Titulos = tituloModelo::all();
        $Registro_Titulo = registroTituloModelo::latest()->first(); //::orderBy('id', 'desc')->first();
        $datos=array(
            'mensajeError'=>"",
            'Establecimiento'=>$Establecimiento,
            'Agentes'=>$Agentes,
            'Titulos'=>$Titulos,
            'ultimoRegistroTitulo'=>$Registro_Titulo,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Agentes'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
        <li class="breadcrumb-item active"><a href="'.route('gestion_establecimientos').'">Gestión de Certificados</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.RegistroTitulo.registro_titulo',$datos);         
    }

    public function formulario_reg_titulo(Request $request){

    }

    /**PARA MANEJAR LOS AGENTES, NO SOLO EL ALTA SINO TAMBIEN BUSQUEDA Y REGISTRO DE TIT Y CERT
     * 
     */
    public function gestion_agentes_alta(){
        $Establecimiento = establecimientosModelo::all();
        $Agentes = datosPersonalesModelo::all();
        $Titulos = tituloModelo::all();
        $Registro_Titulo = registroTituloModelo::latest()->first(); //::orderBy('id', 'desc')->first();
        $datos=array(
            'mensajeError'=>"",
            'Establecimiento'=>$Establecimiento,
            'Agentes'=>$Agentes,
            'Titulos'=>$Titulos,
            'ultimoRegistroTitulo'=>$Registro_Titulo,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Agentes'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
        <li class="breadcrumb-item active"><a href="'.route('gestion_agentes_alta').'">Gestión de Agentes</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.RegistroTitulo.agentes',$datos); 
    }

    public function formularioAgentesAlta(Request $request){
        //dd($request);
        /*
          "_token" => "Bp8gKAl9AQXZNLF6uC7ZQIVmxbgRODMu3IR4Q4q6"
        "Apellido" => "apeprueba"                   listo
        "Nombre" => "nombrePrueba"                  listo
        "DNI" => "99999999"                         listo
        "FechaNacimiento" => "1979-06-06"           listo
        "Ciudad" => "La Rioja"                      listo
        "Telefono" => "03804368321"
        "Nacionalidad" => "Argentina"               listo
        "Provincia" => "La Rioja"
        "Localidad" => "La Rioja"
        "DomicilioActual" => "Las heras 1586"
        "Correo" => "masterdjmov@gmail.com"
        "Observaciones" => "ninguna"
         */

         //agrego el agente nuevo
        $a = new datosPersonalesModelo();
            $a->apellido_nombre  = strtoupper($request->Apellido).", ".strtoupper($request->Nombre);
            $a->apellido = strtoupper($request->Apellido);
            $a->nombre = strtoupper($request->Nombre);
            $a->dni  = $request->DNI;
            $a->fecha_de_nacimiento  = $request->FechaNacimiento;
            $a->nacionalidad  = strtoupper($request->Nacionalidad);
            $a->lugar_de_nacimiento  = strtoupper($request->Ciudad);
            $a->domicilio  = $request->DomicilioActual;
            $a->provincia  = strtoupper($request->Provincia);
            $a->localidad  = strtoupper($request->Localidad);
            $a->numero_telefono  = $request->Telefono;
            $a->correo  = $request->Correo;
            $a->observaciones = $request->Observaciones;
        $a->save();

     return redirect()->back()->with('ConfirmarNuevoAgente','OK');
    }

    public function gestion_agentes_consulta(){
        $Establecimiento = establecimientosModelo::all();
        $Agentes = datosPersonalesModelo::all();
        $Titulos = tituloModelo::all();
        $Registro_Titulo = registroTituloModelo::latest()->first(); //::orderBy('id', 'desc')->first();
        $datos=array(
            'mensajeError'=>"",
            'Establecimiento'=>$Establecimiento,
            'Agentes'=>$Agentes,
            'Titulos'=>$Titulos,
            'ultimoRegistroTitulo'=>$Registro_Titulo,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Agentes - Consultas'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
        <li class="breadcrumb-item active"><a href="'.route('gestion_agentes_consulta').'">Gestión de Agentes - Consultas</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.RegistroTitulo.agentesLista',$datos); 
    }

    public function gestion_agentes_solicitudes_titulos(){
        $Establecimiento = establecimientosModelo::all();
        $Agentes = datosPersonalesModelo::all();
        $Titulos = tituloModelo::all();
        $Registro_Titulo = registroTituloModelo::latest()->first(); //::orderBy('id', 'desc')->first();
        $datos=array(
            'mensajeError'=>"",
            'Establecimiento'=>$Establecimiento,
            'Agentes'=>$Agentes,
            'Titulos'=>$Titulos,
            'ultimoRegistroTitulo'=>$Registro_Titulo,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Agentes - Consultas'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
        <li class="breadcrumb-item active"><a href="'.route('gestion_agentes_solicitudes_titulos').'">Gestión de Agentes - Consultas</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.RegistroTitulo.solicitudesListaTitulo',$datos); 
    }

    public function gestion_agentes_solicitudes_certificados(){
        $Establecimiento = establecimientosModelo::all();
        $Agentes = datosPersonalesModelo::all();
        $Titulos = tituloModelo::all();
        $Registro_Titulo = registroTituloModelo::latest()->first(); //::orderBy('id', 'desc')->first();
        $datos=array(
            'mensajeError'=>"",
            'Establecimiento'=>$Establecimiento,
            'Agentes'=>$Agentes,
            'Titulos'=>$Titulos,
            'ultimoRegistroTitulo'=>$Registro_Titulo,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Agentes - Consultas'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
        <li class="breadcrumb-item active"><a href="'.route('gestion_agentes_solicitudes_certificados').'">Gestión de Agentes - Consultas</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.RegistroTitulo.solicitudesListaCertificado',$datos); 
    }

    public function editarAgenteTitulo($idAgente){
        $Agentes = datosPersonalesModelo::where('idAgente',$idAgente)->first();
        $datos=array(
            'mensajeError'=>"",
            'Agentes'=>$Agentes,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Agentes - Editar / Actualizar'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
        <li class="breadcrumb-item active"><a href="'.route('gestion_agentes_consulta').'">Gestión de Agentes - Consultas</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.RegistroTitulo.editarAgente',$datos); 
     }

     public function formularioAgentesActualizar(Request $request){
        //dd($request);
         /*
           "_token" => "Bp8gKAl9AQXZNLF6uC7ZQIVmxbgRODMu3IR4Q4q6"
            "Apellido" => "VANEGA"
            "Nombre" => "FRANCO GABRIEL"
            "DNI" => "35542068"
            "FechaNacimiento" => "1992-11-30"
            "Ciudad" => "LA RIOJA"
            "Telefono" => "3809999999"
            "Nacionalidad" => "ARG"
            "Provincia" => "LA RIOJA"
            "Localidad" => "TAMA"
            "DomicilioActual" => "25 DE SEPTIEMBRE S/N"
            "Correo" => "vanegaPrueba@gmail.com"
            "Observaciones" => "ninguno"
            "idU" => "1"
          */
         $a = datosPersonalesModelo::where('idAgente',$request->idU)->first();
            $a->apellido_nombre  = strtoupper($request->Apellido).", ".strtoupper($request->Nombre);
            $a->apellido = strtoupper($request->Apellido);
            $a->nombre = strtoupper($request->Nombre);
            $a->dni  = $request->DNI;
            $a->fecha_de_nacimiento  = $request->FechaNacimiento;
            $a->nacionalidad  = strtoupper($request->Nacionalidad);
            $a->lugar_de_nacimiento  = strtoupper($request->Ciudad);
            $a->domicilio  = $request->DomicilioActual;
            $a->provincia  = strtoupper($request->Provincia);
            $a->localidad  = strtoupper($request->Localidad);
            $a->numero_telefono  = $request->Telefono;
            $a->correo  = $request->Correo;
            $a->observaciones = $request->Observaciones;
         $a->save();
 
         return redirect()->back()->with('ConfirmarActualizarAgente','OK');
     }


     public function agregarTituloyCertificado($idAgente){
        //$Establecimiento = establecimientosModelo::all();
        $Establecimiento = establecimientosModelo::select('nombre_establecimiento')
        ->distinct() // Solo valores únicos
        ->orderBy('nombre_establecimiento', 'asc') // Orden alfabético
        ->get();
        
        //$Certificado = certificadosModelo::all();
        $Certificado = certificadosModelo::select('nombre_certificado')
        ->distinct() // Solo valores únicos
        ->orderBy('nombre_certificado', 'asc') // Orden alfabético
        ->get();

        //$Titulos = tituloModelo::all();
        $Titulos = tituloModelo::select('nombre_titulo')
        ->distinct() // Solo valores únicos
        ->orderBy('nombre_titulo', 'asc') // Orden alfabético
        ->get();

        $Agentes = datosPersonalesModelo::where('idAgente',$idAgente)->first();
        
        $Registro_Titulo = registroTituloModelo::orderBy('idRegistroTitulo', 'desc')->first();
        $Registro_Certificado = registroDeCertificadosModelo::orderBy('idRegistroCertificado', 'desc')->first();
        $datos=array(
            'mensajeError'=>"",
            'Establecimiento'=>$Establecimiento,
            'Certificados'=>$Certificado,
            'Agentes'=>$Agentes,
            'Titulos'=>$Titulos,
            'ultimoRegistroTitulo'=>$Registro_Titulo,
            'ultimoRegistroCertificado'=>$Registro_Certificado,
            'idAgente'=>$idAgente,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Agentes - Editar / Actualizar'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
        <li class="breadcrumb-item active"><a href="'.route('gestion_agentes_consulta').'">Gestión de Agentes - Consultas</a></li>
        <li class="breadcrumb-item active"><a href="'.route('agregarTituloyCertificado',$idAgente).'">Agregar Titulo / Certificado - Consultas</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.RegistroTitulo.registro_titulo',$datos); 
     }

     public function editarTituloCreado($idRegistro){
        //$Establecimiento = establecimientosModelo::all();
        $Establecimiento = establecimientosModelo::select('nombre_establecimiento')
        ->distinct() // Solo valores únicos
        ->orderBy('nombre_establecimiento', 'asc') // Orden alfabético
        ->get();
        
        //$Certificado = certificadosModelo::all();
        $Certificado = certificadosModelo::select('nombre_certificado')
        ->distinct() // Solo valores únicos
        ->orderBy('nombre_certificado', 'asc') // Orden alfabético
        ->get();

        //$Titulos = tituloModelo::all();
        $Titulos = tituloModelo::select('nombre_titulo')
        ->distinct() // Solo valores únicos
        ->orderBy('nombre_titulo', 'asc') // Orden alfabético
        ->get();

        //necesito pasarle el dato del agente, lo saco del reg titulo
        $Agente_dni = registroTituloModelo::where('idRegistroTitulo', $idRegistro)->first();

        $Agentes = datosPersonalesModelo::where('dni',$Agente_dni->dni)->first();
        
        $Registro_Titulo = registroTituloModelo::orderBy('idRegistroTitulo', 'desc')->first();
        $Registro_Certificado = registroDeCertificadosModelo::orderBy('idRegistroCertificado', 'desc')->first();
        $datos=array(
            'mensajeError'=>"",
            'Establecimiento'=>$Establecimiento,
            'Certificados'=>$Certificado,
            'Agentes'=>$Agentes,
            'Titulos'=>$Titulos,
            'ultimoRegistroTitulo'=>$Registro_Titulo,
            'ultimoRegistroCertificado'=>$Registro_Certificado,
            'idAgente'=>$Agentes->idAgente,
            'infoRegTitulo'=>$Agente_dni,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Agentes - Editar / Actualizar'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
        <li class="breadcrumb-item active"><a href="'.route('gestion_agentes_consulta').'">Gestión de Agentes - Consultas</a></li>
        <li class="breadcrumb-item active"><a href="'.route('editarTituloCreado',$Agente_dni->idRegistroTitulo).'">Editar Titulo / Certificado - Consultas</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.RegistroTitulo.editar_titulo',$datos); 
     }

     public function formularioTituloYCertificado(Request $request){
        //dd($request);
         /*
            "_token" => "Bp8gKAl9AQXZNLF6uC7ZQIVmxbgRODMu3IR4Q4q6"
            "fechaRegistro" => "2024-07-01"
            "ultimoTitulo" => "76192"
            "ultimoCertificado" => "21780"
            "opcion" => "Titulo"
            "fecha" => "2024-07-01"
            "DescripcionOperacion" => "titulo de prueba epet2"
            "titulo" => "BACHILLER NACIONAL.-"
            "certificado" => "BACHILLER"
            "Establecimiento" => "I. S. F. I. S. P. "BRIGADIER GENERAL JUAN FACUNDO QUIROGA" -  LA RIOJA CAPITAL .-"
            "fechaEgreso" => "2024-07-01"
            "idU" => "1"
            se agrego url2 para secundo titulo y se agrego subir documento extra
          */
        $a="";
        $b="";
        $data = [];
          //valido par ver cual es cual
          if($request->opcion == "Titulo"){
            $a = new registroTituloModelo();
                $a->idRegistroTitulo  = $request->ultimoTitulo; //guardo el id del ultimo +1
                $a->nombre_titulo = strtoupper($request->titulo);
                $a->otorgado_por = $request->Establecimiento;
                $a->fecha_de_titulo  = $request->fecha;
                $a->fecha_de_registro  = $request->fechaRegistro;
                $a->fecha_de_egreso  = $request->fechaEgreso;
                $a->dni = $request->idU;
                $a->estado_agente = 1;
                $a->URL_titulo_online = $request->url;
                $a->URL_titulo_online2 = $request->url2;
                /*agrego el id*/
               
                // Generar el nombre del archivo en MD5 sin la extensión
                $md5Name = md5(date('Y-m-d H:i:s'));
                
                // Concatenar el nombre MD5 con la extensión original
                $newFileName = $request->opcion."-".$request->idU."-".$md5Name . '.pdf';
                $a->URL_doc =$newFileName;

            $a->save();

            //dd($a->dni);
            /* preparo los datos para enviar al pdf */
            $Agentes = datosPersonalesModelo::where('dni',$a->dni)->first();
            //dd($Agentes);
            $data = [
                'name' => $Agentes->apellido_nombre,
                'dni' => $Agentes->dni,
                'registration_date' => $request->fechaRegistro,
                'registration_number' => $request->ultimoTitulo,
                'title' => strtoupper($request->titulo),
                'institution' => $request->Establecimiento,
                'graduation_date' => $request->fechaEgreso,
                'tipoOperacion' => $request->opcion,
                'URL_doc' => $request->url,
                'URL_doc2' => $request->url2
                ];
            //dd($data);
          }else{
            $b = new registroDeCertificadosModelo();
                $b->idRegistroCertificado  = $request->ultimoCertificado; //guardo el id del ultimo +1
                $b->nombre_certificado = strtoupper($request->certificado);
                $b->otorgado_por = $request->Establecimiento;
                $b->fecha_de_certificado  = $request->fecha;
                $b->fecha_de_registro  = $request->fechaRegistro;
                $b->fecha_de_egreso  = $request->fechaEgreso;
                $b->dni = $request->idU;
                $b->estado_certificado = 1;
                $b->URL_certificado_online = $request->url;
                $b->URL_certificado_online2 = $request->url2;
                /*agrego el */
               
                // Generar el nombre del archivo en MD5 sin la extensión
                $md5Name = md5(date('Y-m-d H:i:s'));
                
                // Concatenar el nombre MD5 con la extensión original
                $newFileName = $request->opcion."-".$request->idU."-".$md5Name . '.pdf';
                $b->URL_doc =$newFileName;

            $b->save();

             /* preparo los datos para enviar al pdf */
          $Agentes = datosPersonalesModelo::where('dni',$b->dni)->first();
          $data = [
            'name' => $Agentes->apellido_nombre,
            'dni' => $Agentes->dni,
            'registration_date' => $request->fechaRegistro,
            'registration_number' => $request->ultimoCertificado,
            'title' => strtoupper($request->certificado),
            'institution' => $request->Establecimiento,
            'graduation_date' => $request->fechaEgreso,
            'tipoOperacion' => $request->opcion,
            'URL_doc' => $request->url,
            'URL_doc2' => $request->url2
            ];
          }
        //   $data = [
        //    "name" => "VANEGA, FRANCO GABRIEL",
        //     "dni" => "35542068",
        //     "registration_date" => "2024-07-01",
        //     "registration_number" => "76194",
        //     "title" => "TITULO DE PRUEBA EPET2",
        //     "institution" => "I. S. F. I. S. P. BRIGADIER GENERAL JUAN FACUNDO QUIROGA -  LA RIOJA CAPITAL .-",
        //     "graduation_date" => "2024-07-01",
        //     "tipoOperacion" => "Titulo"
        //     ];

            /* genero el pdf*/
            $html = view('bandeja.RegistroTitulo.generarArchivo',['datos' => $data])->render();

            // Configurar Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            // Crear una instancia de Dompdf con las opciones
            $dompdf = new Dompdf($options);

            // Cargar el HTML en Dompdf
            $dompdf->loadHtml($html);

            // Establecer la orientación de la página (landscape o portrait)
            $dompdf->setPaper('A4', 'portrait'); // Aquí puedes cambiar 'landscape' a 'portrait' si prefieres la orientación vertical
            

            // Renderizar el PDF
            $dompdf->render();

            // Obtener el contenido del PDF generado
            $pdf_content = $dompdf->output();

            // Guardar el PDF en la carpeta de almacenamiento
            $pdf_path = storage_path('app/public/TITCERT/') . $newFileName; // Ruta donde se guardará el archivo PDF

            // Escribir el contenido del PDF en el archivo
            file_put_contents($pdf_path, $pdf_content);

            // Guardar la URL del PDF en la base de datos
           
            //envio archivo
            $mensaje = "Se deja constancia que su Titulo/Certificado se encuentra inscripto en el Registro Provincial de Certificados y Títulos de La Rioja
";
            $mensajePara = $Agentes->correo;

            // Ruta al archivo en storage
            $filePath = storage_path('app/public/TITCERT/'.$newFileName); // Cambia esto a la ruta correcta de tu archivo
            $fileName = $newFileName; // Nombre del archivo

            try {
                // Intenta enviar el correo
                Mail::to($mensajePara)->send(new EjemploMailConAdjunto($mensaje, $filePath, $fileName));
            
                // Si no hay excepciones, continúa con la ejecución normal
                // Puedes agregar alguna lógica aquí si es necesario
            } catch (\Exception $e) {
                // Maneja el error, por ejemplo, loguearlo o mostrar un mensaje al usuario
                //\Log::error('Error al enviar el correo: ' . $e->getMessage());
            
                // Opcional: realiza alguna acción específica en caso de error
            }
 
         return redirect()->back()->with('ConfirmarAgregarTitCer','OK');
     }

     public function download($filename)
     {
         $filePath = public_path('storage/TITCERT/' . $filename);
 
         if (file_exists($filePath)) {
             return response()->download($filePath);
         } else {
             abort(404, 'File not found.');
         }
     }


     public function agregarDocAgenteTitulo($idAgente){
        $Agente = datosPersonalesModelo::where('idAgente',$idAgente)->first();
        $datos=array(
            'mensajeError'=>"",
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Configuración de Agentes - Editar / Actualizar',
            'Usuario'=>$Agente

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Registro De Títulos</a></li>
        <li class="breadcrumb-item active"><a href="'.route('agregarDocAgenteTitulo',$idAgente).'">Agregar Documentos</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('bandeja.RegistroTitulo.agregarDocAgente',$datos); 
     }


     public function formAgregarDocRegTitulo(Request $request){
        //dd($request);
        /*
        "_token" => "VDWrK1mj9vWrgLqstFLiIfMkkPw0rRB6E3eU6I7Y"
      "descripcion" => "titulo",
      "dni" => "35542068",
      "documento" => Symfony\Component\HttpFoundation\File\UploadedFile {#34 ▼
        -test: false
        -originalName: "1_001.png"
        -mimeType: "image/png"
        -error: 0
        path: "/tmp"
        */
        // Generar el nombre del archivo en MD5 sin la extensión
        $md5Name = md5(date('Y-m-d H:i:s'));
                
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName(); // Obtener el nombre original del archivo
            $extension = $file->getClientOriginalExtension(); // Obtener la extensión del archivo
        
            // Generar el nombre del archivo en MD5 sin la extensión
            $md5Name = md5(pathinfo($originalName, PATHINFO_FILENAME));
        
            // Concatenar el nombre MD5 con la extensión original
            $newFileName = $request->descripcion."-".$md5Name . '.' . $extension;
        
            // Obtener la ruta al directorio de almacenamiento deseado
            $destinationPath = storage_path('app/public/TITCERT/' . $request->dni . '/');
        
            // Verificar si el directorio de destino existe, si no, crearlo
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
        
            // Mover el archivo a la ubicación deseada con el nuevo nombre
            $file->move($destinationPath, $newFileName);
        
            // Calcula el hash MD5 del archivo completo
            $md5Hash = md5_file($destinationPath . '/' . $newFileName);
        
            // Agregar el documento a la tabla
            $docNuevo = new RegistroDocModel();
                $docNuevo->idRegistroDocumento = 1;
                $docNuevo->dni = $request->dni;
                $docNuevo->descripcion = $request->descripcion;
                $docNuevo->URL = $newFileName;
            $docNuevo->save();
        
            return redirect()->back()->with('ConfirmarSubirDocOk','OK');

        }
        
        return redirect()->back()->with('ConfirmarSubirDocFail','OK');
           
           

     }


















}
