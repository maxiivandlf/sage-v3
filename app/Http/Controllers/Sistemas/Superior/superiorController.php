<?php

namespace App\Http\Controllers\Sistemas\Superior;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\titulo\datosPersonalesModelo;

class superiorController extends Controller
{
    public function infoSuperior(){

        $infoUsuario = DB::table('tb_usuarios')
        ->where('idUsuario',session('idUsuario'))
        ->first();

        //vemos si hay algo en la tabla de agentes pero en titulo
        $Agente = DB::table('tb_agentes')->where('Documento',$infoUsuario->Documento)->first();
        //$Agente = DB::table('tb_agentes_exportados')->where('Documento',$infoUsuario->Documento)->first();
        //dd($Agente);

        $datos=array(
            'mensajeError'=>"",
            //'Establecimiento'=>$Establecimiento,
            //'Certificados'=>$Certificado,
            'Agente'=>$Agente,
           // 'Titulos'=>$Titulos,
            //'ultimoRegistroTitulo'=>$Registro_Titulo,
           // 'ultimoRegistroCertificado'=>$Registro_Certificado,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Registro de Títulos'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Aegis</a></li>
        <li class="breadcrumb-item active"><a href="'.route('infoSuperior').'"> Superior - Consultas</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('aegis.sistemas/superior/infoSuperior',$datos); 
    }
    public function infoSuperior2(){

        $infoUsuario = DB::table('tb_usuarios')
        ->where('idUsuario',session('idUsuario'))
        ->first();

        //vemos si hay algo en la tabla de agentes pero en titulo
        $Agente = datosPersonalesModelo::where('dni',$infoUsuario->Documento)->first();
        //dd($Agente);

        $datos=array(
            'mensajeError'=>"",
            //'Establecimiento'=>$Establecimiento,
            //'Certificados'=>$Certificado,
            'Agente'=>$Agente,
           // 'Titulos'=>$Titulos,
            //'ultimoRegistroTitulo'=>$Registro_Titulo,
           // 'ultimoRegistroCertificado'=>$Registro_Certificado,
            'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV'=>'Panel de Registro de Títulos'

        );
        $ruta ='
        <li class="breadcrumb-item active"><a href="#">Aegis</a></li>
        <li class="breadcrumb-item active"><a href="'.route('infoSuperior2').'"> Superior - Cargar Llamados</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('aegis.sistemas/superior/infoSuperior2',$datos); 
    }
}
