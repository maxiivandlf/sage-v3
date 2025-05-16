<?php

namespace App\Http\Controllers\Sistemas\Liquidacion;

use App\Http\Controllers\Controller;
use App\Models\InstitucionExtensionModel;
use App\Models\PadronModel;
use App\Models\POFMH\CondicionModel;
use App\Models\POFMH\PofmhActivosModel;
use App\Models\POFMH\PofmhAulas;
use App\Models\POFMH\PofmhDivisiones;
use App\Models\POFMH\PofmhModel;
use App\Models\POFMH\PofmhOrigenCargoModel;
use App\Models\POFMH\PofMhSitRev;
use App\Models\POFMH\PofmhTurnos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class InformacionInstitucionesController extends Controller
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

    public function index()
    {
        $datos = DB::connection('DB8')
            ->table('instarealiq')
            ->select(
                'ID_inst_area_liq',
                'ID_CUEA',
                'CUEA',
                'nombreInstitucion',
                'nivel',
                'modalidad',
                'zonaLiq',
                'codZonaLiq',
                'escu',
                'desc_escu',
                'area',
                'NoIPE',
                'ESTADO'
            )
            ->get();

        return view('liquidacion.informacionInstituciones', compact('datos'));
    }

    public function traerTodoAgenteLiq()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2028M');

        // Cargar todas las colecciones necesarias de una vez
        $institucionExtension = InstitucionExtensionModel::Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
            ->leftJoin('tb_nivelesensenanza', 'tb_institucion_extension.Nivel', '=', 'tb_nivelesensenanza.NivelEnsenanza')
            ->select('tb_institucion_extension.*', 'tb_turnos_usuario.*', 'tb_nivelesensenanza.*')
            ->first();

        $instituciones = DB::table('tb_institucion_extension')
            ->select('CUECOMPLETO', 'Nivel', 'Zona', 'ZonaSupervision', 'idTurnoUsuario')
            ->get()
            ->groupBy('CUECOMPLETO');

        $zonas = DB::table('tb_zonas_liq')
            ->pluck('nombre_loc_zona', 'codigo_letra')
            ->toArray();

        $zonasSupervision = DB::table('tb_zonasupervision')
            ->pluck('Codigo', 'idZonaSupervision')
            ->toArray();

        $dnInBase = DB::table('tb_agentes')
            ->pluck('Documento')
            ->toArray();

        $pofmh = PofmhModel::where('CUECOMPLETO', 'not like', '999%')
            ->where('CUECOMPLETO', 'not like', '950%')
            ->orderBy('CUECOMPLETO', 'ASC')
            ->get();

        $CargosSalariales = DB::table('tb_cargossalariales')
            ->pluck('Cargo', 'idCargo')
            ->toArray();

        $CargosSalarialesCodigo = DB::table('tb_cargossalariales')
            ->pluck('Codigo', 'idCargo')
            ->toArray();

        $Condiciones = CondicionModel::pluck('Descripcion', 'idCondicion')->toArray();
        $Aulas = PofmhAulas::pluck('nombre_aula', 'idAula')->toArray();
        $Divisiones = PofmhDivisiones::pluck('nombre_division', 'idDivision')->toArray();
        $Turnos = PofmhTurnos::pluck('nombre_turno', 'idTurno')->toArray();
        $Activos = PofmhActivosModel::pluck('nombre_activo', 'idActivo')->toArray();
        $OrigenesDeCargos = PofmhOrigenCargoModel::pluck('nombre_origen', 'idOrigenCargo')->toArray();

        $CargosCreados = DB::connection('DB7')->table('tb_padt')
            ->join('tb_origenes_cargos', 'tb_padt.idOrigenCargo', '=', 'tb_origenes_cargos.idOrigenCargo')
            ->join('tb_cargos_pof_origen', 'tb_origenes_cargos.nombre_origen', '=', 'tb_cargos_pof_origen.idCargos_Pof_Origen')
            ->pluck('tb_cargos_pof_origen.nombre_cargo_origen', 'tb_padt.idOrigenCargo')
            ->toArray();

        $SitRev = PofMhSitRev::pluck('Descripcion', 'idSituacionRevista')->toArray();
        $Motivos = DB::table('tb_motivos')->pluck('Nombre_Licencia', 'idMotivo')->toArray();
        $MotivosCodigo = DB::table('tb_motivos')->pluck('Codigo', 'idMotivo')->toArray();

        // Procesar los datos
        foreach ($pofmh as $row) {
            if (isset($instituciones[$row->CUECOMPLETO])) {
                $institucion = $instituciones[$row->CUECOMPLETO]->firstWhere('idTurnoUsuario', $row->Turno);
                if ($institucion) {
                    $row->Nivel = $institucion->Nivel ?? 'S/D';
                    $row->Zona = $zonas[$institucion->Zona] ?? 'S/D';
                    $row->ZonaSupervision = $zonasSupervision[$institucion->ZonaSupervision] ?? 'S/D';
                } else {
                    $row->Nivel = 'S/D';
                    $row->Zona = 'S/D';
                    $row->ZonaSupervision = 'S/D';
                }
            } else {
                $row->Nivel = 'S/D';
                $row->Zona = 'S/D';
                $row->ZonaSupervision = 'S/D';
            }

            $row->isDniLoaded = in_array($row->Agente, $dnInBase);
            $row->Origen = $CargosCreados[$row->Origen] ?? 'S/D';
            $row->SitRev = $SitRev[$row->SitRev] ?? 'S/D';
            $row->Cargo = isset($CargosSalariales[$row->Cargo]) ?
                $CargosSalariales[$row->Cargo] . ' (' . $CargosSalarialesCodigo[$row->Cargo] . ')' : 'S/D';
            $row->Aula = $Aulas[$row->Aula] ?? 'S/D';
            $row->Division = $Divisiones[$row->Division] ?? 'S/D';
            $row->Turno = $Turnos[$row->Turno] ?? 'S/D';
            $row->Condicion = $Condiciones[$row->Condicion] ?? 'S/D';
            $row->Activo = $Activos[$row->Activo] ?? 'S/D';
            $row->Motivo = isset($Motivos[$row->Motivo]) ?
                $Motivos[$row->Motivo] . ' (' . $MotivosCodigo[$row->Motivo] . ')' : 'S/D';
            $row->FechaAltaCargo = $row->FechaAltaCargo ? Carbon::parse($row->FechaAltaCargo)->format('Y-m-d') : 'S/D';
            $row->FechaDesignado = $row->FechaDesignado ? Carbon::parse($row->FechaDesignado)->format('Y-m-d') : 'S/D';
            $row->FechaDesde = $row->FechaDesde ? Carbon::parse($row->FechaDesde)->format('Y-m-d') : 'S/D';
            $row->FechaHasta = $row->FechaHasta ? Carbon::parse($row->FechaHasta)->format('Y-m-d') : 'S/D';
        }

        $datos = [
            'mensajeError' => "",
            'institucionExtension' => $institucionExtension,
            'infoPofMH' => $pofmh,
            'mensajeNAV' => 'Panel de Configuración de POF(Modalidad Horizontal)'
        ];

        return view('bandeja.POF.traerTodoAgenteLiq', $datos);
    }

    //funcion manual para ubicar ambito, sector y estado
    public function actualizarValoresInstituciones()
    {
        // Traer todos los padrones
        $padron = PadronModel::get();

        // Iterar sobre cada registro del padron
        foreach ($padron as $p) {
            // Buscar las extensiones correspondientes al CUECOMPLETO
            $inst_Ext = InstitucionExtensionModel::where('CUECOMPLETO', $p->CUECOMPLETO)->get();

            // Verificar si se encontraron extensiones
            if ($inst_Ext->isNotEmpty()) {
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




    public function actualizarInstarealiq(Request $request)
    {
        $cambios = $request->input('cambios');

        if (empty($cambios)) {
            return response()->json(['message' => 'No se enviaron cambios.'], 400);
        }

        foreach ($cambios as $fila) {
            // Actualizar cada fila
            DB::connection('DB8')->table('instarealiq')
                ->where('ID_inst_area_liq', $fila['ID_inst_area_liq'])
                ->update([
                    'ID_CUEA' => $fila['ID_CUEA'],
                    'CUEA' => $fila['CUEA'],
                    'nombreInstitucion' => $fila['nombreInstitucion'],
                    'nivel' => $fila['nivel'],
                    'modalidad' => $fila['modalidad'],
                    'zonaLiq' => $fila['zonaLiq'],
                    'codZonaLiq' => $fila['codZonaLiq'],
                    'escu' => $fila['escu'],
                    'desc_escu' => $fila['desc_escu'],
                    'area' => $fila['area'],
                    'NoIPE' => $fila['NoIPE'],
                    'ESTADO' => $fila['ESTADO'],
                ]);
        }

        return response()->json(['message' => 'Cambios guardados exitosamente.']);
    }
}
