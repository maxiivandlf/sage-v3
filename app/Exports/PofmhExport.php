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
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
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
        $todosLosDatos = [];
        $procesado = 0;
        PofmhModel::orderBy('orden', 'ASC')->chunk(1000, function ($pofCompleta) use (&$todosLosDatos, &$procesado) {
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
                $infoOrigen = PofmhOrigenCargoModel::where('idOrigenCargo', $pof->Origen)
                    ->join('tb_cargos_pof_origen', 'tb_cargos_pof_origen.idCargos_Pof_Origen', '=', 'tb_origenes_cargos.nombre_origen')
                    ->select('tb_cargos_pof_origen.nombre_cargo_origen as nombre_origen')
                    ->first();
                $infoSitRev = PofMhSitRev::where('idSituacionRevista', $pof->SitRev)->first();
                $infoCargoSal = DB::table('tb_cargossalariales')->where('idCargo', $pof->Cargo)->first();
                $infoAula = PofmhAulas::where('idAula', $pof->Aula)->first();
                $infoDivision = PofmhDivisiones::where('idDivision', $pof->Division)->first();
                $infoTurno = PofmhTurnos::where('idTurno', $pof->Turno)->first();
                $infoCondicion = CondicionModel::where('idCondicion', $pof->Condicion)->first();
                $infoActivo = PofmhActivosModel::where('idActivo', $pof->Activo)->first();
                $infoMotivo = DB::table('tb_motivos')->where('idMotivo', $pof->Motivo)->first(); 
                $infoInstitucion = DB::table('tb_institucion')->where('CUECOMPLETO',$pof->CUECOMPLETO)->first();
                $infoUsuarioDatos = DB::table('tb_agentes')->where('Documento',$pof->Agente)->first();
                $infoInstitucionExt = DB::table('tb_institucion_extension')->where('CUECOMPLETO',$pof->CUECOMPLETO)->first();
                $mes = 11; // Mes de noviembre
               /* $asistenciasAgrupadas = DB::connection('DB7')->table('tb_asistencia')
                ->select('tb_asistencia.tipoAsistencia', DB::raw('COUNT(*) as totalDias'))
                ->where('tb_asistencia.idPofmh', $pof->idPofmh)
                ->whereMonth('tb_asistencia.created_at', 11) // Filtrar solo noviembre
                ->groupBy('tb_asistencia.tipoAsistencia') // Agrupar por tipo de asistencia
                ->orderBy('tb_asistencia.tipoAsistencia', 'ASC') // Ordenar por el ID de tipo de asistencia
                ->get();
*/
                // Inicializar las variables con valores predeterminados
                // Consulta para cada tipo de asistencia
                $presentes = 0;
                $relevos = 0;
                $faltasJustificadas = 0;
                $faltasInjustificadas = 0;
                $licencias = 0;
                $otros = 0;

                /*$presentes = DB::connection('DB7')->table('tb_asistencia')->where('idPofmh', $pof->idPofmh)
                ->whereMonth('created_at', $mes)
                ->where('tipoAsistencia', 1) // 1 es el tipo para 'Presente'
                ->count();*/

                /*$faltasJustificadas = DB::connection('DB7')->table('tb_asistencia')->where('idPofmh', $pof->idPofmh)
                ->whereMonth('created_at', $mes)
                ->where('tipoAsistencia', 2) // 2 es el tipo para 'Falta Justificada'
                ->count();*/

                /*$relevos = DB::connection('DB7')->table('tb_asistencia')->where('idPofmh', $pof->idPofmh)
                ->whereMonth('created_at', $mes)
                ->where('tipoAsistencia', 3) // 3 es el tipo para 'Relevo'
                ->count();*/

                /*$licencias = DB::connection('DB7')->table('tb_asistencia')->where('idPofmh', $pof->idPofmh)
                ->whereMonth('created_at', $mes)
                ->where('tipoAsistencia', 4) // 4 es el tipo para 'Licencia'
                ->count();*/

                /*$otros = DB::connection('DB7')->table('tb_asistencia')->where('idPofmh', $pof->idPofmh)
                ->whereMonth('created_at', $mes)
                ->where('tipoAsistencia', 5) // 5 es el tipo para 'Otro Motivo'
                ->count();*/

                /*$faltasInjustificadas = DB::connection('DB7')->table('tb_asistencia')->where('idPofmh', $pof->idPofmh)
                ->whereMonth('created_at', $mes)
                ->where('tipoAsistencia', 7) // 7 es el tipo para 'Falta Injustificada'
                ->count();*/
                
                // Asignar los valores de las asistencias agrupadas a las variables
               /* foreach ($asistenciasAgrupadas as $asistencia) {
                    switch ($asistencia->tipoAsistencia) {
                        case 1: // PRESENTE
                            $presentes = $asistencia->totalDias;
                            break;
                        case 2: // FALTA JUSTIFICADA
                            $faltasJustificadas = $asistencia->totalDias;
                            break;
                        case 3: // RELEVO
                            $relevos = $asistencia->totalDias;
                            break;
                        case 4: // LICENCIA
                            $licencias = $asistencia->totalDias;
                            break;
                        case 5: // OTRO MOTIVO
                            $otros = $asistencia->totalDias;
                            break;
                        case 7: // FALTA INJUSTIFICADA
                            $faltasInjustificadas = $asistencia->totalDias;
                            break;
                        default:
                            // Si hay otros tipos, manejarlos aquí si es necesario
                            break;
                    }
                }*/
                //dd($asistenciasAgrupadas);
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
                    'orden' => $pof->orden ?? "S/D",
                    'Agente' => $pof->Agente ?? "S/D",
                    'Cuil' => $infoUsuarioDatos->Cuil ?? "S/D",
                    'ApeNom' => $infoUsuarioDatos->ApeNom ?? "S/D",
                    'Sexo' => $infoUsuarioDatos->Sexo ?? "S/D",
                    'Unidad_Liquidacion' => $cadenaUnidLiq ?? "S/D",
                    'CUECOMPLETO' => $pof->CUECOMPLETO ?? "S/D",
                    'Nombre_Institucion' => $infoInstitucionExt->Nombre_Institucion ?? "S/D",
                    'Zona' => $infoInstitucionExt->Zona ?? "S/D",
                    'ZonaSupervision' => $infoEscuela->Descripcion ?? "S/D",
                    'ZonaSupervisionCodigo' => $infoEscuela->Codigo ?? "S/D",
                    'Localidad' => $infoInstitucionExt->Localidad ?? "S/D",
                    'Nivel' => $infoInstitucionExt->Nivel ?? "S/D",
                    'Origen' => $infoOrigen->nombre_origen ?? "S/D",
                    'SitRev' => $infoSitRev->Descripcion ?? "S/D",
                    'Horas' => $pof->Horas ?? "S/D",
                    'Antiguedad' => $pof->Antiguedad ?? "S/D",
                    'CargoSalarial' => $infoCargoSal->Cargo ?? "S/D",
                    'CodigoSalarial' => $infoCargoSal->Codigo ?? "S/D",
                    'Aula' => $infoAula->nombre_aula ?? "S/D",
                    'Division' => $infoDivision->nombre_division ?? "S/D",
                    'Turno' => $infoTurno->nombre_turno ?? "S/D",
                    'EspCur' => $pof->espcur ?? "S/D",
                    'Matricula' => $pof->matricula ?? "S/D",
                    'FechaAltaCargo' => $pof->FechaAltaCargo ?? "S/D",
                    'FechaDesignado' => $pof->FechaDesignado ?? "S/D",
                    'Condicion' => $infoCondicion->Descripcion ?? "S/D",
                    'Activo' => $infoActivo->nombre_activo ?? "S/D",
                    'Motivo' => $infoMotivo ? $infoMotivo->Codigo . "-" . $infoMotivo->Nombre_Licencia : "S/D",
                    'DatosPorCondicion' => $pof->DatosPorCondicion ?? "S/D",
                    'FechaDesde' => $pof->FechaDesde ?? "S/D",
                    'FechaHasta' => $pof->FechaHasta ?? "S/D",
                    'AgenteR' => $pof->AgenteR ?? "S/D",
                    'Presentes' => $presentes ?? "0",
                    'Relevos' => $relevos ?? "0",
                    'Faltas Justificadas' => $faltasJustificadas ?? "0",
                    'Faltas Injustificadas' => $faltasInjustificadas ?? "0",
                    'Faltas Licencias' => $licencias ?? "0",
                    'Faltas Otros' => $otros ?? "0",
                    'Observaciones' => $pof->Observaciones ?? "S/D",
                    'Carrera' => $pof->Carrera ?? "S/D",
                    'Orientacion' => $pof->Orientacion ?? "S/D",
                    'Titulo' => $pof->Titulo ?? "S/D",
                    'Unidad_Liquidacion_Recibo' => $pof->Unidad_Liquidacion_Recibo ?? "S/D",
                    'Trabajo_Recibo' => $pof->Trabajo_Recibo ?? "S/D",
                    'Descripcion_Recibo' => $pof->Descripcion_Recibo ?? "S/D",
                    'Codigo_Area_Recibo' => $pof->Codigo_Area_Recibo ?? "S/D",
                ];
                //echo "Procesado de Asistencia n: '.$procesado.' para El id ".$pof->idPofmh."- Agente: ".$pof->Agente.'<br>';
                //$procesado++;
                $todosLosDatos[] = $datosFila; // Agregar la fila procesada al array
            }
        });
    
        return collect($todosLosDatos); // Devolver una colección de datos
    }
    

    public function headings(): array
    {
        return [
            'orden',
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
            'Origen',
            'SitRev',
            'Horas',
            'Antiguedad',
            'CargoSalarial',
            'CodigoSalarial',
            'Aula',
            'Division',
            'Turno',
            'EspCur',
            'Matricula',
            'FechaAltaCargo',
            'FechaDesignado',
            'Condicion',
            'Activo',
            'Motivo',
            'DatosPorCondicion',
            'FechaDesde',
            'FechaHasta',
            'AgenteR',
            'Presentes',
            'Relevos',
            'Faltas Justificadas',
            'Faltas Injustificadas',
            'Faltas Licencias',
            'Faltas Otros',
            'Observaciones',
            'Carrera',
            'Orientacion',
            'Titulo',
            'Unidad_Liquidacion_Recibo',
            'Trabajo_Recibo',
            'Descripcion_Recibo',
            'Codigo_Area_Recibo',
        ];
    }
}
