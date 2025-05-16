<?php

namespace App\Http\Controllers\Sistemas\Superior;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CarrerasRelSubOrgModel;
use App\Models\Superior\CargoPorLlamado;
use App\Models\Superior\EspacioPorLlamado;
use Illuminate\Http\Request;
use App\Models\Superior\Llamado;
use App\Models\Superior\Zona;
use App\Models\Superior\InstitutoSuperior;
use App\Models\Superior\Carrera;
use App\Models\Superior\EspacioCurricular;
use App\Models\Superior\TipoLlamado;
use App\Models\Superior\TipoEstado;
use App\Models\Superior\Turno;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Validation\Rule;
class LlamadosController extends Controller
{
    
    public function index()
    {
      $llamados = Llamado::conRelaciones()
        ->select(
            // campos de llamada
            'llamado.idllamado',
            'llamado.mes',
            'llamado.descripcion',
            'llamado.nombre_img',
            'llamado.fecha_ini',
            'llamado.fecha_fin',
            'llamado.idtb_tipoestado',              
            'llamado.idtipo_llamado',  
            'estado.nombre_tipoestado',
            'tipollamado.nombre AS tipo_llamado',
            'zona.nombre_zona',
            'instituto.nombre_instsup   AS nombre_instsup', 
            'carrera.nombre_carrera',
            'llamado.url_form AS url_form', 
            // campos de espacios
            'espacio.nombre_espacio',
            'situacion.nombre_situacion_revista',
            'espacios.horacat_espacio',
            'espacios.horario_espacio',
            'turno.nombre_turno',
            'perfil.nombre_perfil',
            'periodo.nombre_periodo',
            // campos de cargos
            'cargosec.nombre_cargo AS nombre_cargo_secundario',
            'situacioncargo.nombre_situacion_revista AS situacion_revista_cargo',
            'turnocargo.nombre_turno AS turno_cargo',
            'perfilcargo.nombre_perfil AS perfil_cargo',
            'periodocargo.nombre_periodo AS periodo_cursado_cargo',
            'cargos.horacat_cargo',
            'cargos.horario_cargo',
        )
       // ->whereIn('llamado.idtb_tipoestado', [8,9]) // en proceso
        ->orderBy('llamado.idllamado', 'desc')
        ->get()
        ->groupBy('idllamado')
        ->map(function ($items) {
            $first = $items->first();
            // Armamos un array con TODOS los campos que espera la vista
            $data = [
                'idllamado'             => $first->idllamado,
                'descripcion'           => $first->descripcion,
                'nombre_img'            => $first->nombre_img,
                'fecha_ini'             => $first->fecha_ini,
                'fecha_fin'             => $first->fecha_fin,
                'idtb_tipoestado'       => $first->idtb_tipoestado,
                'idtipo_llamado'      => $first->idtipo_llamado,
                'nombre_tipoestado'     => $first->nombre_tipoestado,
                'tipo_llamado'          => $first->tipo_llamado,
                'nombre_zona'           => $first->nombre_zona,
                'nombre_instsup'        => $first->nombre_instsup,
                'nombre_carrera'        => $first->nombre_carrera,
                'url_form'              => $first->url_form,
                'mes'                 => $first->mes,
                // Arrastramos espacios
                'espacios' => $items->map(fn($i) => [
                    'nombre_espacio'           => $i->nombre_espacio,
                    'nombre_situacion_revista' => $i->nombre_situacion_revista,
                    'horacat_espacio'          => $i->horacat_espacio,
                    'horario_espacio'          => $i->horario_espacio,
                    'nombre_turno'             => $i->nombre_turno,
                    'nombre_perfil'            => $i->nombre_perfil,
                    'nombre_periodo'           => $i->nombre_periodo,                 

                ])
                ->unique(fn($item) => md5(json_encode($item)))
                ->filter(fn($e) => $e['nombre_espacio']),
                // Y cargos por separado (si lo llegás a necesitar en otra vista)
                'cargos' => $items->map(fn($i) => [
                    'nombre_cargo_secundario'  => $i->nombre_cargo_secundario,
                    'situacion_revista_cargo'  => $i->situacion_revista_cargo,
                    'turno_cargo'              => $i->turno_cargo,
                    'nombre_perfil'             => $i->perfil_cargo,
                    'nombre_periodo'    => $i->periodo_cursado_cargo,
                    'horacat_cargo'          => $i->horacat_cargo,
                    'horario_cargo'          => $i->horario_cargo,
                ])
                ->unique(fn($item) => md5(json_encode($item)))
                ->filter(fn($c) => $c['nombre_cargo_secundario']),
            ];

            // ¡Convertimos el array en objeto para que tu Blade siga usando ->
            return (object) $data;
        })

        ->values();
       // dd($llamados);
        $tipoestado = TipoEstado::all();
        // Usá $llamados para no tocar tu blade
        return view('aegis.sistemas.superior.llamados.index', compact('llamados', 'tipoestado'));
    }


 
    public function lom(){
        $llamados = DB::connection('DB4')->table('tb_lom')
        ->join('tb_zona', 'tb_lom.idtb_zona', '=', 'tb_zona.idtb_zona')
        ->join('tb_instituto_superior', 'tb_lom.id_instituto_superior', '=', 'tb_instituto_superior.id_instituto_superior')
        ->join('tipo_llamado', 'tb_lom.idtipo_llamado', '=', 'tipo_llamado.idtipo_llamado')
        ->leftjoin('tb_carreras', 'tb_lom.idCarrera', '=', 'tb_carreras.idCarrera')
        ->join('tb_cargos', 'tb_lom.idtb_cargo', '=', 'tb_cargos.idtb_cargos')
        ->join('tb_espacioscurriculares', 'tb_lom.idEspacioCurricular', '=', 'tb_espacioscurriculares.idEspacioCurricular')
        ->join('tb_tipoestado', 'tb_lom.idtb_tipoestado', '=', 'tb_tipoestado.idtb_tipoestado')
        ->whereIn('tb_lom.idtb_tipoestado', [4]) // finalizado
        ->select(
            'tb_lom.*',
            'tb_zona.nombre_zona',
            'tb_instituto_superior.nombre_instsup',
            'tipo_llamado.nombre',
            'tb_carreras.nombre_carrera',
            'tb_cargos.nombre_cargo',
            'tb_espacioscurriculares.nombre_espacio',
            'tb_tipoestado.nombre_tipoestado'
        )
        ->get();

        return view('aegis.sistemas.superior.llamados.ver_lom', compact('llamados'));
    }
    
  
    
    public function create()
    { //datos tabla llamados
        $zonas = Zona::all(); 
        $tiposLlamado = TipoLlamado::all();
        $institutos = InstitutoSuperior::all();
        $carreras = Carrera::all();
        $estados = TipoEstado::where('nombre_tipoestado', 'En Proceso')->first(); // Estado reservado
        //tabla espacios por llamado
        $espacios= EspacioPorLlamado::all();
        //tabla cargos por llamado
        $relcargos= CargoPorLlamado::all();
        $cargos = DB::connection('DB4')->table('tb_cargos')->select('idtb_cargos', 'nombre_cargo')->get();
    
        $idEspacioCurricular_modal = EspacioCurricular::all();
        $espacios = DB::connection('DB4')->table('tb_espacioscurriculares')->select('idEspacioCurricular', 'nombre_espacio')->get();
        $turnos = Turno::all();
        $situacion_revista = DB::connection('DB4')->table('tb_situacion_revista')->select('idtb_situacion_revista', 'nombre_situacion_revista')->get();
        $turnos = DB::connection('DB4')->table('tb_turnos')->select('idTurno', 'nombre_turno')->get();
        $perfil = DB::connection('DB4')->table('tb_perfil')->select('idtb_perfil', 'nombre_perfil')->get();
        $periodo_cursado = DB::connection('DB4')->table('tb_periodo_cursado')->select('idtb_periodo_cursado', 'nombre_periodo')->get(); 
       
        return view('aegis.sistemas.superior.llamados.create', compact(
            'zonas', 
            'tiposLlamado', 
            'estados',
            'institutos', 
            'carreras', 
            'espacios', 
            'idEspacioCurricular_modal',
            'cargos',
            'turnos',
            'situacion_revista',
            'perfil',
            'periodo_cursado'));
    }

    
    // public function store(Request $request)
    // {
    //         /*  $validated = $request->validate([
    //         //         'idtb_zona' => [
    //         //             'required',
    //         //             Rule::exists('DB4.tb_zona', 'idtb_zona'),
    //         //         ],
    //         //         'id_instituto_superior' => [
    //         //             'required',
    //         //             Rule::exists('DB4.tb_instituto_superior', 'id_instituto_superior'),
    //         //         ],
    //         //         'idCarrera' => [
    //         //             'required',
    //         //             Rule::exists('DB4.tb_carreras', 'idCarrera'),
    //         //         ],
    //         //         'idtipo_llamado' => [
    //         //             'required',
    //         //             Rule::exists('DB4.tipo_llamado', 'idtipo_llamado'),
    //         //         ],
    //         //         'fecha_ini' => 'required|date',
    //         //         'fecha_fin' => 'required|date|after_or_equal:fecha_ini',
    //         //         'descripcion' => 'nullable|string',
    //         //         'url_form' => 'nullable|url',
    //         //         'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen
    //         //     ]);*/

           
    //         $validated = $request->validate([
    //             'idtb_zona'             => ['required', Rule::exists('DB4.tb_zona','idtb_zona')],
    //             'id_instituto_superior' => ['required', Rule::exists('DB4.tb_instituto_superior','id_instituto_superior')],
    //             'idCarrera'             => ['required', Rule::exists('DB4.tb_carreras','idCarrera')],
    //             'idtipo_llamado'        => ['required', Rule::exists('DB4.tipo_llamado','idtipo_llamado')],
    //             'fecha_ini'             => 'required|date',
    //             'fecha_fin'             => 'required|date|after_or_equal:fecha_ini',
    //             'descripcion'           => 'nullable|string',
    //             'url_form'              => 'nullable|url',
    //             'imagen'                => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);
    //         if ($request->hasFile('imagen')) {
             
                               
    //             // ahora $path = "superior/llamado/nombre_20250426_123456.jpg"
    //             $validated['nombre_img'] =   $request->file('imagen') ->store('superior/llamado', 'public');
    //         }
    //         // Establecer estado "reservado"
    //         $estadoReservado = TipoEstado::where('nombre_tipoestado', 'En Proceso')->first();
    //         $validated['idtb_tipoestado'] = $estadoReservado->idtb_tipoestado;
    //         $llamado = Llamado::create($validated);
    //         // $llamado = Llamado::create([
    //         //     ...$validated,
    //         //     'idtb_tipoestado' => $estadoReservado->idtb_tipoestado,
    //         //     'url_form'        => null, // Si no hay formulario aún
    //         // ]);

    //         return redirect()->route('aegis.sistemas.superior.llamados.edit', $llamado->idllamado)
    //                      ->with('success', 'Llamado creado correctamente.');
    // }
    
    public function editarLlamado($id)
    {   
        //lo necesario para el formulario de editar llamado
        $llamado = Llamado::findOrFail($id);
        $zonas = Zona::all(); 
        $tiposLlamado = TipoLlamado::all();
        $institutos = InstitutoSuperior::all();
        $carreras = Carrera::all();
        $estados = TipoEstado::where('nombre_tipoestado', 'En Proceso')->first(); // Estado reservado
        //tabla espacios por llamado
        $espacios= EspacioPorLlamado::all();
        //tabla cargos por llamado
        $relcargos= CargoPorLlamado::all();
        $cargos = DB::connection('DB4')->table('tb_cargos')->select('idtb_cargos', 'nombre_cargo')->get();
    
        $idEspacioCurricular_modal = EspacioCurricular::all();
        $espacios = DB::connection('DB4')->table('tb_espacioscurriculares')->select('idEspacioCurricular', 'nombre_espacio')->get();
        $turnos = Turno::all();
        $situacion_revista = DB::connection('DB4')->table('tb_situacion_revista')->select('idtb_situacion_revista', 'nombre_situacion_revista')->get();
        $turnos = DB::connection('DB4')->table('tb_turnos')->select('idTurno', 'nombre_turno')->get();
        $perfil = DB::connection('DB4')->table('tb_perfil')->select('idtb_perfil', 'nombre_perfil')->get();
        $periodo_cursado = DB::connection('DB4')->table('tb_periodo_cursado')->select('idtb_periodo_cursado', 'nombre_periodo')->get(); 
       
        return view('aegis.sistemas.superior.llamados.edit', compact(
            'zonas', 
            'tiposLlamado', 
            'estados',
            'institutos', 
            'carreras', 
            'espacios', 
            'idEspacioCurricular_modal',
            'cargos',
            'turnos',
            'situacion_revista',
            'perfil',
            'periodo_cursado',
            'llamado'));

    }




    public function crearLlamado(Request $request)
    {
        $llamado = new Llamado();
         $llamado->idtb_tipoestado = 2; //en proceso
        $llamado->save();

        return response()->json(['id' => $llamado->idllamado]);
    }

    public function actualizarLlamado(Request $request)
    {
            $llamado = Llamado::findOrFail($request->llamado_id);
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Solo nombre sin extensión
                $extension = $file->getClientOriginalExtension();
                $timestamp = Carbon::now()->format('Ymd_His');
            
                $newFileName = $originalName . '_' . $timestamp . '.' . $extension;
            
                // Nombre del mes en español
                $mesActual = Carbon::now()->locale('es')->isoFormat('MMMM'); // ej: "abril"
            
                // Definir la carpeta destino dentro de 'storage/app/public/superior/llamado'
                $destinationPath = storage_path('app/public/superior/llamado/' . ucfirst($mesActual) . '/');
            
                // Crear la carpeta si no existe
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
            
                // Mover el archivo a la carpeta del mes actual
                $file->move($destinationPath, $newFileName);
            
                // Guardar la ruta relativa en la base de datos si querés (opcional)
                $llamado->nombre_img =$newFileName;
                $llamado->mes= ucfirst($mesActual); // Guardar el mes en la base de datos (opcional)
            }

            //$llamado->nombre_img = $newFileName; // Guardar el nuevo nombre de la imagen en la base de datos
            $llamado->idtb_zona = $request->idtb_zona;
            $llamado->id_instituto_superior = $request->id_instituto_superior;
            $llamado->idtipo_llamado = $request->idtipo_llamado;
            $llamado->idCarrera = $request->idCarrera;
            $llamado->fecha_ini = $request->fecha_ini;
            $llamado->fecha_fin = $request->fecha_fin;
            $llamado->url_form = $request->url_form;
            //$llamado->nombre_img = $request->nombre_img;   
            $llamado->descripcion = $request->descripcion;       
            $llamado->save();

            return response()->json(['success' => true]);
    }

    public function agregarEspacio(Request $request)
    {

        $nuevoEspacio = new EspacioPorLlamado();
        $nuevoEspacio->idLlamado = $request->llamado_id;
        $nuevoEspacio->idEspacioCurricular = $request->idEspacioCurricular_modal;
        $nuevoEspacio->idTurno = $request->idTurno_modal;
        $nuevoEspacio->horacat_espacio = $request->horacat_modal;
        $nuevoEspacio->idtb_situacion_revista = $request->idtb_situacion_revista_modal;
        $nuevoEspacio->idtb_periodo_cursado = $request->idtb_periodo_cursado_modal;
        $nuevoEspacio->horario_espacio = $request->horario_modal;
        $nuevoEspacio->idtb_perfil = $request->idtb_perfil_modal;
        $nuevoEspacio->save();

        return response()->json(['success' => true, 'message' => 'Espacio curricular agregado correctamente.']);
    }

    //desde aqui todo para el modal de espacios
    public function obtenerEspaciosPorLlamado(Request $request)
    {
        $idLlamado = $request->input('idLlamado');

        $espacios = DB::connection('DB4')->table('rel_espacios_por_llamado')
        ->where('idllamado', $idLlamado)
        ->join('tb_espacioscurriculares', 'tb_espacioscurriculares.idEspacioCurricular', '=', 'rel_espacios_por_llamado.idEspacioCurricular')
        ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'rel_espacios_por_llamado.idTurno')
        ->join('tb_situacion_revista', 'tb_situacion_revista.idtb_situacion_revista', '=', 'rel_espacios_por_llamado.idtb_situacion_revista')
        ->join('tb_perfil', 'tb_perfil.idtb_perfil', '=', 'rel_espacios_por_llamado.idtb_perfil')
        ->join('tb_periodo_cursado', 'tb_periodo_cursado.idtb_periodo_cursado', '=', 'rel_espacios_por_llamado.idtb_periodo_cursado')
        ->select(
            'rel_espacios_por_llamado.idrel_espacios_por_llamado',
            'rel_espacios_por_llamado.idllamado',
            'rel_espacios_por_llamado.idEspacioCurricular',
            'rel_espacios_por_llamado.idTurno',
            'rel_espacios_por_llamado.idtb_situacion_revista',
            'rel_espacios_por_llamado.idtb_periodo_cursado',
            'rel_espacios_por_llamado.idtb_perfil',
            'rel_espacios_por_llamado.horacat_espacio',
            'rel_espacios_por_llamado.horario_espacio',
            'tb_espacioscurriculares.nombre_espacio',
            'tb_turnos.nombre_turno',
            'tb_situacion_revista.nombre_situacion_revista',
            'tb_perfil.nombre_perfil',
            'tb_periodo_cursado.nombre_periodo'
        )
        ->get();

        return response()->json(['espacios' => $espacios]);
    }

    public function editarEspacio(Request $request)
    {
        // Buscamos el espacio por su ID
        $espacio = EspacioPorLlamado::findOrFail($request->idEspacioEditar);

        // Actualizamos los campos
        $espacio->idEspacioCurricular = $request->idEspacioCurricular_modal;
        $espacio->idTurno = $request->idTurno_modal;
        $espacio->horacat_espacio = $request->horacat_modal?$request->horacat_modal: 0;
        $espacio->idtb_situacion_revista = $request->idtb_situacion_revista_modal;
        $espacio->idtb_periodo_cursado = $request->idtb_periodo_cursado_modal? $request->idtb_periodo_cursado_modal: 4;
        $espacio->horario_espacio = $request->horario_modal;
        $espacio->idtb_perfil = $request->idtb_perfil_modal;
        
        // Guardamos cambios
        $espacio->save();

        return response()->json(['success' => true, 'message' => 'Espacio curricular actualizado correctamente.']);
    }

    public function eliminarEspacio(Request $request)
    {
        // Buscamos el espacio por su ID
        $espacio = EspacioPorLlamado::findOrFail($request->id);

        // Eliminamos el espacio
        $espacio->delete();

        return response()->json(['success' => true, 'message' => 'Espacio curricular eliminado correctamente.']);
    }

    //desde aqui todo para el modal de cargos
    public function agregarCargo(Request $request)
    {
        $nuevoCargo = new CargoPorLlamado();
        $nuevoCargo->idLlamado = $request->llamado_id;
        $nuevoCargo->idtb_cargos = $request->idtb_cargos_modal;
        $nuevoCargo->idTurno = $request->idTurno_modal;
        $nuevoCargo->horacat_cargo = $request->horacat_modal;
        $nuevoCargo->idtb_situacion_revista = $request->idtb_situacion_revista_modal;
        $nuevoCargo->idtb_periodo_cursado = $request->idtb_periodo_cursado_modal;
        $nuevoCargo->horario_cargo = $request->horario_modal;
        $nuevoCargo->idtb_perfil = $request->idtb_perfil_modal;
        $nuevoCargo->save();

        return response()->json(['success' => true, 'message' => 'Cargo agregado correctamente.']);
    }
    public function obtenerCargosPorLlamado(Request $request)
    {
        $idLlamado = $request->input('idLlamado');

        $cargos = DB::connection('DB4')->table('rel_cargo_por_llamado')
        ->where('idllamado', $idLlamado)
        ->join('tb_cargos', 'tb_cargos.idtb_cargos', '=', 'rel_cargo_por_llamado.idtb_cargos')
        ->join('tb_turnos', 'tb_turnos.idTurno', '=', 'rel_cargo_por_llamado.idTurno')
        ->join('tb_situacion_revista', 'tb_situacion_revista.idtb_situacion_revista', '=', 'rel_cargo_por_llamado.idtb_situacion_revista')
        ->join('tb_perfil', 'tb_perfil.idtb_perfil', '=', 'rel_cargo_por_llamado.idtb_perfil')
        ->join('tb_periodo_cursado', 'tb_periodo_cursado.idtb_periodo_cursado', '=', 'rel_cargo_por_llamado.idtb_periodo_cursado')
        ->select(
            'rel_cargo_por_llamado.idrel_cargo_por_llamado',
            'rel_cargo_por_llamado.idllamado',
            'rel_cargo_por_llamado.idtb_cargos',
            'rel_cargo_por_llamado.idTurno',
            'rel_cargo_por_llamado.idtb_situacion_revista',
            'rel_cargo_por_llamado.idtb_periodo_cursado',
            'rel_cargo_por_llamado.idtb_perfil',
            'rel_cargo_por_llamado.horacat_cargo',
            'rel_cargo_por_llamado.horario_cargo',
            'tb_cargos.nombre_cargo',
            'tb_turnos.nombre_turno',
            'tb_situacion_revista.nombre_situacion_revista',
            'tb_perfil.nombre_perfil',
            'tb_periodo_cursado.nombre_periodo'
        )
        ->get();

        return response()->json(['cargos' => $cargos]);
    }

    public function editarCargo(Request $request)
    {
        // Buscamos el cargo por su ID
        $Cargo = CargoPorLlamado::findOrFail($request->idCargoEditar);

        // Actualizamos los campos
        $Cargo->idtb_cargos = $request->idtb_cargos_modal;
        $Cargo->idTurno = $request->idTurno_modal;
        $Cargo->horacat_cargo = $request->horacat_modal;
        $Cargo->idtb_situacion_revista = $request->idtb_situacion_revista_modal;
        $Cargo->idtb_periodo_cursado = $request->idtb_periodo_cursado_modal;
        $Cargo->horario_cargo = $request->horario_modal;
        $Cargo->idtb_perfil = $request->idtb_perfil_modal;
        
        // Guardamos cambios
        $Cargo->save();

        return response()->json(['success' => true, 'message' => 'Cargo actualizado correctamente.']);
    }

    public function eliminarCargo(Request $request)
    {
        // Buscamos el cargo por su ID
        $cargo = CargoPorLlamado::findOrFail($request->id);

        // Eliminamos el cargo
        $cargo->delete();

        return response()->json(['success' => true, 'message' => 'Cargo eliminado correctamente.']);
    }

    //cambio estado
    public function cambiarEstado(Request $request)
    {
        try {
            $llamado = Llamado::findOrFail($request->idllamado);
            $llamado->idtb_tipoestado = $request->idtb_tipoestado;
            $llamado->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

}