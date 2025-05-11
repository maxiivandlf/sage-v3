<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class ChatBlogController extends Controller
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
    public function chatBlog(Request $request){
       
        $turnos = DB::table('tb_turnos')->get();
        $regimen_laboral = DB::table('tb_regimenlaboral')->get();
        $fuentesDelFinanciamiento = DB::table('tb_fuentesdefinanciamiento')->get();
        $tiposDeFuncion = DB::table('tb_tiposdefuncion')->get();
        $Asignaturas = DB::table('tb_asignaturas')->get();
        $CargosSalariales = DB::table('tb_cargossalariales')->get();
        $datos=array(
            'mensajeError'=>"",
            //'idOrg'=>$organizacion[0]->Org,
           // 'NombreOrg'=>$organizacion[0]->Descripcion,
            //'CueOrg'=>$organizacion[0]->CUE,
           // 'infoSubOrganizaciones'=>$suborganizaciones,
            //'idSubOrg'=>$idSubOrg,  //la roto para pasarla a otras ventanas y saber donde volver
            //'infoPlazas'=>$plazas,
            //'CargosSalariales'=>$CargosSalariales,
            'Asignaturas'=>$Asignaturas,
            'tiposDeFuncion'=>$tiposDeFuncion,
        );
        $ruta ='
                <li class="breadcrumb-item active"><a href="#">EXTRAS</a></li>
                <li class="breadcrumb-item active"><a href="'.route('chatBlog').'">CHAT BLOG</a></li>
                '; 
                session(['ruta' => $ruta]);
        return view('bandeja.ChatBlog.chatBlog',$datos);
    }
}
