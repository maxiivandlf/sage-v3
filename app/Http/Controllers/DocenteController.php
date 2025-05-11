<?php

namespace App\Http\Controllers;

use App\Mail\EjemploMailConAdjunto;
use App\Models\AgenteModel;
use App\Models\AgenteRespaldoModel;
use App\Models\UsuarioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
class DocenteController extends Controller
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
    

    public function perfilMulticuenta(){
        $infoUsuario = UsuarioModel::where('idUsuario',session('idUsuario'))->first();
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Perfil del Usuario',
            'InfoUsuario'=>$infoUsuario
           
            //'RelSubOrgAgente'=>$RelSubOrgAgente
        );
        //dd($infoPlaza);
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">Bandeja</a></li>
            <li class="breadcrumb-item active"><a href="'.route('perfilMulticuenta').'">Perfil</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('registro.perfilMulticuenta',$datos);
    }

    public function formPerfilDoc(Request $request){
        //dd($request);
        /*
            "_token" => "pgnE00WjTgGrh5rAxqjtUOjF4DvBvQauzFTgTUCJ"
            "apellido" => "LOYOLA"
            "nombre" => "LEO MARTIN"
            "documento" => "26731952"
            "email" => "masterdjmov@gmail.com"
            "clave" => "123"
            "user" => "830"
            se agrego usuario que es el rol
        */
        if($request->apellido != "" && $request->nombre !=""){
            $o = UsuarioModel::where('idUsuario',$request->user)->first();
                $o->Nombre = strtoupper($request->apellido)." ".strtoupper($request->nombre);
                $o->Clave = $request->clave;
                $o->ape = strtoupper($request->apellido);
                $o->nom = strtoupper($request->nombre);
                //$o->Documento = $request->documento;
                $o->Email = $request->email;
                $o->Usuario = $request->usuario;
                
            $o->save();
            session(['Usuario'=>$o->Nombre]);
            session(['NombreInstitucion'=>$o->Usuario]);
        
         return redirect()->back()->with('ConfirmarActualizarMiInfo','OK');
        }else{
            return redirect()->back()->with('ConfirmarActualizarMiInfoFail','OK');
        }
        
 
       

    }


    //datos personales
    public function datosPersonales(){
        $infoUsuario = UsuarioModel::where('idUsuario',session('idUsuario'))->first();
        $miInfo = AgenteRespaldoModel::where('Documento',$infoUsuario->Documento)->first();
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Perfil del Usuario',
            'InfoUsuario'=>$infoUsuario,
            'miInfo'=>$miInfo
           
            //'RelSubOrgAgente'=>$RelSubOrgAgente
        );
        //dd($infoPlaza);
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">Bandeja</a></li>
            <li class="breadcrumb-item active"><a href="'.route('datosPersonales').'">Datos Personales</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('registro.personal.datos_personales',$datos);
    }




















}
