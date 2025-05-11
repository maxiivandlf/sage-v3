<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\EjemploMailConAdjunto;
use App\Models\UsuarioModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
class RegistroMulticuenta extends Controller
{
    public function registrarDocente(){
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Creación de Usuarios',
           
            //'RelSubOrgAgente'=>$RelSubOrgAgente
        );
        //dd($infoPlaza);
        return view('registro.registroDocente',$datos);
    }

    public function preregistroDocente(){
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Creación de Usuarios',
           
            //'RelSubOrgAgente'=>$RelSubOrgAgente
        );
        //dd($infoPlaza);
        return view('registro.contratoDocente',$datos);
    }

    public function formRegDoc(Request $request){
        //dd($request);
        /*
        "_token" => "SwbvlP8knv5gkbK18HOIPwg8BhDbqmwIBFDDNsc1"
        "documento" => "26731952"
        "apellido" => "Loyola"
        "nombre" => "Leo Martin"
        "email" => "masterdjmov@gmail.com"
        "password1" => "123"
        "password2" => "123"
        */
        
        //controlo si existen los datos
        $encontradoUsuario = $Usuario = DB::table('tb_usuarios')
        ->where('Documento',$request->documento)
        ->count();

        $encontradoEmail = $Usuario = DB::table('tb_usuarios')
        ->where('email',$request->email)
        ->count();
        //dd($encontrado);
        if($encontradoEmail>0 ||$encontradoUsuario > 0){
            return redirect()->back()->with('DatosEncontrados','OK');
        }else{
            $o = new UsuarioModel();
                $o->Nombre = strtoupper($request->apellido)." ".strtoupper($request->nombre);
                $o->Clave = $request->password1;
                $o->Usuario = "Multicuenta";
                $o->ape = strtoupper($request->apellido);
                $o->nom = strtoupper($request->nombre);
                $o->Documento = $request->documento;
                $o->Activo = 'S';
                $o->Email = $request->email;
                $o->idReparticion = 1;
                $o->Nivel = 119;    //los dejo sin datos
                $o->Modo = 13;     //13 es Multicuenta, para los docentes principalmente
                $o->Dependencia = 1;    //sin dependencia
                $o->CUE = null;
                $o->Turno = 1;
                $o->CUEa = null;
                $o->CUECOMPLETO = null;
                $o->avatar="img_profile.svg";
          $o->save();
            return redirect()->back()->with('ConfirmadoRegistroMultiCuenta','OK');
        }
    }

    public function buscar_usuario(Request $request){
        // Toma del data el email
        $email = $request->input('email');
        //return response()->json(['status' => 200, 'msg' => "bien"], 200);
        // Realiza la búsqueda en la base de datos utilizando el modelo UsuarioModel
        $usuario = UsuarioModel::where('email', $email)->first();
        if ($usuario) {
            // Si se encuentra un usuario con el email especificado, devuelve el email
            return response()->json(['status' => 200, 'msg' => "No disponible"], 200);
        } else {
            // Si no se encuentra ningún usuario con el email, devuelve un mensaje indicando que no se encontró
            return response()->json(['status' => 200, 'msg' => 'Disponible'], 200);
        }
    }

    //proceso de recuperar clave
    public function recuperarClaveMulticuenta(){
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Recuperar clave de Usuarios',
           
            //'RelSubOrgAgente'=>$RelSubOrgAgente
        );
        //dd($infoPlaza);
        return view('registro.recuperarClaveMulticuenta',$datos);
    }

    public function formRecDoc(Request $request){
        //dd($request);
        /*
        "_token" => "SwbvlP8knv5gkbK18HOIPwg8BhDbqmwIBFDDNsc1"
        "documento" => "26731952"
        "apellido" => "Loyola"
        "nombre" => "Leo Martin"
        "email" => "masterdjmov@gmail.com"
        "password1" => "123"
        "password2" => "123"
        */
        
        //controlo si existen los datos
        $encontrado = $Usuario = DB::table('tb_usuarios')
        ->where('email',$request->email)
        ->where('Documento',$request->documento)
        ->count();
      
        //dd($encontrado);
        if($encontrado==0){
            return redirect()->back()->with('SeAnuloRecuperoMulticuenta','OK');
        }else{
            $Usuario = DB::table('tb_usuarios')
            ->where('email',$request->email)
            ->where('Documento',$request->documento)
            ->first();

            $mensaje = "Su clave en SAGE Multicuenta es: <b>".$Usuario->Clave."</b>";
            $mensajePara = $request->email;
            //Mail::to($mensajePara)->send(new EjemploMailConAdjunto($mensaje));
            //return redirect()->back()->with('ConfirmadoRecuperarMultiCuenta','OK');
            try {
                Mail::to($mensajePara)->send(new EjemploMailConAdjunto($mensaje));
                return redirect()->back()->with('ConfirmadoRecuperarMultiCuenta', 'OK');
            } catch (\Exception $e) {
                return redirect()->back()->with('SeAnuloRecuperoMulticuenta-red','OK');
            }
        }
    }

    public function validarDni(Request $request)
    {
        $documento = $request->input('documento');
        $existe = UsuarioModel::where('Documento', $documento)->exists();
    
        return response()->json(['existe' => $existe]);
    }













}
