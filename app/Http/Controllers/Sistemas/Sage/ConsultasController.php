<?php

namespace App\Http\Controllers\Sistemas\Sage;

use App\Http\Controllers\Controller;
use App\Models\Sage\PofIpeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Plugin\ForcedCopy;

class ConsultasController extends Controller
{
    public function consultaOrigenCargos(){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
        
        //traer escuelas desde instituciones extension
        $AgentesPof = PofIpeModel::all(); 
        
        //consultar origen a partir del dni y obtener el cargo, sacar la descripcion
        foreach($AgentesPof as $AgentePof){
            $documento = $AgentePof->Documento;
            //comparar con la tabla pof
            $codigoPof = $AgentePof->lcat . $AgentePof->ncat;   //cargo listo
            //buscar el cue a partir de la unidad de liquidacion
            $infoRealiq = DB::connection('DB8')->table('instarealiq')
            ->where('escu', $AgentePof->Escu)
            ->first();

            if($infoRealiq == null){
                $CUECOMPLETO = 'Sin CUE ';
            }else{
                $CUECOMPLETO = $infoRealiq->CUEA?$infoRealiq->CUEA:'S/D';
            }
            

            $encontrado =  DB::connection('DB7')->table('tb_pofmh')
                ->where('Agente', $documento)
                ->where('CUECOMPLETO', $CUECOMPLETO)
                ->join('tb_cargossalariales', 'tb_cargossalariales.idCargo', '=', 'tb_pofmh.Cargo')
                // ->join('tb_cargossalariales', function($join) {
                //     $join->on(DB::raw("CONCAT(tb_pof_ipe.lcat, tb_pof_ipe.ncat)"), '=', 'tb_cargossalariales.Codigo');
                // })
                ->where('tb_cargossalariales.Codigo', $codigoPof)
                ->first();
                //dd($encontrado);
            if($encontrado){    
                $origen  = $encontrado->Origen?$encontrado->Origen:'S/D';
                echo $documento . ' - ' . $origen  .' - CodigoLcatNcat: '. $codigoPof .' - CargoSalarial:'.$encontrado->Codigo . ' - ' .$CUECOMPLETO . ' - ';
                
                //consulto la tabla cargos origeenes y traigo su descripcion
                
                $oc = DB::connection('DB7')->table('tb_origenes_cargos')
                     ->where('idOrigenCargo', $origen)
                    ->first();
                
                if($oc == null){
                    $nombre_origen = 'S/D';
                }else{
                    $nombre_origen = $oc->nombre_origen?$oc->nombre_origen:'S/D';
                }
                echo $nombre_origen . ' - ';

                //buscar el cargo en la tabla
                $cargo = DB::connection('DB7')->table('tb_cargos_pof_origen')
                    ->where('idCargos_Pof_Origen', $nombre_origen)
                    ->first();

                if($cargo == null){
                    $nombre_cargo = 'S/D';
                }else{
                    $nombre_cargo = $cargo->nombre_cargo_origen?$cargo->nombre_cargo_origen:'S/D';
                }
                echo $nombre_cargo . '<br>';
                
                 $AgentePof->CargoOrigen = $nombre_cargo;
               $AgentePof->save(); 
                
            }
           
        }
        //actualizar la tabla pob_if en su campo CargoOrigen

        //repetir

    }

    public function consulta(){
        $mati = DB::connection('DB8')->table('instarealiq')->get()->toArray();
        
        return response()->json(['Mensaje' => $mati]);
    }
}
