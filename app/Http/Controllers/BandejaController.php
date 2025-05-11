<?php

namespace App\Http\Controllers;

//use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class BandejaController extends Controller
{

    public function index(Request $request){
        if ($request->session()->has('Usuario') == true) {
            $datos =array(
                'mensaje'=>"hola");
            return view('bandeja.index',$datos);
        }else{
            Session::flush();
            return redirect('/salir');

        }
    }

    
    public function salir(){
        //dd('Método salir llamado');
        session(['Validar' => '']);
        Session::flush();
        //dd('Redirigiendo a la página principal');
        return redirect('/');
        //return redirect('/test');
    }
}
