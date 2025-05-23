<?php

namespace App\Http\Controllers;

use App\Exports\PofmhCargoDetailExport;
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
use App\Models\POFMH\PofmhModel;
use App\Models\POFMH\PofmhNovedades;
use App\Models\POFMH\PofmhNovedadesExtras;
use App\Models\POFMH\PofmhOrigenCargoModel;
use App\Models\POFMH\PofMhSitRev;
use App\Models\POFMH\PofmhTurnos;
use App\Models\Sage\LogPofmhModel;
use Illuminate\Http\Request;
use App\Jobs\ExportarPof;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PhpParser\NodeVisitor\FirstFindingVisitor;
use App\Exports\PofmhExport;
use App\Models\POFMH\AsistenciaModel;
use App\Models\POFMH\PofmhCalendarioModel;
use App\Models\POFMH\PofmhCargoSalariales;
use App\Models\POFMH\PofmhTipoCalendarioModel;
use App\Models\POFMH\RelCargoAulaDivModel;
use App\Models\Sage\PofIpeModel;
use App\Models\Sage\SuperRelacionCUEModel;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PofHorizontalController extends Controller
{
    //activo middleware, solo lo verán logeado
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

    public function actualizarPorChunks()
    {
        set_time_limit(0); // Para evitar que el proceso se detenga por tiempo de ejecución
        $contador = 0;
       
        // Procesamos la tabla `liqfeb2024` en chunks de 100 registros, ordenando por 'Documento'
        LiqFeb24Model::orderBy('Documento')->chunk(100, function ($liqfeb) use (&$contador) {
            foreach ($liqfeb as $registro) {
                // Paso 1: Buscamos la institución relacionada en `tb_instituciones` usando el código de liquidación (Escuela)
                $institucion = DB::table('tb_institucion')
                                ->where('Unidad_Liquidacion', $registro->Escuela)
                                ->first();
    
                if ($institucion) {
                    // Paso 2: Buscamos la extensión de la institución en `tb_institucion_extension` usando el idInstitucion
                    $institucion_extension = DB::table('tb_institucion_extension')
                                               ->where('idInstitucion', $institucion->idInstitucion)
                                               ->first();
    
                    if ($institucion_extension) {
                        // Paso 3: Buscamos el agente en `tb_pofmh` usando el Documento (DNI) y CUECOMPLETO
                        $agente = PofmhModel::where('Agente', $registro->Documento)
                                            ->where('CUECOMPLETO', $institucion_extension->CUECOMPLETO)
                                            ->first();
    
                        // Paso 4: Si el agente existe y Unidad_Liquidacion está vacío, actualizamos
                        if ($agente && empty($agente->Unidad_Liquidacion)) {
                            // Actualizamos con el valor de la columna Escuela
                            $agente->update(['Unidad_Liquidacion' => $registro->Escuela]);
    
                            // Mostrar mensaje de éxito
                            echo "$contador - Agente {$registro->Documento} actualizado con Escuela: {$registro->Escuela} (CUE: {$institucion_extension->CUECOMPLETO})<br>";
                        } else {
                            // Mensaje si no se aplicó la actualización
                            echo "$contador - Agente {$registro->Documento} no actualizado (ya tiene Unidad_Liquidacion o no coincide CUECOMPLETO).<br>";
                        }
                    } else {
                        // Mensaje si no se encuentra la extensión de la institución
                        echo "$contador - No se encontró la extensión de la institución para idInstitucion {$institucion->idInstitucion}.<br>";
                    }
                } else {
                    // Mensaje si no se encuentra la institución
                    echo "$contador - No se encontró la institución para el código de Escuela {$registro->Escuela}.<br>";
                }
    
                $contador++;
            }
        });
    
        echo "Proceso completado.\n";
    }
    
    //otra funcion pero para agrupar
    public function controlDuplicadosEntreTablas()
    {
        set_time_limit(0); // Evitar que el proceso se detenga por límite de tiempo de ejecución
        $contar = 0;
    
        // Paso 1: Procesar los registros de la tabla `libfeb2024` en chunks
        LiqFeb24Model::select('Documento', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('Documento')
            ->chunk(100, function($registrosLiqFeb) use (&$contar) {
    
            foreach ($registrosLiqFeb as $registroLiqFeb) {
                $contar++;
    
                // Paso 2: Agrupar el DNI en la tabla `pofmh` y contar cuántas veces aparece
                $conteoPofmh = PofmhModel::where('Agente', $registroLiqFeb->Documento)
                    ->count();
    
                // Paso 3: Comparar la cantidad de registros entre ambas tablas
                if ($conteoPofmh > $registroLiqFeb->cantidad) {
                    // Mostrar el Documento cuando la cantidad en `pofmh` es mayor que en `libfeb2024`
                    echo "<h3>DNI con más registros en pofmh que en libfeb2024:</h3>";
                    echo "<b>Documento:</b> " . $registroLiqFeb->Documento . "<br>";
                    echo "<b>Registros en libfeb2024:</b> " . $registroLiqFeb->cantidad . "<br>";
                    echo "<b>Registros en pofmh:</b> " . $conteoPofmh . "<br><br>";
                }
    
                // Mostrar progreso cada 100 registros
                if ($contar % 100 == 0) {
                    echo "<br>--------------------------------------------------------------";
                    echo "<br>Procesados $contar registros<br>";
                    echo "<br>--------------------------------------------------------------";
                    ob_flush();
                    flush();
                }
            }
        });
    
        echo "Proceso completado.<br>";
    }
    
    
    

    //armando proceso de cotejo de docente en aula contra pofmh
    public function verificoDuplicados()
    {
        set_time_limit(0); // Para evitar que el proceso se detenga por tiempo de ejecución
        $contador = 0;
        $contar = 0;
    
        // Procesamos la tabla `liqfeb2024` en chunks de 500 registros
        LiqFeb24Model::chunk(500, function ($registrosLiqFeb) use (&$contador, &$contar) {
    
            foreach ($registrosLiqFeb as $registro) {
    
                // Paso 1: Buscar en la tabla `tb_pofmh` los registros que coincidan con el DNI y la Escuela (Unidad_Liquidacion)
                $registrosPofmh = PofmhModel::join('tb_cargossalariales', 'tb_cargossalariales.idCargo', 'tb_pofmh.Cargo')
                    ->where('Agente', $registro->Documento)
                    ->where('Unidad_Liquidacion', $registro->Escuela)
                    ->select('tb_pofmh.*', 'tb_cargossalariales.Codigo as CodigoSal', 'tb_cargossalariales.Cargo as CargoSal')
                    ->get();
    
                // Contar los registros encontrados en `PofmhModel`
                $cantidadPofmh = $registrosPofmh->count();
    
                // Verificar si hay más de 1 registro en `PofmhModel`
                if ($cantidadPofmh > 1) {
                    // Comprobar si hay situaciones de revista distintas
                    $situacionesUnicas = $registrosPofmh->pluck('SitRev')->unique();
    
                    if ($situacionesUnicas->count() === $cantidadPofmh) {
                        // Imprimir solo si hay duplicados con la misma situación de revista
                        echo "<h3><b>ALERTA: Se encontraron " . $cantidadPofmh . " registros en POFMH para el DNI " . $registro->Documento . " y la Escuela " . $registro->Escuela . "</b></h3><br>";
    
                        // Paso 2: Buscamos la institución relacionada en `tb_instituciones extension` usando el código de liquidación (Escuela)
                        $institucion = DB::table('tb_institucion')
                            ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', 'tb_institucion.CUECOMPLETO')
                            ->where('Unidad_Liquidacion', $registro->Escuela)
                            ->select('tb_institucion_extension.CUECOMPLETO', 'tb_institucion_extension.Nombre_Institucion', 'tb_institucion_extension.Nivel', 'tb_institucion_extension.EsPrivada')
                            ->first();
    
                        // Validar que el registro de la institución no sea null
                        $nombreInstitucion = $institucion ? $institucion->Nombre_Institucion : "Sin Datos";
                        $cueCompleto = $institucion ? $institucion->CUECOMPLETO : "Sin Datos";
                        $nivel = $institucion ? $institucion->Nivel : "Sin Datos";
                        $esPrivada = $institucion ? $institucion->EsPrivada : "Sin Datos";
    
                        echo "Nombre_Institucion: <b>" . $nombreInstitucion . "</b><br>";
                        echo "CUECOMPLETO: <b>" . $cueCompleto . "</b><br>";
                        echo "Nivel: <b>" . $nivel . "</b><br>";
                        echo "Es Privada: <b>" . $esPrivada . "</b><br>";
                        echo "<hr>";
    
                        // Imprimir los detalles de cada registro duplicado encontrado en `PofmhModel`
                        foreach ($registrosPofmh as $registroPofmh) {
                            // Convertir SitRev en texto
                            $sitRevDescripcion = $this->getSitRevDescripcion($registroPofmh->SitRev);
                            $institucionInfo = DB::table('tb_institucion_extension')
                                ->where('tb_institucion_extension.CUECOMPLETO', $registroPofmh->CUECOMPLETO)
                                ->first();
    
                            $nombreInstitucionExt = $institucionInfo ? $institucionInfo->Nombre_Institucion : "Sin Datos";
    
                            echo "ID POFMH: <b>" . $registroPofmh->idPofmh . "</b><br>";
                            echo "CUE Completo: <b>" . $registroPofmh->CUECOMPLETO . "-" . $nombreInstitucionExt . "</b><br>";
                            echo "Orden: <b>" . $registroPofmh->orden . "</b><br>";
                            echo "Agente: <b>" . $registroPofmh->Agente . "</b><br>";
                            echo "Apellido y Nombre: <b>" . $registroPofmh->ApeNom . "</b><br>";
                            echo "Cargo: <b>" . $registroPofmh->CodigoSal . "-" . $registroPofmh->CargoSal . "</b><br>";
                            echo "Horas: <b>" . $registroPofmh->Horas . "</b><br>";
                            echo "Situación Revista: <b>" . $sitRevDescripcion . "</b><br>"; // Mostrar descripción
                            echo "Antigüedad: <b>" . $registroPofmh->Antiguedad . "</b><br>";
                            echo "Unidad Liquidación: <b>" . $registroPofmh->Unidad_Liquidacion . "</b><br>";
                            echo "Nivel: <b>" . $registroPofmh->Nivel . "</b><br><br>";
                        }
    
                        $contador++;
                    }
                }
    
                $contar++;
    
                // Mostrar progreso cada 100 registros
                if ($contar % 100 == 0) {
                    echo "<br>--------------------------------------------------------------";
                    echo "<br>Procesados $contar registros<br>";
                    echo "<br>--------------------------------------------------------------";
                    ob_flush();
                    flush();
                }
            }
        });
    
        echo "Total de registros procesados con duplicados: $contador<br>";
        echo "Proceso completado.<br>";
    }
    
    // Método para obtener la descripción de SitRev
    private function getSitRevDescripcion($sitRev)
    {
        switch ($sitRev) {
            case 1:
                return "SIN DATOS";
            case 2:
                return "TITULAR";
            case 3:
                return "PLANTA PERMANENTE";
            case 4:
                return "VOLANTE";
            case 5:
                return "VINCULADO Y/O TEMPORARIO";
            case 6:
                return "SUPLENTE";
            case 7:
                return "INTERINO";
            default:
                return "DESCONOCIDO";
        }
    }
    
    
    //funcion para traer el estado de los agentes antes de crear nueva POF
    public function generarPDFListadoPOF(){
        set_time_limit(0); // Permitir tiempo de ejecución ilimitado
        //traigo a todos los agentes
        $ListaAgentes =PofmhModel::all();

        
    }
    
    public function cue_sin_datos(){
        set_time_limit(0); // Permitir tiempo de ejecución ilimitado
        //traigo a todos los agentes
        $ListaAgentes =PofmhModel::all();

        
    }
    
    
    
    

    /*proceso para pasar de liq a la tabla */

    //agrego cargo origen a pedido
    public function procesarOrigenesCargos() {
        set_time_limit(0); // Permitir tiempo de ejecución ilimitado
        
        // Números que deseas insertar
        $numerosParaInsertar = [10, 27, 24, 18, 2, 3];
        
        // Ajusta estos valores según tus necesidades
        $nivelBuscado = "Inicial";
        $categoriaBuscada = "1°";
        $cueBuscado = "999999900"; // CUE que estás buscando
    
        // Usar chunk para procesar los registros en bloques
        DB::table('tb_institucion_extension')
            ->where('CUECOMPLETO', $cueBuscado)
            ->where('Nivel', $nivelBuscado)
            ->where('Categoria', $categoriaBuscada)
            ->orderBy('idInstitucionExtension') // Asegúrate de tener una columna de orden (puede ser 'id' o cualquier otra columna relevante)
            ->chunk(1000, function ($instituciones) use ($numerosParaInsertar) {
                foreach ($instituciones as $institucion) {
                    // Inserta cada número como un nuevo registro
                    foreach ($numerosParaInsertar as $numero) {
                        $nuevoRegistro = new PofmhOrigenCargoModel();
                        $nuevoRegistro->nombre_origen = $numero; // Almacena el número
                        $nuevoRegistro->CUECOMPLETO = $institucion->CUECOMPLETO; // Almacena el CUE
    
                        $nuevoRegistro->save();
                        
                        echo "Se insertó el número: $numero para CUE: {$institucion->CUECOMPLETO}<br>";
                    }
                }
            });
    
        echo "Proceso finalizado.";
    }
    
    //modelo general de carga masiva por nivel y categoria
    public function procesarOrigenesCargos_todos() {
        set_time_limit(0); // Permitir tiempo de ejecución ilimitado
        
        // Números que deseas insertar
        //$numerosParaInsertar = [10, 27, 24, 18, 2, 3, 8];   //primera inicial
        //$numerosParaInsertar = [10, 5, 18, 8];   //segunda inicial
        //$numerosParaInsertar = [10, 27, 24, 17, 2, 3, 19, 20, 21, 8];   //primario 1
        //$numerosParaInsertar = [10, 17, 2, 3, 19, 20, 21, 8];   //primario 2
        //$numerosParaInsertar = [11, 17, 2, 3, 12, 8];   //primario 3
        //$numerosParaInsertar = [10, 27, 24, 22, 7, 4, 14, 25, 1, 8,30, 31, 32, 33, 34, 35, 36];   //secundario
        //$numerosParaInsertar = [23,26,24,16,13,15,22,6,7,14,8];   //superior
        //$numerosParaInsertar = [10, 27, 9, 24, 17, 28, 22, 1, 29, 8];   //Adultos
        $numerosParaInsertar = [10, 24,39,40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 8];   //Especial
        
        // Ajusta estos valores según tus necesidades
        $nivelBuscado = "Especial";
        $categoriaBuscada = "3°";
    
        // Usar chunk para procesar los registros en bloques
        DB::table('tb_institucion_extension')
            ->where('Nivel', $nivelBuscado)
            //->where('Categoria', $categoriaBuscada)
            ->orderBy('CUECOMPLETO') // Asegúrate de tener una columna de orden
            ->chunk(1000, function ($instituciones) use ($numerosParaInsertar) {
                foreach ($instituciones as $institucion) {
                    // Inserta cada número como un nuevo registro si no existe
                    foreach ($numerosParaInsertar as $numero) {
                        // Verificar si ya existe el registro
                        $existe = PofmhOrigenCargoModel::where('CUECOMPLETO', $institucion->CUECOMPLETO)
                            ->where('nombre_origen', $numero)
                            ->exists();
                        
                        if (!$existe) {
                            $nuevoRegistro = new PofmhOrigenCargoModel();
                            $nuevoRegistro->nombre_origen = $numero; // Almacena el número
                            $nuevoRegistro->CUECOMPLETO = $institucion->CUECOMPLETO; // Almacena el CUE
    
                            $nuevoRegistro->save();
                            
                            echo "Se insertó el número: $numero para CUE: {$institucion->CUECOMPLETO}<br>";
                        } else {
                            echo "El registro con número: $numero y CUE: {$institucion->CUECOMPLETO} ya existe. No se insertó.<br>";
                        }
                    }
                }
            });
    
        echo "Proceso finalizado.";
    }
    
    
    
    public function procesoliq(){
        set_time_limit(0); // Permitir tiempo de ejecución ilimitado
        //traer bd de liq
        $Liq = LiqFeb24Model::all();

        //recorro cada persona
        $contar=0;
        foreach($Liq as $l){
            //controlando si anda
           /* echo $l->Documento."<br>";
            $contar++;
            */
            // if($contar == 7000)
            // break;

            //busco en la institucion  por ese codigo de escuela
            $inst = InstitucionModel::where('Unidad_Liquidacion',$l->Escuela)->first(); //traigo la primera que encuentra
            //pregunto para ver si encontro o no
            if($inst){
                //si encontro dato ahora consulto a la tabla inst ext para localizar su cuecompleto
                $instExt = InstitucionExtensionModel::where('idInstitucion', $inst->idInstitucion)
                ->orderBy('CUECOMPLETO', 'asc') //traera solo turno Mañana por lo general, es 00
                ->first(); //traigo la primera ext que encuentra

                //si no esta vacia
                if($instExt){
                    //extraigo el cue
                    echo $contar."-".$l->Documento." si se puede vincular al cue ".$instExt->CUECOMPLETO."<br>";

                    //lo inserto 
                    $nuevo = new PofmhModel();
                        $nuevo->CUECOMPLETO = $instExt->CUECOMPLETO; // Asignar el CUE completo
                        $nuevo->idTurnoUsuario = $instExt->idTurnoUsuario; // Asignar el turno del usuario
                        $nuevo->orden = 0; // Orden correspondiente
                        $nuevo->Agente = $l->Documento; // Nombre del agente
                        $nuevo->Cuil = $l->Cuil; // CUIL del agente
                        $nuevo->ApeNom = $l->ApeNom; // Apellido y Nombre del agente
                        $nuevo->Cargo = 1; // Cargo del agente
                        $nuevo->Divisiones = 1; // Divisiones asignadas
                        $nuevo->updated_at = now(); // Asignar fecha actual para actualización
                        $nuevo->created_at = now(); // Asignar fecha actual para creación
                        $nuevo->EspCur = ""; // Especialidad curricular
                        $nuevo->Turno = 1; // Turno del agente
                        $nuevo->Horas = 0; // Horas asignadas
                        $nuevo->Origen = "cargar"; // Origen del agente
                        $nuevo->SitRev = 1; // Situación de revista
                        $nuevo->FechaAltaCargo = null; // Fecha alta del cargo
                        $nuevo->FechaDesignado = null; // Fecha de designación
                        $nuevo->Condicion = 1; // Condición del agente
                        $nuevo->FechaDesde = null; // Fecha desde
                        $nuevo->FechaHasta = null; // Fecha hasta
                        $nuevo->Motivo = 1; // Motivo de la asignación
                        $nuevo->DatosPorCondicion = "cargar"; // Datos según la condición
                        $nuevo->Antiguedad = 0; // Antigüedad del agente
                        $nuevo->AgenteR = null; // Agente responsable
                        $nuevo->Novedades = null; // Novedades registradas
                        $nuevo->Asistencia = 0; // Asistencia del agente
                        $nuevo->Justificada = 0; // Asistencia justificada
                        $nuevo->Injustificada = 0; // Asistencia injustificada
                        $nuevo->Observaciones = "Sin observaciones"; // Observaciones generales
                        $nuevo->Sexo = $l->Sexo; // Sexo del agente
                        $nuevo->Zona = $l->Zona; // Zona de trabajo
                        $nuevo->Descuento_zona = $l->Descuento_zona; // Descuento por zona
                        $nuevo->Unidad_Liquidacion = $l->Unidad_Liquidacion; // Unidad de liquidación
                        $nuevo->Descuento_Escuela = $l->Descuento_Escuela; // Descuento aplicado a la escuela
                        $nuevo->Nivel = $l->Nivel; // Nivel asignado
                        $nuevo->Plan = $l->Plan; // Plan asignado
                        $nuevo->Descuento_Plan = $l->Descuento_Plan; // Descuento aplicado al plan
                        $nuevo->Agrupamiento = $l->Agrupamiento; // Agrupamiento del agente
                        $nuevo->Descuento_Agrupamiento = $l->Descuento_Agrupamiento; // Descuento por agrupamiento
                        $nuevo->LCategoria = $l->LCategoria; // Categoría de liquidación
                        $nuevo->NCategoria = $l->NCategoria; // Nueva categoría
                        $nuevo->Codigo_Nomenclador = $l->Codigo_Nomenclador; // Código del nomenclador
                        $nuevo->Nomenclador = $l->Nomenclador; // Nombre del nomenclador

                        // Guardar el nuevo registro en la base de datos
                        $nuevo->save();

                    //aqui me gustaria saber si puedo eliminar el objeto $l, osea borrar el registro
                    // Eliminar el registro actual de la tabla original
                    $l->migrado="Si";
                    //$l->delete();
                }else{
                    //echo $contar."-".$l->Documento." no se encontro o no esta vinculada la institucion(".$l->Escuela.")<br>";
                    $l->migrado="no";
                }
                $l->save();

            }else{
                //echo $contar."-".$l->Documento." no se encontro unidad de liquidacion, no se puede vincular<br>";
            }
            $contar++;
            //sleep(1);
        }
        echo "fin cantidad registros $contar";
    }

    public function procesoliq_mejor() {
        set_time_limit(0); // Permitir tiempo de ejecución ilimitado
    
        $contar = 0;
    
        // Usar chunk para procesar los registros en bloques
        LiqFeb24Model::chunk(1000, function ($Liq) use (&$contar) {
            foreach ($Liq as $l) {
                // Buscar la institución por el código de escuela
                $inst = InstitucionModel::where('Unidad_Liquidacion', $l->Escuela)->first();
                
                if ($inst) {
                    // Buscar la extensión de la institución
                    /*$instExt = InstitucionExtensionModel::where('idInstitucion', $inst->idInstitucion)
                        ->orderBy('CUECOMPLETO', 'asc') // Generalmente trae el turno mañana (00)
                        ->first();*/
    
                    if ($inst) {
                        // Insertar el nuevo registro en PofmhModel
                        $nuevo = new PofmhModel();
                        $nuevo->CUECOMPLETO = $inst->CUECOMPLETO;
                        $nuevo->orden = 0;
                        $nuevo->Agente = $l->Documento;
                        $nuevo->Cuil = $l->Cuil;
                        $nuevo->ApeNom = $l->ApeNom;
                        //busco el cargo o sit rev
                            $infoCargo = DB::table('tb_cargossalariales')->where('Codigo',$l->Codigo_Nomenclador)->first();
                        $nuevo->Cargo = $infoCargo->idCargo;    //Ej Maestro de Jardin D01
                        $nuevo->Aula = 1;
                        $nuevo->Division = 1;
                        $nuevo->updated_at = now();
                        $nuevo->created_at = now();
                        $nuevo->EspCur = "";
                        $nuevo->Turno = 1;  //lo dejo en mañana por defecto
                        $nuevo->Horas = $l->Hora;
                        $nuevo->Origen = "cargar";
                        $infoSitRev = PofMhSitRev::where('Descripcion', $l->Descuento_Plan)->first();

                        // Validar si se encontró un resultado
                        if($infoSitRev) {
                            $nuevo->SitRev = $infoSitRev->idSituacionRevista;
                        } else {
                            // Manejo cuando no se encuentra la situación de revista
                            $nuevo->SitRev = null; // o puedes asignar un valor predeterminado si lo deseas
                        }
                        $nuevo->FechaAltaCargo = null;
                        $nuevo->FechaDesignado = null;
                        $nuevo->Condicion = 1;
                        $nuevo->FechaDesde = null;
                        $nuevo->FechaHasta = null;
                        $nuevo->Motivo = 65;        //como es la primera vez no tiene info
                        $nuevo->DatosPorCondicion = "cargar";
                        $nuevo->Antiguedad = $l->Antiguedad;
                        $nuevo->AgenteR = null;
                        //ahora en novedad se usa un modal
                        $nuevo->Asistencia = 0;     //se justifica en modal
                        $nuevo->Justificada = 0;    //se justifica en modal
                        $nuevo->Injustificada = 0;  //se justifica en modal
                        $nuevo->Observaciones = "Sin observaciones";
                        $nuevo->Sexo = $l->Sexo;
                        $nuevo->Zona = $l->Zona;
                        $nuevo->Descuento_zona = $l->Descuento_zona;
                        $nuevo->Unidad_Liquidacion = $l->Escuela;
                        $nuevo->Descuento_Escuela = $l->Descuento_Escuela;
                        $nuevo->Nivel = $l->Nivel;
                        $nuevo->Plan = $l->Plan;
                        $nuevo->Descuento_Plan = $l->Descuento_Plan;
                        $nuevo->Agrupamiento = $l->Agrupamiento;
                        $nuevo->Descuento_Agrupamiento = $l->Descuento_Agrupamiento;
                        $nuevo->LCategoria = $l->LCategoria;
                        $nuevo->NCategoria = $l->NCategoria;
                        $nuevo->Codigo_Nomenclador = $l->Codigo_Nomenclador;
                        $nuevo->Nomenclador = $l->Nomenclador;
                        $nuevo->Carrera = null;
                        $nuevo->Orientacion = null;
                        $nuevo->Titulo = null;
                        $nuevo->save();
    
                        // Actualizar estado del registro en la tabla original
                        $l->migrado = "Si";
                    } else {
                        $l->migrado = "No";
                        echo "No se pudo imprimir el EDULIC: ".$l->Escuela."<br>";
                    }
                }
    
                $l->save();
                $contar++;
    
                // Mostrar progreso
                if ($contar % 100 == 0) {
                    echo "Procesados $contar registros<br>";
                    ob_flush();
                    flush();
                }
            }
        });
    
        echo "Proceso finalizado. Total de registros procesados: $contar";
    }
    
    public function verificar_dni_nombre(){
        set_time_limit(0);

        //separo la logica en agentes y pof
        $agentes = DB::table('tb_agentes')
        ->select('Documento', 'ApeNom')
        ->get()
        ->pluck('ApeNom', 'Documento') // Convierte el resultado en un array asociativo [Documento => ApeNom]
        ->toArray();
        
        //como la pofmh es grande, le aplico modo chunck para ir trayendo de a poquito
        PofmhModel::chunk(1000,function($pofmh) use ($agentes){
            foreach($pofmh as $pof){
                //aqui verifico si la tabla tiene campos vacios en su nombre
                if($pof->ApeNom == "" || $pof->ApeNom == null){
                    //aqui tenemos el bug, debemos corregirlo
                    //voy a consultar la tabla agentes para poder localizar el DNI si existe y traer su nombre
                    $infoAgente =  DB::table('tb_agentes')->where('Documento',$pof->Agente)->first();

                    //en caso que exista lo modifico en pof usando el apellido
                    if (isset($agentes[$pof->Agente])) {
                        $pof->ApeNom = $agentes[$pof->Agente];
                        $pof->save(); // no debo olvidar el save, sino no me los guardara :)
                    }
                }
            }
        });
    }

    //proceso para crear los agentes desde liquidacion
    public function procesoliq_agentes_liq() {
        set_time_limit(0); // Permitir tiempo de ejecución ilimitado
    
        $contar = 0;
    
        // Usar chunk para procesar los registros en bloques
        LiqFeb24Model::chunk(1000, function ($Liq) use (&$contar) {
            foreach ($Liq as $l) {
                // Buscar la institución por el código de escuela
                $Agente = AgenteRespaldoModel::where('Documento',$l->Documento)->first();
                
                if (!$Agente) {
                    // Insertar el nuevo registro en PofmhModel
                    $nuevo = new AgenteRespaldoModel();
                        $nuevo->Documento = $l->Documento;
                        $nuevo->Cuil = $l->Cuil;
                        $nuevo->Apenom = $l->ApeNom;
                    $nuevo->Sexo = $l->Sexo;
                    
                    $nuevo->save();

                    // Actualizar estado del registro en la tabla original
                    $l->migrado = "Si";
                    echo "Si se pudo Agregar el agente, ya existe: ".$l->Documento."<br>";
                } else {
                    $l->migrado = "No";
                    echo "No se pudo Agregar el agente, ya existe: ".$l->Documento."<br>";
                }
    
                $l->save();
                $contar++;
    
                // Mostrar progreso
                if ($contar % 100 == 0) {
                    echo "Procesados $contar registros<br>";
                    ob_flush();
                    flush();
                }
            }
        });
    
        echo "Proceso finalizado. Total de registros procesados: $contar";
    }
    /* fin de proceso*/
    public function cargar_pof_horizontal(){
        //le paso todos los datos que necesito

        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
        );
        
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">POF Horizontal</a></li>
            <li class="breadcrumb-item active"><a href="'.route('cargar_pof_horizontal').'">Modelo de POF Nuevo</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.POF.cargar_pof_horizontal',$datos);
    }

    public function crearRegistro(Request $request)
    {
        // Capturar los datos enviados desde el cliente
        $cue = $request->input('cue'); // Obtener el valor de cue
        $turno = $request->input('turno'); // Obtener el valor de turno
    
        // Crear un nuevo registro vacío
        $registro = new PofmhModel();
            $registro->CUECOMPLETO = $cue; 
            $registro->Turno = $turno; 
        $registro->save(); // Guardar en la base de datos
    
        // Verificar que se guardó correctamente
        if ($registro->exists) {
            // Retornar el ID del registro
            return response()->json([
                'success' => true,
                'id' => $registro->idPofmh
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear el registro.'
            ]);
        }
    }

  public function escuelasCargadasPOFMH(){
            //extras a enviar
            $escuelas = InstitucionExtensionModel::Join('tb_ambitos', 'tb_ambitos.idAmbito', '=', \DB::raw('IFNULL(tb_institucion_extension.Ambito, 1)'))
            ->leftJoin('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
            ->leftJoin('tb_zonasupervision', 'tb_zonasupervision.idZonaSupervision', '=', 'tb_institucion_extension.ZonaSupervision')
            ->select(
                'tb_institucion_extension.idInstitucionExtension',
                'tb_institucion_extension.CUECOMPLETO',
                'tb_institucion_extension.Nombre_Institucion',
                'tb_institucion_extension.Nivel',
                'tb_institucion_extension.Categoria',
                'tb_institucion_extension.Localidad',
                'tb_institucion_extension.Departamento',
                'tb_institucion_extension.Zona',
                'tb_institucion_extension.ZonaSupervision',
                'tb_institucion_extension.Jornada',
                'tb_institucion_extension.Ambito',
                'tb_ambitos.nombreAmbito',
                'tb_turnos_usuario.Descripcion',
                \DB::raw('IFNULL(tb_zonasupervision.Descripcion, "Sin datos") as ZonaSuper'),
                \DB::raw('IFNULL(tb_zonasupervision.Codigo, "Sin datos") as ZonaSuperCodigo')
            )
            ->get();
        
        


           
            $datos=array(
                'mensajeError'=>"",
                'Escuelas'=>$escuelas,
                
                'mensajeNAV'=>'Panel de Configuración de Usuarios',
            );
            //dd($infoPlaza);
            return view('bandeja.POF.escuelasCargadas',$datos);
  }

  public function verPofMhidExt($idExtension){
    ini_set('memory_limit', '2028M');
    //busco la institucion para cargar sus datos
    $institucionExtension = InstitucionExtensionModel::where('idInstitucionExtension', $idExtension)
    ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
    ->leftJoin('tb_nivelesensenanza', 'tb_institucion_extension.Nivel', '=', 'tb_nivelesensenanza.NivelEnsenanza') // Usa leftJoin para traer resultados sin importar si Nivel es null
    ->select('tb_institucion_extension.*', 'tb_turnos_usuario.*', 'tb_nivelesensenanza.*')
    ->first();

    //dd($institucionExtension);
    //traigo su nuevo POFMH
    $perPage = request('perPage', 75); // Por defecto 50 registros
    if ($perPage == 'all') {
        $pofmh = PofmhModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->orderBy('orden', 'ASC')
            ->paginate(5000); // Obtener todos los registros
    } else {
        $pofmh = PofmhModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->orderBy('orden', 'ASC')
            ->paginate($perPage);
    }

    //dd($pofmh);
    //cargo los anexos
    $CargosSalariales =   DB::table('tb_cargossalariales')
    ->orderBy('Codigo','ASC')
    ->get();
    $Condiciones =   CondicionModel::all();
    $Aulas =   PofmhAulas::all();
    $Divisiones = PofmhDivisiones::all();
    $NovedadesExtras = PofmhNovedadesExtras::all();
    //probando turnos solicitados simples
    // Obtener el CUE enviado desde el frontend
    $valCUE = $institucionExtension->CUECOMPLETO;
    
    // Obtener los turnos habilitados de la tabla tb_institucion_extension
    $turnosHabilitados = DB::table('tb_institucion_extension')
        ->where('CUECOMPLETO', $valCUE)
        ->pluck('idTurnoUsuario'); // Extraer solo los valores de 'idTurnoUsuario'

    // Obtener los registros de PofmhTurnos filtrados por los turnos habilitados
    $Turnos = PofmhTurnos::whereIn('idTurno', $turnosHabilitados)->get();

    //$Turnos =   PofmhTurnos::all();
    $Activos =   PofmhActivosModel::all();
    $OrigenesDeCargos = PofmhOrigenCargoModel::all();
   // $AulasCreadas = RelCargoAulaDivModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)->get();
    
    $CargosCreados = PofmhOrigenCargoModel::where('CUECOMPLETO', 'like', '%' . $institucionExtension->CUE . '%')
    ->join('tb_cargos_pof_origen','tb_cargos_pof_origen.idCargos_Pof_Origen','tb_origenes_cargos.nombre_origen')
    ->get();
    
    //$EspCur =   DB::table('tb_turnos_usuario')->get();

    $SitRev =   PofMhSitRev::all();//DB::table('tb_situacionrevista')->get();
    $Motivos =   DB::table('tb_motivos')->get();
    //dd($Divisiones);
    $datos=array(
        'mensajeError'=>"",
        'institucionExtension'=>$institucionExtension,
        'infoPofMH'=>$pofmh,
        'CargosSalariales'=>$CargosSalariales,
        'Divisiones'=>$Divisiones,
        'Turnos'=>$Turnos,
        'SitRev'=>$SitRev,
        'Motivos'=>$Motivos,
        'Condiciones'=>$Condiciones,
        'Aulas'=>$Aulas,
        'NovedadesExtras'=>$NovedadesExtras,
        'Activos'=>$Activos,
        'OrigenesDeCargos'=>$OrigenesDeCargos,
        'CargosCreados'=>$CargosCreados,
        'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
    );
    
    $ruta ='
        <li class="breadcrumb-item active"><a href="#">POF Horizontal</a></li>
        <li class="breadcrumb-item active"><a href="'.route('cargar_pof_horizontal').'">Modelo de POF Nuevo</a></li>
        '; 
        session(['ruta' => $ruta]);
    return view('bandeja.POF.cargar_pof_horizontal',$datos);
  }


  public function verPofMhidExtPrueba($idExtension){
    set_time_limit(0);
    ini_set('memory_limit', '2028M');
    
    //busco la institucion para obtener el cue
    $CUECOMPLETO = InstitucionExtensionModel::where('idInstitucionExtension', $idExtension)
    ->pluck('CUECOMPLETO')
    ->first();
    //dd($CUECOMPLETO);
    
    //consulto la unidad y area desde instarealiq con su cue
    $infoUnidadLiq = DB::connection('DB8')->table('instarealiq')
    ->where('CUEA',$CUECOMPLETO)
    ->select('escu','area')
    ->groupBy('escu','area')
    ->first();
    //dd($infoUnidadLiq);

    if($infoUnidadLiq){
        //traer desde la tb_pof_ip los agentes
        $AgentesLista = PofIpeModel::where(function($query) use ($infoUnidadLiq) {
            if ($infoUnidadLiq) {
                $query->where('Escu', $infoUnidadLiq->escu)
                    ->where('Area', $infoUnidadLiq->area);
            }
        })
        ->join('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pof_ipe.Plan')
        ->join('tb_cargossalariales', function($join) {
            $join->on(DB::raw("CONCAT(tb_pof_ipe.lcat, tb_pof_ipe.ncat)"), '=', 'tb_cargossalariales.Codigo');
        })
        ->orderBy('idPofIpe', 'ASC')
        ->get();
        
    }else{
        $AgentesLista = null;
    }
    //dd($AgentesLista);

    $datos=array(
        'mensajeError'=>"",
        'AgentesLista'=>$AgentesLista,
        'infoUnidadLiq'=>$infoUnidadLiq,
        'CUECOMPLETO'=>$CUECOMPLETO,
        'idInstitucionExtension'=>$idExtension,
        'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
    );
    
    $ruta ='
        <li class="breadcrumb-item active"><a href="#">POF Horizontal Prueba</a></li>
        <li class="breadcrumb-item active"><a href="'.route('verPofMhidExtPrueba',$idExtension).'">Modelo de POF Nuevo</a></li>
        '; 
        session(['ruta' => $ruta]);
    return view('bandeja.POF.modeloUnificado.cargar_pof_horizontal_prueba',$datos);
  }
/*
  public function consultas_pof_agrupadas_ultima_concargo()
{
    ini_set('memory_limit', '2028M');

    // Obtener la institución por su CUE
    $institucionExtension = InstitucionExtensionModel::join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->leftJoin('tb_nivelesensenanza', 'tb_institucion_extension.Nivel', '=', 'tb_nivelesensenanza.NivelEnsenanza')
        ->select('tb_institucion_extension.*', 'tb_turnos_usuario.*', 'tb_nivelesensenanza.*')
        ->where('CUECOMPLETO', '460018300') // Limitar al CUE solicitado
        ->first();


    // Obtener los registros de POFMH con las columnas específicas
    $pofmh = PofmhModel::select('idPofmh','CUECOMPLETO', 'Origen','Cargo') // Seleccionar solo las columnas necesarias
        //->where('Nivel', $Nivel)
        //->where('Nivel', 'not like', 'Priv%') // Excluir los que comienzan con "Priv"
        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
        ->orderBy('idPofmh', 'ASC')
        ->limit(500)
        ->get();

 

    // Preparar los datos para la vista
    $datos = [
        'institucionExtension' => $institucionExtension,
        'infoPofMH' => $pofmh,
        'mensajeNAV' => 'Panel de Configuración de POF (Modalidad Horizontal)',
    ];

    $ruta = '
        <li class="breadcrumb-item active"><a href="#">POF Horizontal</a></li>
        <li class="breadcrumb-item active"><a href="' . route('cargar_pof_horizontal') . '">Modelo de POF Nuevo</a></li>
    ';
    session(['ruta' => $ruta]);

    return view('bandeja.POF.cantidadConCargo', $datos);
}
*/

public function consultas_pof_agrupadas_ultima_concargo()
{
    set_time_limit(0);
    ini_set('memory_limit', '2028M');

    // Obtener los registros de POFMH
    $pofmh = PofmhModel::select('idPofmh', 'CUECOMPLETO', 'Origen', 'Cargo')
        ->where('CUECOMPLETO', 'not like', '9500%')
        ->where('CUECOMPLETO', 'not like', '9999%')
        ->orderBy('idPofmh', 'ASC')
        //->limit(500)
        ->get();

    // Procesar los datos
    $data = $pofmh->map(function ($detalle) {
        // Obtener institución relacionada
        $institucion = DB::table('tb_institucion_extension')
            ->where('CUECOMPLETO', $detalle->CUECOMPLETO)
            ->select('Nivel', 'Nombre_Institucion')
            ->first();

        // Obtener cargo origen relacionado
        $cargoOrigen = null;
        if ($detalle->Origen) {
            $cargoOrigen = DB::connection('DB7')
                ->table('tb_origenes_cargos')
                ->where('idOrigenCargo', $detalle->Origen)
                ->join('tb_cargos_pof_origen', 'tb_cargos_pof_origen.idCargos_Pof_Origen', '=', 'tb_origenes_cargos.nombre_origen')
                ->select('tb_cargos_pof_origen.nombre_cargo_origen')
                ->first();
        }

        // Obtener cargo salarial relacionado
        $cargoSalarial = DB::table('tb_cargossalariales')
            ->where('idCargo', $detalle->Cargo)
            ->select('Cargo', 'Codigo')
            ->first();

        // Formatear los datos
        return [
            'idPofmh' => $detalle->idPofmh,
            'CUECOMPLETO' => $detalle->CUECOMPLETO,
            'Nivel' => $institucion->Nivel ?? 'Sin Definir',
            'Nombre_Institucion' => $institucion->Nombre_Institucion ?? 'Sin Definir',
            'Cargo_Origen' => $cargoOrigen->nombre_cargo_origen ?? 'Sin Definir',
            'Cargo_Salarial' => $cargoSalarial ? "{$cargoSalarial->Cargo} ({$cargoSalarial->Codigo})" : 'Sin Definir',
        ];
    });

    // Exportar a Excel
    return Excel::download(new PofmhCargoDetailExport($data), 'detalle_pofmh_con_cargo.xlsx');
}







  //desded aqui actualizando por celda
  public function actualizarOrden(Request $request)
  {

      $registro = PofmhModel::find($request->idPofmh);
  
      if ($registro) {
          $registro->orden = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
          $registro->save();
  
          return response()->json(['success' => true]);
      }
  
      return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
  }

  //dni

  public function actualizarDNI(Request $request)
  {
        //voy a crear el agente si no existe y si existe traigo algunos datos para la vista
        //consulto el agente por el dni
       /* $agente = DB::table('tb_agentes')
        ->where('Documento',$request->datoACambiar)->first();

        if($agente){
            $ApeNom = $agente->ApeNom;
        }*/

      $registro = PofmhModel::find($request->idPofmh);
  
      if ($registro) {
          $registro->Agente = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
          $registro->save();
  
          return response()->json(['success' => true]);
      }
  
      return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
  }

  //apenom
  public function actualizarApeNom(Request $request)
  {
      $registro = PofmhModel::find($request->idPofmh);
  
      if ($registro) {
          $registro->ApeNom = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
          $registro->save();
  
          return response()->json(['success' => true]);
      }
  
      return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
  }

  //cargo salarial
  public function actualizarCargoSalarial(Request $request){
    
    $registro = PofmhModel::find($request->idPofmh);
  
    if ($registro) {
        $registro->Cargo = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
        $registro->save();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
  }

    //aulas
    public function actualizarAula(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Aula = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
      }

  //divisiones
  public function actualizarDivision(Request $request){
    
    $registro = PofmhModel::find($request->idPofmh);
  
    if ($registro) {
        $registro->Division = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
        $registro->save();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
  }

  //esp cur
  public function actualizarEspCur(Request $request){
    
    $registro = PofmhModel::find($request->idPofmh);
  
    if ($registro) {
        $registro->EspCur = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
        $registro->save();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
  }

    //matricula
    public function actualizarMatricula(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Matricula = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
      }

  //turnos
  public function actualizarTurno(Request $request){
    
    $registro = PofmhModel::find($request->idPofmh);
  
    if ($registro) {
        $registro->Turno = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
        $registro->save();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
  }

  //Horas
  public function actualizarHoras(Request $request){
    
    $registro = PofmhModel::find($request->idPofmh);
  
    if ($registro) {
        $registro->Horas = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
        $registro->save();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
  }


  //Origen
  public function actualizarOrigen(Request $request){
    
    $registro = PofmhModel::find($request->idPofmh);
  
    if ($registro) {
        $registro->Origen = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
        $registro->save();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
  }

    //sit rev
    public function actualizarSitRev(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->SitRev = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }


    //5 fechas
    public function actualizarFechaAltaCargo(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->FechaAltaCargo = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    public function actualizarFechaDesignado(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->FechaDesignado = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    public function actualizarCondicion(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Condicion = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    public function actualizarActivo(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Activo = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    public function actualizarFechaDesde(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->FechaDesde = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    public function actualizarFechaHasta(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->FechaHasta = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    //motivos
    public function actualizarMotivo(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Motivo = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    //datos por condicion
    public function actualizarDatosPorCondicion(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->DatosPorCondicion = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    //datos por condicion
    public function actualizarAntiguedad(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Antiguedad = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    //datos por condicion
    public function actualizarAgenteR(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->AgenteR = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    //novedades
    public function actualizarNovedades(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Novedades = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    //asistencias tipos
    public function actualizarAsistencia(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Asistencia = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    public function actualizarAsistenciaJustificada(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Justificada = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    public function actualizarAsistenciaInjustificada(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Injustificada = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    //observaciones
    public function actualizarObservaciones(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Observaciones = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    //carrera
    public function actualizarCarrera(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Carrera = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    //Orientacion
    public function actualizarOrientacion(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Orientacion = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    //titulo
    public function actualizarTitulo(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->Titulo = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }

    //actualizar zona sup inicial
    public function actualizarZonaSupervision(Request $request){
    
        $registro = PofmhModel::find($request->idPofmh);
      
        if ($registro) {
            $registro->ZonaSupervision = $request->datoACambiar; // Asegúrate de que este campo sea el correcto
            $registro->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
    }
    public function obtener_cargosSalariales(Request $request) {
        // Obtener todos los registros de CargoSalarial
        $registro = DB::table('tb_cargossalariales')->orderBy('Cargo','ASC')->get();       
        // Verificar si se encontraron registros
        if ($registro->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
        }
    
        // Asegurarte de que el registro tenga los campos esperados
        $cargos = $registro->map(function($item) {
            return [
                'idCargo' => $item->idCargo, // Asegúrate de que este campo existe
                'Cargo' => $item->Cargo, // Asegúrate de que este campo existe
                'Codigo' => $item->Codigo // Asegúrate de que este campo existe
            ];
        });
    
        // Devolver los registros en formato JSON
        return response()->json(['success' => true, 'data' => $cargos]);
    }
    
    public function obtener_aulas(Request $request) {
        // Obtener todos los registros de aulas
        $registro = PofmhAulas::all();     

        // Verificar si se encontraron registros
        if ($registro->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
        }
    
        // Asegurarte de que el registro tenga los campos esperados
        $cargos = $registro->map(function($item) {
            return [
                'idAula' => $item->idAula, // Asegúrate de que este campo existe
                'nombre_aula' => $item->nombre_aula
            ];
        });
    
        // Devolver los registros en formato JSON
        return response()->json(['success' => true, 'data' => $cargos]);
    }
    public function obtener_division(Request $request) {
        // Obtener todos los registros de CargoSalarial
        $registro  = PofmhDivisiones::all();  

        // Verificar si se encontraron registros
        if ($registro->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
        }
    
        // Asegurarte de que el registro tenga los campos esperados
        $cargos = $registro->map(function($item) {
            return [
                'idDivision' => $item->idDivision, // Asegúrate de que este campo existe
                'nombre_division' => $item->nombre_division
            ];
        });
    
        // Devolver los registros en formato JSON
        return response()->json(['success' => true, 'data' => $cargos]);
    }

    public function obtenerAulaYDivisionPorOrigen(Request $request)
    {
        // Validar que se envíe un idOrigenCargo
        $idOrigenCargo = $request->input('idOrigenCargo');
        $turno = $request->input('turno');
        if (!$idOrigenCargo) {
            return response()->json(['success' => false, 'message' => 'idOrigenCargo es requerido.']);
        }
    
        // Primero, buscar en la tabla origenes para obtener `nombre_origen`, `CUECOMPLETO`, y `idTurno`
        $infoOrigen = PofmhOrigenCargoModel::where('idOrigenCargo', $idOrigenCargo)->first();
        if (!$infoOrigen) {
            return response()->json(['success' => false, 'message' => 'No se encontró el idOrigenCargo en la tabla PofmhOrigenCargoModel.']);
        }
        $nombreOrigen = $infoOrigen->nombre_origen;
        $CUECOMPLETO = $infoOrigen->CUECOMPLETO;
        $Turno = $infoOrigen->idTurno;
    

        $padtRegistros = RelCargoAulaDivModel::where('idOrigenCargo', $idOrigenCargo)
        ->where(function($query) use ($infoOrigen, $turno) {
            $query->where('CUECOMPLETO', $infoOrigen->CUECOMPLETO)
                ->where('idTurno', $turno)
                ->orWhere('idTurno', 5);
        })
        ->get();

        
    
        if ($padtRegistros->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No se encontraron registros en tb_Padt para los idOrigenCargo relacionados.']);
        }
    
        // Obtener las aulas y divisiones correspondientes
        $aulas = [];
        $divisiones = [];
    
        foreach ($padtRegistros as $registro) {
            $aula = PofmhAulas::where('idAula', $registro->idAula)->first();
            $division = PofmhDivisiones::where('idDivision', $registro->idDivision)->first();
    
            if ($aula) {
                $aulas[] = [
                    'idAula' => $aula->idAula,
                    'nombre_aula' => $aula->nombre_aula
                ];
            }
    
            if ($division) {
                $divisiones[] = [
                    'idDivision' => $division->idDivision,
                    'nombre_division' => $division->nombre_division
                ];
            }
        }
    
        // Eliminar duplicados en los arrays de aulas y divisiones
        $aulas = collect($aulas)->unique('idAula')->values()->all();
        $divisiones = collect($divisiones)->unique('idDivision')->values()->all();
    
        if( $nombreOrigen===22 ||  $nombreOrigen===14){
           //obtengo las aulas solo para preceptores y horas
            $filtro =[22,23,24,25,26,27,29,30,49];
            $aulas = DB::connection('DB7')->table('tb_aulas')
            ->whereIn('idAula',$filtro)->get()->toArray();
            //obtengo las divisiones totales solo para ellos
            $divisiones = DB::connection('DB7')->table('tb_divisiones')
            ->get()->toArray();
        }
        // Devolver los datos en formato JSON
        return response()->json([
            'success' => true,
            'aulas' => $aulas,
            'divisiones' => $divisiones
        ]);
    }
    
    public function obtenerAulasPorOrigen(Request $request)
{
    $idOrigenCargo = $request->input('idOrigenCargo');

    if (!$idOrigenCargo) {
        return response()->json(['success' => false, 'message' => 'idOrigenCargo es requerido.']);
    }

    // Aquí buscas las aulas relacionadas con `idOrigenCargo`
    $aulasRelacionadas = DB::connection('DB7')->table('tb_padt')
        ->where('idOrigenCargo', $idOrigenCargo)
        ->join('tb_aulas', 'tb_padt.idAula', '=', 'tb_aulas.idAula')
        ->select('tb_aulas.idAula', 'tb_aulas.nombre_aula')
        ->distinct()
        ->get();

    return response()->json([
        'success' => true,
        'aulas' => $aulasRelacionadas
    ]);
}


    public function obtener_turnos(Request $request) {
        // Obtener el CUE enviado desde el frontend
        $valCUE = $request->input('valCUE');
    
        // Obtener los turnos habilitados de la tabla tb_institucion_extension
        $turnosHabilitados = DB::table('tb_institucion_extension')
            ->where('CUECOMPLETO', $valCUE)
            ->pluck('idTurnoUsuario'); // Extraer solo los valores de 'idTurnoUsuario'
    
        // Verificar si hay turnos habilitados
        if ($turnosHabilitados->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No hay turnos habilitados para este CUE.']);
        }
    
        // Obtener los registros de PofmhTurnos filtrados por los turnos habilitados
        $registro = PofmhTurnos::whereIn('idTurno', $turnosHabilitados)->get();
    
        // Verificar si se encontraron registros
        if ($registro->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No se encontraron turnos habilitados.']);
        }
    
        // Mapear los datos
        $cargos = $registro->map(function($item) {
            return [
                'idTurno' => $item->idTurno,
                'nombre_turno' => $item->nombre_turno
            ];
        });
    
        // Devolver los registros en formato JSON
        return response()->json(['success' => true, 'data' => $cargos]);
    }
    


    public function obtener_sitrev(Request $request) {
        // Obtener todos los registros de CargoSalarial
        $registro = PofMhSitRev::all();//DB::table('tb_situacionrevista')->get();       
        // Verificar si se encontraron registros
        if ($registro->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
        }
    
        // Asegurarte de que el registro tenga los campos esperados
        $cargos = $registro->map(function($item) {
            return [
                'idSituacionRevista' => $item->idSituacionRevista, // Asegúrate de que este campo existe
                'Descripcion' => $item->Descripcion // Asegúrate de que este campo existe
            ];
        });
    
        // Devolver los registros en formato JSON
        return response()->json(['success' => true, 'data' => $cargos]);
    }

    public function obtener_motivos(Request $request) {
        // Obtener todos los registros de CargoSalarial
        $registro = DB::table('tb_motivos')->get();       
        // Verificar si se encontraron registros
        if ($registro->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
        }
    
        // Asegurarte de que el registro tenga los campos esperados
        $cargos = $registro->map(function($item) {
            return [
                'idMotivo' => $item->idMotivo, // Asegúrate de que este campo existe
                'Codigo' => $item->Codigo, // Asegúrate de que este campo existe
                'Nombre_Licencia' => $item->Nombre_Licencia // Asegúrate de que este campo existe
            ];
        });
    
        // Devolver los registros en formato JSON
        return response()->json(['success' => true, 'data' => $cargos]);
    }

    public function obtener_condiciones(Request $request) {
        // Obtener todos los registros de CargoSalarial
        $registro = CondicionModel::all();       
        // Verificar si se encontraron registros
        if ($registro->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
        }
    
        // Asegurarte de que el registro tenga los campos esperados
        $cargos = $registro->map(function($item) {
            return [
                'idCondicion' => $item->idCondicion, // Asegúrate de que este campo existe
                'Descripcion' => $item->Descripcion // Asegúrate de que este campo existe
            ];
        });
    
        // Devolver los registros en formato JSON
        return response()->json(['success' => true, 'data' => $cargos]);
    }


    public function obtener_activos(Request $request) {
        // Obtener todos los registros de CargoSalarial
        $registro = PofmhActivosModel::all();       
        // Verificar si se encontraron registros
        if ($registro->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
        }
    
        // Asegurarte de que el registro tenga los campos esperados
        $cargos = $registro->map(function($item) {
            return [
                'idActivo' => $item->idActivo, // Asegúrate de que este campo existe
                'nombre_activo' => $item->nombre_activo // Asegúrate de que este campo existe
            ];
        });
    
        // Devolver los registros en formato JSON
        return response()->json(['success' => true, 'data' => $cargos]);
    }

    public function obtener_origenes(Request $request) {
        $idExt = $request->input('idExt');

        $institucion=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.idInstitucionExtension',$idExt)
                ->first();

        // Obtener todos los registros de CargoSalarial
        $registro = PofmhOrigenCargoModel::where('CUECOMPLETO', 'like', '%' . $institucion->CUE . '%')
        ->join('tb_cargos_pof_origen','tb_cargos_pof_origen.idCargos_Pof_Origen','tb_origenes_cargos.nombre_origen')
        //->where('idTurno',$institucion->idTurnoUsuario)
        ->get();      

        // Verificar si se encontraron registros
        if ($registro->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
        }
        // $padtRegistros = RelCargoAulaDivModel::where('idOrigenCargo', $idOrigenCargo)
        // ->where(function($query) {
        //     $query->where('CUECOMPLETO', session('CUECOMPLETO'))
        //         ->where('idTurno',session('idTurnoUsuario'))
        //         ->orWhere('idTurno', 5);
        // })
        // ->get();
        // Asegurarte de que el registro tenga los campos esperados
        $cargos = $registro->map(function($item) {
            return [
                'idOrigenCargo' => $item->idOrigenCargo, // Asegúrate de que este campo existe
                'nombre_origen' => $item->nombre_cargo_origen // Asegúrate de que este campo existe
            ];
        });
    
        // Devolver los registros en formato JSON
        return response()->json(['success' => true, 'data' => $cargos]);
    }

    public function borrarFilaPofmh(Request $request)
    {
        $id = $request->input('id'); // Obtener el ID del cuerpo de la solicitud
    
        // Buscar la fila por ID
        $fila = PofmhModel::where('idPofmh', $id)->first();
    
        if (!$fila) {
            return response()->json(array('status' => 404, 'msg' => 'Fila no encontrada'), 404);
        }
        
        //genero el mensaje
        $mensajeObs = "Se Elimino el Agente ".$fila->Agente.".-Apelido y Nombre: ".$fila->ApeNom." - Eliminado por: " . session('Usuario') . " - ID: " . session('idUsuario') . " el " . date('Y-m-d H:i:s')." - CUE: ".$fila->CUECOMPLETO . " - Turno: ".$fila->Turno;
        //antes de borrar la fila, creo un respaldo en tabla log pofhm
        
        $log = new LogPofmhModel();
            $log->idPofmh = $fila->idPofmh;
            $log->idUsuario = session('idUsuario');
            $log->Agente = $fila->Agente;
            $log->ApeNom = $fila->ApeNom;
            $log->CUECOMPLETO = $fila->CUECOMPLETO;
            $log->Turno = $fila->Turno;
            $log->Estado = "Eliminado";
            $log->Observaciones =  $mensajeObs;
        $log->save();
        // Eliminar la fila
        $fila->delete();
    
        return response()->json(array('status' => 200, 'msg' => 'Fila eliminada correctamente'), 200); // Respuesta de éxito
    }
//return response()->json(array('status' => 200, 'msg' => $agente->nomb), 200);


public function pofmhformularioNovedadParticular(Request $request){
    //dd($request);
    $novedad = new PofmhNovedades();
        $novedad->Agente = $request->novedad_dni;
        $novedad->CUECOMPLETO = $request->novedad_cue;
        $novedad->Turno = $request->novedad_turno;
        
        $novedad->FechaDesde = $request->FechaInicio;
        $novedad->FechaHasta = $request->FechaHasta;
        $novedad->TotalDias = 1;
        $novedad->Mes = date('m');
        $novedad->Anio = date('Y');
        $novedad->Motivo = $request->Motivos;   //le coloco cero para decir que no son motivos de la tabla motivos, sino generales de la escuela
        $novedad->Observaciones = $request->Observaciones;
        $novedad->idNovedadExtra = $request->TipoNovedad;

        // Crear objetos DateTime
        $fechaInicialObj = new DateTime($request->FechaInicio);//new DateTime(Carbon::parse(Carbon::now())->format('Y-m-d'));
        $fechaFinalObj = new DateTime($request->FechaHasta);
        $fechaFinalObj->modify('+1 day');   //aplico fix para que sume bien 1 dia al total ejemplo, 24 al 27 = 4
        // Calcular la diferencia entre las dos fechas
        $intervalo = $fechaInicialObj->diff($fechaFinalObj);

        // Obtener la cantidad de días
        $cantidadDias = $intervalo->days;

        $novedad->TotalDiasLicencia = $cantidadDias;
    $novedad->save();
    
    return response()->json([
        'status' => 'OK', // Indica que la operación fue exitosa
        'message' => 'Novedad agregada correctamente.',
        'novedad' => $novedad 
    ]);
}


public function pofmhNovedades($dni, $cue)
{
     // Recuperar las novedades con el join correcto
     $novedades = PofmhNovedades::where('Agente', $dni)
     ->join('tb_novedades_extras', 'tb_novedades.idNovedadExtra', '=', 'tb_novedades_extras.idNovedadExtra')
     ->where('tb_novedades.CUECOMPLETO', $cue)
     ->get();
     $Motivos =   DB::table('tb_motivos')->get()->toArray();
     return response()->json([
        'novedades' => $novedades,
        'Motivos' => $Motivos
    ]);
}

    public function uploadpofmh(Request $request)
    {
        
        //dd($request->all());
        // Verificar si hay un docente seleccionado
        if (!empty($request->Agente)) {
            // Capturar y buscar al docente
            $CUECOMPLETO = $request->CueX;
            $AGENTE = $request->Agente;
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = $file->getClientOriginalName(); // Obtener el nombre original del archivo
                $extension = $file->getClientOriginalExtension(); // Obtener la extensión del archivo
                $timestamp = Carbon::now()->format('Ymd_His'); // Genera la fecha y hora actual en formato YYYYMMDD_HHMMSS
                
                // Generar el nombre del archivo en MD5 sin la extensión
                //$md5Name = md5(pathinfo($originalName, PATHINFO_FILENAME));
            
                // Concatenar el nombre MD5 con la extensión original
                $newFileName = $originalName . '_' . $timestamp . '.' . $extension;
            
                // Obtener la ruta al directorio de almacenamiento deseado
                $destinationPath = storage_path('app/public/DOCUMENTOS/' . $CUECOMPLETO . '/' . $AGENTE);
            
                // Verificar si el directorio de destino existe, si no, crearlo
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
            
                // Mover el archivo a la ubicación deseada con el nuevo nombre
                $file->move($destinationPath, $newFileName);
            
                // Calcula el hash MD5 del archivo completo
                $md5Hash = md5_file($destinationPath . '/' . $newFileName);
            
                // Agregar el documento a la tabla
                $docNuevo = new DocumentosModel();
                $docNuevo->CUECOMPLETO = $CUECOMPLETO;
                $docNuevo->Agente = $AGENTE;
                $docNuevo->URL = $newFileName;
                $docNuevo->FechaAlta = Carbon::now();
                $docNuevo->save();
            
                return response()->json(array('success' => 200, 'SubirDocExito' => 'OK'), 200);
            }
            
            return response()->json(array('success' => 200, 'SubirDocFallo' => 'OK'), 200);
        
        
        } else {
            return response()->json(array('success' => 200, 'SubirDocError' => 'OK'), 200);
        
        }
    
    }


    public function traerArchivospofmh(Request $request)
    {
            $CUECOMPLETO = $request->CueX;
            $AGENTE = $request->Agente;

            // Obtener los documentos que coincidan con el CUECOMPLETO y el Agente
            $documentos = DocumentosModel::where('CUECOMPLETO', $CUECOMPLETO)
                ->where('Agente', $AGENTE)
                ->orderBy('created_at', 'desc')
                ->get();

        // Devolver la vista parcial que contiene los archivos (esto depende de cómo quieras manejarlo en tu aplicación)
        return view('bandeja.documentospof', compact('documentos'));
    }

    public function borrarDocumentoAgentePof(Request $request)
    {
        $documentoId = $request->doc;
    
        // Borrar el documento
        $deleted = DocumentosModel::where('idDocumento', $documentoId)->delete();
    
        if ($deleted) {
            return response()->json(['status' => 'success', 'message' => 'Documento eliminado con éxito.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error al eliminar el documento.']);
        }
    }

    //para decreto
    public function uploadpofmhdecreto(Request $request)
    {
        
        //dd($request->all());
        // Verificar si hay un cue existente
        if (!empty($request->CueX)) {
            // Capturar y buscar al docente
            $CUECOMPLETO = $request->CueX;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = $file->getClientOriginalName(); // Obtener el nombre original del archivo
                $extension = $file->getClientOriginalExtension(); // Obtener la extensión del archivo
                $timestamp = Carbon::now()->format('Ymd_His'); // Genera la fecha y hora actual en formato YYYYMMDD_HHMMSS
                
                // Generar el nombre del archivo en MD5 sin la extensión
                //$md5Name = md5(pathinfo($originalName, PATHINFO_FILENAME));
            
                // Concatenar el nombre MD5 con la extensión original
                $newFileName = $originalName . '_' . $timestamp . '.' . $extension;
            
                // Obtener la ruta al directorio de almacenamiento deseado
                $destinationPath = storage_path('app/public/CUE/' . $CUECOMPLETO);
            
                // Verificar si el directorio de destino existe, si no, crearlo
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
            
                // Mover el archivo a la ubicación deseada con el nuevo nombre
                $file->move($destinationPath, $newFileName);
            
                // Calcula el hash MD5 del archivo completo
                $md5Hash = md5_file($destinationPath . '/' . $newFileName);
            
                // Agregar el documento a la tabla
                $docNuevo = new DocumentosModel();
                $docNuevo->CUECOMPLETO = $CUECOMPLETO;
                $docNuevo->URL = $newFileName;
                $docNuevo->tipodoc = "decorigen";   //aqui todos los tipos
                $docNuevo->FechaAlta = Carbon::now();
                $docNuevo->save();
            
                return response()->json(array('success' => 200, 'SubirDocExito' => 'OK'), 200);
            }
            
            return response()->json(array('success' => 200, 'SubirDocFallo' => 'OK'), 200);
        
        
        } else {
            return response()->json(array('success' => 200, 'SubirDocError' => 'OK'), 200);
        
        }
    
    }


    public function traerArchivospofmhdecreto(Request $request)
    {
            $CUECOMPLETO = $request->CueX;

            // Obtener los documentos que coincidan con el CUECOMPLETO y el Agente
            $documentos = DocumentosModel::where('CUECOMPLETO', $CUECOMPLETO)
                ->where('tipodoc','decorigen')
                ->orderBy('created_at', 'desc')
                ->get();

        // Devolver la vista parcial que contiene los archivos (esto depende de cómo quieras manejarlo en tu aplicación)
        return view('bandeja.documentospofdec', compact('documentos'));
    }

    public function borrarDocumentoAgentePofDecreto(Request $request)
    {
        $documentoId = $request->doc;
    
        // Borrar el documento
        $deleted = DocumentosModel::where('idDocumento', $documentoId)->delete();
    
        if ($deleted) {
            return response()->json(['status' => 'success', 'message' => 'Documento eliminado con éxito.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error al eliminar el documento.']);
        }
    }

    //para novedades super
    //para decreto
    public function uploadnovedadsuper(Request $request)
    {
        
        //dd($request->all());
        // Verificar si hay un cue existente
        if (!empty($request->CueX)) {
            // Capturar y buscar al docente
            $CUECOMPLETO = $request->CueX;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = $file->getClientOriginalName(); // Obtener el nombre original del archivo
                $extension = $file->getClientOriginalExtension(); // Obtener la extensión del archivo
                $timestamp = Carbon::now()->format('Ymd_His'); // Genera la fecha y hora actual en formato YYYYMMDD_HHMMSS
                
                // Generar el nombre del archivo en MD5 sin la extensión
                //$md5Name = md5(pathinfo($originalName, PATHINFO_FILENAME));
            
                // Concatenar el nombre MD5 con la extensión original
                $newFileName = $originalName . '_' . $timestamp . '.' . $extension;
            
                // Obtener la ruta al directorio de almacenamiento deseado
                $destinationPath = storage_path('app/public/CUE/' . $CUECOMPLETO.'/novedades');
            
                // Verificar si el directorio de destino existe, si no, crearlo
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
            
                // Mover el archivo a la ubicación deseada con el nuevo nombre
                $file->move($destinationPath, $newFileName);
            
                // Calcula el hash MD5 del archivo completo
                $md5Hash = md5_file($destinationPath . '/' . $newFileName);
            
                // Agregar el documento a la tabla
                $docNuevo = new DocumentosModel();
                $docNuevo->CUECOMPLETO = $CUECOMPLETO;
                $docNuevo->URL = $newFileName;
                $docNuevo->tipodoc = "novedad";   //aqui todos los tipos
                $docNuevo->FechaAlta = Carbon::now();
                $docNuevo->save();
            
                return response()->json(array('success' => 200, 'SubirDocExito' => 'OK'), 200);
            }
            
            return response()->json(array('success' => 200, 'SubirDocFallo' => 'OK'), 200);
        
        
        } else {
            return response()->json(array('success' => 200, 'SubirDocError' => 'OK'), 200);
        
        }
    
    }


    public function traerArchivosNovedades(Request $request)
    {
            $CUECOMPLETO = $request->CueX;

            // Obtener los documentos que coincidan con el CUECOMPLETO y el Agente
            $documentos = DocumentosModel::where('CUECOMPLETO', $CUECOMPLETO)
                ->where('tipodoc','novedad')
                ->orderBy('created_at', 'desc')
                ->get();

        // Devolver la vista parcial que contiene los archivos (esto depende de cómo quieras manejarlo en tu aplicación)
        return view('bandeja.documentospofnov', compact('documentos'));
    }

    public function borrarDocumentoNovedades(Request $request)
    {
        $documentoId = $request->doc;
    
        // Borrar el documento
        $deleted = DocumentosModel::where('idDocumento', $documentoId)->delete();
    
        if ($deleted) {
            return response()->json(['status' => 'success', 'message' => 'Documento eliminado con éxito.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error al eliminar el documento.']);
        }
    }
    //dssd
    public function verCargosCreados($idExt){
                  //obtengo el usuario que es la escuela a trabajar
                /*$idReparticion = session('idReparticion');
                //consulto a reparticiones
                $reparticion = DB::table('tb_reparticiones')
                ->where('tb_reparticiones.idReparticion',$idReparticion)
                ->get();*/
                //dd($reparticion[0]->Organizacion);
        
                //traigo el edificio de una suborg
                $institucion=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.idInstitucionExtension',$idExt)
                ->first();
                //pregunto si tiene extensiones para pasarlas
                $cantidadInstituciones = DB::table('tb_institucion_extension')
                //->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','tb_institucion_extension.idTurnoUsuario')
                ->select('CUE', 'CUECOMPLETO', 'Nombre_Institucion', 'Localidad')
                ->where('tb_institucion_extension.CUE', $institucion->CUE)
                ->groupBy('CUE', 'CUECOMPLETO', 'Nombre_Institucion', 'Localidad')
                ->orderBy('CUECOMPLETO','ASC')
                ->get();
                
                //dd($cantidadInstituciones);
                //$ListaCargos = DB::table('tb_cargossalariales')->orderBy('Cargo','ASC')->get();
                $ListaCargos = CargoOrigenPofMHModel::all();
                $CargosCreados = PofmhOrigenCargoModel::where('CUECOMPLETO', 'like', '%' . $institucion->CUE . '%')
                ->join('tb_cargos_pof_origen','tb_cargos_pof_origen.idCargos_Pof_Origen','tb_origenes_cargos.nombre_origen')
                //->where('tb_origenes_cargos.idTurno',$institucion->idTurnoUsuario)
                
                ->get()
                ->toArray();        //no olvidar convertir a array
                //dd($CargosCreados);
                $Aulas =   PofmhAulas::all();
                $Divisiones = PofmhDivisiones::all();
                $Turnos =   PofmhTurnos::where('idTurno',$institucion->idTurnoUsuario)->first();
                $TurnosTodos =   PofmhTurnos::all();
                $TurnosTodosArray =   PofmhTurnos::all()->toArray();

                //cargar la relacion tadt y devolver si es json
                                
                $AulasCargosCreados = RelCargoAulaDivModel::where('CUECOMPLETO', 'like', '%' . $institucion->CUE . '%')
                //->where('idTurno', $institucion->idTurnoUsuario)
                ->orderBy('CUECOMPLETO','ASC')
                ->get()
                ->toArray();

                if (request()->ajax()) {
                    $tipo = request('tipo');
            
                    // Retornar datos según el tipo de solicitud
                    if ($tipo === 'AulasCargos') {
                        return response()->json([
                            'AulasCargosCreados' => $AulasCargosCreados,
                            'CargosCreados' => $CargosCreados,
                            'Aulas' => $Aulas,
                            'Divisiones' => $Divisiones,
                            'Turnos' => $Turnos,
                            'Extensiones'=>$cantidadInstituciones,
                            'TurnosTodos'=>$TurnosTodosArray
                        ])->header('Content-Type', 'application/json');
                    } elseif ($tipo === 'Cargos') {
                        return response()->json([
                            'CargosCreados' => $CargosCreados,
                            'AulasCargosCreados' => $AulasCargosCreados,
                            'Turnos' => $Turnos,
                            'Extensiones'=>$cantidadInstituciones,
                            'TurnosTodos'=>$TurnosTodosArray
                        ])->header('Content-Type', 'application/json');
                    }
                }
                
                
                
                
               
                //dd($Turnos);
                //dd($CargosCreados);
                $datos=array(
                    'mensajeError'=>"",
                    'Institucion'=>$institucion,
                    'ListaCargos'=>$ListaCargos,
                    'idExt'=>$idExt,
                    'CargosCreados'=>$CargosCreados,
                    'Aulas'=>$Aulas,
                    'Divisiones'=>$Divisiones,
                    'Turno'=>$Turnos, //el turno lo determina la institucion segun el CUE usado con su idExt
                    'TurnosTodos'=>$TurnosTodos,
                    'Extensiones'=>$cantidadInstituciones,
                    'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
                    'mensajeNAV'=>'Panel de Configuración de Cursos y Divisiones'
        
                );
                //dd($datos);
                $ruta ='
                <li class="breadcrumb-item active"><a href="#">LEGAJO UNICO INSTITUCIONAL</a></li>
                <li class="breadcrumb-item active"><a href="'.route('verCargosCreados',$idExt).'">Cargos Institucionales</a></li>
                '; 
                session(['ruta' => $ruta]);
                return view('bandeja.POF.origenCargos',$datos); 
    }

    public function verCargosPofvsNominal($idExt){
                  //obtengo el usuario que es la escuela a trabajar
                /*$idReparticion = session('idReparticion');
                //consulto a reparticiones
                $reparticion = DB::table('tb_reparticiones')
                ->where('tb_reparticiones.idReparticion',$idReparticion)
                ->get();*/
                //dd($reparticion[0]->Organizacion);
        
                //traigo el edificio de una suborg
                $institucion=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.idInstitucionExtension',$idExt)
                ->first();
                //pregunto si tiene extensiones para pasarlas
                $cantidadInstituciones = DB::table('tb_institucion_extension')
                //->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','tb_institucion_extension.idTurnoUsuario')
                ->select('CUE', 'CUECOMPLETO', 'Nombre_Institucion', 'Localidad')
                ->where('tb_institucion_extension.CUE', $institucion->CUE)
                ->groupBy('CUE', 'CUECOMPLETO', 'Nombre_Institucion', 'Localidad')
                ->orderBy('CUECOMPLETO','ASC')
                ->get();
                
                //dd($cantidadInstituciones);
                //$ListaCargos = DB::table('tb_cargossalariales')->orderBy('Cargo','ASC')->get();
                $ListaCargos = CargoOrigenPofMHModel::all();
                $CargosCreados = PofmhOrigenCargoModel::where('CUECOMPLETO', 'like', '%' . $institucion->CUE . '%')
                ->join('tb_cargos_pof_origen','tb_cargos_pof_origen.idCargos_Pof_Origen','tb_origenes_cargos.nombre_origen')
                //->select('tb_cargos_pof_origen.*')
                
                ->get();
                //->toArray();        //no olvidar convertir a array
                //dd($CargosCreados);
                $Aulas =   PofmhAulas::all();
                $Divisiones = PofmhDivisiones::all();
                $Turnos =   PofmhTurnos::where('idTurno',$institucion->idTurnoUsuario)->first();
                $TurnosTodos =   PofmhTurnos::all();
                $TurnosTodosArray =   PofmhTurnos::all()->toArray();
                $infoPofMH = PofmhModel::where('CUECOMPLETO', 'like', $institucion->CUE . '%')->get();
                //cargar la relacion tadt y devolver si es json
                                
                $AulasCargosCreados = RelCargoAulaDivModel::where('CUECOMPLETO', 'like', '%' . $institucion->CUE . '%')
                ->select(
                    'tb_padt.idOrigenCargo',
                    'tb_padt.idAula',
                    'tb_padt.idDivision',
                    'tb_padt.idTurno',
                    'tb_padt.CUECOMPLETO',
                )
                ->distinct()
                //->where('idTurno', $institucion->idTurnoUsuario)
                ->orderBy('CUECOMPLETO', 'ASC')
                //->groupBy('idAula', 'idDivision', 'idTurno', 'CUECOMPLETO', 'idOrigenCargo') // Agregado 'idOrigenCargo'
                ->get();

                //solo cues
                $SoloCUES = RelCargoAulaDivModel::where('CUECOMPLETO', 'like', '%' . $institucion->CUE . '%')
                ->select('tb_padt.CUECOMPLETO',DB::raw('COUNT(*) as total'))
                ->distinct()
                ->orderBy('CUECOMPLETO', 'ASC')
                ->groupBy('CUECOMPLETO') // Agregado 'idOrigenCargo'
                ->get(); 

                //dd($SoloCUES);
                if (request()->ajax()) {
                    $tipo = request('tipo');
            
                    // Retornar datos según el tipo de solicitud
                    if ($tipo === 'AulasCargos') {
                        return response()->json([
                            'AulasCargosCreados' => $AulasCargosCreados,
                            'CargosCreados' => $CargosCreados,
                            'Aulas' => $Aulas,
                            'Divisiones' => $Divisiones,
                            'Turnos' => $Turnos,
                            'Extensiones'=>$cantidadInstituciones,
                            'TurnosTodos'=>$TurnosTodosArray
                        ]);
                    } elseif ($tipo === 'Cargos') {
                        return response()->json([
                            'CargosCreados' => $CargosCreados,
                            'AulasCargosCreados' => $AulasCargosCreados,
                            'Turnos' => $Turnos,
                            'Extensiones'=>$cantidadInstituciones,
                            'TurnosTodos'=>$TurnosTodosArray
                        ]);
                    }
                }
                $CargosSalariales =   DB::table('tb_cargossalariales')->get();
                $Condiciones =   CondicionModel::all();
                $Aulas =   PofmhAulas::all();
                $Divisiones = PofmhDivisiones::all();
                // Obtener los registros de PofmhTurnos filtrados por los turnos habilitados
                $Turnos = PofmhTurnos::all();
            
                //$Turnos =   PofmhTurnos::all();
                $Activos =   PofmhActivosModel::all();
                $OrigenesDeCargos = PofmhOrigenCargoModel::all();
            
                $SitRev =   PofMhSitRev::all();//DB::table('tb_situacionrevista')->get();
                $Motivos =   DB::table('tb_motivos')->get();
                
                
                
               
                //dd($Turnos);
                //dd($CargosCreados);
                $datos=array(
                    'mensajeError'=>"",
                    'Institucion'=>$institucion,
                    'institucionExtension'=>$institucion,
                    'ListaCargos'=>$ListaCargos,
                    'CargosSalariales'=>$CargosSalariales,
                    'idExt'=>$idExt,
                    'CargosCreados'=>$CargosCreados,
                    'AulasCargosCreados'=>$AulasCargosCreados,
                    'Aulas'=>$Aulas,
                    'Divisiones'=>$Divisiones,
                    'infoPofMH'=>$infoPofMH,
                    'SitRev'=>$SitRev,
                    'Motivos'=>$Motivos,
                    'SoloCUES'=>$SoloCUES,
                    'Turnos'=>$Turnos, //el turno lo determina la institucion segun el CUE usado con su idExt
                    'TurnosTodos'=>$TurnosTodos,
                    'Extensiones'=>$cantidadInstituciones,
                    'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
                    'mensajeNAV'=>'Panel de Configuración de Cursos y Divisiones'
        
                );
                $ruta ='
                <li class="breadcrumb-item active"><a href="#">LEGAJO UNICO INSTITUCIONAL</a></li>
                <li class="breadcrumb-item active"><a href="'.route('verCargosPofvsNominal',$idExt).'">Cargos Institucionales Relacionados</a></li>
                '; 
                session(['ruta' => $ruta]);
                return view('bandeja.POF.origenCargosPofvsNominal',$datos); 
    }

    public function verCargosNivelInicial(){
                //traigo el edificio de una suborg
                
                $institucion=DB::table('tb_institucion_extension')
                ->select('CUECOMPLETO')
                ->where('Nivel','like','%Inic%')
                ->groupBy('CUECOMPLETO')
                ->get();    
                dd($institucion);
                $CargosCreados = PofmhOrigenCargoModel::join('tb_cargos_pof_origen','tb_cargos_pof_origen.idCargos_Pof_Origen','tb_origenes_cargos.nombre_origen')
                ->whereIn('tb_origenes_cargos.idTurno',$institucion->idTurnoUsuario)
                
                ->get()
                ->toArray();        //no olvidar convertir a array
                //dd($CargosCreados);
                $Aulas =   PofmhAulas::all();
                $Divisiones = PofmhDivisiones::all();
                $Turnos =   PofmhTurnos::where('idTurno',$institucion->idTurnoUsuario)->first();
                $TurnosTodos =   PofmhTurnos::all();
                $TurnosTodosArray =   PofmhTurnos::all()->toArray();

                //cargar la relacion tadt y devolver si es json
                                
                $AulasCargosCreados = RelCargoAulaDivModel::where('CUECOMPLETO', 'like', '%' . $institucion->CUE . '%')
                //->where('idTurno', $institucion->idTurnoUsuario)
                ->orderBy('CUECOMPLETO','ASC')
                ->get()
                ->toArray();

                if (request()->ajax()) {
                    $tipo = request('tipo');
            
                    // Retornar datos según el tipo de solicitud
                    if ($tipo === 'AulasCargos') {
                        return response()->json([
                            'AulasCargosCreados' => $AulasCargosCreados,
                            'CargosCreados' => $CargosCreados,
                            'Aulas' => $Aulas,
                            'Divisiones' => $Divisiones,
                            'Turnos' => $Turnos,
                            'Extensiones'=>$cantidadInstituciones,
                            'TurnosTodos'=>$TurnosTodosArray
                        ]);
                    } elseif ($tipo === 'Cargos') {
                        return response()->json([
                            'CargosCreados' => $CargosCreados,
                            'AulasCargosCreados' => $AulasCargosCreados,
                            'Turnos' => $Turnos,
                            'Extensiones'=>$cantidadInstituciones,
                            'TurnosTodos'=>$TurnosTodosArray
                        ]);
                    }
                }
                
                
                
                
               
                //dd($Turnos);
                //dd($CargosCreados);
                $datos=array(
                    'mensajeError'=>"",
                    'Institucion'=>$institucion,
                    'ListaCargos'=>$ListaCargos,
                    'idExt'=>$idExt,
                    'CargosCreados'=>$CargosCreados,
                    'Aulas'=>$Aulas,
                    'Divisiones'=>$Divisiones,
                    'Turno'=>$Turnos, //el turno lo determina la institucion segun el CUE usado con su idExt
                    'TurnosTodos'=>$TurnosTodos,
                    'Extensiones'=>$cantidadInstituciones,
                    'FechaActual'=> Carbon::parse(Carbon::now())->format('Y-m-d'),
                    'mensajeNAV'=>'Panel de Configuración de Cursos y Divisiones'
        
                );
                $ruta ='
                <li class="breadcrumb-item active"><a href="#">LEGAJO UNICO INSTITUCIONAL</a></li>
                <li class="breadcrumb-item active"><a href="'.route('verCargosCreados',$idExt).'">Cargos Institucionales</a></li>
                '; 
                session(['ruta' => $ruta]);
                return view('bandeja.POF.origenCargos',$datos); 
    }

    public function formularioCargosOriginales(Request $request){
        //dd($request);
        /*
        "_token" => "XFktkIFmG19o7RLGMpEvx7y5uvLQNpowHtLaQ5Za"
      "Cargo" => "6"
      "id" => "576"
      se agrego el turno Turno
        */
        $institucion=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.idInstitucionExtension',$request->id)
                ->first();

        $origen = new PofmhOrigenCargoModel();
                $origen->nombre_origen = $request->Cargo;
                $origen->CUECOMPLETO = $institucion->CUECOMPLETO;
                $origen->idTurno =$request->Turno;
                $origen->estado = 0;
        $origen->save();

        $id=$request->id;
        return redirect("/verCargosCreados/$id")->with('ConfirmarActualizarOrigenCargo','OK');
    }

    public function formularioAulaCargosOriginales(Request $request){
        //dd($request);
        /*
        "_token" => "XFktkIFmG19o7RLGMpEvx7y5uvLQNpowHtLaQ5Za"
      cargo, aula, division, id, turno,cue

        */
        /*$institucion=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.idInstitucionExtension',$request->id)
                ->first();*/

        $a = new RelCargoAulaDivModel();
                $a->idOrigenCargo = $request->Cargo;
                $a->CUECOMPLETO = $request->Cue;    //el base o las extensiones
                $a->idTurno =$request->Turno;
                $a->idAula =$request->Aula;
                $a->idDivision =$request->Division;
        $a->save();

        $id=$request->id;   //el id indica quien esta cargando, generalmente una base 00
        return redirect("/verCargosCreados/$id")->with('ConfirmarActualizarOrigenCargo','OK');
    }
    public function desvincularOrigenCargo($idCargo)
    {
        $cargoEncontrado = PofmhOrigenCargoModel::where('idOrigenCargo', $idCargo)->first();
        
        if (!$cargoEncontrado) {
            return response()->json(['error' => 'Cargo no encontrado.'], 404); // Responder con error si no existe
        }
        
        $institucion = DB::table('tb_institucion_extension')
            ->where('CUECOMPLETO', $cargoEncontrado->CUECOMPLETO)
            ->where('idTurnoUsuario', $cargoEncontrado->idTurno)
            ->first();
        
        if (!$institucion) {
            return response()->json(['error' => 'Institución no encontrada.'], 404); // Responder con error si no existe
        }
        
        $id = $institucion->idInstitucionExtension;
    
        // Verificar si existen registros activos vinculados a ese cargo y ponerlos a NULL
        $registros = PofmhModel::where('Origen', $idCargo)->get();
    
        if (!$registros->isEmpty()) {
            foreach ($registros as $registro) {
                $registro->Origen = null;
                $registro->save();
            }
        }
    
        // Proceder a eliminar el cargo
        PofmhOrigenCargoModel::where('idOrigenCargo', $idCargo)->delete();
        
        return redirect("/verCargosCreados/$id")->with('ConfirmarEliminarOrigenCargo', 'OK');
    }
    
    public function desvincularAulaOrigenCargo($idPadt)
    {
        $RelAulaCargoEcontrado = RelCargoAulaDivModel::where('idPadt',$idPadt)->first();
    
        $institucion=DB::table('tb_institucion_extension')
                ->where('tb_institucion_extension.CUECOMPLETO',$RelAulaCargoEcontrado->CUECOMPLETO)
                ->first();
        
        $id=$institucion->idInstitucionExtension;

        // Si no existen registros, proceder a eliminar
        RelCargoAulaDivModel::where('idPadt', $idPadt)
            ->where('CUECOMPLETO', $RelAulaCargoEcontrado->CUECOMPLETO)
            ->delete();
        
        // Redirigir con confirmación de eliminación exitosa
        return redirect("/verCargosCreados/$id")->with('ConfirmarEliminarOrigenCargo', 'OK');
        
    }
    //para recuperar agentes
    public function escuelasCargadasRecAgente(){
        //extras a enviar
        $escuelas = InstitucionExtensionModel::Join('tb_ambitos', 'tb_ambitos.idAmbito', '=', 'tb_institucion_extension.Ambito')
        ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->select('tb_institucion_extension.*','tb_ambitos.*','tb_turnos_usuario.*')
        ->get();

       
        $datos=array(
            'mensajeError'=>"",
            'Escuelas'=>$escuelas,
            
            'mensajeNAV'=>'Panel de Configuración de Usuarios',
        );
        //dd($infoPlaza);
        return view('bandeja.POF.escuelasRecAgentes',$datos);
    }


    public function buscar_dni_cue_pofmh($CUECOMPLETO){
            //cargo en memoruia el cue a trabajar
            session(['REC_CUECOMPLETO' => $CUECOMPLETO]);
    $institucionExtension = InstitucionExtensionModel::where('CUECOMPLETO',$CUECOMPLETO)
            ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
            ->Join('tb_nivelesensenanza', 'tb_nivelesensenanza.NivelEnsenanza', '=', 'tb_institucion_extension.Nivel')
            ->first();
            $pofmh = PofmhModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)
    //->where('Turno',$institucionExtension->idTurnoUsuario)
    ->orderBy('orden','ASC')
    ->get();
    //dd($pofmh);
    //cargo los anexos
    $CargosSalariales =   DB::table('tb_cargossalariales')->get();
    $Condiciones =   CondicionModel::all();
    $Aulas =   PofmhAulas::all();
    $Divisiones = PofmhDivisiones::all();
    $NovedadesExtras = PofmhNovedadesExtras::all();
    $Turnos =   PofmhTurnos::all();
    $Activos =   PofmhActivosModel::all();
    $OrigenesDeCargos = PofmhOrigenCargoModel::all();
    $CargosCreados = PofmhOrigenCargoModel::where('CUECOMPLETO',session('CUECOMPLETO'))->get();
    //$EspCur =   DB::table('tb_turnos_usuario')->get();

    $SitRev =   PofMhSitRev::all();//DB::table('tb_situacionrevista')->get();
    $Motivos =   DB::table('tb_motivos')->get();
    //dd($Divisiones);
            $datos=array(
                'estado'=>"Sin Accion",
                'CUECOMPLETO'=>$CUECOMPLETO,
                'institucionExtension'=>$institucionExtension,
                'infoPofMH'=>$pofmh,
                'CargosSalariales'=>$CargosSalariales,
                'Divisiones'=>$Divisiones,
                'Turnos'=>$Turnos,
                'SitRev'=>$SitRev,
                'Motivos'=>$Motivos,
                'Condiciones'=>$Condiciones,
                'Aulas'=>$Aulas,
                'NovedadesExtras'=>$NovedadesExtras,
                'Activos'=>$Activos,
                'OrigenesDeCargos'=>$OrigenesDeCargos,
                'CargosCreados'=>$CargosCreados,
            );
    
        return view('bandeja.POF.buscadorUsuariosRec',$datos);
    }

    public function buscar_dni_ajax(Request $request)
    {
        // Obtener el DNI del request
        $dni = $request->input('dni');
    
        // Validar que el DNI no esté vacío
        if (empty($dni)) {
            return response()->json(['error' => 'El DNI no puede estar vacío'], 400);
        }
    
        // Consulta en la tabla liqfeb2024 para buscar el DNI
        $usuarios = LiqFeb24Model::where('Documento', 'LIKE', "%{$dni}%")->get();
    
        // Verifica si se encontraron usuarios
        if ($usuarios->isEmpty()) {
            return response()->json(['message' => 'No se encontraron usuarios'], 404);
        }
    
        // Devuelve una respuesta en formato JSON
        return response()->json($usuarios);
    }

    public function insertar_usuario(Request $request)
    {
        $usuario = json_decode($request->input('usuario'));
        $cueCompleto = $request->input('cueCompleto'); // Captura el CUE desde la solicitud
    
        // Ahora utiliza $cueCompleto para buscar la institución
        $inst = InstitucionExtensionModel::where('CUECOMPLETO', $cueCompleto)->first();
        
        if ($inst) {
            $nuevo = new PofmhModel();
                $nuevo->CUECOMPLETO = $cueCompleto;
                $nuevo->orden = 0;
                $nuevo->Agente = $usuario->Documento;
                $nuevo->Cuil = $usuario->Cuil;
                $nuevo->ApeNom = $usuario->ApeNom;
                    //busco el cargo o sit rev
                    $infoCargo = DB::table('tb_cargossalariales')->where('Codigo',$usuario->Codigo_Nomenclador)->first();
                $nuevo->Cargo = $infoCargo->idCargo;    //Ej Maestro de Jardin D01
                $nuevo->Aula = 1;
                $nuevo->Division = 1;
                $nuevo->updated_at = now();
                $nuevo->created_at = now();
                $nuevo->EspCur = "";
                $nuevo->Turno = 1;  //lo dejo en mañana por defecto
                $nuevo->Horas = $usuario->Hora;
                $nuevo->Origen = "cargar";
                $infoSitRev = PofMhSitRev::where('Descripcion', $usuario->Descuento_Plan)->first();

                // Validar si se encontró un resultado
                if($infoSitRev) {
                    $nuevo->SitRev = $infoSitRev->idSituacionRevista;
                } else {
                    // Manejo cuando no se encuentra la situación de revista
                    $nuevo->SitRev = null; // o puedes asignar un valor predeterminado si lo deseas
                }
                $nuevo->FechaAltaCargo = null;
                $nuevo->FechaDesignado = null;
                $nuevo->Condicion = 1;
                $nuevo->FechaDesde = null;
                $nuevo->FechaHasta = null;
                $nuevo->Motivo = 65;        //como es la primera vez no tiene info
                $nuevo->DatosPorCondicion = "cargar";
                $nuevo->Antiguedad = $usuario->Antiguedad;
                $nuevo->AgenteR = null;
                //ahora en novedad se usa un modal
                $nuevo->Asistencia = 0;     //se justifica en modal
                $nuevo->Justificada = 0;    //se justifica en modal
                $nuevo->Injustificada = 0;  //se justifica en modal
                $nuevo->Observaciones = "Sin observaciones";
                $nuevo->Sexo = $usuario->Sexo;
                $nuevo->Zona = $usuario->Zona;
                $nuevo->Descuento_zona = $usuario->Descuento_Zona;
                $nuevo->Unidad_Liquidacion = $usuario->Escuela;
                $nuevo->Descuento_Escuela = $usuario->Descuento_Escuela;
                $nuevo->Nivel = $usuario->Nivel;
                $nuevo->Plan = $usuario->Plan;
                $nuevo->Descuento_Plan = $usuario->Descuento_Plan;
                $nuevo->Agrupamiento = $usuario->Agrupamiento;
                $nuevo->Descuento_Agrupamiento = $usuario->Descuento_Agrupamiento;
                $nuevo->LCategoria = $usuario->LCategoria;
                $nuevo->NCategoria = $usuario->NCategoria;
                $nuevo->Codigo_Nomenclador = $usuario->Codigo_Nomenclador;
                $nuevo->Nomenclador = $usuario->Nomenclador;
                $nuevo->Carrera = null;
                $nuevo->Orientacion = null;
                $nuevo->Titulo = null;
            $nuevo->save();
            $infoPofMH = PofmhModel::where('CUECOMPLETO', $cueCompleto)->get();
            return response()->json([
                'status' => 'success', 
                'message' => 'Usuario insertado correctamente.',
                'data' => $infoPofMH]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No se pudo encontrar la institución.']);
        }
    }
    
    //verifico si existe el dni
    public function verificarDNI(Request $request)
    {
        // Valida que se envíe el DNI
        $request->validate([
            'dni' => 'required|string|max:20', // Limita la longitud máxima del DNI si es necesario
        ]);
    
        // Busca el agente en la tabla 'tb_agentes' por el DNI
        $agente = DB::table('tb_agentes')->where('Documento', $request->dni)->first();
    
        // Si el agente existe, devuelve una respuesta positiva
        if ($agente) {
            return response()->json([
                'success' => true, 
                'message' => 'DNI encontrado',
                'ApeNom' => $agente->ApeNom // Asegúrate de que esto se ajuste a lo que necesitas
            ]);
        }
    
        // Si no existe, devuelve una respuesta negativa
        return response()->json(['success' => false, 'message' => 'DNI no encontrado']);
    }
    

    //para supervisores
    public function listaSupervisora(){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
        //saco el modo de la memoria
        $infoUsuario = session('InfoUsuario');
        $modoUsuario = $infoUsuario->Modo;
        $valorModo=0;

        //sin filtro por zonas
        $escuelas = InstitucionExtensionModel::Join('tb_ambitos', 'tb_ambitos.idAmbito', '=', 'tb_institucion_extension.Ambito')
        ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->join('tb_zonasupervision','tb_zonasupervision.idZonaSupervision','tb_institucion_extension.ZonaSupervision')
        //->where('ZonaSupervision',$valorModo)
        ->select(
            'tb_institucion_extension.idInstitucionExtension',
            'tb_institucion_extension.CUECOMPLETO',
            'tb_institucion_extension.Nombre_Institucion',
            'tb_institucion_extension.Nivel',
            'tb_institucion_extension.Categoria',
            'tb_institucion_extension.Localidad',
            'tb_institucion_extension.Departamento',
            'tb_institucion_extension.Zona',
            'tb_institucion_extension.ZonaSupervision',
            'tb_institucion_extension.Jornada',
            'tb_institucion_extension.Ambito',
            'tb_ambitos.nombreAmbito',
            'tb_turnos_usuario.Descripcion',
            \DB::raw('IFNULL(tb_zonasupervision.Descripcion, "Sin datos") as ZonaSuper'),
            \DB::raw('IFNULL(tb_zonasupervision.Codigo, "Sin datos") as ZonaSuperCodigo')
        )
        ->get();
       
        $datos=array(
            'mensajeError'=>"",
            'Escuelas'=>$escuelas,
            'valorModo'=>$valorModo,
            'mensajeNAV'=>'Panel de Administracion de Supervisores',
        );
        //dd($infoPlaza);
        
        return view('bandeja.POF.supervisora.escuelasSupervisora',$datos);
    }

    public function listaGestionPrivada(){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
        //saco el modo de la memoria
        $infoUsuario = session('InfoUsuario');
        $modoUsuario = $infoUsuario->Modo;
        $valorModo=0;
        //debo traer de instarealiq todos los escu que sean 800 y obtener sus cues, estos son solo los que debo pasar
        
        $escuelasFiltro = DB::connection('DB8')->table('instarealiq')->where('escu', 'like', '8%')->get();
        //dd($escuelasFiltro);
        //sin filtro por zonas
        $escuelas = InstitucionExtensionModel::Join('tb_ambitos', 'tb_ambitos.idAmbito', '=', 'tb_institucion_extension.Ambito')
        ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->join('tb_zonasupervision','tb_zonasupervision.idZonaSupervision','tb_institucion_extension.ZonaSupervision')
        //->where('ZonaSupervision',$valorModo)
        ->whereIn('tb_institucion_extension.CUECOMPLETO', $escuelasFiltro->pluck('CUEA'))
        ->select(
            'tb_institucion_extension.idInstitucionExtension',
            'tb_institucion_extension.CUECOMPLETO',
            'tb_institucion_extension.Nombre_Institucion',
            'tb_institucion_extension.Nivel',
            'tb_institucion_extension.Categoria',
            'tb_institucion_extension.Localidad',
            'tb_institucion_extension.Departamento',
            'tb_institucion_extension.Zona',
            'tb_institucion_extension.ZonaSupervision',
            'tb_institucion_extension.Jornada',
            'tb_institucion_extension.Ambito',
            'tb_ambitos.nombreAmbito',
            'tb_turnos_usuario.Descripcion',
            \DB::raw('IFNULL(tb_zonasupervision.Descripcion, "Sin datos") as ZonaSuper'),
            \DB::raw('IFNULL(tb_zonasupervision.Codigo, "Sin datos") as ZonaSuperCodigo')
        )
        ->get();
       
        $datos=array(
            'mensajeError'=>"",
            'Escuelas'=>$escuelas,
            'valorModo'=>$valorModo,
            'mensajeNAV'=>'Panel de Administracion de Supervisores',
        );
        //dd($infoPlaza);
        return view('bandeja.POF.supervisora.escuelasSupervisora',$datos);
    }

    public function listaGestionMunicipal(){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
        //saco el modo de la memoria
        $infoUsuario = session('InfoUsuario');
        $modoUsuario = $infoUsuario->Modo;
        $valorModo=0;
        //debo traer de instarealiq todos los escu que sean 800 y obtener sus cues, estos son solo los que debo pasar
        
        $escuelasFiltro = DB::connection('DB8')->table('instarealiq')->where('escu', '820')->get();
        //dd($escuelasFiltro);
        //sin filtro por zonas
        $escuelas = InstitucionExtensionModel::Join('tb_ambitos', 'tb_ambitos.idAmbito', '=', 'tb_institucion_extension.Ambito')
        ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->join('tb_zonasupervision','tb_zonasupervision.idZonaSupervision','tb_institucion_extension.ZonaSupervision')
        //->where('ZonaSupervision',$valorModo)
        ->whereIn('tb_institucion_extension.CUECOMPLETO', $escuelasFiltro->pluck('CUEA'))
        ->select(
            'tb_institucion_extension.idInstitucionExtension',
            'tb_institucion_extension.CUECOMPLETO',
            'tb_institucion_extension.Nombre_Institucion',
            'tb_institucion_extension.Nivel',
            'tb_institucion_extension.Categoria',
            'tb_institucion_extension.Localidad',
            'tb_institucion_extension.Departamento',
            'tb_institucion_extension.Zona',
            'tb_institucion_extension.ZonaSupervision',
            'tb_institucion_extension.Jornada',
            'tb_institucion_extension.Ambito',
            'tb_ambitos.nombreAmbito',
            'tb_turnos_usuario.Descripcion',
            \DB::raw('IFNULL(tb_zonasupervision.Descripcion, "Sin datos") as ZonaSuper'),
            \DB::raw('IFNULL(tb_zonasupervision.Codigo, "Sin datos") as ZonaSuperCodigo')
        )
        ->get();
       
        $datos=array(
            'mensajeError'=>"",
            'Escuelas'=>$escuelas,
            'valorModo'=>$valorModo,
            'mensajeNAV'=>'Panel de Administracion de Supervisores',
        );
        //dd($infoPlaza);
        return view('bandeja.POF.supervisora.escuelasSupervisora',$datos);
    }
    public function listaSupervisoraVinculada(){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
        //saco el modo de la memoria
        $infoUsuario = session('InfoUsuario');
        $modoUsuario = $infoUsuario->Modo;
        $valorModo=0;
        /*
        //inicial
        if($modoUsuario == 14){
            $valorModo=44; //zona A
        }
        if($modoUsuario == 15){
            $valorModo=45; //zona B
        }
        if($modoUsuario == 16){
            $valorModo=46; //zona C
        }
        //primaria
        if($modoUsuario == 17){
            $valorModo=47; //zona 1A
        }
        if($modoUsuario == 18){
            $valorModo=48; //zona 1B
        }
        if($modoUsuario == 19){
            $valorModo=49; //zona 1C
        }
        if($modoUsuario == 20){
            $valorModo=50; //zona 1D
        }
        if($modoUsuario == 21){
            $valorModo=51; //zona 1E
        }
        if($modoUsuario == 22){
            $valorModo=52; //zona 1F
        }
        if($modoUsuario == 23){
            $valorModo=53; //zona 2A
        }
        if($modoUsuario == 24){
            $valorModo=54; //zona 2B
        }
        //numeros de zona solo
        if($modoUsuario == 25){
            $valorModo=55; //zona 3
        }
        if($modoUsuario == 26){
            $valorModo=56; //zona 4
        }
        if($modoUsuario == 27){
            $valorModo=57; //zona 5
        }
        if($modoUsuario == 28){
            $valorModo=58; //zona 6
        }
        if($modoUsuario == 29){
            $valorModo=59; //zona 7
        }
        if($modoUsuario == 30){
            $valorModo=60; //zona 8
        }
        if($modoUsuario == 31){
            $valorModo=61; //zona 9
        }
        if($modoUsuario == 32){
            $valorModo=62; //zona 10
        }
        if($modoUsuario == 33){
            $valorModo=63; //zona 11
        }
        if($modoUsuario == 34){
            $valorModo=64; //zona 12
        }
        if($modoUsuario == 35){
            $valorModo=65; //zona 13
        }
        if($modoUsuario == 36){
            $valorModo=66; //zona 14
        }
        if($modoUsuario == 37){
            $valorModo=67; //zona 15
        }
        if($modoUsuario > 38){
            $valorModo=41; // DIRECTORAS
            $escuelas = InstitucionExtensionModel::Join('tb_ambitos', 'tb_ambitos.idAmbito', '=', 'tb_institucion_extension.Ambito')
            ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
            ->join('tb_zonasupervision','tb_zonasupervision.idZonaSupervision','tb_institucion_extension.ZonaSupervision')
            //->where('ZonaSupervision',$valorModo)
            ->select(
                'tb_institucion_extension.idInstitucionExtension',
                'tb_institucion_extension.CUECOMPLETO',
                'tb_institucion_extension.Nombre_Institucion',
                'tb_institucion_extension.Nivel',
                'tb_institucion_extension.Categoria',
                'tb_institucion_extension.Localidad',
                'tb_institucion_extension.Departamento',
                'tb_institucion_extension.Zona',
                'tb_institucion_extension.ZonaSupervision',
                'tb_institucion_extension.Jornada',
                'tb_institucion_extension.Ambito',
                'tb_ambitos.nombreAmbito',
                'tb_turnos_usuario.Descripcion',
                \DB::raw('IFNULL(tb_zonasupervision.Descripcion, "Sin datos") as ZonaSuper'),
                \DB::raw('IFNULL(tb_zonasupervision.Codigo, "Sin datos") as ZonaSuperCodigo')
            )
            ->get();
        }else{
            $escuelas = InstitucionExtensionModel::Join('tb_ambitos', 'tb_ambitos.idAmbito', '=', 'tb_institucion_extension.Ambito')
            ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
            ->join('tb_zonasupervision','tb_zonasupervision.idZonaSupervision','tb_institucion_extension.ZonaSupervision')
            ->where('ZonaSupervision',$valorModo)
            ->select(
                'tb_institucion_extension.idInstitucionExtension',
                'tb_institucion_extension.CUECOMPLETO',
                'tb_institucion_extension.Nombre_Institucion',
                'tb_institucion_extension.Nivel',
                'tb_institucion_extension.Categoria',
                'tb_institucion_extension.Localidad',
                'tb_institucion_extension.Departamento',
                'tb_institucion_extension.Zona',
                'tb_institucion_extension.ZonaSupervision',
                'tb_institucion_extension.Jornada',
                'tb_institucion_extension.Ambito',
                'tb_ambitos.nombreAmbito',
                'tb_turnos_usuario.Descripcion',
                \DB::raw('IFNULL(tb_zonasupervision.Descripcion, "Sin datos") as ZonaSuper'),
                \DB::raw('IFNULL(tb_zonasupervision.Codigo, "Sin datos") as ZonaSuperCodigo')
            )
            ->get();
        }
        */

        //aqui traigo la tabla de relaciones mias, osea de la cuenta activa
        
        $relaciones = SuperRelacionCUEModel::where('idUsuarioSuper',session('idUsuario'))->get();
        $filtroRel =$relaciones->pluck('idInstitucionExtension')->toArray();
        //dd($filtroRel);
        //sin filtro por zonas
        $escuelas = InstitucionExtensionModel::Join('tb_ambitos', 'tb_ambitos.idAmbito', '=', 'tb_institucion_extension.Ambito')
        ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->join('tb_zonasupervision','tb_zonasupervision.idZonaSupervision','tb_institucion_extension.ZonaSupervision')
        //->where('ZonaSupervision',$valorModo)
        ->join('tb_super_cue_relacion','tb_super_cue_relacion.idInstitucionExtension','tb_institucion_extension.idInstitucionExtension')
        ->where(DB::connection('mysql')->getDatabaseName() . '.tb_super_cue_relacion.idUsuarioSuper',session('idUsuario'))
        //->whereIn('tb_super_relacion_cue.idInstitucionExtension',$filtroRel)

        
        ->select(
            'tb_institucion_extension.idInstitucionExtension',
            'tb_institucion_extension.CUECOMPLETO',
            'tb_institucion_extension.Nombre_Institucion',
            'tb_institucion_extension.Nivel',
            'tb_institucion_extension.Categoria',
            'tb_institucion_extension.Localidad',
            'tb_institucion_extension.Departamento',
            'tb_institucion_extension.Zona',
            'tb_institucion_extension.ZonaSupervision',
            'tb_institucion_extension.Jornada',
            'tb_institucion_extension.Ambito',
            'tb_ambitos.nombreAmbito',
            'tb_turnos_usuario.Descripcion',
            \DB::raw('IFNULL(tb_zonasupervision.Descripcion, "Sin datos") as ZonaSuper'),
            \DB::raw('IFNULL(tb_zonasupervision.Codigo, "Sin datos") as ZonaSuperCodigo')
        )
        ->get();
       
        $datos=array(
            'mensajeError'=>"",
            'Escuelas'=>$escuelas,
            'valorModo'=>$valorModo,
            'mensajeNAV'=>'Panel de Administracion de Supervisores',
        );
        //dd($infoPlaza);
        return view('bandeja.POF.supervisora.escuelasSupervisoraVinculada',$datos);
    }

public function listaSupervisoraMensajes(){
    set_time_limit(0);
    ini_set('memory_limit', '2028M');

    $infoUsuario = session('InfoUsuario');
    $mesActual = Carbon::now()->month;
    $anioActual = Carbon::now()->year;

    // Filtrar relaciones por mes y año actual
    $Misrelaciones = SuperRelacionCUEModel::where('idUsuarioSuper', session('idUsuario'))
        ->whereMonth('created_at', $mesActual)
        ->whereYear('created_at', $anioActual)
        ->get();

    $CUEs = $Misrelaciones->pluck('CUECOMPLETO')->toArray();

    // Filtrar mensajes también por mes y año actual
    $AlertasMensajes = DB::table('tb_alerta_novedades')
        ->whereIn('CUECOMPLETO', $CUEs)
        ->whereMonth('created_at', $mesActual)
        ->whereYear('created_at', $anioActual)
        ->orderBy('Estado', 'DESC')
        ->get();

    $datos = [
        'mensajeError' => "",
        'valorModo' => 1,
        'Misrelaciones' => $Misrelaciones,
        'AlertasMensajes' => $AlertasMensajes,
        'mensajeNAV' => 'Panel de Administracion de Supervisores',
    ];

    $ruta ='
    <li class="breadcrumb-item active"><a href="#">PANEL DE SUPERVISORES</a></li>
    <li class="breadcrumb-item active"><a href="'.route('listaSupervisoraMensajes').'">LISTA DE MENSAJES</a></li>
    '; 

    session(['ruta' => $ruta]);

    return view('bandeja.POF.supervisora.listaMensajesSuper', $datos);
}
    //control de super agregar o quitar cue
    public function agregar_relacion_cue_super(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'cue' => 'required|string',
            'id' => 'required|integer',
            'super_id' => 'required|integer',
        ]);

        // Insertar la relación en la tabla M:N
        $relacion = new SuperRelacionCUEModel();
            $relacion->CUECOMPLETO = $request->cue;
            $relacion->idInstitucionExtension = $request->id; // Asegúrate de que el nombre del campo sea correcto
            $relacion->idUsuarioSuper = $request->super_id; // Asegúrate de que el nombre del campo sea correcto
        $relacion->save();

        // Respuesta al cliente
        return response()->json(['success' => true]);
    }

    public function eliminar_relacion_cue_super(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'cue' => 'required|string',
            'id' => 'required|integer',
            'super_id' => 'required|integer',
        ]);

        // Eliminar la relación en la tabla M:N
        $relacion = SuperRelacionCUEModel::where('CUECOMPLETO', $request->cue)
                            ->where('idInstitucionExtension', $request->id)
                            ->where('idUsuarioSuper', $request->super_id)
                            ->first();

        if ($relacion) {
            $relacion->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function verPofMhidExtSuper($idExtension){
        //busco la institucion para cargar sus datos
        $institucionExtension = InstitucionExtensionModel::where('idInstitucionExtension',$idExtension)
        ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->Join('tb_nivelesensenanza', 'tb_nivelesensenanza.NivelEnsenanza', '=', 'tb_institucion_extension.Nivel')
        ->first();
        //dd($institucionExtension);
        //traigo su nuevo POFMH
        $pofmh = PofmhModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)
        //->where('Turno',$institucionExtension->idTurnoUsuario)
        ->orderBy('orden','ASC')
        ->get();
        //dd($pofmh);
        //cargo los anexos
        $CargosSalariales =   DB::table('tb_cargossalariales')->get();
        $Condiciones =   CondicionModel::all();
        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $NovedadesExtras = PofmhNovedadesExtras::all();
        $Turnos =   PofmhTurnos::all();
        $Activos =   PofmhActivosModel::all();
        $OrigenesDeCargos = PofmhOrigenCargoModel::all();
        $CargosCreados = DB::connection('DB7')->table('tb_origenes_cargos')
        ->where('tb_origenes_cargos.CUECOMPLETO', $institucionExtension->CUECOMPLETO)
        ->join('tb_cargos_pof_origen', 'tb_cargos_pof_origen.idCargos_Pof_Origen', '=', 'tb_origenes_cargos.nombre_origen')
        ->get();

        //$EspCur =   DB::table('tb_turnos_usuario')->get();
    
        $SitRev =   PofMhSitRev::all();//DB::table('tb_situacionrevista')->get();
        $Motivos =   DB::table('tb_motivos')->get();
        //dd($Divisiones);
        $datos=array(
            'mensajeError'=>"",
            'institucionExtension'=>$institucionExtension,
            'infoPofMH'=>$pofmh,
            'CargosSalariales'=>$CargosSalariales,
            'Divisiones'=>$Divisiones,
            'Turnos'=>$Turnos,
            'SitRev'=>$SitRev,
            'Motivos'=>$Motivos,
            'Condiciones'=>$Condiciones,
            'Aulas'=>$Aulas,
            'NovedadesExtras'=>$NovedadesExtras,
            'Activos'=>$Activos,
            'OrigenesDeCargos'=>$OrigenesDeCargos,
            'CargosCreados'=>$CargosCreados,
            'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
        );
        
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">POF Horizontal</a></li>
            <li class="breadcrumb-item active"><a href="'.route('cargar_pof_horizontal').'">Modelo de POF Nuevo</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.POF.supervisora.cargarPofhmNoEdit',$datos);
      }

      public function novedadesModal($id)
      {
          $novedad = PofmhNovedades::where('idNovedad',$id)->first(); // Asegúrate de reemplazar Novedad con el modelo correcto
          if ($novedad) {
              $novedad->delete();
              return response()->json(['success' => true, 'message' => 'Novedad eliminada correctamente.']);
          }
          return response()->json(['success' => false, 'message' => 'Novedad no encontrada.'], 404);
      }



//para liquidacion
    public function buscar_dni_cue_pofmh_liq(){
            //cargo en memoruia el cue a trabajar
         $institucionExtension = InstitucionExtensionModel::where('CUECOMPLETO',1)
             ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
             ->Join('tb_nivelesensenanza', 'tb_nivelesensenanza.NivelEnsenanza', '=', 'tb_institucion_extension.Nivel')
             ->first();
            $pofmh = PofmhModel::where('CUECOMPLETO',1)
        //->where('Turno',$institucionExtension->idTurnoUsuario)
        ->orderBy('orden','ASC')
        ->get();
        //dd($pofmh);
        //cargo los anexos
        $CargosSalariales =   DB::table('tb_cargossalariales')->get();
        $Condiciones =   CondicionModel::all();
        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $NovedadesExtras = PofmhNovedadesExtras::all();
        $Turnos =   PofmhTurnos::all();
        $Activos =   PofmhActivosModel::all();
        $OrigenesDeCargos = PofmhOrigenCargoModel::all();
        $CargosCreados = PofmhOrigenCargoModel::where('CUECOMPLETO',session('CUECOMPLETO'))->get();
        //$EspCur =   DB::table('tb_turnos_usuario')->get();

        $SitRev =   PofMhSitRev::all();//DB::table('tb_situacionrevista')->get();
        $Motivos =   DB::table('tb_motivos')->get();
        //dd($Divisiones);
            $datos=array(
                'estado'=>"Sin Accion",
                //'CUECOMPLETO'=>$CUECOMPLETO,
                //'institucionExtension'=>$institucionExtension,
                'infoPofMH'=>$pofmh,
                'CargosSalariales'=>$CargosSalariales,
                'Divisiones'=>$Divisiones,
                'Turnos'=>$Turnos,
                'SitRev'=>$SitRev,
                'Motivos'=>$Motivos,
                'Condiciones'=>$Condiciones,
                'Aulas'=>$Aulas,
                'NovedadesExtras'=>$NovedadesExtras,
                'Activos'=>$Activos,
                'OrigenesDeCargos'=>$OrigenesDeCargos,
                'CargosCreados'=>$CargosCreados,
            );

        return view('bandeja.POF.buscarUsuariosRecLiq',$datos);
    }

    public function buscar_dni_ajax_liq(Request $request)
    {
        // Obtener el DNI del request
        $dni = $request->input('dni');
        //guardo en memoria el nombre
        session(['AgenteDuplicadoBuscado'=>$dni]);
        // Validar que el DNI no esté vacío
        if (empty($dni)) {
            return response()->json(['error' => 'El DNI no puede estar vacío'], 400);
        }
    
        // Realizar la consulta con join entre bases de datos
        $usuarios = PofmhModel::where('Agente', $dni)
            ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', '=', 'tb_pofmh.CUECOMPLETO')
            ->where('tb_pofmh.CUECOMPLETO', 'not like', '999999%') // Excluir CUECOMPLETO que comiencen con 999999
            ->select(
                'tb_pofmh.*',
                'tb_institucion_extension.Nombre_Institucion',
                'tb_institucion_extension.CUE'
                // Agrega otros campos de tb_institucion_extension que necesites
            )
            ->get();
    
        // Verifica si se encontraron usuarios
        if ($usuarios->isEmpty()) {
            return response()->json(['message' => 'No se encontraron usuarios'], 404);
        }
    
        // Agrupar los datos por Documento (si hay múltiples registros por DNI)
        $datosAgrupados = $usuarios->groupBy('Agente')->map(function($item) {
            // Asumiendo que los datos personales son iguales en todos los registros
            $personal = [
                'ApeNom' => $item->first()->ApeNom ? $item->first()->ApeNom : "S/D",
                'Documento' => $item->first()->Agente ? $item->first()->Agente : "S/D",  //esto es el DNI
                'Cuil' => $item->first()->Cuil ? $item->first()->Cuil : "S/D",
                'Sexo' => $item->first()->Sexo ? $item->first()->Sexo : "S/D",
                // Agrega otros campos personales si es necesario
            ];
    
            // Información POF (agrupada para evitar duplicados)
            $pof = $item->unique('idPofmh')->map(function($p) {
                $infoSitRev = PofMhSitRev::where('idSituacionRevista', $p->SitRev)->first();
                $infoCargoSal = DB::table('tb_cargossalariales')->where('idCargo', $p->Cargo)->first();
                return [
                    'Situacion_Revista' => $infoSitRev ? $infoSitRev->Descripcion : 'S/D',
                    'Antiguedad' => $p->Antiguedad ? $p->Antiguedad : 'S/D',
                    'Hora' => $p->Horas ? $p->Horas : 'S/D',
                    'Cargo_Salarial' => $infoCargoSal ? $infoCargoSal->Cargo : 'S/D', 
                    'Codigo_Salarial' => $infoCargoSal ? $infoCargoSal->Codigo : 'S/D',
                    'Posesion_Cargo' => $p->FechaAltaCargo ? $p->FechaAltaCargo : 'S/D',
                    'Designado_Cargo' => $p->FechaDesignado ? $p->FechaDesignado : 'S/D',
                    'idPofmh'=>$p->idPofmh
                ];
            });
    
            // Información Institucional (agrupada para evitar duplicados)
            $institucional = $item->unique('idPofmh')->map(function($i) {
                $instExt = DB::table('tb_institucion_extension')
                    ->where('CUECOMPLETO', $i->CUECOMPLETO)
                    ->first();
                $unidadLiq = DB::connection('DB8')->table('instarealiq')->where('CUEA', $i->CUECOMPLETO)->first();    
                return [
                    'CUE' => $i->CUECOMPLETO ?? 'S/D',
                    'Codigo_Liq' => $unidadLiq ? ($unidadLiq->escu ?? 'S/D') : 'S/D',
                    'Area_Liq' => $unidadLiq ? ($unidadLiq->area ?? 'S/D') : 'S/D',
                    'Nombre_Institucion' => $unidadLiq ? ($unidadLiq->nombreInstitucion ?? 'S/D') : 'S/D',
                    'Nivel' => $unidadLiq ? ($unidadLiq->nivel ?? 'S/D') : 'S/D',
                    'Zona' => $unidadLiq ? ($unidadLiq->codZonaLiq ?? 'S/D') : 'S/D',
                    'ZonaSuper' => $instExt ? ($instExt->Domicilio_Institucion ?? 'S/D') : 'S/D',
                    'Localidad' => $instExt ? ($instExt->Localidad ?? 'S/D') : 'S/D',
                ];
            });
    
            // Información Aúlica (agrupada para evitar duplicados)
            $informacionAulica = $item->unique('idPofmh')->map(function($ia) {
                $infoAula = PofmhAulas::where('idAula', (int) $ia->Aula)->first();
                $infoDivision = PofmhDivisiones::where('idDivision', $ia->Division)->first();
                $infoTurno = PofmhTurnos::where('idTurno', $ia->Turno)->first();
                $infoCondicion = CondicionModel::where('idCondicion', $ia->Condicion)->first();
                $infoActivos = PofmhActivosModel::where('idActivo', $ia->Activo)->first();
    
                return [
                    'Nombre_Institucion' =>  $ia->Nombre_Institucion ? $ia->Nombre_Institucion : "S/D",
                    'Aula' => $infoAula ? $infoAula->nombre_aula : 'S/D',
                    'Division' => $infoDivision ? $infoDivision->nombre_division : 'S/D',
                    'Turno' => $infoTurno ? $infoTurno->nombre_turno : 'S/D',
                    'EspCur' => $ia->EspCur ? $ia->EspCur : 'S/D',
                    'Matricula' => $ia->Matricula ? $ia->Matricula : 'S/D',
                    'Condicion' => $infoCondicion ? $infoCondicion->Descripcion : 'S/D',
                    'EnFuncion' => $infoActivos ? $infoActivos->nombre_activo : 'S/D',
                    'observacion_cond' => $ia->DatosPorCondicion ? $ia->DatosPorCondicion : "S/D",
                    'AsisTotal' => $ia->Asistencia ? $ia->Asistencia : "0",
                    'AsistJust' => $ia->Justificada ? $ia->Justificada : "0",
                    'AsistInjust' => $ia->Injustificada ? $ia->Injustificada : "0",
                ];
            });
    
            return [
                'personal' => $personal,
                'pof' => $pof,
                'institucional' => $institucional,
                'aulica' => $informacionAulica
            ];
        });
    
        // Devuelve una respuesta en formato JSON
        return response()->json($datosAgrupados);
    }
    public function listarInstarealiq()
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

        return view('bandeja.POF.editarInstaReaLiq', compact('datos'));
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
    //tecnicos
    public function buscar_dni_cue_pofmh_tec(){
        //cargo en memoruia el cue a trabajar
     $institucionExtension = InstitucionExtensionModel::where('CUECOMPLETO',1)
         ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
         ->Join('tb_nivelesensenanza', 'tb_nivelesensenanza.NivelEnsenanza', '=', 'tb_institucion_extension.Nivel')
         ->first();
        $pofmh = PofmhModel::where('CUECOMPLETO',1)
    //->where('Turno',$institucionExtension->idTurnoUsuario)
    ->orderBy('orden','ASC')
    ->get();
    //dd($pofmh);
    //cargo los anexos
    $CargosSalariales =   DB::table('tb_cargossalariales')->get();
    $Condiciones =   CondicionModel::all();
    $Aulas =   PofmhAulas::all();
    $Divisiones = PofmhDivisiones::all();
    $NovedadesExtras = PofmhNovedadesExtras::all();
    $Turnos =   PofmhTurnos::all();
    $Activos =   PofmhActivosModel::all();
    $OrigenesDeCargos = PofmhOrigenCargoModel::all();
    $CargosCreados = PofmhOrigenCargoModel::where('CUECOMPLETO',session('CUECOMPLETO'))->get();
    //$EspCur =   DB::table('tb_turnos_usuario')->get();

    $SitRev =   PofMhSitRev::all();//DB::table('tb_situacionrevista')->get();
    $Motivos =   DB::table('tb_motivos')->get();
    //dd($Divisiones);
        $datos=array(
            'estado'=>"Sin Accion",
            //'CUECOMPLETO'=>$CUECOMPLETO,
            //'institucionExtension'=>$institucionExtension,
            'infoPofMH'=>$pofmh,
            'CargosSalariales'=>$CargosSalariales,
            'Divisiones'=>$Divisiones,
            'Turnos'=>$Turnos,
            'SitRev'=>$SitRev,
            'Motivos'=>$Motivos,
            'Condiciones'=>$Condiciones,
            'Aulas'=>$Aulas,
            'NovedadesExtras'=>$NovedadesExtras,
            'Activos'=>$Activos,
            'OrigenesDeCargos'=>$OrigenesDeCargos,
            'CargosCreados'=>$CargosCreados,
        );

    return view('bandeja.POF.buscarUsuariosRecTec',$datos);
}

public function buscar_dni_ajax_tec(Request $request)
{
    $dni = $request->input('dni');
    session(['AgenteDuplicadoBuscado'=>$dni]);
    if (empty($dni)) {
        return response()->json(['error' => 'El DNI no puede estar vacío'], 400);
    }

    // Consulta 1: Traer datos desde libfeb2024
    $resultadosLibfeb2024 = LiqFeb24Model::where('Documento', $dni)->get();

    // Consulta 2: Traer datos desde tb_pofmh
    $resultadosPofmh = PofmhModel::where('Agente', $dni)
    //->join('tb_cargossalariales', 'tb_cargossalariales.idCargo', '=', 'tb_pofmh.Cargo')
    //->join(DB::connection('DB7')->getDatabaseName() . '.tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', '=', 'tb_pofmh.SitRev')
    //->join(DB::connection('DB7')->getDatabaseName() . '.tb_turnos', 'tb_turnos.idTurno', '=', 'tb_pofmh.Turno')
   /* ->join('tb_institucion_extension', function ($join) {
        $join->on('tb_institucion_extension.CUECOMPLETO', '=', 'tb_pofmh.CUECOMPLETO')
             ->where('tb_institucion_extension.idTurnoUsuario', '=', 'tb_pofmh.Turno');
    })*/
    //->select('tb_pofmh.*')
    ->distinct()
    ->get();
    
    // Verificar si se encontraron resultados
    if ($resultadosLibfeb2024->isEmpty() && $resultadosPofmh->isEmpty()) {
        return response()->json(['message' => 'No se encontraron usuarios'], 404);
    }

    $cargos = DB::table('tb_cargossalariales')->get();
    $institucion = DB::table('tb_institucion_extension')->get();
    $turnos = DB::table('tb_turnos_usuario')->get();
    //traer aqui los datos de cargos, motivo, condiciones, etc
    $condiciones = CondicionModel::all();
    $Activos = PofmhActivosModel::all();
    $Motivos = DB::table('tb_motivos')->get();
    // Respuesta JSON
    return response()->json([
        'libfeb2024' => $resultadosLibfeb2024,
        'pofmh' => $resultadosPofmh,
        'CargosSalariales'=>$cargos,
        'Turnos'=>$turnos,
        'Instituciones'=>$institucion,
        'Condiciones'=>$condiciones,
        'Activos'=>$Activos,
        'Motivos'=>$Motivos
    ]);
}

    public function buscar_cue_pofmh_liq(){
        //cargo en memoruia el cue a trabajar
     $institucionExtension = InstitucionExtensionModel::where('CUECOMPLETO',1)
         ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
         ->Join('tb_nivelesensenanza', 'tb_nivelesensenanza.NivelEnsenanza', '=', 'tb_institucion_extension.Nivel')
         ->first();
        $pofmh = PofmhModel::where('CUECOMPLETO',1)
    //->where('Turno',$institucionExtension->idTurnoUsuario)
    ->orderBy('orden','ASC')
    ->get();
    //dd($pofmh);
    //cargo los anexos
    $CargosSalariales =   DB::table('tb_cargossalariales')->get();
    $Condiciones =   CondicionModel::all();
    $Aulas =   PofmhAulas::all();
    $Divisiones = PofmhDivisiones::all();
    $NovedadesExtras = PofmhNovedadesExtras::all();
    $Turnos =   PofmhTurnos::all();
    $Activos =   PofmhActivosModel::all();
    $OrigenesDeCargos = PofmhOrigenCargoModel::all();
    $CargosCreados = PofmhOrigenCargoModel::where('CUECOMPLETO',session('CUECOMPLETO'))->get();
    //$EspCur =   DB::table('tb_turnos_usuario')->get();
    $instarealiq_escu = DB::connection('DB8')->table('instarealiq')
    ->whereNotNull('escu')
    ->select('escu')
    ->groupBy('escu')
    ->get();   
    $instarealiq_area = DB::connection('DB8')->table('instarealiq')
    ->whereNotNull('area')
    ->select('area')
    ->groupBy('area')
    ->get(); 
    $SitRev =   PofMhSitRev::all();//DB::table('tb_situacionrevista')->get();
    $Motivos =   DB::table('tb_motivos')->get();
    //dd($Divisiones);
        $datos=array(
            'estado'=>"Sin Accion",
            //'CUECOMPLETO'=>$CUECOMPLETO,
            //'institucionExtension'=>$institucionExtension,
            'infoPofMH'=>$pofmh,
            'CargosSalariales'=>$CargosSalariales,
            'Divisiones'=>$Divisiones,
            'Turnos'=>$Turnos,
            'SitRev'=>$SitRev,
            'Motivos'=>$Motivos,
            'Condiciones'=>$Condiciones,
            'Aulas'=>$Aulas,
            'NovedadesExtras'=>$NovedadesExtras,
            'instarealiq_escu'=>$instarealiq_escu,
            'instarealiq_area'=>$instarealiq_area,
            'Activos'=>$Activos,
            'OrigenesDeCargos'=>$OrigenesDeCargos,
            'CargosCreados'=>$CargosCreados,
        );

    return view('bandeja.POF.buscarUsuariosPofLiq',$datos);
}

public function buscarAreas(Request $request)
{
    $areas = DB::connection('DB8')->table('instarealiq')
        ->where('escu', $request->escu)
        ->select('area')
        ->groupBy('area')
        ->orderBy('area')
        ->get();

    return response()->json($areas);
}

public function buscarCues(Request $request)
{
    $cues = DB::connection('DB8')->table('instarealiq')
        ->where('escu', $request->escu)
        ->where('area', $request->area)
        ->select('CUEA', 'nombreInstitucion')
        ->groupBy('CUEA', 'nombreInstitucion')
        ->orderBy('nombreInstitucion')
        ->get();

    return response()->json($cues);
}
public function buscar_cue_ajax_liq(Request $request)
{
    // Obtener el CUE del request
    $cue = $request->input('cue');

    // Validar que el CUE no esté vacío
    if (empty($cue)) {
        return response()->json(['error' => 'El CUE no puede estar vacío'], 400);
    }

    // Consultar los datos combinados
    $usuarios = PofmhModel::where('tb_pofmh.CUECOMPLETO', $cue)
        ->join('tb_institucion_extension', 'tb_institucion_extension.CUECOMPLETO', '=', 'tb_pofmh.CUECOMPLETO')
        ->where('tb_pofmh.CUECOMPLETO', 'not like', '999999%')
        ->select(
            'tb_pofmh.*',
            'tb_institucion_extension.Nombre_Institucion',
            'tb_institucion_extension.idInstitucionExtension',
            'tb_institucion_extension.CUE'
        )
        ->get();

    // Verificar si hay resultados
    if ($usuarios->isEmpty()) {
        return response()->json(['message' => 'No se encontraron usuarios'], 404);
    }

    // Agrupar los datos por Documento (Agente)
    $datosAgrupados = $usuarios->groupBy('Agente')->map(function($item) {
        $personal = [
            'ApeNom' => $item->first()->ApeNom ?? 'S/D',
            'Documento' => $item->first()->Agente ?? 'S/D',
            'Cuil' => $item->first()->Cuil ?? 'S/D',
            'Sexo' => $item->first()->Sexo ?? 'S/D',
        ];

        $pof = $item->unique('idPofmh')->map(function($p) {
            $infoSitRev = PofMhSitRev::where('idSituacionRevista', $p->SitRev)->first();
            $infoCargoSal = DB::table('tb_cargossalariales')->where('idCargo', $p->Cargo)->first();
            return [
                'Agente' => $p->ApeNom ?? 'S/D',
                'Situacion_Revista' => $infoSitRev->Descripcion ?? 'S/D',
                'Antiguedad' => $p->Antiguedad ?? 'S/D',
                'Hora' => $p->Horas ?? 'S/D',
                'Cargo_Salarial' => $infoCargoSal->Cargo ?? 'S/D', 
                'Codigo_Salarial' => $infoCargoSal->Codigo ?? 'S/D',
                'Posesion_Cargo' => $p->FechaAltaCargo ?? 'S/D',
                'Designado_Cargo' => $p->FechaDesignado ?? 'S/D',
            ];
        });

        $institucional = $item->unique('idPofmh')->map(function($i) {
            $instExt = DB::table('tb_institucion_extension')
                ->where('CUECOMPLETO', $i->CUECOMPLETO)
                ->first();
            $unidadLiq = DB::connection('DB8')->table('instarealiq')->where('CUEA', $i->CUECOMPLETO)->first();    
            return [
                'Agente' => $i->ApeNom ?? 'S/D',
                'Codigo_Liq' => $unidadLiq->escu ?? 'S/D',
                'Area_Liq' => $unidadLiq->area ?? 'S/D',
                'Nombre_Institucion' => $unidadLiq->nombreInstitucion ?? 'S/D',
                'Nivel' => $unidadLiq->nivel ?? 'S/D',
                'Zona' => $unidadLiq->codZonaLiq ?? 'S/D',
                'Domicilio' => $instExt->Domicilio_Institucion ?? 'S/D',
                'Localidad' => $instExt->Localidad ?? 'S/D',
            ];
        });

        $aulica = $item->unique('idPofmh')->map(function($ia) {
            $infoAula = PofmhAulas::where('idAula', (int) $ia->Aula)->first();
            $infoDivision = PofmhDivisiones::where('idDivision', $ia->Division)->first();
            $infoTurno = PofmhTurnos::where('idTurno', $ia->Turno)->first();
            $infoCondicion = CondicionModel::where('idCondicion', $ia->Condicion)->first();
            $infoActivo = PofmhActivosModel::where('idActivo', $ia->Activo)->first();

            return [
                'Agente' => $ia->ApeNom ?? 'S/D',
                'Nombre_Institucion' => $ia->Nombre_Institucion ?? 'S/D',
                'Aula' => $infoAula->nombre_aula ?? 'S/D',
                'Division' => $infoDivision->nombre_division ?? 'S/D',
                'Turno' => $infoTurno->nombre_turno ?? 'S/D',
                'EspCur' => $ia->EspCur ?? 'S/D',
                'Matricula' => $ia->Matricula ?? '0',
                'Condicion' => $infoCondicion->Descripcion ?? 'S/D',
                'EnFuncion' => $infoActivo->nombre_activo ?? 'S/D',
                'observacion_cond' => $ia->DatosPorCondicion ?? 'S/D',
                'AsisTotal' => $ia->Asistencia ?? '0',
                'AsistJust' => $ia->Justificada ?? '0',
                'AsistInjust' => $ia->Injustificada ?? '0',
            ];
        });

        return [
            'personal' => $personal,
            'pof' => $pof,
            'institucional' => $institucional,
            'aulica' => $aulica,
        ];
    });

    // 🔥 Devolver correctamente: idInstitucionExtension y datos separados
    return response()->json([
        'idInstitucionExtension' => $usuarios->first()->idInstitucionExtension,
        'datos' => $datosAgrupados
    ]);
}



//AGREGUÉ ESTO - Este anda mejor ---HMO---
public function traerTodoAgenteLiq() {
    set_time_limit(0);
    ini_set('memory_limit', '2028M');

    // Cargar todas las colecciones necesarias de una vez
    // $institucionExtension = InstitucionExtensionModel::Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
    //     ->leftJoin('tb_nivelesensenanza', 'tb_institucion_extension.Nivel', '=', 'tb_nivelesensenanza.NivelEnsenanza')
    //     ->select('tb_institucion_extension.*', 'tb_turnos_usuario.*', 'tb_nivelesensenanza.*')
    //     //->first(); //<esto no sera correcto porque solo trae 1 solo registro
    //     ->get();

    // $instituciones = DB::table('tb_institucion_extension')
    //     ->select('CUECOMPLETO', 'Nivel', 'Zona', 'ZonaSupervision', 'idTurnoUsuario')
    //     ->get()
    //     ->groupBy('CUECOMPLETO');

    // $zonas = DB::table('tb_zonas_liq')
    //     ->pluck('nombre_loc_zona', 'codigo_letra')
    //     ->toArray();
    //dd($zonas);
    // $zonasSupervision = DB::table('tb_zonasupervision')
    //     ->pluck('Codigo', 'idZonaSupervision')
    //     ->toArray();

    // $dnInBase = DB::table('tb_agentes')
    //     ->pluck('Documento')
    //     ->toArray();

    // $pofmh = PofmhModel::where('CUECOMPLETO', 'not like', '999%')
    //     ->where('CUECOMPLETO', 'not like', '950%')
    //     ->orderBy('CUECOMPLETO', 'ASC')
    //     ->get();
    $pofmh = DB::connection('DB7')->table('_tb_pofmh_abr_escu_area')->where('CUECOMPLETO', 'not like', '999%')
        ->where('CUECOMPLETO', 'not like', '950%')
        ->orderBy('CUECOMPLETO', 'ASC')
        ->get();

    // $CargosSalariales = DB::table('tb_cargossalariales')
    //     ->pluck('Cargo', 'idCargo')
    //     ->toArray();

    // $CargosSalarialesCodigo = DB::table('tb_cargossalariales')
    //     ->pluck('Codigo', 'idCargo')
    //     ->toArray();

    // $Condiciones = CondicionModel::pluck('Descripcion', 'idCondicion')->toArray();
    // $Aulas = PofmhAulas::pluck('nombre_aula', 'idAula')->toArray();
    // $Divisiones = PofmhDivisiones::pluck('nombre_division', 'idDivision')->toArray();
    // $Turnos = PofmhTurnos::pluck('nombre_turno', 'idTurno')->toArray();
    // $Activos = PofmhActivosModel::pluck('nombre_activo', 'idActivo')->toArray();
    // $OrigenesDeCargos = PofmhOrigenCargoModel::pluck('nombre_origen', 'idOrigenCargo')->toArray();

    // $CargosCreados = DB::connection('DB7')->table('tb_padt')
    //     ->join('tb_origenes_cargos', 'tb_padt.idOrigenCargo', '=', 'tb_origenes_cargos.idOrigenCargo')
    //     ->join('tb_cargos_pof_origen', 'tb_origenes_cargos.nombre_origen', '=', 'tb_cargos_pof_origen.idCargos_Pof_Origen')
    //     ->pluck('tb_cargos_pof_origen.nombre_cargo_origen', 'tb_padt.idOrigenCargo')
    //     ->toArray();

    // $SitRev = PofMhSitRev::pluck('Descripcion', 'idSituacionRevista')->toArray();
    // $Motivos = DB::table('tb_motivos')->pluck('Nombre_Licencia', 'idMotivo')->toArray();
    // $MotivosCodigo = DB::table('tb_motivos')->pluck('Codigo', 'idMotivo')->toArray();

    // Procesar los datos
    /*
    foreach ($pofmh as $row) {
        //no termina de convencerme
        if (isset($instituciones[$row->CUECOMPLETO])) {
            //dd($row->Turno);
            //dd($instituciones[$row->CUECOMPLETO]);
            $institucion = $instituciones[$row->CUECOMPLETO]->firstWhere('idTurnoUsuario', $row->Turno);
            dd($institucion);
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

        $row->isDniLoaded = in_array($row->docu, $dnInBase);
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
    //dd($pofmh);
    */
    $datos = [
        'mensajeError' => "",
        //'institucionExtension' => $institucionExtension,
        'infoPofMH' => $pofmh,
        'mensajeNAV' => 'Panel de Configuración de POF(Modalidad Horizontal)'
    ];

    return view('bandeja.POF.traerTodoAgenteLiq', $datos);
}


/*public function traerTodoAgenteLiq(){
   //busco la institucion para cargar sus datos
   $institucionExtension = InstitucionExtensionModel::Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
   ->leftJoin('tb_nivelesensenanza', 'tb_institucion_extension.Nivel', '=', 'tb_nivelesensenanza.NivelEnsenanza') // Usa leftJoin para traer resultados sin importar si Nivel es null
   ->select('tb_institucion_extension.*', 'tb_turnos_usuario.*', 'tb_nivelesensenanza.*')
   ->first();

   //dd($institucionExtension);
   //traigo su nuevo POFMH
   $pofmh = PofmhModel::orderBy('CUECOMPLETO', 'ASC')->paginate(50); // Pagina de 50 en 50


   //dd($pofmh);
   //cargo los anexos
   $CargosSalariales =   DB::table('tb_cargossalariales')->get();
   $Condiciones =   CondicionModel::all();
   $Aulas =   PofmhAulas::all();
   $Divisiones = PofmhDivisiones::all();
   $NovedadesExtras = PofmhNovedadesExtras::all();
   $Turnos =   PofmhTurnos::all();
   $Activos =   PofmhActivosModel::all();
   $OrigenesDeCargos = PofmhOrigenCargoModel::all();
   $CargosCreados = PofmhOrigenCargoModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)
   ->join('tb_cargos_pof_origen','tb_cargos_pof_origen.idCargos_Pof_Origen','tb_origenes_cargos.nombre_origen')
   ->get();
   
   //$EspCur =   DB::table('tb_turnos_usuario')->get();

   $SitRev =   PofMhSitRev::all();//DB::table('tb_situacionrevista')->get();
   $Motivos =   DB::table('tb_motivos')->get();
   //dd($Divisiones);
   $datos=array(
       'mensajeError'=>"",
       'institucionExtension'=>$institucionExtension,
       'infoPofMH'=>$pofmh,
       'CargosSalariales'=>$CargosSalariales,
       'Divisiones'=>$Divisiones,
       'Turnos'=>$Turnos,
       'SitRev'=>$SitRev,
       'Motivos'=>$Motivos,
       'Condiciones'=>$Condiciones,
       'Aulas'=>$Aulas,
       'NovedadesExtras'=>$NovedadesExtras,
       'Activos'=>$Activos,
       'OrigenesDeCargos'=>$OrigenesDeCargos,
       'CargosCreados'=>$CargosCreados,
       'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
   );
    //dd($infoPlaza);
    return view('bandeja.POF.traerTodoAgenteLiq',$datos);
}*/

    public function eliminar_pof_agente($id)
    {
        try {
            $pofmh = PofmhModel::findOrFail($id); // Buscar el registro por su ID
            $pofmh->delete(); // Eliminar el registro

            return response()->json(['message' => 'Registro eliminado exitosamente.']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar el registro.'], 500);
        }
    }

    public function actualizarDatos(Request $request) {
        // Obtener todos los datos del request
        $data = $request->all();
        //return response()->json(['success' => true, 'message' => 'Datos actualizados correctamente.',"info"=>$data]);
        // Manejar el ID del registro
        $id = $request->input('id'); 

        // Buscar el registro en la base de datos
        $registro = PofmhModel::find($id); 

        if (!$registro) {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado.'], 404);
        }

        /*
         
        "id": 67713,
        "orden": "2.0",                             si
        "dni": "21395973",                          si
        "apenom": "CARESANO, ROXANA MABEL",         cambiado
        "cargo": "160",                             cambiado
        "aula": "1",                                si
        "division": "36",                           si
        "turno": "1",
        "horas": "5",
        "origen": "438",
        "sitrev": "3",
        "condicion": "1",
        "motivos": "65",
        "activo": "2",
        "antiguedad": "15"

         */
        // Verificar y actualizar los campos según el data recibido
        if (isset($data['orden'])) {
            $registro->orden = $data['orden'];
        }

        if (isset($data['dni'])) {
            $registro->Agente = $data['dni'];
        }

        if (isset($data['apenom'])) {
            $registro->ApeNom = $data['apenom'];
        }

        if (isset($data['cargo'])) {
            $registro->Cargo = $data['cargo'];
        }

        if (isset($data['aula'])) {
            $registro->Aula = $data['aula'];
        }

        if (isset($data['division'])) {
            $registro->Division = $data['division'];
        }

        if (isset($data['espcur'])) {
            $registro->EspCur = $data['espcur'];
        }

        if (isset($data['matricula'])) {
            $registro->Matricula = $data['matricula'];
        }

        if (isset($data['turno'])) {
            $registro->Turno = $data['turno'];
        }

        if (isset($data['horas'])) {
            $registro->Horas = $data['horas'];
        }

        if (isset($data['origen'])) {
            $registro->Origen = $data['origen'];
        }

        if (isset($data['sitrev'])) {
            $registro->SitRev = $data['sitrev'];
        }

        if (isset($data['fechaAltaCargo'])) {
            $registro->fechaAltaCargo = $data['fechaAltaCargo'];
        }

        if (isset($data['fechaDesignado'])) {
            $registro->FechaDesignado = $data['fechaDesignado'];
        }

        if (isset($data['condicion'])) {
            $registro->Condicion = $data['condicion'];
        }

        if (isset($data['activo'])) {
            $registro->Activo = $data['activo'];
        }

        if (isset($data['fechaDesde'])) {
            $registro->FechaDesde = $data['fechaDesde'];
        }

        if (isset($data['fechaHasta'])) {
            $registro->FechaHasta = $data['fechaHasta'];
        }

        if (isset($data['motivos'])) {
            $registro->Motivo = $data['motivos'];
        }

        if (isset($data['antiguedad'])) {
            $registro->Antiguedad = $data['antiguedad'];
        }

        if (isset($data['agenteR'])) {
            $registro->AgenteR = $data['agenteR'];
        }

        // Asistencias
        if (isset($data['asistencia'])) {
            $registro->Asistencia = $data['asistencia'];
        }

        if (isset($data['asistenciaJustificada'])) {
            $registro->Justificada = $data['asistenciaJustificada'];
        }

        if (isset($data['asistenciaInjustificada'])) {
            $registro->Injustificada = $data['asistenciaInjustificada'];
        }

        if (isset($data['observaciones'])) {
            $registro->Observaciones = $data['observaciones'];
        }

        if (isset($data['carrera'])) {
            $registro->Carrera = $data['carrera'];
        }

        if (isset($data['orientacion'])) {
            $registro->Orientacion = $data['orientacion'];
        }

        if (isset($data['titulo'])) {
            $registro->Titulo = $data['titulo'];
        }

        // Guardar los cambios en la base de datos
        $registro->save();

        return response()->json(['success' => true, 'message' => 'Datos actualizados correctamente.']);
    }


    //prueba de traer datos 
    public function traerDatosPof() {
        set_time_limit(0);
        return Excel::download(new PofmhExport, 'pof_data.xlsx'); // Generar y descargar el archivo Excel

    }
    
    public function exportar_pof(){
        set_time_limit(0);
        return Excel::download(new PofmhExport, 'pof_data.xlsx'); // Generar y descargar el archivo Excel

    }

    public function verificarDNIs()
    {
        set_time_limit(0);
        // Obtener todos los DNIs de la tabla 'comparar'
        $dniconComparar = DB::connection('DB7')->table('comparar')->pluck('docu');
    
        // Inicializar contadores
        $encontrados = 0;
        $noEncontrados = 0;
    
        // Array para almacenar resultados
        $resultado = [];
    
        // Verificar cada DNI en la tabla 'tb_pofmh'
        foreach ($dniconComparar as $dni) {
            $existe = DB::connection('DB7')->table('tb_pofmh')->where('Agente', $dni)->exists();
    
            // Contar encontrados y no encontrados
            if ($existe) {
                $encontrados++;
                //$resultado[] = "DNI: $dni => ENCONTRADO";
            } else {
                $noEncontrados++;
                $resultado[] = "DNI: $dni => NO ENCONTRADO";
                echo $dni."<br>";
            }
        }
    
        // Mostrar los resultados
        echo "Resultados de la Comparación:<br>";
        foreach ($resultado as $linea) {
            echo $linea . "<br>";
        }
    
        // Mostrar el total de encontrados y no encontrados
        echo "<br>Total encontrados: $encontrados";
        echo "<br>Total no encontrados: $noEncontrados";
    }


    public function consultas_pof_cantEscuela(){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
        
        $niveles = DB::table('tb_nivelesensenanza')
        ->whereNotIn('idNivelEnsenanza',[6,101,119])
        ->limit(8)
        ->get();
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)',
            'Niveles'=>$niveles
        );
         //dd($infoPlaza);
         return view('bandeja.POF.cantidadEscuelasPofCargadaOrigen',$datos);
    }
    public function consultas_pof($Nivel){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
 
        //cargar la relacion tadt y devolver si es json
        $instituciones = DB::table('tb_institucion_extension')
        ->select('CUECOMPLETO')
        ->where('Nivel', $Nivel)
        ->where('EsPrivada', 'N')
        ->where('Nivel', 'not like', 'Priv%') // Excluir los que comienzan con "Priv"
        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
        ->groupBy('CUECOMPLETO')
        ->distinct()
        ->get()
        ->pluck('CUECOMPLETO')
        ->toArray();
        
        //dd($instituciones);

        // Traer todas las aulas con la condición whereIn
        $AulasCargosCreados = DB::connection('DB7')->table('tb_padt')
        ->select(
            'CUECOMPLETO',
            'nombre_turno',
            'nombre_division',
            'nombre_aula',
            'idOrigenCargo'
        )
        ->join('tb_turnos', 'tb_turnos.idTurno', 'tb_padt.idTurno')
        ->join('tb_aulas', 'tb_aulas.idAula', 'tb_padt.idAula')
        ->join('tb_divisiones', 'tb_divisiones.idDivision', 'tb_padt.idDivision')
        ->where(function($query) use ($instituciones) {
            foreach ($instituciones as $cue) {
                $query->orWhere('CUECOMPLETO', 'like', $cue . '%');
            }
        })
        ->get();
        //dd($AulasCargosCreados);
        $datos=array(
            'mensajeError'=>"",
            'infoDatos'=>$AulasCargosCreados,
            'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
        );
         //dd($infoPlaza);
         return view('bandeja.POF.pofGenerarExcel',$datos);
    }

    public function consultas_pof_cantidad($Nivel){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');

        //cargar la relacion tadt y devolver si es json
        $instituciones = DB::table('tb_institucion_extension')
        ->select('CUE')
        ->where('Nivel', $Nivel)
        ->where('EsPrivada', 'N')
        ->where('Nivel', 'not like', 'Priv%') // Excluir los que comienzan con "Priv"
        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
        ->groupBy('CUE')
        ->get()
        ->pluck('CUE')      //filtro por los cue solo
        ->toArray();
        
        //dd($instituciones);

        // Traer todas las aulas con la condición whereIn
        $origen = DB::connection('DB7')->table('tb_origenes_cargos')
        ->where(function($query) use ($instituciones) {
            foreach ($instituciones as $cue) {
                $query->orWhere('CUECOMPLETO', 'like', $cue . '%');
            }
        })
        //->distinct()
        ->orderBy('CUECOMPLETO', 'ASC')
        ->get();

        $datos=array(
            'mensajeError'=>"",
            'infoDatos'=>$origen,
            'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
        );
         //dd($infoPlaza);
         return view('bandeja.POF.pofCantidadInicial',$datos);
    }


    public function consultas_pof_agrupadas($Nivel){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');

        //estatal
        $instituciones = DB::table('tb_institucion_extension')
        ->select('CUECOMPLETO')
        ->where('Nivel', $Nivel)
        ->where('EsPrivada', 'N')
        //->where('Nivel', 'not like', 'Priv%') // Excluir los que comienzan con "Priv"
        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
        ->groupBy('CUECOMPLETO')
        ->distinct()
        ->get()
        ->pluck('CUECOMPLETO')
        ->toArray();
        
        //dd($instituciones);

        
    // Traer todas las aulas con la condición whereIn
    $AulasCargosCreados = RelCargoAulaDivModel::where(function($query) use ($instituciones) {
        foreach ($instituciones as $cue) {
            $query->orWhere('CUECOMPLETO', 'like', $cue . '%');
        }
    })->get();


    $agrupados = [];


    foreach ($AulasCargosCreados as $cargo) {

        $infoOrigen = DB::connection('DB7')->table('tb_origenes_cargos')->where('idOrigenCargo', $cargo->idOrigenCargo)->first();

        if ($infoOrigen) { 
            $valorBuscadoAgrupar = $infoOrigen->nombre_origen;  //el nombre tiene el id a comparar en cargos pof
            if (isset($agrupados[$valorBuscadoAgrupar])) {
                $agrupados[$valorBuscadoAgrupar] += 1;
            } else {
                $agrupados[$valorBuscadoAgrupar] = 1;
            }
        }
    }
   
    //dd($agrupados);
    $resultadoAgrupado = [];
    foreach ($agrupados as $nombre => $cantidad) {
        // Buscar el registro correspondiente
        $CargosOrigen = DB::connection('DB7')->table('tb_cargos_pof_origen')->where('idCargos_Pof_Origen', $nombre)->first();
    
        // Verificar si se encontró un resultado antes de acceder a la propiedad
        if ($CargosOrigen) {
            $resultadoAgrupado[] = ['nombre_origen' => $CargosOrigen->nombre_cargo_origen, 'cantidad' => $cantidad];
        } else {
            // Manejar el caso en el que no se encuentra el registro, si es necesario
            $resultadoAgrupado[] = ['nombre_origen' => 'No encontrado', 'cantidad' => $cantidad];
        }
    }
    
    //proceso de los privados
    $instituciones2 = DB::table('tb_institucion_extension')
        ->select('CUECOMPLETO')
        ->where('Nivel', $Nivel)
        ->where('EsPrivada', '=','S')
        //->where('Nivel', 'not like', 'Priv%') // Excluir los que comienzan con "Priv"
        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
        ->groupBy('CUECOMPLETO')
        ->distinct()
        ->get()
        ->pluck('CUECOMPLETO')
        ->toArray();
        
        //dd($instituciones);

        
    // Traer todas las aulas con la condición whereIn
    $AulasCargosCreados2 = RelCargoAulaDivModel::where(function($query) use ($instituciones2) {
        foreach ($instituciones2 as $cue2) {
            $query->orWhere('CUECOMPLETO', 'like', $cue2 . '%');
        }
    })->get();


    $agrupados2 = [];


    foreach ($AulasCargosCreados2 as $cargo2) {

        $infoOrigen2 = DB::connection('DB7')->table('tb_origenes_cargos')->where('idOrigenCargo', $cargo2->idOrigenCargo)->first();

        if ($infoOrigen2) { 
            $valorBuscadoAgrupar2 = $infoOrigen2->nombre_origen;  //el nombre tiene el id a comparar en cargos pof
            if (isset($agrupados2[$valorBuscadoAgrupar2])) {
                $agrupados2[$valorBuscadoAgrupar2] += 1;
            } else {
                $agrupados2[$valorBuscadoAgrupar2] = 1;
            }
        }
    }
   
    //dd($agrupados);
    $resultadoAgrupado2 = [];
    foreach ($agrupados2 as $nombre2 => $cantidad2) {
        // Buscar el registro correspondiente
        $CargosOrigen2 = DB::connection('DB7')->table('tb_cargos_pof_origen')->where('idCargos_Pof_Origen', $nombre2)->first();
    
        // Verificar si se encontró un resultado antes de acceder a la propiedad
        if ($CargosOrigen2) {
            $resultadoAgrupado2[] = ['nombre_origen' => $CargosOrigen2->nombre_cargo_origen, 'cantidad' => $cantidad2];
        } else {
            // Manejar el caso en el que no se encuentra el registro, si es necesario
            $resultadoAgrupado2[] = ['nombre_origen' => 'No encontrado', 'cantidad' => $cantidad2];
        }
    }
    // foreach ($resultadoAgrupado2 as &$elemento2) {
    //     foreach ($resultadoAgrupado as $elemento1) {
    //         // Si los nombres de origen coinciden, sumamos las cantidades
    //         if ($elemento2['nombre_origen'] === $elemento1['nombre_origen']) {
    //             $elemento2['cantidad'] += $elemento1['cantidad'];
    //             break; // Salimos del bucle interno ya que encontramos una coincidencia
    //         }
    //     }
    // }
    // unset($elemento2); 
    // Ahora puedes usar $resultadoAgrupado según necesites, por ejemplo, para mostrarlo en una vista
    //dd($resultadoAgrupado);


     usort($resultadoAgrupado, function ($a, $b) {
         return strcmp($a['nombre_origen'], $b['nombre_origen']);
     });

     // Ordenar $resultadoAgrupado2 alfabéticamente por 'nombre_origen'
     usort($resultadoAgrupado2, function ($a, $b) {
         return strcmp($a['nombre_origen'], $b['nombre_origen']);
     });
        $datos=array(
            'mensajeError'=>"",
            'infoDatos'=>$resultadoAgrupado,
            'infoDatos2'=>$resultadoAgrupado2,
            'NivelSeleccionado'=>$Nivel,
            'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
        );
         //dd($infoPlaza);
         return view('bandeja.POF.porCantidadInicialAgrupada',$datos);
    }

    public function consultaBajas(){
        $infoPofmh = DB::connection('DB7')->table('tb_pofmh')->whereIn('Condicion', [17, 25])->get();
        $datos=array(
            'mensajeError'=>"",
            'infoPofmh'=>$infoPofmh,
            'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
        );
         //dd($infoPlaza);
         return view('bandeja.POF.consultaBajas',$datos);
    }





    public function consultas_pof_agrupadas_ultima($Nivel)
    {
        set_time_limit(0);
        ini_set('memory_limit', '2028M');
    
        // Obtener instituciones estatales
        $instituciones = DB::table('tb_institucion_extension')
            ->select('CUECOMPLETO', 'Nombre_Institucion', 'Nivel')
            ->where('Nivel', $Nivel)
            ->where('EsPrivada', 'N')
            ->where('CUECOMPLETO', 'not like', '9500%')
            ->where('CUECOMPLETO', 'not like', '9999%')
            ->distinct()
            ->get()
            ->keyBy('CUECOMPLETO');
    
        // Obtener aulas con cargos creados
        $AulasCargosCreados = RelCargoAulaDivModel::where(function ($query) use ($instituciones) {
            foreach ($instituciones as $cue => $datos) {
                $query->orWhere('CUECOMPLETO', 'like', $cue . '%');
            }
        })->get();
    
        $agrupados = [];
        $detallesInstituciones = [];
    
        foreach ($AulasCargosCreados as $cargo) {
            $infoOrigen = DB::connection('DB7')->table('tb_origenes_cargos')
                ->where('idOrigenCargo', $cargo->idOrigenCargo)
                ->first();
    
            if ($infoOrigen) {
                $valorBuscadoAgrupar = $infoOrigen->nombre_origen;
    
                // Contabilizar cargos por origen
                if (isset($agrupados[$valorBuscadoAgrupar])) {
                    $agrupados[$valorBuscadoAgrupar] += 1;
                } else {
                    $agrupados[$valorBuscadoAgrupar] = 1;
                }
    
                // Registrar detalles de las instituciones
                if (!isset($detallesInstituciones[$valorBuscadoAgrupar])) {
                    $detallesInstituciones[$valorBuscadoAgrupar] = [];
                }
    
                $cue = $cargo->CUECOMPLETO;
                $institucion = $instituciones->get($cue);
    
                if ($institucion) {
                    $detallesInstituciones[$valorBuscadoAgrupar][] = [
                        'nombre_cargo' => $infoOrigen->nombre_origen,
                        'nombre_institucion' => $institucion->Nombre_Institucion,
                        'cuecompleto' => $cue,
                        'nivel' => $institucion->Nivel,
                    ];
                }
            }
        }
    
        // Mapear nombres de cargos
        $resultadoAgrupado = [];
        foreach ($agrupados as $nombre => $cantidad) {
            $CargosOrigen = DB::connection('DB7')->table('tb_cargos_pof_origen')
                ->where('idCargos_Pof_Origen', $nombre)
                ->first();
    
            if ($CargosOrigen) {
                $resultadoAgrupado[] = [
                    'nombre_origen' => $CargosOrigen->nombre_cargo_origen,
                    'cantidad' => $cantidad,
                ];
            } else {
                $resultadoAgrupado[] = [
                    'nombre_origen' => 'No encontrado',
                    'cantidad' => $cantidad,
                ];
            }
        }
    
        $datos = [
            'mensajeError' => "",
            'infoDatos' => $resultadoAgrupado,
            'detallesInstituciones' => $detallesInstituciones,
            'NivelSeleccionado' => $Nivel,
            'mensajeNAV' => 'Panel de Configuración de POF(Modalidad Horizontal)',
        ];
    
        return view('bandeja.POF.porCantidadAgrupadaEscuelaDetallePof', $datos);
    }
    
    












































    /*
public function consultas_pof_agrupadas($Nivel) {
    set_time_limit(0);
    ini_set('memory_limit', '2028M');

    // **Obtener instituciones públicas**
    $instituciones = DB::table('tb_institucion_extension')
        ->select('CUECOMPLETO')
        ->where('Nivel', $Nivel)
        ->where('EsPrivada', 'N')
        ->whereNotNull('CUECOMPLETO') 
        ->where('CUECOMPLETO', 'not like', '9500%') 
        ->where('CUECOMPLETO', 'not like', '9999%') 
        ->distinct()
        ->pluck('CUECOMPLETO')
        ->toArray();

    // **Procesar cargos públicos**
    $AulasCargosCreados = RelCargoAulaDivModel::where(function($query) use ($instituciones) {
        foreach ($instituciones as $cue) {
            $query->orWhere('CUECOMPLETO', 'like', $cue . '%');
        }
    })->get();

    $agrupados = [];
    foreach ($AulasCargosCreados as $cargo) {
        $infoOrigen = DB::connection('DB7')->table('tb_origenes_cargos')->where('idOrigenCargo', $cargo->idOrigenCargo)->first();
        if ($infoOrigen) {
            $valorBuscadoAgrupar = $infoOrigen->nombre_origen;
            if (!isset($agrupados[$valorBuscadoAgrupar])) {
                $agrupados[$valorBuscadoAgrupar] = 0;
            }
            $agrupados[$valorBuscadoAgrupar]++;
        }
    }

    $resultadoAgrupado = array_map(function ($nombre, $cantidad) {
        $CargosOrigen = DB::connection('DB7')->table('tb_cargos_pof_origen')->where('idCargos_Pof_Origen', $nombre)->first();
        return [
            'nombre_origen' => $CargosOrigen->nombre_cargo_origen ?? 'No encontrado',
            'cantidad' => $cantidad
        ];
    }, array_keys($agrupados), $agrupados);

    // **Obtener instituciones privadas**
    $instituciones2 = DB::table('tb_institucion_extension')
        ->select('CUECOMPLETO')
        ->where('Nivel', $Nivel)
        ->where('EsPrivada', '=', 'S')
        ->whereNotNull('CUECOMPLETO') 
        ->where('CUECOMPLETO', 'not like', '9500%') 
        ->where('CUECOMPLETO', 'not like', '9999%') 
        ->distinct()
        ->pluck('CUECOMPLETO')
        ->toArray();

    // **Procesar cargos privados**
    $AulasCargosCreados2 = RelCargoAulaDivModel::where(function($query) use ($instituciones2) {
        foreach ($instituciones2 as $cue2) {
            $query->orWhere('CUECOMPLETO', 'like', $cue2 . '%');
        }
    })->get();

    $agrupados2 = [];
    foreach ($AulasCargosCreados2 as $cargo2) {
        $infoOrigen2 = DB::connection('DB7')->table('tb_origenes_cargos')->where('idOrigenCargo', $cargo2->idOrigenCargo)->first();
        if ($infoOrigen2) {
            $valorBuscadoAgrupar2 = $infoOrigen2->nombre_origen;
            if (!isset($agrupados2[$valorBuscadoAgrupar2])) {
                $agrupados2[$valorBuscadoAgrupar2] = 0;
            }
            $agrupados2[$valorBuscadoAgrupar2]++;
        }
    }

    $resultadoAgrupado2 = array_map(function ($nombre2, $cantidad2) {
        $CargosOrigen2 = DB::connection('DB7')->table('tb_cargos_pof_origen')->where('idCargos_Pof_Origen', $nombre2)->first();
        return [
            'nombre_origen' => $CargosOrigen2->nombre_cargo_origen ?? 'No encontrado',
            'cantidad' => $cantidad2
        ];
    }, array_keys($agrupados2), $agrupados2);

    // **Ordenar resultados**
    usort($resultadoAgrupado, fn($a, $b) => strcmp($a['nombre_origen'], $b['nombre_origen']));
    usort($resultadoAgrupado2, fn($a, $b) => strcmp($a['nombre_origen'], $b['nombre_origen']));

    // **Preparar los datos para la vista**
    $datos = [
        'mensajeError' => "",
        'infoDatos' => $resultadoAgrupado,
        'infoDatos2' => $resultadoAgrupado2,
        'NivelSeleccionado' => $Nivel,
        'mensajeNAV' => 'Panel de Configuración de POF (Modalidad Horizontal)'
    ];

    return view('bandeja.POF.porCantidadInicialAgrupada', $datos);
}*/
























public function consultas_pof_agrupadas_detalle($Nivel) {
    set_time_limit(0);
    ini_set('memory_limit', '2028M');

    // **Obtener instituciones públicas**
    $instituciones = DB::table('tb_institucion_extension')
        ->select('CUECOMPLETO')
        ->where('Nivel', $Nivel)
        ->where('EsPrivada', 'N')
        ->whereNotNull('CUECOMPLETO') 
        ->where('CUECOMPLETO', 'not like', '9500%') 
        ->where('CUECOMPLETO', 'not like', '9999%') 
        ->distinct()
        ->pluck('CUECOMPLETO')
        ->toArray();

    // **Procesar cargos públicos**
    $AulasCargosCreados = RelCargoAulaDivModel::where(function($query) use ($instituciones) {
        foreach ($instituciones as $cue) {
            $query->orWhere('CUECOMPLETO', 'like', $cue . '%');
        }
    })->get();

    $agrupados = [];
    foreach ($AulasCargosCreados as $cargo) {
        $infoOrigen = DB::connection('DB7')->table('tb_origenes_cargos')->where('idOrigenCargo', $cargo->idOrigenCargo)->first();
        if ($infoOrigen) {
            $valorBuscadoAgrupar = $infoOrigen->nombre_origen;
            if (!isset($agrupados[$valorBuscadoAgrupar])) {
                $agrupados[$valorBuscadoAgrupar] = 0;
            }
            $agrupados[$valorBuscadoAgrupar]++;
        }
    }

    $resultadoAgrupado = array_map(function ($nombre, $cantidad) {
        $CargosOrigen = DB::connection('DB7')->table('tb_cargos_pof_origen')->where('idCargos_Pof_Origen', $nombre)->first();
        return [
            'nombre_origen' => $CargosOrigen->nombre_cargo_origen ?? 'No encontrado',
            'cantidad' => $cantidad
        ];
    }, array_keys($agrupados), $agrupados);

   
    // **Preparar los datos para la vista**
    $datos = [
        'mensajeError' => "",
        'infoDatos' => $resultadoAgrupado,
        'NivelSeleccionado' => $Nivel,
        'mensajeNAV' => 'Panel de Configuración de POF (Modalidad Horizontal)'
    ];

    return view('bandeja.POF.porCantidadInicialAgrupada_Detalle', $datos);
}


public function consultas_pof_detalladas($Nivel) {
    set_time_limit(0);
    ini_set('memory_limit', '2028M');

    // **Consulta para instituciones públicas (EsPrivada = 'N')**
    $instituciones = DB::table('tb_institucion_extension')
        ->select('CUECOMPLETO', 'Nombre_Institucion', 'Nivel')
        ->where('Nivel', $Nivel)
        ->where('EsPrivada', 'N')
        ->whereNotNull('CUECOMPLETO')
        ->where('CUECOMPLETO', 'not like', '9500%')
        ->where('CUECOMPLETO', 'not like', '9999%')
        ->distinct()
        ->get();

    $detalles = [];
    foreach ($instituciones as $institucion) {
        // Obtener cargos relacionados con cada institución
        $cargos = DB::connection('DB7')->table('tb_origenes_cargos')
            ->where('CUECOMPLETO', $institucion->CUECOMPLETO)
            ->get();

        foreach ($cargos as $cargo) {
            // Obtener el nombre del cargo
            $nombreCargo = DB::connection('DB7')->table('tb_cargos_pof_origen')
                ->where('idCargos_Pof_Origen', $cargo->nombre_origen)
                ->value('nombre_cargo_origen');

            // Agregar detalles al arreglo (sin usar operador `??`)
            $detalles[] = [
                'nombre_cargo' => isset($nombreCargo) ? $nombreCargo : 'No encontrado',
                'nombre_institucion' => $institucion->Nombre_Institucion,
                'cuecompleto' => $institucion->CUECOMPLETO,
                'nivel' => $institucion->Nivel,
            ];
        }
    }

    // Agrupar datos por `nombre_cargo` para la tabla de agrupados
    $agrupados = [];
    foreach ($detalles as $detalle) {
        $nombreCargo = $detalle['nombre_cargo'];
        if (!isset($agrupados[$nombreCargo])) {
            $agrupados[$nombreCargo] = 0;
        }
        $agrupados[$nombreCargo]++;
    }

    $infoDatos2 = [];
    if (!empty($agrupados)) {
        foreach ($agrupados as $nombreCargo => $cantidad) {
            $infoDatos2[] = [
                'nombre_origen' => $nombreCargo,
                'cantidad' => $cantidad,
            ];
        }
    } else {
        $infoDatos2[] = [
            'nombre_origen' => 'Sin datos',
            'cantidad' => 0,
        ];
    }

    $datos = [
        'mensajeError' => "",
        'infoDatos' => $detalles,
        'infoDatos2' => $infoDatos2,
        'NivelSeleccionado' => $Nivel,
        'mensajeNAV' => 'Panel de Configuración de POF (Modalidad Horizontal)'
    ];

    return view('bandeja.POF.porCantidadDetallada', $datos);
}



    public function consultas_pof_agrupadas_categoria($Nivel){
        set_time_limit(0);
        ini_set('memory_limit', '2028M');

        //estatal
        $instituciones = DB::table('tb_institucion_extension')
        ->select('CUECOMPLETO')
        ->where('Nivel', $Nivel)
        ->where('EsPrivada', 'N')
        ->where('Nivel', 'not like', 'Priv%') // Excluir los que comienzan con "Priv"
        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
        ->groupBy('CUECOMPLETO')
        ->distinct()
        ->get()
        ->pluck('CUECOMPLETO')
        ->toArray();
        
        //dd($instituciones);

        
    // Traer todas las aulas con la condición whereIn
    $AulasCargosCreados = RelCargoAulaDivModel::where(function($query) use ($instituciones) {
        foreach ($instituciones as $cue) {
            $query->orWhere('CUECOMPLETO', 'like', $cue . '%');
        }
    })->get();


    $agrupados = [];


    foreach ($AulasCargosCreados as $cargo) {

        $infoOrigen = DB::connection('DB7')->table('tb_origenes_cargos')->where('idOrigenCargo', $cargo->idOrigenCargo)->first();

        if ($infoOrigen) { 
            $valorBuscadoAgrupar = $infoOrigen->nombre_origen;  //el nombre tiene el id a comparar en cargos pof
            if (isset($agrupados[$valorBuscadoAgrupar])) {
                $agrupados[$valorBuscadoAgrupar] += 1;
            } else {
                $agrupados[$valorBuscadoAgrupar] = 1;
            }
        }
    }
   
    //dd($agrupados);
    $resultadoAgrupado = [];
    foreach ($agrupados as $nombre => $cantidad) {
        // Buscar el registro correspondiente
        $CargosOrigen = DB::connection('DB7')->table('tb_cargos_pof_origen')->where('idCargos_Pof_Origen', $nombre)->first();
    
        // Verificar si se encontró un resultado antes de acceder a la propiedad
        if ($CargosOrigen) {
            $resultadoAgrupado[] = ['nombre_origen' => $CargosOrigen->nombre_cargo_origen, 'cantidad' => $cantidad];
        } else {
            // Manejar el caso en el que no se encuentra el registro, si es necesario
            $resultadoAgrupado[] = ['nombre_origen' => 'No encontrado', 'cantidad' => $cantidad];
        }
    }
    
    //proceso de los privados
    $instituciones2 = DB::table('tb_institucion_extension')
        ->select('CUECOMPLETO')
        ->where('Nivel', $Nivel)
        //->where('EsPrivada', 'N')
        //->where('Nivel', 'not like', 'Priv%') // Excluir los que comienzan con "Priv"
        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
        ->groupBy('CUECOMPLETO')
        ->distinct()
        ->get()
        ->pluck('CUECOMPLETO')
        ->toArray();
        
        //dd($instituciones);

        
    // Traer todas las aulas con la condición whereIn
    $AulasCargosCreados2 = RelCargoAulaDivModel::where(function($query) use ($instituciones2) {
        foreach ($instituciones2 as $cue) {
            $query->orWhere('CUECOMPLETO', 'like', $cue . '%');
        }
    })->get();


    $agrupados2 = [];


    foreach ($AulasCargosCreados2 as $cargo) {

        $infoOrigen = DB::connection('DB7')->table('tb_origenes_cargos')->where('idOrigenCargo', $cargo->idOrigenCargo)->first();

        if ($infoOrigen) { 
            $valorBuscadoAgrupar2 = $infoOrigen->nombre_origen;  //el nombre tiene el id a comparar en cargos pof
            if (isset($agrupados2[$valorBuscadoAgrupar2])) {
                $agrupados2[$valorBuscadoAgrupar2] += 1;
            } else {
                $agrupados2[$valorBuscadoAgrupar2] = 1;
            }
        }
    }
   
    //dd($agrupados);
    $resultadoAgrupado2 = [];
    foreach ($agrupados2 as $nombre => $cantidad) {
        // Buscar el registro correspondiente
        $CargosOrigen = DB::connection('DB7')->table('tb_cargos_pof_origen')->where('idCargos_Pof_Origen', $nombre)->first();
    
        // Verificar si se encontró un resultado antes de acceder a la propiedad
        if ($CargosOrigen) {
            $resultadoAgrupado2[] = ['nombre_origen' => $CargosOrigen->nombre_cargo_origen, 'cantidad' => $cantidad];
        } else {
            // Manejar el caso en el que no se encuentra el registro, si es necesario
            $resultadoAgrupado2[] = ['nombre_origen' => 'No encontrado', 'cantidad' => $cantidad];
        }
    }

    // Ahora puedes usar $resultadoAgrupado según necesites, por ejemplo, para mostrarlo en una vista
    //dd($resultadoAgrupado);

        $datos=array(
            'mensajeError'=>"",
            'infoDatos'=>$resultadoAgrupado,
            'infoDatos2'=>$resultadoAgrupado2,
            'NivelSeleccionado'=>$Nivel,
            'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
        );
         //dd($infoPlaza);
         return view('bandeja.POF.porCantidadInicialAgrupada',$datos);
    }
/*
    public function comparacionLiqPof()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2028M');

        $Cargos = PofmhCargoSalariales::all();
        $resultados = [];
        foreach($Cargos as $c){
            //realizo la consulta por su codigo y su tipo
            $volantes = DB::connection('DB7')->table('tb_pofmh AS t')
            ->join('tb_cargossalariales AS c', 'c.idCargo', '=', 't.Cargo')
            ->join('tb_situacionrevista AS s', 's.idSituacionRevista', '=', 't.SitRev')
            ->select('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->where('c.Codigo', '=', $c->Codigo)
            ->where('s.Descripcion', '=', 'VOLANTE')
            ->whereNotNull('Agente')
            ->whereNotNull('Codigo')
            ->groupBy('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->orderBy('Agente')
            ->get();
            $titular = DB::connection('DB7')->table('tb_pofmh AS t')
            ->join('tb_cargossalariales AS c', 'c.idCargo', '=', 't.Cargo')
            ->join('tb_situacionrevista AS s', 's.idSituacionRevista', '=', 't.SitRev')
            ->select('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->where('c.Codigo', '=', $c->Codigo)
            ->where('s.Descripcion', '=', 'TITULAR')
            ->whereNotNull('Agente')
            ->whereNotNull('Codigo')
            ->groupBy('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->orderBy('Agente')
            ->get();
            $suplente= DB::connection('DB7')->table('tb_pofmh AS t')
            ->join('tb_cargossalariales AS c', 'c.idCargo', '=', 't.Cargo')
            ->join('tb_situacionrevista AS s', 's.idSituacionRevista', '=', 't.SitRev')
            ->select('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->where('c.Codigo', '=', $c->Codigo)
            ->where('s.Descripcion', '=', 'SUPLENTE')
            ->whereNotNull('Agente')
            ->whereNotNull('Codigo')
            ->groupBy('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->orderBy('Agente')
            ->get();
            $interino= DB::connection('DB7')->table('tb_pofmh AS t')
            ->join('tb_cargossalariales AS c', 'c.idCargo', '=', 't.Cargo')
            ->join('tb_situacionrevista AS s', 's.idSituacionRevista', '=', 't.SitRev')
            ->select('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->where('c.Codigo', '=', $c->Codigo)
            ->where('s.Descripcion', '=', 'INTERINO')
            ->whereNotNull('Agente')
            ->whereNotNull('Codigo')
            ->groupBy('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->orderBy('Agente')
            ->get();
            $planta_permanente= DB::connection('DB7')->table('tb_pofmh AS t')
            ->join('tb_cargossalariales AS c', 'c.idCargo', '=', 't.Cargo')
            ->join('tb_situacionrevista AS s', 's.idSituacionRevista', '=', 't.SitRev')
            ->select('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->where('c.Codigo', '=', $c->Codigo)
            ->where('s.Descripcion', '=', 'PLANTA PERMANENTE')
            ->whereNotNull('Agente')
            ->whereNotNull('Codigo')
            ->groupBy('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->orderBy('Agente')
            ->get();
            $vinculado= DB::connection('DB7')->table('tb_pofmh AS t')
            ->join('tb_cargossalariales AS c', 'c.idCargo', '=', 't.Cargo')
            ->join('tb_situacionrevista AS s', 's.idSituacionRevista', '=', 't.SitRev')
            ->select('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->where('c.Codigo', '=', $c->Codigo)
            ->where('s.Descripcion', '=', 'VINCULADO Y/O P.TEMPORARIO')
            ->whereNotNull('Agente')
            ->whereNotNull('Codigo')
            ->groupBy('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->orderBy('Agente')
            ->get();

             // Calcular total
        $total = $titular->count() + $interino->count() + $suplente->count() + $volantes->count() + $planta_permanente->count() + $vinculado->count();

            $resultados[] = [
                'codigo' => $c->Codigo,
                'descripcion' => $c->Cargo,
                'titular' => $titular->count(),
                'interino' => $interino->count(),
                'suplente' => $suplente->count(),
                'volante' => $volantes->count(),
                'planta_permanente' => $planta_permanente->count(),
                'vinculado' => $vinculado->count(), 
                'total' => 0
            ];
        }

         
     
 
     // Pasar el array de resultados a la vista
     return view('bandeja.POF.comparacionLiqPof', ['cargos' => $resultados]);
    }
    
    */
    public function comparacionLiqPof()
{
    set_time_limit(0);
    ini_set('memory_limit', '2028M');

    $Cargos = PofmhCargoSalariales::all();
    $resultados = [];
    $resultadosLiq = [];
    
    $cueCompletoInstituciones = DB::table('tb_institucion_extension')
    ->where('EsPrivada', '<>', 'S') 
    ->where(function($query) {
        $query->where('CUECOMPLETO', 'not like', '9999%')
              ->where('CUECOMPLETO', 'not like', '950%');
    })
    ->pluck('CUECOMPLETO'); 
    //->whereIn('t.CUECOMPLETO', $cueCompletoInstituciones)

 
    foreach($Cargos as $c){
        // Realiza una sola consulta para obtener todos los tipos de situaciones en una sola operación
        $liquidacion = DB::connection('DB7')->table('tb_pofmh AS t')
            ->join('tb_cargossalariales AS c', 'c.idCargo', '=', 't.Cargo')
            ->join('tb_situacionrevista AS s', 's.idSituacionRevista', '=', 't.SitRev')
            ->select('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->where('c.Codigo', '=', $c->Codigo)
            ->whereNotNull('Agente')
            ->whereNotNull('Codigo')
            ->whereIn('t.CUECOMPLETO', $cueCompletoInstituciones)
            ->groupBy('Agente', 'c.Codigo', 'Unidad_Liquidacion', 's.Descripcion')
            ->get();
        
        // Inicializa los contadores
        $contadores = [
            'titular' => 0,
            'interino' => 0,
            'suplente' => 0,
            'volante' => 0,
            'planta_permanente' => 0,
            'vinculado' => 0,
        ];

        // Cuenta las ocurrencias de cada tipo
        foreach ($liquidacion as $item) {
            switch ($item->Descripcion) {
                case 'TITULAR':
                    $contadores['titular']++;
                    break;
                case 'INTERINO':
                    $contadores['interino']++;
                    break;
                case 'SUPLENTE':
                    $contadores['suplente']++;
                    break;
                case 'VOLANTE':
                    $contadores['volante']++;
                    break;
                case 'PLANTA PERMANENTE':
                    $contadores['planta_permanente']++;
                    break;
                case 'VINCULADO Y/O P.TEMPORARIO':
                    $contadores['vinculado']++;
                    break;
            }
        }

        // Agrega los resultados al array
        $resultados[] = [
            'codigo' => $c->Codigo,
            'descripcion' => $c->Cargo,
            'titular' => $contadores['titular'],
            'interino' => $contadores['interino'],
            'titinter'=>$contadores['titular'] + $contadores['interino'],
            'suplente' => $contadores['suplente'],
            'volante' => $contadores['volante'],
            'planta_permanente' => $contadores['planta_permanente'],
            'vinculado' => $contadores['vinculado'],
            'total' => $contadores['titular'] + $contadores['interino'] + $contadores['suplente'] + $contadores['volante'] + $contadores['planta_permanente'] + $contadores['vinculado'], 
        ];
    }
    //fin proceso sage

    //AHORA REALIZO EL PROCESO DE LIQUIDACION
foreach($Cargos as $c){
        // Realiza una sola consulta para obtener todos los tipos de situaciones en una sola operación
        $liquidacionConex = DB::connection('DB7')->table('liqfeb2024 AS t')
            ->join('tb_cargossalariales AS c', 'c.Codigo', '=', 't.Codigo_Nomenclador')
            ->join('tb_situacionrevista AS s', 's.Descripcion', '=', 't.Descuento_Plan')
            ->select('Documento', 'c.Codigo', 'Escuela', 's.Descripcion')
            ->where('c.Codigo', '=', $c->Codigo)
            ->where('t.Nivel', '<>', 'PRIVA')
            ->whereNotNull('Documento')
            ->groupBy('Documento', 'c.Codigo', 'Escuela', 's.Descripcion')
            ->get();
        //dd($liquidacionConex);
        // Inicializa los contadores
        $contadoresConex = [
            'titular' => 0,
            'interino' => 0,
            'suplente' => 0,
            'volante' => 0,
            'planta_permanente' => 0,
            'vinculado' => 0,
        ];

        // Cuenta las ocurrencias de cada tipo
        foreach ($liquidacionConex as $item) {
            switch ($item->Descripcion) {
                case 'TITULAR':
                    $contadoresConex['titular']++;
                    break;
                case 'INTERINO':
                    $contadoresConex['interino']++;
                    break;
                case 'SUPLENTE':
                    $contadoresConex['suplente']++;
                    break;
                case 'VOLANTE':
                    $contadoresConex['volante']++;
                    break;
                case 'PLANTA PERMANENTE':
                    $contadoresConex['planta_permanente']++;
                    break;
                case 'VINCULADO Y/O P.TEMPORARIO':
                    $contadoresConex['vinculado']++;
                    break;
            }
        }

        // Agrega los resultados al array
        $resultadosLiq[] = [
            'codigo' => $c->Codigo,
            'descripcion' => $c->Cargo,
            'titular' => $contadoresConex['titular'],
            'interino' => $contadoresConex['interino'],
            'titinter'=>$contadoresConex['titular'] + $contadoresConex['interino'],
            'suplente' => $contadoresConex['suplente'],
            'volante' => $contadoresConex['volante'],
            'planta_permanente' => $contadoresConex['planta_permanente'],
            'vinculado' => $contadoresConex['vinculado'],
            'total' => $contadoresConex['titular'] + $contadoresConex['interino'] + $contadoresConex['suplente'] + $contadoresConex['volante'] + $contadoresConex['planta_permanente'] + $contadoresConex['vinculado'], 
        ];
    }
    // Pasar el array de resultados a la vista
    return view('bandeja.POF.comparacionLiqPof', [
            'cargos' => $resultados,
            'cargosLiq' => $resultadosLiq]);
}


    
public function generarExcel(Request $request)
{
// Verificar si se ha recibido un archivo
if ($request->hasFile('file')) {
    // Obtener el archivo
    $file = $request->file('file');

    // Guardar el archivo en la ubicación deseada
    $path = $file->storeAs('public/POF', $file->getClientOriginalName());

    return response()->json(['success' => 'Archivo guardado exitosamente']);
}

return response()->json(['error' => 'No se ha recibido ningún archivo'], 400);
}
  
    

public function obtenerAulasPOFMH($cue, $idOrigen){
    try {
        //consultar la pofmh
        $aulasAsociadas = PofmhModel::select('Aula', 'Division', 'Turno', DB::raw('SUM(Horas) as total_horas'))
        ->where('CUECOMPLETO', $cue)
        ->where('Origen', $idOrigen)
        ->groupBy('Aula', 'Division', 'Turno')
        ->orderBy('Turno')
        ->orderBy('Aula')
        ->orderBy('Division')
        ->get();

        if ($aulasAsociadas->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No se encontraron aulas asociadas.']);
        }

        //paso mas datos
        $turnos = DB::table('tb_turnos_usuario')->get();
        $Aulas = PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        return response()->json([
            'success' => true, 
            'aulasAsociadas' => 
            $aulasAsociadas,
            'Turnos'=>$turnos,
            'Aulas'=>$Aulas,
            'Divisiones'=>$Divisiones
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error al obtener las aulas asociadas.']);
    }
}
    
    

    //asistencias
    public function asistencias_pofmh($idExtension){
        ini_set('memory_limit', '2028M');
        set_time_limit(0);
        //busco la institucion para cargar sus datos
        $institucionExtension = InstitucionExtensionModel::where('idInstitucionExtension', $idExtension)
        ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->leftJoin('tb_nivelesensenanza', 'tb_institucion_extension.Nivel', '=', 'tb_nivelesensenanza.NivelEnsenanza') // Usa leftJoin para traer resultados sin importar si Nivel es null
        ->select('tb_institucion_extension.*', 'tb_turnos_usuario.*', 'tb_nivelesensenanza.*')
        ->first();
        $Motivos =   DB::table('tb_motivos')->get();
        $listadoFiltro = $numerosParaInsertar = [2,6,12,14,15,16,17,18,19,25];
        //dd($institucionExtension);
        //traigo su nuevo POFMH
        $pofmh = PofmhModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
        ->select(
            'idPofmh','orden','Agente','ApeNom','EspCur','Aula','Division','Horas','Origen'
            )
        ->where('Turno', $institucionExtension->idTurnoUsuario)
        ->whereNotIn('Condicion', $listadoFiltro)
        ->orderBy('orden','ASC') // Usa UNSIGNED para números enteros
        ->get();

        $fechassistema = PofmhCalendarioModel::where('CUECOMPLETO','000000000')->get();
        $fechaescuelas = PofmhCalendarioModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)->get();
        //dd($fechassistema);
        /*
        $aulasRelacionadas = DB::connection('DB7')->table('tb_padt')
        ->where('idOrigenCargo', $origenUnico)
        ->join('tb_aulas', 'tb_padt.idAula', '=', 'tb_aulas.idAula')
        ->select('tb_aulas.idAula', 'tb_aulas.nombre_aula')
        ->distinct()
        ->get();
        DB::connection('DB7')->table('tb_padt')
        ->where('idOrigenCargo', $origenUnico)
        ->join('tb_divisiones', 'tb_padt.idDivision', '=', 'tb_divisiones.idDivision')
        ->select('tb_divisiones.idDivision', 'tb_divisiones.nombre_division')
        ->distinct()
        ->get();*/
        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $NovedadesExtras = PofmhNovedadesExtras::all();
        //probando turnos solicitados simples
        // Obtener el CUE enviado desde el frontend
        $valCUE = $institucionExtension->CUECOMPLETO;  
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;
        //traigo las asistencias
        $asistencias = AsistenciaModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)
        ->where('Turno', $institucionExtension->idTurnoUsuario)
        ->whereMonth('FechaInicioAsist', $mesActual) //aplico la regla de mes actual
        ->whereYear('FechaInicioAsist', $anioActual) //anio actual
        ->get();
        
        //dd($Divisiones);
        $datos=array(
            'mensajeError'=>"",
            'institucionExtension'=>$institucionExtension,
            'infoPofMH'=>$pofmh,
            'Divisiones'=>$Divisiones,
            'Aulas'=>$Aulas,
            'NovedadesExtras'=>$NovedadesExtras,
            'Asistencias'=>$asistencias,
            'Motivos'=>$Motivos,
            'fechassistema'=>$fechassistema,
            'fechaescuelas'=>$fechaescuelas,
            'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
        );
        
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">POF Horizontal</a></li>
            <li class="breadcrumb-item active"><a href="'.route('asistencias_pofmh',$idExtension).'">Modelo de POF Nuevo</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.POF.asistencias_pofmh',$datos);
    }
    
    //asistencias novedad nueva
    public function asistencias_modelo_pofmh($idExtension){
        ini_set('memory_limit', '2028M');
        set_time_limit(0);
        //busco la institucion para cargar sus datos
        $institucionExtension = InstitucionExtensionModel::where('idInstitucionExtension', $idExtension)
        ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->leftJoin('tb_nivelesensenanza', 'tb_institucion_extension.Nivel', '=', 'tb_nivelesensenanza.NivelEnsenanza') // Usa leftJoin para traer resultados sin importar si Nivel es null
        ->select('tb_institucion_extension.*', 'tb_turnos_usuario.*', 'tb_nivelesensenanza.*')
        ->first();
        $Motivos =   DB::table('tb_motivos')->get();
        $listadoFiltro = $numerosParaInsertar = [2,6,12,14,15,16,17,18,19,25];
        //dd($institucionExtension);
        //traigo su nuevo POFMH
        $pofmh = PofmhModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
        ->select(
            'idPofmh','orden','Agente','ApeNom','EspCur','Aula','Division','Horas','Origen'
            )
        ->where('Turno', $institucionExtension->idTurnoUsuario)
        ->whereNotIn('Condicion', $listadoFiltro)
        ->orderBy('orden','ASC') // Usa UNSIGNED para números enteros
        ->get();

        $fechassistema = PofmhCalendarioModel::where('CUECOMPLETO','000000000')->get();
        $fechaescuelas = PofmhCalendarioModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)->get();
        //dd($fechassistema);
        /*
        $aulasRelacionadas = DB::connection('DB7')->table('tb_padt')
        ->where('idOrigenCargo', $origenUnico)
        ->join('tb_aulas', 'tb_padt.idAula', '=', 'tb_aulas.idAula')
        ->select('tb_aulas.idAula', 'tb_aulas.nombre_aula')
        ->distinct()
        ->get();
        DB::connection('DB7')->table('tb_padt')
        ->where('idOrigenCargo', $origenUnico)
        ->join('tb_divisiones', 'tb_padt.idDivision', '=', 'tb_divisiones.idDivision')
        ->select('tb_divisiones.idDivision', 'tb_divisiones.nombre_division')
        ->distinct()
        ->get();*/
        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $NovedadesExtras = PofmhNovedadesExtras::all();
        //probando turnos solicitados simples
        // Obtener el CUE enviado desde el frontend
        $valCUE = $institucionExtension->CUECOMPLETO;  
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;
        //traigo las asistencias
        $asistencias = AsistenciaModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)
        ->where('Turno', $institucionExtension->idTurnoUsuario)
        ->whereMonth('FechaInicioAsist', $mesActual) //aplico la regla de mes actual
        ->whereYear('FechaInicioAsist', $anioActual) //anio actual
        ->get();
        
        //dd($Divisiones);
        $datos=array(
            'mensajeError'=>"",
            'institucionExtension'=>$institucionExtension,
            'infoPofMH'=>$pofmh,
            'Divisiones'=>$Divisiones,
            'Aulas'=>$Aulas,
            'NovedadesExtras'=>$NovedadesExtras,
            'Asistencias'=>$asistencias,
            'Motivos'=>$Motivos,
            'fechassistema'=>$fechassistema,
            'fechaescuelas'=>$fechaescuelas,
            'mensaje'=>"algo de mensaje",
            'Novedades'=>null,
            'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
        );
        
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">POF Horizontal</a></li>
            <li class="breadcrumb-item active"><a href="'.route('asistencias_pofmh',$idExtension).'">Modelo de POF Nuevo</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.POF.asistencia_modelo_pof',$datos);
    }

    public function buscarPofmh(Request $request, $idExtension) {
        ini_set('memory_limit', '2028M');
        set_time_limit(0);
        $query = $request->input('query');
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $institucionExtension = InstitucionExtensionModel::where('idInstitucionExtension', $idExtension)
            ->first();
    
        $fechassistema = PofmhCalendarioModel::where('CUECOMPLETO','000000000')
        ->get()
        ->toArray();
        
        $fechaescuelas = PofmhCalendarioModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
        ->get()
        ->toArray();
            

        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $NovedadesExtras = PofmhNovedadesExtras::all();
        //probando turnos solicitados simples
        // Obtener el CUE enviado desde el frontend
        $valCUE = $institucionExtension->CUECOMPLETO; 

        $listadoFiltro = $numerosParaInsertar = [2,6,12,14,15,16,17,18,19,25];
        $pofmh = PofmhModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('ApeNom', 'LIKE', '%' . $query . '%')
                             ->orWhere('Agente', 'LIKE', '%' . $query . '%');
            })
            ->whereNotIn('Condicion', $listadoFiltro)
            ->select(
                'idPofmh','orden','Agente','ApeNom','EspCur','Aula','Division','Horas'
                )
            ->orderBy('orden', 'ASC')
            ->get();

        $asistencias = AsistenciaModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->whereYear('FechaInicioAsist', $currentYear) //usamos el mes y anio actual para traer las asistencias
            ->whereMonth('FechaInicioAsist', $currentMonth)
            ->get();

        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();

        return response()->json([
            'pofmh' => $pofmh,
            'asistencias' => $asistencias,
            'Divisiones'=>$Divisiones,
            'Aulas'=>$Aulas,
            'fechassistema'=>$fechassistema,
            'fechaescuelas'=>$fechaescuelas
        ]);
    }

    public function buscarPofmhCompleto(Request $request, $idExtension) {
        ini_set('memory_limit', '2028M');
        set_time_limit(0);
        $query = $request->input('query');
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $institucionExtension = InstitucionExtensionModel::where('idInstitucionExtension', $idExtension)
            ->first();
    
        $fechassistema = PofmhCalendarioModel::where('CUECOMPLETO','000000000')->get()->toArray();
        $fechaescuelas = PofmhCalendarioModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)->get()->toArray();
            
        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $NovedadesExtras = PofmhNovedadesExtras::all();
        //probando turnos solicitados simples
        // Obtener el CUE enviado desde el frontend
        $valCUE = $institucionExtension->CUECOMPLETO; 

        $listadoFiltro = $numerosParaInsertar = [2,6,12,14,15,16,17,18,19,25];

        if($query === "1"){
            $pofmh = PofmhModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->whereNotIn('Condicion', $listadoFiltro)
            ->select(
                'idPofmh','orden','Agente','ApeNom','EspCur','Aula','Division','Horas'
                )
            ->orderBy('orden', 'ASC')
            ->get();
        }else{
            $pofmh = PofmhModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->select(
                'idPofmh','orden','Agente','ApeNom','EspCur','Aula','Division','Horas'
                )
            ->orderBy('orden', 'ASC')
            ->get();
        }
        
        /*
        $asistencias = AsistenciaModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->get();
        */

         // Filtrar asistencias por el mes y año actual
        $asistencias = AsistenciaModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
        ->where('Turno', $institucionExtension->idTurnoUsuario)
        ->whereYear('FechaInicioAsist', $currentYear) //usamos el mes y anio actual para traer las asistencias
        ->whereMonth('FechaInicioAsist', $currentMonth)
        ->get();


            $Aulas =   PofmhAulas::all();
            $Divisiones = PofmhDivisiones::all();

            return response()->json([
                'pofmh' => $pofmh,
                'asistencias' => $asistencias,
                'Divisiones'=>$Divisiones,
                'Aulas'=>$Aulas,
                'fechassistema'=>$fechassistema,
                'fechaescuelas'=>$fechaescuelas
            ]);
    }

 public function colocarAsistncia(Request $request)
{   
    $tipo=1;
    switch($request->status){
        case 'P': $tipo=1; break;
        case 'FJ': $tipo=2; break;
        case 'R': $tipo=3; break;
        case 'L': $tipo=4; break;
        case 'O': $tipo=5; break;
        case 'A': $tipo=6; break;
        case 'C': $tipo=8; break;
        case 'FI': $tipo=7; break;
    }
    try {
        // Obtener el día, mes y año de FechaInicio
        $fechaInicio = Carbon::parse($request->FechaInicio);
        $dia = $fechaInicio->day;
        $mes = $fechaInicio->month;
        $anio = $fechaInicio->year;

        //evaluo cada caso
        switch($tipo){
            case 6:
                //debo borrar el dia cargado
                AsistenciaModel::where('dia', (int)$request->diaSeleccionado) // Convertir a entero
                ->where('mes', (int)$mes) // Asegúrate de convertir el mes también
                ->where('anio', (int)$anio) // Asegúrate de convertir el año
                ->where('idPofmh', (int)$request->idPofmh) // Convertir a entero si es necesario
                ->delete();

                return response()->json(['message' => 'Asistencia eliminada con éxito']);
                break;
            case 4:
                // Obtener las fechas de inicio y fin de la licencia
                $fechaInicio = Carbon::parse($request->FechaInicio);
                $fechaFin = Carbon::parse($request->FechaHasta);

                // Ciclo para iterar desde FechaInicio hasta FechaHasta
                while ($fechaInicio <= $fechaFin) {
                    // Verificar que el día no sea sábado (6) ni domingo (0)
                    if (!$fechaInicio->isWeekend()) {
                        // Crear una nueva entrada en el modelo de asistencia solo si es día laborable
                        $asistencia = new AsistenciaModel();
                        $asistencia->idPofmh = $request->idPofmh;
                        $asistencia->Agente = $request->agente;
                        $asistencia->dia = $fechaInicio->day;
                        $asistencia->mes = $fechaInicio->month;
                        $asistencia->anio = $fechaInicio->year;
                        $asistencia->tipoAsistencia = $tipo;  // Tipo 4 para licencia
                        $asistencia->CUECOMPLETO = $request->cue;
                        $asistencia->Turno = $request->turno;
                        $asistencia->FechaInicioAsist = $request->FechaInicio;
                        $asistencia->FechaHastaAsist = $request->FechaHasta;

                        $asistencia->save();
                    }

                    // Incrementar la fecha en un día
                    $fechaInicio->addDay();
                }

                return response()->json(['message' => 'Asistencia de licencia registrada exitosamente']);
                break;
            case 8:
                // Eliminar asistencia en el rango de fechas
                $fechaFin = Carbon::parse($request->FechaHasta);

                while ($fechaInicio <= $fechaFin) {
                    if (!$fechaInicio->isWeekend()) {
                        AsistenciaModel::where('idPofmh', $request->idPofmh)
                            ->where('dia', $fechaInicio->day)
                            ->where('mes', $fechaInicio->month)
                            ->where('anio', $fechaInicio->year)
                            ->where('tipoAsistencia', 4) // Asegurarse de eliminar solo registros de licencia
                            ->delete();
                    }
                    $fechaInicio->addDay();
                }

                return response()->json(['message' => 'Asistencia de licencia eliminada exitosamente']);
                break;
            case 1:
            case 2:
            case 3:
            case 5:
            case 7:
                //primero borro por si hay algo con esa misma fecha, osea, si esta ocupado
                AsistenciaModel::where('dia', (int)$request->diaSeleccionado) // Convertir a entero
                ->where('mes', (int)$mes) // Asegúrate de convertir el mes también
                ->where('anio', (int)$anio) // Asegúrate de convertir el año
                ->where('idPofmh', (int)$request->idPofmh) // Convertir a entero si es necesario
                ->delete();
                
                    // Crear una nueva entrada en el modelo de asistencia
                $asistencia = new AsistenciaModel();
                $asistencia->idPofmh = $request->idPofmh;
                $asistencia->Agente = $request->agente;
                $asistencia->dia = $request->diaSeleccionado;
                $asistencia->mes = $mes;
                $asistencia->anio = $anio;
                $asistencia->tipoAsistencia = $tipo;  // Usar el valor del request
                $asistencia->CUECOMPLETO = $request->cue;
                $asistencia->Turno = $request->turno;
                $asistencia->FechaInicioAsist = $request->FechaInicio;
                $asistencia->FechaHastaAsist = $request->FechaHasta;

            $asistencia->save();
            return response()->json(['message' => 'Asistencia actualizada con éxito']);
        }


       
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


//borrar todo esto despues de 15 dias
    //asistencias
    public function asistencias_pofmh_anterior($idExtension){
        ini_set('memory_limit', '2028M');
        set_time_limit(0);
        //busco la institucion para cargar sus datos
        $institucionExtension = InstitucionExtensionModel::where('idInstitucionExtension', $idExtension)
        ->Join('tb_turnos_usuario', 'tb_turnos_usuario.idTurnoUsuario', '=', 'tb_institucion_extension.idTurnoUsuario')
        ->leftJoin('tb_nivelesensenanza', 'tb_institucion_extension.Nivel', '=', 'tb_nivelesensenanza.NivelEnsenanza') // Usa leftJoin para traer resultados sin importar si Nivel es null
        ->select('tb_institucion_extension.*', 'tb_turnos_usuario.*', 'tb_nivelesensenanza.*')
        ->first();
        $Motivos =   DB::table('tb_motivos')->get();
        $listadoFiltro = $numerosParaInsertar = [2,6,12,14,15,16,17,18,19,25];
        //dd($institucionExtension);
        //traigo su nuevo POFMH
        $pofmh = PofmhModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
        ->select(
            'idPofmh','orden','Agente','ApeNom','EspCur','Aula','Division','Horas','Origen'
            )
        ->where('Turno', $institucionExtension->idTurnoUsuario)
        ->whereNotIn('Condicion', $listadoFiltro)
        ->orderBy('orden','ASC') // Usa UNSIGNED para números enteros
        ->get();

        $fechassistema = PofmhCalendarioModel::where('CUECOMPLETO','000000000')->get();
        $fechaescuelas = PofmhCalendarioModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)->get();
        //dd($fechassistema);
        /*
        $aulasRelacionadas = DB::connection('DB7')->table('tb_padt')
        ->where('idOrigenCargo', $origenUnico)
        ->join('tb_aulas', 'tb_padt.idAula', '=', 'tb_aulas.idAula')
        ->select('tb_aulas.idAula', 'tb_aulas.nombre_aula')
        ->distinct()
        ->get();
        DB::connection('DB7')->table('tb_padt')
        ->where('idOrigenCargo', $origenUnico)
        ->join('tb_divisiones', 'tb_padt.idDivision', '=', 'tb_divisiones.idDivision')
        ->select('tb_divisiones.idDivision', 'tb_divisiones.nombre_division')
        ->distinct()
        ->get();*/
        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $NovedadesExtras = PofmhNovedadesExtras::all();
        //probando turnos solicitados simples
        // Obtener el CUE enviado desde el frontend
        $valCUE = $institucionExtension->CUECOMPLETO;  
        $mesAnterior = Carbon::now()->subMonth()->month;
        $anioActual = Carbon::now()->year;
        //traigo las asistencias
        $asistencias = AsistenciaModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)
        ->where('Turno', $institucionExtension->idTurnoUsuario)
        ->whereMonth('FechaInicioAsist', $mesAnterior) //aplico la regla de mes actual
        ->whereYear('FechaInicioAsist', $anioActual) //anio actual
        ->get();
        //traigo las asistencias
        
        //dd($asistencias);
        //dd($Divisiones);
        $datos=array(
            'mensajeError'=>"",
            'institucionExtension'=>$institucionExtension,
            'infoPofMH'=>$pofmh,
            'Divisiones'=>$Divisiones,
            'Aulas'=>$Aulas,
            'NovedadesExtras'=>$NovedadesExtras,
            'Asistencias'=>$asistencias,
            'Motivos'=>$Motivos,
            'fechassistema'=>$fechassistema,
            'fechaescuelas'=>$fechaescuelas,
            'mensajeNAV'=>'Panel de Configuración de POF(Modalidad Horizontal)'
        );
        
        $ruta ='
            <li class="breadcrumb-item active"><a href="#">POF Horizontal</a></li>
            <li class="breadcrumb-item active"><a href="'.route('asistencias_pofmh_anterior',$idExtension).'">POF Anterior</a></li>
            '; 
            session(['ruta' => $ruta]);
        return view('bandeja.POF.noviembre.asistencias_pofmh_anterior',$datos);
    }
    
    
    public function buscarPofmh_nov(Request $request, $idExtension) {
        ini_set('memory_limit', '2028M');
        set_time_limit(0);
        $query = $request->input('query');
        $mesAnterior = Carbon::now()->subMonth()->month;
        $anioActual = Carbon::now()->year;
        
        $institucionExtension = InstitucionExtensionModel::where('idInstitucionExtension', $idExtension)
            ->first();
    
            $fechassistema = PofmhCalendarioModel::where('CUECOMPLETO','000000000')->get()->toArray();
            $fechaescuelas = PofmhCalendarioModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)->get()->toArray();
            

        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $NovedadesExtras = PofmhNovedadesExtras::all();
        //probando turnos solicitados simples
        // Obtener el CUE enviado desde el frontend
        $valCUE = $institucionExtension->CUECOMPLETO; 

        $listadoFiltro = $numerosParaInsertar = [2,6,12,14,15,16,17,18,19,25];
        $pofmh = PofmhModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('ApeNom', 'LIKE', '%' . $query . '%')
                             ->orWhere('Agente', 'LIKE', '%' . $query . '%');
            })
            ->whereNotIn('Condicion', $listadoFiltro)
            ->select(
                'idPofmh','orden','Agente','ApeNom','EspCur','Aula','Division','Horas'
                )
            ->orderBy('orden', 'ASC')
            ->get();
            $asistencias = AsistenciaModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->whereYear('FechaInicioAsist', $anioActual) //usamos el mes y anio actual para traer las asistencias
            ->whereMonth('FechaInicioAsist', $mesAnterior)
            ->get();

            $Aulas =   PofmhAulas::all();
            $Divisiones = PofmhDivisiones::all();

            return response()->json([
                'pofmh' => $pofmh,
                'asistencias' => $asistencias,
                'Divisiones'=>$Divisiones,
                'Aulas'=>$Aulas,
                'fechassistema'=>$fechassistema,
                'fechaescuelas'=>$fechaescuelas,
                'mes'=>$mesAnterior
            ]);
    }

    public function buscarPofmhCompleto_nov(Request $request, $idExtension) {
        ini_set('memory_limit', '2028M');
        set_time_limit(0);
        $query = $request->input('query');
        $currentMonth = Carbon::now()->subMonth()->month;
        $currentYear = Carbon::now()->year;

        $institucionExtension = InstitucionExtensionModel::where('idInstitucionExtension', $idExtension)
            ->first();
    
        $fechassistema = PofmhCalendarioModel::where('CUECOMPLETO','000000000')->get()->toArray();
        $fechaescuelas = PofmhCalendarioModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)->get()->toArray();
            
        $Aulas =   PofmhAulas::all();
        $Divisiones = PofmhDivisiones::all();
        $NovedadesExtras = PofmhNovedadesExtras::all();
        //probando turnos solicitados simples
        // Obtener el CUE enviado desde el frontend
        $valCUE = $institucionExtension->CUECOMPLETO; 

        $listadoFiltro = $numerosParaInsertar = [2,6,12,14,15,16,17,18,19,25];

        if($query === "1"){
            $pofmh = PofmhModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->whereNotIn('Condicion', $listadoFiltro)
            ->select(
                'idPofmh','orden','Agente','ApeNom','EspCur','Aula','Division','Horas'
                )
            ->orderBy('orden', 'ASC')
            ->get();
        }else{
            $pofmh = PofmhModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->select(
                'idPofmh','orden','Agente','ApeNom','EspCur','Aula','Division','Horas'
                )
            ->orderBy('orden', 'ASC')
            ->get();
        }
        
        /*
        $asistencias = AsistenciaModel::where('CUECOMPLETO',$institucionExtension->CUECOMPLETO)
            ->where('Turno', $institucionExtension->idTurnoUsuario)
            ->get();
        */

         // Filtrar asistencias por el mes y año actual
        $asistencias = AsistenciaModel::where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
        ->where('Turno', $institucionExtension->idTurnoUsuario)
        ->whereYear('FechaInicioAsist', $currentYear) //usamos el mes y anio actual para traer las asistencias
        ->whereMonth('FechaInicioAsist', $currentMonth)
        ->get();


            $Aulas =   PofmhAulas::all();
            $Divisiones = PofmhDivisiones::all();

            return response()->json([
                'pofmh' => $pofmh,
                'asistencias' => $asistencias,
                'Divisiones'=>$Divisiones,
                'Aulas'=>$Aulas,
                'fechassistema'=>$fechassistema,
                'fechaescuelas'=>$fechaescuelas
            ]);
    }

 public function colocarAsistncia_nov(Request $request)
{   
    $tipo=1;
    switch($request->status){
        case 'P': $tipo=1; break;
        case 'FJ': $tipo=2; break;
        case 'R': $tipo=3; break;
        case 'L': $tipo=4; break;
        case 'O': $tipo=5; break;
        case 'A': $tipo=6; break;
        case 'C': $tipo=8; break;
        case 'FI': $tipo=7; break;
    }
    try {
        // Obtener el día, mes y año de FechaInicio
        $fechaInicio = Carbon::parse($request->FechaInicio);
        $dia = $fechaInicio->day;
        $mes = $fechaInicio->month;
        $anio = $fechaInicio->year;

        //evaluo cada caso
        switch($tipo){
            case 6:
                //debo borrar el dia cargado
                AsistenciaModel::where('dia', (int)$request->diaSeleccionado) // Convertir a entero
                ->where('mes', (int)$mes) // Asegúrate de convertir el mes también
                ->where('anio', (int)$anio) // Asegúrate de convertir el año
                ->where('idPofmh', (int)$request->idPofmh) // Convertir a entero si es necesario
                ->delete();

                return response()->json(['message' => 'Asistencia eliminada con éxito']);
                break;
            case 4:
                // Obtener las fechas de inicio y fin de la licencia
                $fechaInicio = Carbon::parse($request->FechaInicio);
                $fechaFin = Carbon::parse($request->FechaHasta);

                // Ciclo para iterar desde FechaInicio hasta FechaHasta
                while ($fechaInicio <= $fechaFin) {
                    // Verificar que el día no sea sábado (6) ni domingo (0)
                    if (!$fechaInicio->isWeekend()) {
                        // Crear una nueva entrada en el modelo de asistencia solo si es día laborable
                        $asistencia = new AsistenciaModel();
                        $asistencia->idPofmh = $request->idPofmh;
                        $asistencia->Agente = $request->agente;
                        $asistencia->dia = $fechaInicio->day;
                        $asistencia->mes = $fechaInicio->month;
                        $asistencia->anio = $fechaInicio->year;
                        $asistencia->tipoAsistencia = $tipo;  // Tipo 4 para licencia
                        $asistencia->CUECOMPLETO = $request->cue;
                        $asistencia->Turno = $request->turno;
                        $asistencia->FechaInicioAsist = $request->FechaInicio;
                        $asistencia->FechaHastaAsist = $request->FechaHasta;

                        $asistencia->save();
                    }

                    // Incrementar la fecha en un día
                    $fechaInicio->addDay();
                }

                return response()->json(['message' => 'Asistencia de licencia registrada exitosamente']);
                break;
            case 8:
                // Eliminar asistencia en el rango de fechas
                $fechaFin = Carbon::parse($request->FechaHasta);

                while ($fechaInicio <= $fechaFin) {
                    if (!$fechaInicio->isWeekend()) {
                        AsistenciaModel::where('idPofmh', $request->idPofmh)
                            ->where('dia', $fechaInicio->day)
                            ->where('mes', $fechaInicio->month)
                            ->where('anio', $fechaInicio->year)
                            ->where('tipoAsistencia', 4) // Asegurarse de eliminar solo registros de licencia
                            ->delete();
                    }
                    $fechaInicio->addDay();
                }

                return response()->json(['message' => 'Asistencia de licencia eliminada exitosamente']);
                break;
            case 1:
            case 2:
            case 3:
            case 5:
            case 7:
                    // Crear una nueva entrada en el modelo de asistencia
                $asistencia = new AsistenciaModel();
                $asistencia->idPofmh = $request->idPofmh;
                $asistencia->Agente = $request->agente;
                $asistencia->dia = $request->diaSeleccionado;
                $asistencia->mes = $mes;
                $asistencia->anio = $anio;
                $asistencia->tipoAsistencia = $tipo;  // Usar el valor del request
                $asistencia->CUECOMPLETO = $request->cue;
                $asistencia->Turno = $request->turno;
                $asistencia->FechaInicioAsist = $request->FechaInicio;
                $asistencia->FechaHastaAsist = $request->FechaHasta;

            $asistencia->save();
            return response()->json(['message' => 'Asistencia actualizada con éxito']);
        }


       
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


//

    public function calendario(){
        //extras a enviar
        $TiposDeDocumentos = DB::table('tb_tiposdedocumento')->get();
        $TiposDeAgentes = DB::table('tb_tiposdeagente')->get();
        $Sexos = DB::table('tb_sexo')->get();
        $EstadosCiviles = DB::table('tb_estadosciviles')->get();
        $Nacionalidades = DB::table('tb_nacionalidad')->get();
        $TurnosUsuario = DB::table('tb_turnos_usuario')->get();
        $Modos = DB::table('tb_modos')->get();
        //se agrego el 18 abril
    
        $tipoCalendario = PofmhTipoCalendarioModel::all();
        //dd($RelSubOrgAgente);
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Creación de Usuarios',
            'TurnosUsuario'=>$TurnosUsuario,
            'Modos'=>$Modos,
            'TiposCalendario'=>$tipoCalendario
            //'RelSubOrgAgente'=>$RelSubOrgAgente
        );
        //dd($infoPlaza);
        return view('bandeja.POF.calendario',$datos);
    }
    public function calendarioEsc(){
        //extras a enviar
        $TiposDeDocumentos = DB::table('tb_tiposdedocumento')->get();
        $TiposDeAgentes = DB::table('tb_tiposdeagente')->get();
        $Sexos = DB::table('tb_sexo')->get();
        $EstadosCiviles = DB::table('tb_estadosciviles')->get();
        $Nacionalidades = DB::table('tb_nacionalidad')->get();
        $TurnosUsuario = DB::table('tb_turnos_usuario')->get();
        $Modos = DB::table('tb_modos')->get();
        //se agrego el 18 abril
    
        $tipoCalendario = PofmhTipoCalendarioModel::all();
        //dd($RelSubOrgAgente);
        $datos=array(
            'mensajeError'=>"",
            'mensajeNAV'=>'Panel de Creación de Usuarios',
            'TurnosUsuario'=>$TurnosUsuario,
            'Modos'=>$Modos,
            'TiposCalendario'=>$tipoCalendario
            //'RelSubOrgAgente'=>$RelSubOrgAgente
        );
        //dd($infoPlaza);
        return view('bandeja.POF.calendarioEsc',$datos);
    }

    public function FormNuevaFecha(Request $request)
    {
        //dd($request);

            $c = new PofmhCalendarioModel();
                $c->titulo = $request->Titulo;
                $c->tipoCalendario = $request->tipoCalendario;
                $c->fecha = $request->Fecha;
                $c->es_feriado = $request->esFeriado;
                $c->descripcion = $request->Descripcion;
                $c->CUECOMPLETO = session('CUECOMPLETO');
            $c->save();
            return response()->json(['success' => true, 'message' => 'Fecha agregada exitosamente.']);

    }

    public function obtenerFechas()
    {
        $fechas = DB::connection('DB7')->table('tb_calendario')->join('tb_tipo_calendario','tb_tipo_calendario.idTipoCalendario','tb_calendario.tipoCalendario')->get();
        return response()->json($fechas);
    }

    public function obtenerFechasEsc()
    {
        $fechas = DB::connection('DB7')->table('tb_calendario')
            ->join('tb_tipo_calendario','tb_tipo_calendario.idTipoCalendario','tb_calendario.tipoCalendario')
            ->where('CUECOMPLETO',session('CUECOMPLETO'))
            ->get();
        return response()->json($fechas);
    }

    public function eliminarFecha($id)
{
    try {
        // Buscar el registro por su ID
        $fecha = PofmhCalendarioModel::findOrFail($id);
        
        // Eliminar el registro
        $fecha->delete();

        return response()->json(['success' => true, 'message' => 'Fecha eliminada exitosamente.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Hubo un problema al eliminar la fecha.', 'error' => $e->getMessage()]);
    }
}
















}
