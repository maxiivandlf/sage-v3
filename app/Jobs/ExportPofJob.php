<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\AgenteModel;
use App\Models\AgenteRespaldoModel;
use App\Models\DocumentosModel;
use App\Models\InstitucionExtensionModel;
use App\Models\InstitucionModel;
use App\Models\POFMH\CargoOrigenPofMHModel;
use App\Models\POFMH\CondicionModel;
use App\Models\POFMH\LiqFeb24Model;
use App\Models\POFMH\PofmhActivosModel;
use App\Models\POFMH\PofmhAulas;
use App\Models\POFMH\PofmhDivisiones;
use App\Models\POFMH\PofmhNovedades;
use App\Models\POFMH\PofmhNovedadesExtras;
use App\Models\POFMH\PofmhOrigenCargoModel;
use App\Models\POFMH\PofMhSitRev;
use App\Models\POFMH\PofmhTurnos;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\POFMH\PofmhModel;
use Maatwebsite\Excel\Facades\Excel; // Asegúrate de que tienes este paquete instalado
use App\Exports\PofExport; // Reemplaza con la ubicación de tu clase de exportación
use App\Exports\PofmhExport;

class ExportPofJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Contador de elementos procesados
        $processedItems = 0;

        // Obtener todos los datos a procesar
        $pofCompleta = PofmhModel::all();
        $totalItems = $pofCompleta->count(); // Total de registros a procesar

        $todosLosDatos = [];

        foreach ($pofCompleta as $pof) {
            // Aquí puedes crear un array con los datos que deseas sobrescribir o modificar
            $infoUsuario = DB::table('tb_agentes')->where('Documento', $pof->Agente)->first();
            $infoEscuela = DB::table('tb_institucion_extension')->where('CUECOMPLETO', $pof->CUECOMPLETO)->first();
            $infoOrigen = PofmhOrigenCargoModel::where('nombre_origen', $pof->Origen)
                ->join('tb_cargos_pof_origen', 'tb_cargos_pof_origen.idCargos_Pof_Origen', '=', 'tb_origenes_cargos.idOrigenCargo')
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

            $datosFila = [
                'Agente' => $pof->Agente ?? "S/D",
                'Cuil' => $infoUsuario->Cuil ?? "S/D",
                'ApeNom' => $pof->ApeNom ?? "S/D",
                'Sexo' => $infoUsuario->Sexo ?? "S/D",
                'CUECOMPLETO' => $pof->CUECOMPLETO ?? "S/D",
                'Nombre_Institucion' => $infoEscuela->Nombre_Institucion ?? "S/D",
                'Zona' => $infoEscuela->Zona ?? "S/D",
                'Localidad' => $infoEscuela->Localidad ?? "S/D",
                'Nivel' => $infoEscuela->Nivel ?? "S/D",
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
                'Asistencia' => $pof->Asistencia ?? "0",
                'Justificada' => $pof->Justificada ?? "0",
                'Injustificada' => $pof->Injustificada ?? "0",
                'Observaciones' => $pof->Observaciones ?? "S/D",
                'Carrera' => $pof->Carrera ?? "S/D",
                'Orientacion' => $pof->Orientacion ?? "S/D",
                'Titulo' => $pof->Titulo ?? "S/D",
            ];

            $todosLosDatos[] = $datosFila; // Agregar la fila procesada al array

            // Incrementar el contador de elementos procesados
            $processedItems++;

            // Calcular el progreso y almacenarlo en la caché
            $progress = intval(($processedItems / $totalItems) * 100);
            Cache::put('progreso_exportacion', $progress);
        }

        // Exportar a Excel
    
        Excel::store(new PofExport($todosLosDatos), 'exported_data.xlsx');

        // Asegúrate de establecer el progreso final a 100%
        Cache::put('progreso_exportacion', 100);
    }
}
