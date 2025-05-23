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
use App\Models\Superior\Lom;
use App\Models\Superior\Perfil;
use App\Models\Superior\RelInstSupCarrera;
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
        $tipoestado = TipoEstado::whereIn('idtb_tipoestado', [2,8,9])->get();
        // Usá $llamados para no tocar tu blade
        return view('aegis.sistemas.superior.llamados.index', compact('llamados', 'tipoestado'));
    }


    // TODO LOM
    //ver lom
    public function lom(){
        $llamados = DB::connection('DB4')->table('tb_lom')
        ->join('tb_zona', 'tb_lom.idtb_zona', '=', 'tb_zona.idtb_zona')
        ->join('tb_instituto_superior', 'tb_lom.id_instituto_superior', '=', 'tb_instituto_superior.id_instituto_superior')
        ->join('tipo_llamado', 'tb_lom.idtipo_llamado', '=', 'tipo_llamado.idtipo_llamado')
        ->leftjoin('tb_carreras', 'tb_lom.idCarrera', '=', 'tb_carreras.idCarrera')
        ->join('tb_cargos', 'tb_lom.idtb_cargo', '=', 'tb_cargos.idtb_cargos')
        ->join('tb_espacioscurriculares', 'tb_lom.idEspacioCurricular', '=', 'tb_espacioscurriculares.idEspacioCurricular')
        ->join('tb_tipoestado', 'tb_lom.idtb_tipoestado', '=', 'tb_tipoestado.idtb_tipoestado')
        ->whereIn('tb_lom.idtb_tipoestado', [4,2]) // finalizado
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
        $tipoestado = TipoEstado::whereIn('idtb_tipoestado', [4,2])->get();
        $zona = Zona::all();
        return view('aegis.sistemas.superior.llamados.ver_lom', compact('llamados', 'tipoestado','zona'));
    }

    //ver lom formulario
    public function formcrearLom()
    {
        //datos tabla lom
        $institutos = [];
        $carreras = [];
        $relInstCarr=RelInstSupCarrera::all();
        $zonas = Zona::all(); 
        $tiposLlamado = TipoLlamado::all();
        $lom = Lom::all();
        // $institutos = InstitutoSuperior::all();
        // $carreras = Carrera::all();
        $estados = TipoEstado::where('nombre_tipoestado', 'En Proceso')->first(); // Estado reservado
        $cargos = DB::connection('DB4')->table('tb_cargos')->select('idtb_cargos', 'nombre_cargo')->get();
        $espacios = DB::connection('DB4')->table('tb_espacioscurriculares')->select('idEspacioCurricular', 'nombre_espacio')->get();           

        return view('aegis.sistemas.superior.lom.crearLom', compact(
            'zonas', 
            'tiposLlamado', 
            'institutos', 
            'carreras', 
            'estados',
            'cargos',
            'espacios',
            'relInstCarr',
            'lom'
        ));
    }
    // cargar lom inserta en la tabla tb_lom con el form
    public function agregarLom (Request $request)
{
    // // Validación
    // $request->validate([
    //     'idtb_zona' => 'required|integer',
    //     'id_instituto_superior' => 'required|integer',
    //     'idCarrera' => 'required|integer',
    //     'idtipo_llamado' => 'required|integer',
    //     'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     'pdf' => 'nullable|mimes:pdf|max:5120',
    // ]);

    // Manejo de archivos
    $rutaImagen = null;
    $rutaPdf = null;

    if ($request->hasFile('imagen')) {
        $rutaImagen = $request->file('imagen')->store('lom', 'public');
    }

    if ($request->hasFile('pdf')) {
        $rutaPdf = $request->file('pdf')->store('lom', 'public');
    }

    // Insertar en DB
    // DB::connection('DB4')->table('tb_lom')->insert([
    //     'idtb_zona' => $request->idtb_zona,
    //     'id_instituto_superior' => $request->id_instituto_superior,
    //     'idCarrera' => $request->idCarrera,
    //     'idtipo_llamado' => $request->idtipo_llamado,
    //     'imagen' => $rutaImagen,
    //     'pdf' => $rutaPdf,
    //     'idtb_tipoestado' => 4, // En Proceso, o el valor que corresponda
    //     'created_at' => now(),
    //     'updated_at' => now(),
    // ]);
    $lom= new Lom();
    $lom->idtb_zona = 8;
    $lom->id_instituto_superior = 1;
    $lom->idCarrera = 171;
    $lom->idtipo_llamado =1;
    $lom->idtb_tipoestado = 2; // En Proceso, o el valor que corresponda
    $lom->imglom = 'imagen_prueba.jpg';
    $lom->pdf = 'pdf-prueba.pdf';
    $lom->idtb_cargo = 13;
    $lom->idEspacioCurricular = 1;
    $lom->idUsuarioCrear =  session('idUsuario'); // Cambia esto por el ID del usuario que crea el LOM
    $lom->idUsuarioEditar =  session('idUsuario'); // Cambia esto por el ID del usuario que edita el LOM
    $lom->mes = null; // Cambia esto por el mes que corresponda
    $lom->CUE = $request->CUE; // Cambia esto por el CUE que corresponda
      
    $lom->save();

    return response()->json(['id' => $lom->idtb_lom]);

   // return redirect()->route('aegis.sistemas.superior.lom.agregarLom')->with('success', 'LOM creado correctamente.');
}
    // traer lom
    public function obtenerLom(Request $request)
    {
         $lom = Lom::findOrFail($request->lom_id);
             //para imagen
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Solo nombre sin extensión
                $extension = $file->getClientOriginalExtension();
                $timestamp = Carbon::now()->format('Ymd_His');            
                $newFileName = $originalName . '_' . $timestamp . '.' . $extension;
                // Nombre del mes en español
                $mesActual = Carbon::now()->locale('es')->isoFormat('MMMM'); // ej: "abril"            
                // Definir la carpeta destino dentro de 'storage/app/public/superior/llamado'
                $destinationPath = storage_path('app/public/superior/lom/' . ucfirst($mesActual) . '/');
            
                // Crear la carpeta si no existe
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }            
                // Mover el archivo a la carpeta del mes actual
                $file->move($destinationPath, $newFileName);            
                // Guardar la ruta relativa en la base de datos si querés (opcional)
                $lom->imglom =$newFileName;
                $lom->mes= ucfirst($mesActual); // Guardar el mes en la base de datos (opcional)
            }

             //para pdf
            if ($request->hasFile('pdf')) {
                $file = $request->file('pdf');
                $originalName2 = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Solo nombre sin extensión
                $extension = $file->getClientOriginalExtension();
                $timestamp = Carbon::now()->format('Ymd_His');            
                $newFileName2 = $originalName2 . '_' . $timestamp . '.' . $extension;
                // Nombre del mes en español
                $mesActual = Carbon::now()->locale('es')->isoFormat('MMMM'); // ej: "abril"            
                // Definir la carpeta destino dentro de 'storage/app/public/superior/llamado'
                $destinationPath = storage_path('app/public/superior/lom/' . ucfirst($mesActual) . '/');
            
                // Crear la carpeta si no existe
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }            
                // Mover el archivo a la carpeta del mes actual
                $file->move($destinationPath, $newFileName2);            
                // Guardar la ruta relativa en la base de datos si querés (opcional)
                $lom->pdf =$newFileName2;
                $lom->mes= ucfirst($mesActual); // Guardar el mes en la base de datos (opcional)
            }

            //$llamado->nombre_img = $newFileName; // Guardar el nuevo nombre de la imagen en la base de datos
            $lom->idtb_zona = $request->idtb_zona;
            $lom->id_instituto_superior = $request->id_instituto_superior;
            $lom->idtipo_llamado = $request->idtipo_llamado;
            $lom->idCarrera = $request->idCarrera;
            $lom->idtb_tipoestado = 2;
            $lom->idtb_cargo = 13;
            $lom->idEspacioCurricular = 1;
          
           
            $lom->CUE = $request->CUE; // Cambia esto por el CUE que corresponda
             $lom->idUsuarioEditar =  session('idUsuario');
            $lom->save();
            return response()->json(['success' => true]);
       }
    //editar lom
    public function editarLom($id)
    {
        $lom = Lom::findOrFail($id);
        $zonas = Zona::all(); 
     
        $relInstCarr=RelInstSupCarrera::all();
        $tiposLlamado = TipoLlamado::all();
        $institutos = InstitutoSuperior::where('idtb_zona', $lom->idtb_zona)->get();
        $carreras = Carrera::whereIn('idCarrera', function($query) use ($lom) {
        $query->select('idCarrera')
            ->from('rel_instsup_carrera')
            ->where('id_instituto_superior', $lom->id_instituto_superior);
          })->get();
        $estados = TipoEstado::where('nombre_tipoestado', 'En Proceso')->first(); // Estado reservado
        $cargos = DB::connection('DB4')->table('tb_cargos')->select('idtb_cargos', 'nombre_cargo')->get();
        $espacios = DB::connection('DB4')->table('tb_espacioscurriculares')->select('idEspacioCurricular', 'nombre_espacio')->get();           
        //dd($lom);
        return view('aegis.sistemas.superior.lom.editarLom', compact(
            'zonas', 
            'tiposLlamado', 
            'institutos', 
            'carreras', 
            'estados',
            'cargos',
            'espacios',
            'lom'
        ));
    }   
  
    // TODO LLAMADOS
    public function create()
    { //datos tabla llamados
        // $zonas = DB::connection('DB4')->table('tb_zona')
        // ->select('idtb_zona', 'nombre_zona')
        // ->groupBy('idtb_zona', 'nombre_zona')
        // ->get();

        // $institutos = DB::connection('DB4')->table('tb_instituto_superior')
        //     ->select('id_instituto_superior', 'nombre_instsup')
        //     ->groupBy('id_instituto_superior', 'nombre_instsup')
        //     ->get();

        // $carreras = DB::connection('DB4')->table('tb_carreras')
        // ->join('rel_instsup_carrera', 'tb_carreras.idCarrera', '=', 'rel_instsup_carrera.idCarrera')
        // ->select('tb_carreras.idCarrera', 'tb_carreras.nombre_carrera')
        // ->groupBy('tb_carreras.idCarrera', 'tb_carreras.nombre_carrera')
        // ->get();

        $institutos = [];
        $carreras = [];
        $relInstCarr=RelInstSupCarrera::all();
        $zonas = Zona::all(); 
        $tiposLlamado = TipoLlamado::all();
        // $institutos = InstitutoSuperior::all();
        // $carreras = Carrera::all();
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
  
    
    public function editarLlamado($id)
    {   
        //lo necesario para el formulario de editar llamado
        $llamado = Llamado::findOrFail($id);
        // $zonas = DB::connection('DB4')->table('tb_zona')
        //     ->select('idtb_zona', 'nombre_zona')
        //     ->groupBy('idtb_zona', 'nombre_zona')
        //     ->get();

        // $institutos = DB::connection('DB4')->table('tb_instituto_superior')
        //     ->select('id_instituto_superior', 'nombre_instsup')
        //     ->groupBy('id_instituto_superior', 'nombre_instsup')
        //     ->get();

        // $carreras = DB::connection('DB4')->table('tb_carreras')
        //     ->join('rel_instsup_carrera', 'tb_carreras.idCarrera', '=', 'rel_instsup_carrera.idCarrera')
        //     ->select('tb_carreras.idCarrera', 'tb_carreras.nombre_carrera')
        //     ->groupBy('tb_carreras.idCarrera', 'tb_carreras.nombre_carrera')
        //     ->get();
        $institutos = [];
        $carreras = [];
        $relInstCarr=RelInstSupCarrera::all();
        $zonas = Zona::all(); 
        $tiposLlamado = TipoLlamado::all();
        // $institutos = InstitutoSuperior::all();
        // $carreras = Carrera::all();
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
            'llamado',
            'relcargos'
        ));

    }




    public function crearLlamado(Request $request)
    {
        $llamado = new Llamado();
        $llamado->idtb_tipoestado = 2; //en proceso
        $llamado->fecha_ini=Carbon::now();
        $llamado->fecha_fin=Carbon::now();
        $llamado->idtb_zona = 8;
        $llamado->id_instituto_superior = 1;
        $llamado->idtipo_llamado = 1;
        $llamado->idCarrera = 171;
       // $llamado->descripcion = 'Llamado de prueba';
        $llamado->url_form = 'https://example.com/formulario';
        $llamado->nombre_img = 'imagen_prueba.jpg'; // Cambia esto por el nombre de la imagen que subiste
        $llamado->mes = null; // Cambia esto por el mes actual
        $llamado->idUsuarioCrear = session('idUsuario'); // Cambia esto por el ID del usuario que crea el llamado
        $llamado->idUsuarioEditar=session('idUsuario');// Cambia esto por el ID del estado que quieras asignar
        



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
            $llamado->idUsuarioEditar=session('idUsuario');
            $llamado->save();

            return response()->json(['success' => true]);
    }

    public function agregarEspacio(Request $request)
    {

        $nuevoEspacio = new EspacioPorLlamado();
        $nuevoEspacio->idLlamado = $request->llamado_id;
        $nuevoEspacio->idEspacioCurricular = $request->idEspacioCurricular_modal;
        $nuevoEspacio->idTurno = $request->idTurno?$request->idTurno:6;
        $nuevoEspacio->horacat_espacio = $request->horacat_modal?$request->horacat_modal: 0;
        $nuevoEspacio->idtb_situacion_revista = $request->idtb_situacion_revista_modal;
        $nuevoEspacio->idtb_periodo_cursado = $request->idtb_periodo_cursado?$request->idtb_periodo_cursado:4;
        $nuevoEspacio->horario_espacio = $request->horario_modal;
       // $nuevoEspacio->idtb_perfil = $request->idtb_perfil_modal;
        $nuevoEspacio->idtb_perfil = $request->idtb_perfil_modal!=null?$request->idtb_perfil_modal:3; // perfil por defecto
        $nuevoEspacio->idUsuarioCrear = session('idUsuario'); // Cambia esto por el ID del usuario que crea el llamado
        $nuevoEspacio->idUsuarioEditar=session('idUsuario');// Cambia esto por el ID del estado que quieras asignar
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
        $espacio->idUsuarioEditar=session('idUsuario');// Cam
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
        $nuevoCargo->idUsuarioCrear = session('idUsuario'); // Cambia esto por el ID del usuario que crea el llamado
        $nuevoCargo->idUsuarioEditar=session('idUsuario');// Cam
        $nuevoCargo->idtb_perfil = $request->idtb_perfil_modal!=null?$request->idtb_perfil_modal:3; // perfil por defecto
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
        $Cargo->idUsuarioEditar=session('idUsuario');// Cam
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

    //cambio estado para el llamado
    public function cambiarEstado(Request $request)
    {
        try {
            $llamado = Llamado::findOrFail($request->idllamado);
            $llamado->idtb_tipoestado = $request->idtb_tipoestado;
            $llamado->idUsuarioEditar = session('idUsuario'); // Cambia esto por el ID del usuario que edita el llamado
            $llamado->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    //cambio estado para el LOM
    // En LlamadosController o LomController
    public function cambiarEstadoLom(Request $request)
    {
        try {
            $lom = Lom::findOrFail($request->idllamado); // el ID viene del select con data-id
            $lom->idtb_tipoestado = $request->idtb_tipoestado;
            $lom->idUsuarioEditar = session('idUsuario'); // Cambia esto por el ID del usuario que edita el LOM
            $lom->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

     // instituto por zona
    public function obtenerInstitutosPorZona(Request $request)
    {
        try {
             $zonaId = $request->input('zona_id');
     
             if (!$zonaId) {
                 return response()->json(['error' => 'ID de zona no proporcionado'], 400);
             }
     
             $institutos = DB::connection('DB4')->table('tb_instituto_superior')
                 ->join('rel_zona_instsup', 'tb_instituto_superior.id_instituto_superior', '=', 'rel_zona_instsup.id_instituto_superior')
                 ->where('rel_zona_instsup.idtb_zona', $zonaId)
                 ->select('tb_instituto_superior.id_instituto_superior', 'tb_instituto_superior.nombre_instsup')
                 ->orderBy('tb_instituto_superior.nombre_instsup')
                 ->get();
     
             return response()->json($institutos);
        } catch (\Throwable $e) {
             return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // carrera por instituto
    public function obtenerCarrerasPorInstituto(Request $request)
    {
        try {
            $institutoId = $request->input('instituto_id');

            if (!$institutoId) {
                return response()->json(['error' => 'ID de instituto no proporcionado'], 400);
            }

            $carreras = DB::connection('DB4')->table('tb_carreras')
                ->join('rel_instsup_carrera', 'tb_carreras.idCarrera', '=', 'rel_instsup_carrera.idCarrera')
                ->where('rel_instsup_carrera.id_instituto_superior', $institutoId)
                ->select('tb_carreras.idCarrera', 'tb_carreras.nombre_carrera')
                ->orderBy('tb_carreras.nombre_carrera')
                ->get();

            return response()->json($carreras);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function eliminarLlamado($id)
    {
        $llamado = Llamado::findOrFail($id);
        $llamado->delete();

        return redirect()->route('aegis.sistemas.superior.llamados.index')
            ->with('success', 'Llamado eliminado correctamente.');
    }
    
    //perfil por espacio y por cargo
    public function listarPerfiles() {
        return Perfil::all();
    }
    public function nuevoPerfil(Request $request)
    {
        $request->validate([
            'nombre_perfil' => 'required|string|max:255'
        ]);

        $perfil = new Perfil(); // Modelo: Perfil
        $perfil->nombre_perfil = $request->nombre_perfil;
       
        $perfil->save();

        return response()->json([
            'success' => true,
            'mensaje' => 'Perfil creado correctamente',
            'perfil' => $perfil
        ]);
    }

    public function editarPerfil(Request $request, $id)
    {
        $request->validate([
            'nombre_perfil' => 'required|string|max:255'
        ]);

        $perfil = Perfil::findOrFail($id);
        $perfil->nombre_perfil = $request->nombre_perfil;
        $perfil->save();

        return response()->json([
            'success' => true,
            'mensaje' => 'Perfil actualizado correctamente',
            'perfil' => $perfil
        ]);
    }
    //listar espacios curriculares
    public function listarEspaciosCurriculares()
    {
        return EspacioCurricular::all(); // ajustá el modelo si es otro
    }
    //espacio curricular agregar/editar

    public function nuevoEspacioCurricular(Request $request)
    {
        $request->validate([
            'nombre_espacio' => 'required|string|max:255'
        ]);

        $espacio = new EspacioCurricular(); // tu modelo
        $espacio->nombre_espacio = $request->nombre_espacio;
        $espacio->save();

        return response()->json([
            'success' => true,
            'mensaje' => 'Espacio Curricular creado correctamente',
            'espacio' => $espacio
        ]);
    }

    public function editarEspacioCurricular(Request $request, $id)
    {
        $request->validate([
            'nombre_espacio' => 'required|string|max:255'
        ]);

        $espacio = EspacioCurricular::findOrFail($id);
        $espacio->nombre_espacio = $request->nombre_espacio;
        $espacio->save();

        return response()->json([
            'success' => true,
            'mensaje' => 'Espacio Curricular actualizado correctamente',
            'espacio' => $espacio
        ]);
    }


}