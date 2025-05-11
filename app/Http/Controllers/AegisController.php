<?php

namespace App\Http\Controllers;

use App\Models\POFMH\AreasLiqModel;
use App\Models\POFMH\CondicionModel;
use App\Models\POFMH\PofmhActivosModel;
use App\Models\POFMH\PofmhAulas;
use App\Models\POFMH\PofmhDivisiones;
use App\Models\POFMH\PofmhModel;
use App\Models\POFMH\PofmhOrigenCargoModel;
use App\Models\POFMH\PofMhSitRev;
use App\Models\POFMH\PofmhTurnos;
use App\Models\titulo\datosPersonalesModelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AegisController extends Controller
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
    public function infoRegTitulo(){
        //$Establecimiento = establecimientosModelo::all();
        //$Certificado = certificadosModelo::all();
        
        //$Titulos = tituloModelo::all();
       //$Registro_Titulo = registroTituloModelo::orderBy('idRegistroTitulo', 'desc')->first();
        //$Registro_Certificado = registroDeCertificadosModelo::orderBy('idRegistroCertificado', 'desc')->first();

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
        <li class="breadcrumb-item active"><a href="'.route('infoRegTitulo').'"> Títulos  y Certificado - Consultas</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('aegis.infoRegTitulo',$datos); 
    }

    public function infoSuperior(){

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
        <li class="breadcrumb-item active"><a href="'.route('infoRegTitulo').'"> Títulos  y Certificado - Consultas</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('aegis.infoSuperior',$datos); 
    }

    public function infoSuri(){
        //$Establecimiento = establecimientosModelo::all();
        //$Certificado = certificadosModelo::all();
        
        //$Titulos = tituloModelo::all();
       //$Registro_Titulo = registroTituloModelo::orderBy('idRegistroTitulo', 'desc')->first();
        //$Registro_Certificado = registroDeCertificadosModelo::orderBy('idRegistroCertificado', 'desc')->first();

        $infoUsuario = DB::table('tb_usuarios')
        ->where('idUsuario',session('idUsuario'))
        ->first();

        $Agente = datosPersonalesModelo::where('dni',$infoUsuario->Documento)->first();


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
        <li class="breadcrumb-item active"><a href="'.route('infoRegTitulo').'"> Títulos  y Certificado - Consultas</a></li>
        '; 
        session(['ruta' => $ruta]);
        return view('aegis.infoSuri',$datos); 
    }

    public function infoSAGE(){
        // Obtengo la información del usuario desde la sesión
        $infoUsuario = session('InfoUsuario');
        
       
        $infoCUE = DB::connection('DB7')->table('tb_pofmh')
            ->select('CUECOMPLETO')
            ->where('Agente', $infoUsuario->Documento)
            ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
            ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
            ->distinct()
            //->pluck('CUECOMPLETO') 
            ->get(); 
        //dd($infoCUE);
    
       
    
       
        $Agente = datosPersonalesModelo::where('dni', $infoUsuario->Documento)->first();
        $CargosSalariales =   DB::table('tb_cargossalariales')->get();
        $Condiciones =   CondicionModel::all();
        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $Turnos =   PofmhTurnos::all();
        $Activos =   PofmhActivosModel::all();
        $OrigenesDeCargos = PofmhOrigenCargoModel::all();
        $Motivos =   DB::table('tb_motivos')->get();
        $SitRev =   PofMhSitRev::all();
        $CodArea = AreasLiqModel::all();

        $datos = array(
            'mensajeError' => "",
            'TrabajosCUES' => $infoCUE, // CUEs únicos
            'Agente' => $Agente,
            'CargosSalariales'=>$CargosSalariales,
            'Divisiones'=>$Divisiones,
            'Turnos'=>$Turnos,
            'SitRev'=>$SitRev,
            'Motivos'=>$Motivos,
            'Condiciones'=>$Condiciones,
            'Aulas'=>$Aulas,
            'Activos'=>$Activos,
            'CodArea'=>$CodArea,
            'OrigenesDeCargos'=>$OrigenesDeCargos,
            'FechaActual' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'mensajeNAV' => 'Panel de Registro de Títulos'
        );
    
        // 6. Configuro la ruta de navegación
        $ruta = '
        <li class="breadcrumb-item active"><a href="#">Aegis</a></li>
        <li class="breadcrumb-item active"><a href="'.route('infoSAGE').'"> Consulta de Legajo Institucional</a></li>
        '; 
        session(['ruta' => $ruta]);
    
        // Retorno la vista con los datos
        return view('aegis.infoSAGE', $datos); 
    }

    public function ActualizarPofmhRecibo(Request $request)
    {
        // $validated = $request->validate([
        //     'codliq' => 'required|string|max:255',
        //     'descescuela' => 'required|string|max:255',
        //     'codtrabajo' => 'required|integer|min:1', 
        //     'codarea' => 'required|string|max:255',
        //     'idPof' => 'required|integer'
        // ]);
    
        // Actualizar la base de datos
        $pof = PofmhModel::find($request->idPof);
        if ($pof) {
            if (is_numeric($request->codliq)) {
                $pof->Unidad_Liquidacion_Recibo = str_pad($request->codliq, 3, '0', STR_PAD_LEFT);
            } else {
                $pof->Unidad_Liquidacion_Recibo = $request->codliq;
            }
            $pof->Descripcion_Recibo = strtoupper($request->descescuela); //$validated['descescuela'];
            $pof->Trabajo_Recibo = str_pad($request->codtrabajo, 3, '0', STR_PAD_LEFT);//$request->codtrabajo;//$validated['codtrabajo'];
            $pof->Codigo_Area_Recibo = $request->codarea;//$validated['codarea'];
            $pof->save();
    
            return response()->json(['message' => 'Datos actualizados correctamente.'], 200);
        }
    
        return response()->json(['message' => 'Registro no encontrado.'], 404);
    }
    











}
