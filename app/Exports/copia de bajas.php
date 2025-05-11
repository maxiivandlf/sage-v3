<?php

namespace App\Exports;

use App\Models\POFMH\CondicionModel;
use App\Models\POFMH\PofmhActivosModel;
use App\Models\POFMH\PofmhAulas;
use App\Models\POFMH\PofmhDivisiones;
use App\Models\POFMH\PofmhOrigenCargoModel;
use App\Models\POFMH\PofMhSitRev;
use App\Models\POFMH\PofmhTurnos;
use App\Models\POFMH\PofmhModel;
use App\Models\POFMH\PofmhNovedades;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class PofmhExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        /*$pofCompleta = PofmhModel::where('Agente','26731952')
        ->orderBy('orden','ASC')
        ->get();*/
        //$pofCompleta = PofmhModel::limit(2000)
        //->orderBy('orden','ASC')
        //->get();
        //$pofCompleta = PofmhModel::orderBy('orden','ASC')->get();
        //whereIn('Condicion',[1,3,4,5])->
        //para cotejar por bajas whereIn('Condicion', [17, 25])
        $todosLosDatos = [];
        $procesado = 0;
        PofmhNovedades::where('Anio',2025)->whereIn('idNovedadExtra', [10])->orderBy('idNovedad', 'ASC')->chunk(1000, function ($pofCompleta) use (&$todosLosDatos, &$procesado) {
            // Recolectar los documentos de agentes y CUECOMPLETO en un solo paso
            $documentos = $pofCompleta->pluck('Agente')->unique()->toArray();
            $cueCompleto = $pofCompleta->pluck('CUECOMPLETO')->unique()->toArray();
    
            // Consultar información de agentes y escuelas
            $infoUsuarios = DB::table('tb_agentes')->whereIn('Documento', $documentos)->get()->keyBy('Documento');
            $infoEscuelas = DB::table('tb_institucion_extension')
            ->join('tb_zonasupervision','tb_zonasupervision.idZonaSupervision','=','tb_institucion_extension.ZonaSupervision')
            ->whereIn('CUECOMPLETO', $cueCompleto)
            //->where('EsPrivada','=','N')
            ->get()->keyBy('CUECOMPLETO');
    
            // Recorrer cada registro
            foreach ($pofCompleta as $pof) {
                
                $infoUsuario = $infoUsuarios->get($pof->Agente);
                $infoEscuela = $infoEscuelas->get($pof->CUECOMPLETO);
                /*$infoSitRev = PofMhSitRev::where('idSituacionRevista', $pof->SitRev)->first();
                $infoCargoSal = DB::table('tb_cargossalariales')->where('idCargo', $pof->Cargo)->first();
                $infoAula = PofmhAulas::where('idAula', $pof->Aula)->first();
                $infoDivision = PofmhDivisiones::where('idDivision', $pof->Division)->first();
                $infoTurno = PofmhTurnos::where('idTurno', $pof->Turno)->first();
                $infoCondicion = CondicionModel::where('idCondicion', $pof->Condicion)->first();
                $infoActivo = PofmhActivosModel::where('idActivo', $pof->Activo)->first();
                $infoMotivo = DB::table('tb_motivos')->where('idMotivo', $pof->Motivo)->first(); 
                $infoInstitucion = DB::table('tb_institucion')->where('CUECOMPLETO',$pof->CUECOMPLETO)->first();
                $infoUsuarioDatos = DB::table('tb_agentes')->where('Documento',$pof->Agente)->first();
                $infoInstitucionExt = DB::table('tb_institucion_extension')->where('CUECOMPLETO',$pof->CUECOMPLETO)->first();*/
                $mes = 11; // Mes de noviembre
               /* $asistenciasAgrupadas = DB::connection('DB7')->table('tb_asistencia')
                ->select('tb_asistencia.tipoAsistencia', DB::raw('COUNT(*) as totalDias'))
                ->where('tb_asistencia.idPofmh', $pof->idPofmh)
                ->whereMonth('tb_asistencia.created_at', 11) // Filtrar solo noviembre
                ->groupBy('tb_asistencia.tipoAsistencia') // Agrupar por tipo de asistencia
                ->orderBy('tb_asistencia.tipoAsistencia', 'ASC') // Ordenar por el ID de tipo de asistencia
                ->get();
*/
                

              
                //armo consulta para traer el unid mas o menos correcto desde sage 2.1
                $infoUnidadLiquidacion = DB::connection('DB8')->table('instarealiq')->where('CUEA',$pof->CUECOMPLETO)->get();
                if ($infoUnidadLiquidacion->isEmpty()) {
                    $cadenaUnidLiq = 'No hay resultados';
                } else {
                    $cadenaUnidLiq = $infoUnidadLiquidacion->map(function($item) {
                        return $item->escu . ' ' . $item->area; 
                    })->implode(','); 
                }
                
                $datosFila = [
                    'Agente' => $pof->Agente ?? "S/D",
                    'Cuil' => $infoUsuario->Cuil ?? "S/D",
                    'ApeNom' => $infoUsuario->ApeNom ?? "S/D",
                    'Sexo' => $infoUsuario->Sexo ?? "S/D",
                    'Unidad_Liquidacion' => $cadenaUnidLiq ?? "S/D",
                    'CUECOMPLETO' => $pof->CUECOMPLETO ?? "S/D",
                    'Nombre_Institucion' => $infoEscuela->Nombre_Institucion ?? "S/D",
                    'Zona' => $infoEscuela->Zona ?? "S/D",
                    'ZonaSupervision' => $infoEscuela->Descripcion ?? "S/D",
                    'ZonaSupervisionCodigo' => $infoEscuela->Codigo ?? "S/D",
                    'Localidad' => $infoEscuela->Localidad ?? "S/D",
                    'Nivel' => $infoEscuela->Nivel ?? "S/D",
                    'Mes' => $pof->Mes ?? "S/D",
                    'Anio' => $pof->Anio ?? "S/D",
                    'TotalDiasLicencia' => $pof->TotalDiasLicencia ?? "S/D",
                    'Observacion'=>$pof->Observaciones ?? "S/D",
                    'Fecha Desde' => isset($pof->FechaDesde) ? Carbon::parse($pof->FechaDesde)->format('d-m-Y') : "S/D",
                    'Fecha Hasta' => isset($pof->FechaHasta) ? Carbon::parse($pof->FechaHasta)->format('d-m-Y') : "S/D",

                ];
                //echo "Procesado de Asistencia n: '.$procesado.' para El id ".$pof->idPofmh."- Agente: ".$pof->Agente.'<br>';
                //$procesado++;
                $todosLosDatos[] = $datosFila; // Agregar la fila procesada al array
            }
        });
    
        return collect($todosLosDatos); // Devolver una colección de datos
    }
    

    //consulta por tabla novedad
  
    public function headings(): array
    {
        return [
           
            'Agente',
            'Cuil',
            'ApeNom',
            'Sexo',
            'Unidad_Liquidacion',
            'CUECOMPLETO',
            'Nombre_Institucion',
            'Zona',
            'ZonaSupervision',
            'ZonaSupervisionCodigo',
            'Localidad',
            'Nivel',
            'Mes',
            'Anio',
            'Total Dias Lic',
            'Observaciones',
            'Fecha Desde',
            'Fecha Hasta'
        ];
    }
}
