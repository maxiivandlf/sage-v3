<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EjemploMail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
class PruebaController extends Controller
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
    public function index(){
        $mensaje = "Este es un mensaje de ejemplo, saludos desde el planeta Tierra";
        $mensajePara = "hmatiasoyola@gmail.com";
        Mail::to($mensajePara)->send(new EjemploMail($mensaje));
        return "Enviado mensaje a ".$mensajePara;
    }

    public function verDatos(Request $request){

        return response()->json(array('status' => 200, 'msg' => "Guardado Correctamente: $request->cant_horas"), 200);
    }
}
