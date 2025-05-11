<?php

namespace App\Http\Controllers;

use App\Models\EdificioModel;
use App\Models\InstitucionExtensionModel;
use App\Models\PadronModel;
use App\Models\SubOrganizacionesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use function PHPUnit\Framework\isEmpty;

class SistemaController extends Controller
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
    public function vincularSubOrgEdi(){
        //busco las suborg, todas
        $suborganizaciones = DB::table('tb_suborganizaciones')->get();
            //por cada sub debo crear un edificio y colocarle los datos que tengo en las sub
            foreach($suborganizaciones as $sub){
                //creo un edificio y le asigno los datos que tengo temporalmente en suborg
                $edificio = new EdificioModel();
                $edificio->Domicilio = $sub->Domicilio;
                $edificio->ZonaSupervision = $sub->ZonaSupervision;
                $edificio->save();
 
                //obtengo el id, ahora se lo paso a la sub seleccionada
                $selecSub = SubOrganizacionesModel::where('idSubOrganizacion', $sub->idSubOrganizacion)
                ->update(['Edificio'=>$edificio->idEdificio]);

               /* DB::table('post')
                ->where('id', 3)
                ->update(['title' => "Updated Title"]);*/
            }
            echo "<hr>FIN";
    }

    public function buscar_dni_cue(Request $request){
        
        if($_POST){
            $indoDesglose=0;
            $indoDesglose2=0;
            if(isset($_POST['btnCUE'])){
                $indoDesglose = DB::table('tb_desglose_agentes')
                ->leftjoin('tb_institucion', 'tb_institucion.Unidad_Liquidacion', '=', 'tb_desglose_agentes.escu')
                ->where(function ($query) use ($request) {
                    $query->where('tb_institucion.CUE', 'like', '%' . $request->dni . '%');
                })
                ->select(
                    'tb_institucion.*',
                    'tb_desglose_agentes.*',
                    'tb_desglose_agentes.area as desc_area'
                )
                ->get();

                $indoDesglose2 = DB::table('tb_jardines')
                ->where(function ($query) use ($request) {
                    $query->where('tb_jardines.CUE', 1)
                        ->orWhere('tb_jardines.Nombre', 'like', '%AHASJASKJHASASAS%');
                })
                ->select(
                    'tb_jardines.*',
                  
                )
                ->get();
            }

            if(isset($_POST['btnDNI'])){
                $indoDesglose = DB::table('tb_desglose_agentes')
                ->leftjoin('tb_institucion', 'tb_institucion.Unidad_Liquidacion', '=', 'tb_desglose_agentes.escu')
                ->where(function ($query) use ($request) {
                    $query->where('tb_desglose_agentes.docu', $request->dni)
                        ->orWhere('tb_desglose_agentes.nomb', 'like', '%' . $request->dni . '%');
                })
                ->select(
                    'tb_institucion.*',
                    'tb_desglose_agentes.*',
                    'tb_desglose_agentes.area as desc_area'
                )
                ->get();

                $indoDesglose2 = DB::table('tb_jardines')
                ->where(function ($query) use ($request) {
                    $query->where('tb_jardines.CUE', 1)
                        ->orWhere('tb_jardines.Nombre', 'like', '%AHASJASKJHASASAS%');
                })
                ->select(
                    'tb_jardines.*',
                  
                )
                ->get();
            }

            if(isset($_POST['btnCUE2'])){
                $indoDesglose2 = DB::table('tb_jardines')
                ->where(function ($query) use ($request) {
                    $query->where('tb_jardines.CUE', $request->dni)
                        ->orWhere('tb_jardines.Nombre', 'like', '%' . $request->dni . '%');
                })
                ->select(
                    'tb_jardines.*',
                  
                )
                ->get();
                $indoDesglose=DB::table('tb_desglose_agentes')
                    ->join('tb_institucion', 'tb_institucion.Unidad_Liquidacion', '=', 'tb_desglose_agentes.escu')
                // ->join('tb_institucion_extension', 'tb_institucion_extension.idInstitucion', '=', 'tb_institucion.idInstitucion')
                    ->where('tb_desglose_agentes.docu','1')
                    ->select(
                    'tb_institucion.*',
                    //'tb_institucion_extension.*',
                    'tb_desglose_agentes.*'
                    )
                    ->get();
            }
            //dd($indoDesglose);
            $datos=array(
                'estado'=>"Agente Localizado",
                'indoDesglose'=>$indoDesglose,
                'indoDesglose2'=>$indoDesglose2,
                'dniUsuario'=>$request->dni
            );
            //dd($indoDesglose);
        }else{
            $indoDesglose=DB::table('tb_desglose_agentes')
            ->join('tb_institucion', 'tb_institucion.Unidad_Liquidacion', '=', 'tb_desglose_agentes.escu')
           // ->join('tb_institucion_extension', 'tb_institucion_extension.idInstitucion', '=', 'tb_institucion.idInstitucion')
            ->where('tb_desglose_agentes.docu','1')
            ->select(
            'tb_institucion.*',
            //'tb_institucion_extension.*',
            'tb_desglose_agentes.*'
            )
            ->get();
            
            $indoDesglose2 = DB::table('tb_jardines')
            ->where(function ($query) use ($request) {
                $query->where('tb_jardines.CUE', 1)
                    ->orWhere('tb_jardines.Nombre', 'like', '%AHASJASKJHASASAS%');
            })
            ->select(
                'tb_jardines.*',
              
            )
            ->get();
            $datos=array(
                'estado'=>"Sin Accion",
                'indoDesglose'=>$indoDesglose,
                'indoDesglose2'=>$indoDesglose2,
                'dniUsuario'=>1
            );
        }
        /*$indoDesglose=DB::table('tb_desglose_agentes')
        //->join('tb_institucion', 'tb_institucion.idInstitucion', '=', 'tb_desglose_agentes.escu')
        ->select(
            //'tb_institucion.*',
            'tb_desglose_agentes.*'
        )
        ->get();*/


        //dd($indoDesglose);
        //traemos otros array
       
        //lo guardo para controlar a las personas de una determinada cue/suborg

        //dd($plazas);
        return view('bandeja.LUP.usuarios_dni_cue',$datos);
    }
    public function buscar_dni_liq(Request $request){
        
        if($_POST){
            $indoDesglose=0;
            $indoDesglose2=0;
            //dd($request);
            if(isset($_POST['btnDNI'])){
                // Primero, obtenemos los IDs únicos
                $uniqueIds = DB::table('tb_nodos')
                ->join('tb_agentes', 'tb_agentes.Documento', '=', 'tb_nodos.Agente')
                ->where(function ($query) use ($request) {
                    $query->where('tb_agentes.Documento', 'like', '%' . $request->dni . '%');
                })
                ->select('tb_nodos.idNodo') // Asumiendo que 'id' es un campo único en 'tb_nodos'
                ->distinct()
                ->pluck('tb_nodos.idNodo');
                //dd($uniqueIds);
                // Luego, usamos esos IDs para obtener todos los detalles
                $indoDesglose = DB::table('tb_nodos')
                ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', '=', 'tb_nodos.CUECOMPLETO')
                ->join('tb_agentes', 'tb_agentes.Documento', '=', 'tb_nodos.Agente')
                ->whereIn('tb_nodos.idNodo', $uniqueIds)
                ->select(
                    'tb_institucion_extension.*',
                    'tb_institucion_extension.Localidad as iloc',
                    'tb_agentes.*',
                    'tb_nodos.*'
                )
                ->get();
                
                // Filtrar duplicados basados en campos relevantes
                $indoDesglose = $indoDesglose->unique(function ($item) {
                    return $item->Documento . '-' . $item->CUECOMPLETO;
                });     
                //dd($indoDesglose);  
            }

            $datos=array(
                'estado'=>"Agente Localizado",
                'indoDesglose'=>$indoDesglose->values(),
                'dniUsuario'=>$request->dni
            );
            //dd($indoDesglose);
        }else{
            $indoDesglose=DB::table('tb_nodos')
            ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', '=', 'tb_nodos.CUECOMPLETO')
            ->join('tb_agentes', 'tb_agentes.Documento', '=', 'tb_nodos.Agente')
            ->where('tb_agentes.Documento','1')
            ->select(
            'tb_institucion_extension.*',
            'tb_institucion_extension.Localidad as iloc',
            'tb_agentes.*',
            'tb_nodos.*'
            )
            ->get();
            
            
            $datos=array(
                'estado'=>"Sin Accion",
                'indoDesglose'=>$indoDesglose,
                'dniUsuario'=>1
            );
        }
       
        return view('bandeja.ADMIN.usuarios_dni_liq',$datos);
    }
    public function buscar_cue_liq(Request $request){
        
        if($_POST){
            $indoDesglose=0;
            $indoDesglose2=0;

            if(isset($_POST['btnCUE'])){
                // Primero, obtenemos los IDs únicos
                $uniqueIds = DB::table('tb_nodos')
                ->join('tb_agentes', 'tb_agentes.Documento', '=', 'tb_nodos.Agente')
                ->where(function ($query) use ($request) {
                    $query->where('tb_nodos.CUECOMPLETO', 'like', '%' . $request->cue . '%');
                })
                ->select('tb_nodos.idNodo') // Asumiendo que 'id' es un campo único en 'tb_nodos'
                ->distinct()
                ->pluck('tb_nodos.idNodo');
                
                // Luego, usamos esos IDs para obtener todos los detalles
                $indoDesglose = DB::table('tb_nodos')
                ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', '=', 'tb_nodos.CUECOMPLETO')
                ->join('tb_agentes', 'tb_agentes.Documento', '=', 'tb_nodos.Agente')
                ->whereIn('tb_nodos.idNodo', $uniqueIds)
                ->select(
                    'tb_institucion_extension.*',
                    'tb_institucion_extension.Localidad as iloc',
                    'tb_agentes.*',
                    'tb_nodos.*'
                )
                ->get();
            
                // Filtrar duplicados basados en campos relevantes
                $indoDesglose = $indoDesglose->unique(function ($item) {
                    return $item->Documento . '-' . $item->CUECOMPLETO;
                });       
            }

            $datos=array(
                'estado'=>"Agente Localizado",
                'indoDesglose'=>$indoDesglose->values(),
               
            );
            //dd($indoDesglose);
        }else{
            $indoDesglose=DB::table('tb_nodos')
            ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', '=', 'tb_nodos.CUECOMPLETO')
            ->join('tb_agentes', 'tb_agentes.Documento', '=', 'tb_nodos.Agente')
            ->where('tb_agentes.Documento','1')
            ->select(
            'tb_institucion_extension.*',
            'tb_institucion_extension.Localidad as iloc',
            'tb_agentes.*',
            'tb_nodos.*'
            )
            ->get();
            
            
            $datos=array(
                'estado'=>"Sin Accion",
                'indoDesglose'=>$indoDesglose,
                'dniUsuario'=>1
            );
        }
       
        return view('bandeja.ADMIN.usuarios_cue_liq',$datos);
    }

    /*
    Esta función sera para traer todas las zonas de supervision 
    */
    public function buscar_zonas_consultas(Request $request){
        
        if($_POST){
            $indoDesglose=0;
            $indoDesglose2=0;

            if(isset($_POST['btnCUE'])){
                $indoDesglose = DB::table('tb_desglose_agentes')
                ->leftjoin('tb_institucion', 'tb_institucion.Unidad_Liquidacion', '=', 'tb_desglose_agentes.escu')
                ->where(function ($query) use ($request) {
                    $query->where('tb_institucion.CUE', 'like', '%' . $request->cue . '%');
                })
                ->select(
                    'tb_institucion.*',
                    'tb_desglose_agentes.*',
                    'tb_desglose_agentes.area as desc_area'
                )
                ->get();

            }

            $datos=array(
                'estado'=>"Agente Localizado",
                'indoDesglose'=>$indoDesglose,
               
            );
            //dd($indoDesglose);
        }else{
            $indoDesglose=DB::table('tb_desglose_agentes')
            ->join('tb_institucion', 'tb_institucion.Unidad_Liquidacion', '=', 'tb_desglose_agentes.escu')
           // ->join('tb_institucion_extension', 'tb_institucion_extension.idInstitucion', '=', 'tb_institucion.idInstitucion')
            ->where('tb_desglose_agentes.docu','1')
            ->select(
            'tb_institucion.*',
            //'tb_institucion_extension.*',
            'tb_desglose_agentes.*'
            )
            ->get();
            
            
            $datos=array(
                'estado'=>"Sin Accion",
                'indoDesglose'=>$indoDesglose,
                'dniUsuario'=>1
            );
        }
       
        return view('bandeja.ADMIN.usuarios_zonas',$datos);
    }
    //funcion manual para ubicar ambito, sector y estado
    public function actualizarValoresInstituciones(){
        // Traer todos los padrones
        $padron = PadronModel::get();
        
        // Iterar sobre cada registro del padron
        foreach($padron as $p){
            // Buscar las extensiones correspondientes al CUECOMPLETO
            $inst_Ext = InstitucionExtensionModel::where('CUECOMPLETO', $p->CUECOMPLETO)->get();
            
            // Verificar si se encontraron extensiones
            if($inst_Ext->isNotEmpty()){
                // Iterar sobre cada extensión encontrada para actualizarla
                foreach ($inst_Ext as $extension) {
                    $extension->Ambito = $p->Ambito;
                    $extension->EsPrivada = $p->Sector;
                    $extension->Habilitado = $p->Estado;
                    $extension->Departamento = $p->Departamento;
                    $extension->Localidad = $p->Localidad;
                    $extension->Oferta_Tipo = $p->Oferta_Tipo;
                    $extension->Telefono = $p->Telefono;
                    $extension->save();
                }
            }
        }
        
        echo "Fin de modificaciones";
    }
    
}
