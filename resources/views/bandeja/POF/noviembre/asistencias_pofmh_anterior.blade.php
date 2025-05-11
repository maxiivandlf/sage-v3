@extends('layout.app')

@section('Titulo', 'Sage2.1 - POF MH')

@section('LinkCSS')
  <link rel="stylesheet" href="{{ asset('css/asistencias.css') }}">
  <style>
    /* Estilos para los botones */
    .btn {
        margin: 10px;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }
    .btn-print {
        background-color: #007bff; /* Color para el botón de imprimir */
    }
    .btn-excel {
        background-color: #28a745; /* Color para el botón de Excel */
    }

</style>
@endsection
@section('ContenidoPrincipal')

<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <h2 id="tituloAsistencia">Planilla de Asistencias - ACTIVO POR 15 DIAS</h2>
            <input type="hidden" id="idInstExt" value="{{$institucionExtension->idInstitucionExtension}}">
            <input type="hidden" id="valCUE" value="{{$institucionExtension->CUECOMPLETO}}">
            <input type="hidden" id="valTurno" value="{{$institucionExtension->idTurnoUsuario}}">
            <div>
                <button class="btn btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button class="btn btn-excel" onclick="exportarExcel()">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </button>

                <input type="checkbox" id="mostrarTodoCheckbox" value="0"> Mostrar Todo
            </div>
            <section class="content" style="background-color: #cdc2ee;padding:1rem">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <form id="searchForm">
                                <div class="input-group">
                                    <input type="search" id="searchInput" class="form-control form-control-lg" placeholder="Indique el parámetro por el cual buscar">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-lg btn-default" type="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <script>
                const horasCatedraPorSemana = 16;
                const semanasPorMes = 4;
                const minutosPorHoraCatedra = 40;
                const totalHorasCatedraMensuales = horasCatedraPorSemana * semanasPorMes; // 64 horas cátedra en el mes
            </script>
                <table id="POFMHAsistencia">
                    @php
                        use Carbon\Carbon;
                        // Crear un conjunto de fechas bloqueadas
                        $diasBloqueados = collect();

                        // Agregar las fechas de $fechassistema y $fechaescuelas al conjunto
                        foreach ($fechassistema as $fecha) {
                            $diasBloqueados->push(Carbon::parse($fecha->fecha)->format('Y-m-d'));
                        }

                        foreach ($fechaescuelas as $fecha) {
                            $diasBloqueados->push(Carbon::parse($fecha->fecha)->format('Y-m-d'));
                        }

                        // Eliminar duplicados si es necesario
                        $diasBloqueados = $diasBloqueados->unique();

                        // Array de días de la semana en español
                        $diasSemana = ['Mon' => 'L', 'Tue' => 'M', 'Wed' => 'M', 'Thu' => 'J', 'Fri' => 'V', 'Sat' => 'S', 'Sun' => 'D'];
                        
                        // Genera un array con los días de noviembre y sus abreviaturas en español
                        /*$noviembre = [];
                        for ($i = 1; $i <= 30; $i++) {
                            $fecha = Carbon::create(null, 11, $i);
                            $diaIngles = $fecha->format('D'); // Obtiene el día de la semana en inglés (Mon, Tue, etc.)
                            $abreviatura = $diasSemana[$diaIngles]; // Convierte el día a su abreviatura en español
                            $noviembre[$i] = $abreviatura;
                        }*/

                        // Obtener el mes y el año del mes anterior
                        //$fechaAnterior = Carbon::now()->subMonth();
                        //$mesAnterior = $fechaAnterior->month;
                        //$anioAnterior = $fechaAnterior->year;

                        $mesAnterior = Carbon::now()->subMonth()->month;
                        $anioActual = Carbon::now()->year;
                        // Inicializa el arreglo para el mes anterior
                        $mesAnteriorArray = [];

                        // Obtiene la cantidad de días del mes anterior
                        $numeroDias = Carbon::create($anioActual, $mesAnterior)->daysInMonth;

                        for ($i = 1; $i <= $numeroDias; $i++) {
                            // Crea la fecha para el día correspondiente del mes anterior
                            $fecha = Carbon::create($anioActual, $mesAnterior, $i);
                            
                            // Obtiene el día de la semana en inglés (Mon, Tue, etc.)
                            $diaIngles = $fecha->format('D');
                            
                            // Convierte el día a su abreviatura en español
                            $abreviatura = $diasSemana[$diaIngles];
                            
                            // Asigna la abreviatura al arreglo
                            $mesAnteriorArray[$i] = $abreviatura;
                        }
                       // dd($mesAnteriorArray);
                        // Obtener la fecha actual
                        $fechaHoy = Carbon::now()->format('Y-m-d');
                    @endphp
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Orden</th>
                            <th>DNI</th>
                            <th>Apellido y Nombre</th>
                            <th>Espacio Curricular</th>
                            <th>Aula</th>
                            <th>Division</th>
                            <th>Horas</th>
                            @foreach ($mesAnteriorArray as $dia => $abreviatura)
                                @php
                                    $fechaAnterior = \Carbon\Carbon::now()->subMonth();
                                    // Formatear la fecha del día actual del ciclo a 'Y-m-d'
                                    $fechaActual = date('Y') . '-' .  str_pad($fechaAnterior->month, 2, '0', STR_PAD_LEFT)  . '-' .  str_pad($dia, 2, '0', STR_PAD_LEFT);
                                    //dd($fechaActual);
                                @endphp
                                @if ($diasBloqueados->contains($fechaActual))
                                    <td class="attendance-cell" data-day="{{ $dia }}" id="nolaboral" title="Día Bloqueado">
                                        {{ $abreviatura }} {{ $dia }}
                                    </td>
                                @else
                                    @if ($abreviatura == 'S' || $abreviatura == 'D')
                                        <td class="attendance-cell" data-day="{{ $dia }}" id="finde">
                                            {{ $abreviatura }} {{ $dia }}
                                        </td>
                                    @else
                                        @if ($fechaActual > $fechaHoy)
                                            <td class="attendance-cell" data-day="{{ $dia }}" id="laboral">
                                                {{ $abreviatura }} {{ $dia }}
                                            </td>
                                        @else
                                            <td class="attendance-cell" data-day="{{ $dia }}" id="laboral" onclick="openModal(this)">
                                                {{ $abreviatura }} {{ $dia }}
                                            </td>
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                            <th>Presentes</th>
                            <th>Relevos</th>
                            <th>Licencia</th>
                            <th>Faltas Just.</th>
                            <th>Faltas Injust.</th>
                            <th>Otros</th>
                            <th>-</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($infoPofMH as $pof)
                            @if(!empty($pof->Agente))
                            @php
                                // Buscar el nombre del aula y la división usando los ID de cada uno
                                $nombreAula = $Aulas->firstWhere('idAula', $pof->Aula)->nombre_aula ?? 'S/D';
                                $nombreDivision = $Divisiones->firstWhere('idDivision', $pof->Division)->nombre_division ?? 'S/D';
                            @endphp
                                <tr>
                                    <td data-id="{{$pof->idPofmh}}" class="info">{{$pof->idPofmh}}</td>
                                    <td data-id="{{$pof->orden}}" class="info">{{$pof->orden}}</td>
                                    <td data-id="{{$pof->Agente}}" class="info">{{$pof->Agente}}</td>
                                    <td data-id="{{$pof->ApeNom}}" class="info">{{$pof->ApeNom}}</td>
                                    <td data-id="{{$pof->EspCur}}" class="info">{{$pof->EspCur}}</td>
                                    <td data-id="{{$pof->Aula}}" class="info">{{$nombreAula}}</td>
                                    <td data-id="{{$pof->Division}}" class="info">{{$nombreDivision}}</td>
                                    <td data-id="{{$pof->Horas}}" class="info">{{$pof->Horas}}</td>
                                    @foreach ($mesAnteriorArray as $dia => $abreviatura)
                                        @php
                                            $asistencia = $Asistencias->filter(function($item) use ($dia,$mesAnterior,$anioActual, $pof) {
                                                return $item->dia == $dia 
                                                    && $item->idPofmh == $pof->idPofmh 
                                                    && $item->mes == $mesAnterior 
                                                    && $item->anio == $anioActual;
                                            })->first();
                                        
                                            $tipo = $asistencia ? $asistencia->tipoAsistencia : null;
                                            $cellClass = '';
                                            switch($tipo) {
                                                case 1: $cellClass = 'attendance-P'; break;
                                                case 2: $cellClass = 'attendance-FJ'; break;
                                                case 3: $cellClass = 'attendance-R'; break;
                                                case 4: $cellClass = 'attendance-L'; break;
                                                case 5: $cellClass = 'attendance-O'; break;
                                                case 6: $cellClass = 'attendance-A'; break;
                                                case 7: $cellClass = 'attendance-FI'; break;
                                            }
                                            switch($tipo){
                                                case 1: $tipo='P'; break;
                                                case 2: $tipo='FJ'; break;
                                                case 7: $tipo='FI'; break;
                                                case 3: $tipo='R'; break;
                                                case 4: $tipo='L'; break;
                                                case 5: $tipo='O'; break;
                                                case 6: $tipo='A'; break;
                                            }
                                            $currentDate = date('Y') . '-11-' . str_pad($dia, 2, '0', STR_PAD_LEFT);
                                        @endphp
                                        @if ($diasBloqueados->contains($currentDate))
                                            <td class="attendance-cell" data-day="{{ $dia }}" data-date="{{ $currentDate }}" id="nolaboral">
                                            *
                                            </td>
                                        @else
                                            @if ($abreviatura == 'D')
                                                <td class="attendance-cell " data-day="{{ $dia }}" data-date="{{ $currentDate }}" id="finde">
                                                    -
                                                </td>
                                            @else
                                                @if ($currentDate > $fechaHoy)
                                                    <td class="attendance-cell {{ $cellClass }}" data-day="{{ $dia }}" data-date="{{ $currentDate }}" id="laboral">
                                                        {{ $tipo ? $tipo : '' }}
                                                    </td>
                                                @else
                                                    <td class="attendance-cell {{ $cellClass }}" data-day="{{ $dia }}" data-date="{{ $currentDate }}" id="laboral">
                                                        {{ $tipo ? $tipo : '' }}
                                                    </td>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                    <td class="total-presentes">0</td>
                                    <td class="total-relevos">0</td>
                                    <td class="total-licencia">0</td>
                                    <td class="total-faltas-just">0</td>
                                    <td class="total-faltas-injust">0</td>
                                    <td class="total-otros">0</td>
                                    <td class="">0</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

        </section>
    </section> 
</section>
<!-- Modal para mostrar el gráfico -->
<div class="modal fade" id="modal-novedades">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Panel de Novedades</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12">
          <div class="card">
            <div class="card-header d-flex p-0">
              <h3 class="card-title p-3">Panel de Novedades Generales</h3>
              <ul class="nav nav-pills ml-auto p-2">
                <li class="nav-item">
                    <a class="nav-link active" href="#tab_0" data-toggle="tab">
                        <i class="fas fa-clipboard-list"></i> Agregar Asistencia
                    </a>
                  </li>
                <li class="nav-item">
                  <a class="nav-link" href="#tab_1" data-toggle="tab">
                      <i class="fas fa-plus-circle"></i> Agregar novedad
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#tab_2" data-toggle="tab">
                      <i class="fas fa-eye"></i> Ver Novedades
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#tab_3" data-toggle="tab">
                      <i class="fas fa-upload"></i> Subir Documentación
                  </a>
                </li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane active" id="tab_0">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="FechaInicio">Fecha Inicio</label>
                            <input type="date" class="form-control" id="FechaInicioAsist" name="FechaInicioAsist" placeholder="Fecha Inicio" value="">
                        </div>
                        <div class="form-group" style="margin-left: 20px">
                            <label for="FechaHasta">Fecha Hasta</label>
                            <input type="date" class="form-control" id="FechaHastaAsist" name="FechaHastaAsist" placeholder="Fecha Hasta" value="">
                        </div>
                    </div>
                    <h2>Detalles de Asistencia</h2>
                        <p id="modal-agente"></p>
                        <p id="modal-orden"></p>
                        <p id="modal-apenom"></p>
                        <p id="modal-especialidad"></p>
                        <p id="modal-horas"></p>
                        <p id="modal-dia"></p>
                        <input type="hidden" id="diaSeleccionado" value="">
                        <div class="activadores" style="display: flex;justify-content:space-between">
                            <div class="botones-izquierdos">
                                <button class="btn btn-success" onclick="confirmAttendance('P')">Presente (P)</button>
                                <button class="btn" style="background-color: #b2f09a" onclick="confirmAttendance('FJ')">Falta Justificada (FJ)</button>
                                <button class="btn btn-danger" onclick="confirmAttendance('FI')">Falta Injustificada (FI)</button>
                                <button class="btn btn-info" onclick="confirmAttendance('R')">Relevo (R)</button>
                                <button class="btn btn-warning" onclick="confirmAttendance('L')">Licencia (L)</button>
                                <button class="btn btn-warning" onclick="confirmAttendance('O')">Otro Motivo (O)</button>
                            </div>
                            <div class="botones-derechos">
                                <button class="btn btn-primary" onclick="confirmAttendance('C')">Borrar Licencia Rango</button>
                                <button class="btn btn-primary" onclick="confirmAttendance('A')">Borrar Celda</button>
                            </div>
                        </div>
                        
                       
                </div>
                <div class="tab-pane" id="tab_1">
                  <form method="POST" action="{{ route('pofmhformularioNovedadParticular') }}" class="pofmhformularioNovedadParticular" id="pofmhformularioNovedadParticular">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="FechaInicio">Fecha Inicio</label>
                                <input type="date" class="form-control" id="FechaInicio" name="FechaInicio" placeholder="Fecha Inicio" value="">
                            </div>
                            <div class="form-group" style="margin-left: 20px">
                                <label for="FechaHasta">Fecha Hasta</label>
                                <input type="date" class="form-control" id="FechaHasta" name="FechaHasta" placeholder="Fecha Hasta" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="DNI">DNI del Agente"</label>
                                <input type="text" class="form-control" id="DNI" name="DNI" placeholder="Ingrese DNI del Agente" value="" readonly disabled>
                            </div>
                            
                            <div class="form-group"  style="margin-left: 20px">
                                <label for="ApeNom">Apellido y Nombre"</label>
                                <input type="text" class="form-control" id="ApeNom" name="ApeNom" placeholder="Agente" value="" disabled readonly>
                            </div>
                            <div class="form-group" style="display: flex">
                                <div class="form-group" style="margin-left: 20px">
                                    <label for="TL">Tipo de Novedad </label>
                                    <select name="TipoNovedad" class="form-control custom-select">
                                        @foreach($NovedadesExtras as $key => $o)
                                            <option value="{{$o->idNovedadExtra}}">({{$o->tipo_novedad}})</option>
                                        @endforeach 
                                    </select>
                                </div>
                                <div class="form-group" style="margin-left: 20px">
                                    <label for="TL">Tipo de Licencia </label>
                                    <select class="form-control motivos-input" name="Motivos" id="Motivos" >
                                        @foreach($Motivos as $key => $o)
                                            <option value="{{$o->idMotivo}}"><b>({{$o->Codigo}})</b>{{$o->Nombre_Licencia}}</option>
                                        @endforeach
                                      </select>
                                </div>
                            </div>
                            
                        </div>
                        <input type="hidden" id="novedad_dni" name="novedad_dni" value="">
                        <input type="hidden" id="novedad_apenom" name="novedad_apenom" value="">
                        <input type="hidden" id="novedad_cue" name="novedad_cue" value="">
                        <input type="hidden" id="novedad_turno" name="novedad_turno" value="">
                        <div class="form-group">
                            <label for="Observacion">Observación</label><br>
                            <textarea class="form-control" name="Observaciones" id="novedad_observacion" rows="5" cols="100%"></textarea>
                        </div>
                        
                        
                       
                    </div>
                    <div class="card-footer bg-transparent">
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                    
                </form>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2" style="text-align:center">DNI</th>
                            <th colspan="3" style="text-align:center">Fecha Novedad</th>
                            <th rowspan="2" style="text-align:center">Tipo Novedad</th>
                            <th rowspan="2" style="text-align:center">Observaciones</th>
                            <th rowspan="2" style="text-align:center">Acciones</th>
                        </tr>
                        <tr>
                            <th style="text-align:center">Fecha Desde</th>
                            <th style="text-align:center">Fecha Hasta</th>
                            <th style="text-align:center">Total Días</th>
                            
                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
                <!-- /.tab-pane -->
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">
                  <div class="container_archivos"  style="display: flex; gap:1rem;">
                        <!-- INICIO SUBIR DOC -->
                      <div class="card card-secondary col-6">
                        <div class="card-header">
                            <h3 class="card-title">Subir Documentos <small><em></em></small></h3>
                        </div>
                        <div class="card-body" >
                            <div id="actions" class="row">
                                <div class="">
                                    <div class="btn-group w-100" >
                                        <span class="btn btn-success fileinput-button">
                                            <i class="fas fa-plus"></i>
                                            Agregar
                                        </span>                        
                                    </div>
                                </div>
                                <div class="col-lg-6 d-flex align-items-center">
                                    <div class="fileupload-process w-100">
                                        <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table table-striped files" id="previews">
                                <div id="template2" class="row mt-2">
                                    <div class="col-auto">
                                        <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                                    </div>
                                    <div class="col d-flex align-items-center">
                                        <p class="mb-0">
                                            <span class="lead" data-dz-name></span>
                                            (<span data-dz-size></span>)
                                        </p>
                                        <strong class="error text-danger" data-dz-errormessage></strong>
                                    </div>
                                    <div class="col-4 d-flex align-items-center">
                                        <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex align-items-center">
                                        <div class="btn-group">
                                            <button class="btn btn-primary start" title="Enviar Archivo">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                            <button data-dz-remove class="btn btn-warning cancel"  title="Cancelar Subida">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                            <button data-dz-remove class="btn btn-danger delete"  title="Borrar Envio">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer" id="upload-status">                                  
                            <!-- Aquí se mostrarán los mensajes de estado o errores de la carga de archivos -->
                        </div>
                    </div>
                    <!-- /.card -->
                      <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="text-align:center">Archivo</th>
                                <th style="text-align:center">Fecha Alta</th>
                                <th style="text-align:center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="modalBody">
                                                          
                        </tbody>
                      </table>
                  </div>
                  
                </div>
                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div><!-- /.card-body -->
          </div>
          
          <!-- /.card -->
      </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar Panel</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


@endsection

@section('Script')
<script>
    // Convertir la variable PHP a JavaScript
    var noviembre = @json($mesAnteriorArray);
</script>
<script src="{{ asset('js/pofmh_asistencia.js') }}"></script>

<!-- Incluye la librería SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


 <script>

updateTotals()

  $(document).ready(function() {
      // Función para cargar datos en la tabla de novedades
      function cargarNovedades() {
          var dni = $('#DNI').val();
          var cue = $('#valCUE').val();
          $.ajax({
              url: "/pofmhNovedades/" + dni + "/" + cue, // Ruta definida en web.php
              method: "GET",
              dataType: "json",
              success: function(data) {
                  // Limpiar la tabla antes de llenarla
                  $('#tab_2 tbody').empty();
                  
                  // Iterar sobre los datos y llenar la tabla
                  $.each(data.novedades, function(key, n) { // Aquí usamos data.novedades
                    let motivo = data.Motivos.find(m => m.idMotivo === n.Motivo) || { Codigo: 'N/A', Nombre_Licencia: 'N/A' };
                    
                    var row = `<tr class="gradeX" data-id="${n.idNovedad}">
                        <td>${n.Agente || 'Sin datos'}</td>
                        <td class="text-center">${new Date(n.FechaDesde).toLocaleDateString('es-ES')}</td>
                        <td class="text-center">${new Date(n.FechaHasta).toLocaleDateString('es-ES')}</td>
                        <td class="text-center">${n.TotalDiasLicencia || '1'}</td>
                        <td class="text-center">${n.tipo_novedad || 'Sin novedad'}</td>
                        <td class="text-center">${motivo.Codigo}-${motivo.Nombre_Licencia}</td>
                        <td>${n.Observaciones || 'Sin observaciones'}</td>
                        <td>
                            <i class="fas fa-eraser btn-eliminar-pof" style="color:red"></i>
                        </td>
                    </tr>`;
                    $('#tab_2 tbody').append(row);
                });
              },
              error: function(xhr) {
                  console.error("Error al cargar las novedades:", xhr);
              }
          });
      }
  
      // Cargar datos al abrir el modal
      $('#modal-novedades').on('show.bs.modal', function () {
          cargarNovedades(); // Llama a la función para cargar las novedades
      });
  
      $('.pofmhformularioNovedadParticular').submit(function(e){
          e.preventDefault();
          
          var dni = $('#DNI').val();
          var fi = $('#FechaInicio').val();
          var fh = $('#FechaHasta').val();
          var ob = $('#novedad_observacion').val();
  
          if (!dni || !fi || !fh || !ob) {
              Swal.fire({
                  title: 'Error',
                  text: 'Debe completar todos los campos solicitados.',
                  icon: 'error'
              });
              return;
          }
  
          Swal.fire({
              title: '¿Está seguro de querer agregar una novedad para el Agente?',
              text: "Recuerde colocar datos verdaderos",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si, guardo el registro!'
          }).then((result) => {
              if (result.isConfirmed) {
                  var formData = new FormData(this);
                  
                  $.ajax({
                      url: $(this).attr('action'), // URL del formulario
                      method: 'POST',
                      data: formData,
                      processData: false,
                      contentType: false,
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                      },
                      success: function(response) {
                          Swal.fire('Éxito', 'Novedad agregada correctamente.', 'success');
                          cargarNovedades(); // Actualiza la tabla de novedades después de agregar
                      },
                      error: function(xhr, status, error) {
                          console.error(xhr.responseText);
                          Swal.fire('Error', 'No se pudo agregar la novedad.', 'error');
                      }
                  });
              }
          });
      });
  });

  $(document).on('click', '.btn-eliminar-pof', function() {
    var fila = $(this).closest('tr'); // Encuentra la fila correspondiente
    var id = fila.data('id'); // Obtiene el ID de la novedad

    Swal.fire({
        title: '¿Está seguro de querer eliminar esta novedad?',
        text: "¡Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/novedadesModal/${id}`, // URL para eliminar la novedad
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                },
                success: function(response) {
                    Swal.fire('Eliminado', 'Novedad eliminada correctamente.', 'success');
                    fila.remove(); // Eliminar la fila de la tabla
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'No se pudo eliminar la novedad.', 'error');
                }
            });
        }
    });
});

    let selectedCell;
function resetModal() {
    document.getElementById('pofmhformularioNovedadParticular').reset(); // Resetea todos los campos del formulario
}
    
function openModal(cell) {
    resetModal();
    selectedCell = cell;
    const row = cell.parentElement;
    const dia = cell.getAttribute('data-day'); // Obtén el día de la celda

    // Obtener el año y el mes actuales
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Mes en formato 2 dígitos
    const day = String(dia).padStart(2, '0'); // Día en formato 2 dígitos

    // Construir la fecha seleccionada en formato YYYY-MM-DD
    const selectedDate = `${year}-11-${day}`;

    // Obtener datos de las otras celdas
    const idPofmh = row.children[0].getAttribute('data-id');
    const orden = row.children[1].getAttribute('data-id');
    const agente = row.children[2].getAttribute('data-id');
    const apeNom = row.children[3].getAttribute('data-id');
    const espCur = row.children[4].getAttribute('data-id');
    const horas = row.children[7].getAttribute('data-id');

    // Pasar los datos al modal
    document.getElementById('modal-agente').textContent = `Agente: ${agente}`;
    document.getElementById('modal-orden').textContent = `Orden: ${orden}`;
    document.getElementById('modal-apenom').textContent = `Nombre: ${apeNom}`;
    document.getElementById('modal-especialidad').textContent = `Especialidad: ${espCur}`;
    document.getElementById('modal-horas').textContent = `Horas: ${horas}`;
    document.getElementById('modal-dia').textContent = `Día Seleccionado: ${dia}`;
    document.getElementById('diaSeleccionado').value = dia;

    const valCue = $('#valCUE').val();
    const valTurno = $('#valTurno').val();

    // Asignar valores al modal
    $('#DNI').val(agente);
    $('#novedad_dni').val(agente);
    $('#ApeNom').val(apeNom);
    $('#novedad_apenom').val(apeNom);
    $('#novedad_cue').val(valCue);
    $('#novedad_turno').val(valTurno);

    // Establecer la fecha seleccionada en los campos de fecha
    $('#FechaInicioAsist').val(selectedDate);
    $('#FechaHastaAsist').val(selectedDate);

    //coloco las fechas tambien a las de novedad
    $('#FechaInicio').val(selectedDate);
    $('#FechaHasta').val(selectedDate);

    $('#modal-novedades').modal('show');
}


    

    function updateAttendance(status) {
        if(status !== 'C'){
            selectedCell.textContent = status;
            selectedCell.className = 'attendance-cell attendance-' + status;
        }
        

        // Obtener los datos que se enviarán al servidor
        const idPofmh = selectedCell.parentElement.children[0].getAttribute('data-id');
        const agente = $('#novedad_dni').val(); // Agente desde el modal
        const valCue = $('#novedad_cue').val(); // CUE desde el modal
        const valTurno = $('#novedad_turno').val(); // Turno desde el modal
        const FechaInicio = $('#FechaInicioAsist').val(); // Turno desde el modal
        const FechaHasta = $('#FechaHastaAsist').val(); // Turno desde el modal
        const diaSeleccionado = $('#diaSeleccionado').val(); // Turno desde el modal
        
        // Crear un objeto con los datos a enviar
        const data = {
            idPofmh: idPofmh,
            agente: agente,
            cue: valCue,
            turno: valTurno,
            FechaInicio: FechaInicio,
            FechaHasta: FechaHasta,
            diaSeleccionado: diaSeleccionado,
            status: status // El estado de asistencia
        };
        // Realizar la solicitud AJAX
        $.ajax({
            url: '/colocarAsistncia_nov', 
            type: 'POST',
            data: {
                _token: csrfToken, // Usando el token CSRF obtenido
                idPofmh: idPofmh, // Directamente
                agente: agente,
                cue: valCue,
                turno: valTurno,
                FechaInicio: FechaInicio,
                FechaHasta: FechaHasta,
                diaSeleccionado: diaSeleccionado,
                status: status // El estado de asistencia
            },
            success: function(response) {
                // Manejar la respuesta del servidor
                updateTotals()

                if (status === 'C') {
                    selectedCell.textContent = '';
                    selectedCell.className = 'attendance-cell attendance-' + 'A';
                    const selectedRow = selectedCell.parentElement;
                    applyLicenseHighlightNueva(selectedRow, FechaInicio, FechaHasta,'A','#f1f2f7');
                }
                if (status === 'L') {
                    const selectedRow = selectedCell.parentElement;
                    applyLicenseHighlightNueva(selectedRow, FechaInicio, FechaHasta,status,'#bbdefb');
                }
                
                
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', error);
            }
        });

        
    }

    
    function updateTotals(FechaInicioAsist, FechaHastaAsist) {
    const rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {
        const hoursAssignedCell = row.children[5];
        const hoursAssigned = hoursAssignedCell ? parseInt(hoursAssignedCell.textContent) : 0; // Verifica que la celda de horas asignadas exista

        let presentCount = 0;
        let relayCount = 0;
        let licenseCount = 0;
        let absenceCountFJ = 0;
        let absenceCountFI = 0;
        let otherCount = 0;

        row.querySelectorAll('.attendance-cell').forEach(cell => {
            const cellText = cell.textContent.trim();

            switch (cellText) {
                case 'P':
                    presentCount++;
                    break;
                case 'FJ':
                    absenceCountFJ++;
                    break;
                case 'FI':
                    absenceCountFI++;
                    break;
                case 'R':
                    relayCount++;
                    break;
                case 'L':
                    licenseCount++;
                    break;
                case 'O':
                    otherCount++;
                    break;
            }
        });

        // Verificar si las celdas de totales existen antes de intentar actualizarlas
        const totalPresentesCell = row.querySelector('.total-presentes');
        const totalRelevosCell = row.querySelector('.total-relevos');
        const totalLicenciaCell = row.querySelector('.total-licencia');
        const totalFaltasCellJ = row.querySelector('.total-faltas-just');
        const totalFaltasCellI = row.querySelector('.total-faltas-injust');
        const totalOtrosCell = row.querySelector('.total-otros');
        const attendancePercentageCell = row.querySelector('.attendance-percentage');

        if (totalPresentesCell) totalPresentesCell.textContent = presentCount;
        if (totalRelevosCell) totalRelevosCell.textContent = relayCount;
        if (totalLicenciaCell) totalLicenciaCell.textContent = licenseCount;
        if (totalFaltasCellJ) totalFaltasCellJ.textContent = absenceCountFJ;
        if (totalFaltasCellI) totalFaltasCellI.textContent = absenceCountFI;
        if (totalOtrosCell) totalOtrosCell.textContent = otherCount;

        // Calcular el porcentaje de asistencia basado en las horas asignadas, si existen
        if (hoursAssigned > 0 && attendancePercentageCell) {
            const attendedHours = (presentCount / 30) * hoursAssigned;
            const attendancePercentage = (attendedHours / hoursAssigned) * 100;
            attendancePercentageCell.textContent = attendancePercentage.toFixed(2) + '%';
        }

        // Pintar las celdas de "L" para el rango de fechas
        if (licenseCount > 0) {
            applyLicenseHighlight(row, FechaInicioAsist, FechaHastaAsist);
        }
    });
}



function applyLicenseHighlight(row, FechaInicioAsist, FechaHastaAsist) {
    const startDate = new Date(FechaInicioAsist);
    const endDate = new Date(FechaHastaAsist);

    row.querySelectorAll('.attendance-cell').forEach(cell => {
        const cellDateText = cell.getAttribute("data-date");
        const cellDate = new Date(cellDateText);

        if (cellDate >= startDate && cellDate <= endDate) {
            const dayOfWeek = cellDate.getDay();
            if (dayOfWeek !== 6 && dayOfWeek !== 7) { // 6 = Sábado, 7 = Domingo
                cell.style.backgroundColor = "#FFDD99"; // Color de fondo para licencia
            }
        }
    });
}
//funcion propia de Licencia
function applyLicenseHighlightNueva(row, FechaInicioAsist, FechaHastaAsist,status,color) {
    const startDate = new Date(FechaInicioAsist);
    const endDate = new Date(FechaHastaAsist);

    row.querySelectorAll('.attendance-cell').forEach(cell => {
        const cellDateText = cell.getAttribute("data-date");
        const cellDate = new Date(cellDateText);
        
        // Verificar si la fecha de la celda está dentro del rango de fechas
        if (cellDate >= startDate && cellDate <= endDate) {
            const dayOfWeek = cellDate.getDay()+1;
            if (dayOfWeek !== 6 && dayOfWeek !== 7) { // Excluir fines de semana (sábado y domingo) y el guion
                cell.textContent = status; // Colocar la letra del estado (e.g., "L")
                cell.className = 'attendance-cell attendance-' + status;
                cell.style.backgroundColor = color; // Color de fondo para licencia
            }
        }
    });
}




$(document).ready(function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let idExtension = document.getElementById("idInstExt").value;
    
    // Cargar datos al inicio (opcional)
    //loadData();

    // Event listener para la entrada de búsqueda
    $('#searchInput').on('input', function () {
        const query = $(this).val();
        searchPofmh(query);
    });

    function loadData() {
        $.ajax({
            url: '/obtener-pofmh/' + idExtension,
            type: 'GET',
            success: function (data) {
                fillTable(data);
                updateTotals()
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar los datos:", error);
            }
        });
    }
    //proceso del checkbox
    $('#mostrarTodoCheckbox').change(function() {
        if ($(this).is(':checked')) {
            // Mostrar todos los elementos
            searchPofmhCompleto('0', true); 
        } else {
            // Aplicar el filtro predeterminado
            const query = $('#searchInput').val(); 
            searchPofmhCompleto('1', false);
        }
    });
    
    function searchPofmhCompleto(query, mostrarTodo) {
        $.ajax({
            url: '/buscarPofmhCompleto_nov/' + idExtension,
            type: 'GET',
            data: { query: query, mostrarTodo: mostrarTodo },
            success: function(data) {
                console.log("mostrando noviembre")
                fillTable(data);
                updateTotals();
            },
            error: function(xhr, status, error) {
                console.error("Error en la búsqueda:", error);
            }
        });
    };

    function searchPofmh(query) {
        $.ajax({
            url: '/buscarPofmh_nov/' + idExtension,
            type: 'GET',
            data: { query: query },
            success: function (data) {
                fillTable(data);
                updateTotals()
            },
            error: function (xhr, status, error) {
                console.error("Error en la búsqueda:", error);
            }
        });
    }

    function fillTable(data) {
        $('#POFMHAsistencia tbody').empty(); // Limpiar la tabla antes de llenarla

        const asistencias = data.asistencias;
        const aulas = data.Aulas;
        const divisiones = data.Divisiones;
        const fechassistema = data.fechassistema || []; // Garantiza que sea un array
        const fechaescuelas = data.fechaescuelas || []; // Garantiza que sea un array
        const today = new Date(); // Obtener la fecha de hoy

        // Crear un conjunto de fechas bloqueadas
        const diasBloqueados = new Set();

        // Agregar fechas bloqueadas de `fechassistema` y `fechaescuelas`
        if (Array.isArray(fechassistema)) {
            fechassistema.forEach(fecha => {
                const fechaFormateada = new Date(fecha.fecha).toISOString().split('T')[0];
                diasBloqueados.add(fechaFormateada);
            });
        }

        if (Array.isArray(fechaescuelas)) {
            fechaescuelas.forEach(fecha => {
                const fechaFormateada = new Date(fecha.fecha).toISOString().split('T')[0];
                diasBloqueados.add(fechaFormateada);
            });
        }

        $.each(data.pofmh, function (index, item) {
            if (item.Agente !== null) {
                const nombreAula = aulas.find(aula => aula.idAula === item.Aula)?.nombre_aula || "S/D";
                const nombreDivision = divisiones.find(division => division.idDivision === item.Division)?.nombre_division || "S/D";

                let rowHtml = `
                    <tr>
                        <td data-id="${item.idPofmh}">${item.idPofmh}</td>
                        <td data-id="${item.orden}">${item.orden}</td>
                        <td data-id="${item.Agente}">${item.Agente}</td>
                        <td data-id="${item.ApeNom}">${item.ApeNom ? item.ApeNom : ""}</td>
                        <td data-id="${item.EspCur}">${item.EspCur ? item.EspCur : ""}</td>
                        <td data-id="${nombreAula}">${nombreAula}</td>
                        <td data-id="${nombreDivision}">${nombreDivision}</td>
                        <td data-id="${item.Horas}">${item.Horas}</td>
                `;
                // Obtener la fecha actual
                const today = new Date();

                // Restar un mes para obtener el mes anterior
                const mesAnterior = new Date(today.setMonth(today.getMonth() - 1));

                // Lista de nombres de los meses
                const nombresMeses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

                // Obtener el nombre del mes anterior
                const mesAnteriorNombre = nombresMeses[mesAnterior.getMonth()];

                // Aquí asumo que 'noviembre' contiene los días del mes. Si necesitas cambiar la variable a dinámico (mes anterior), deberías adaptarlo.
                let mesAnteriorArray = [];
                const numeroDias = new Date(mesAnterior.getFullYear(), mesAnterior.getMonth() + 1, 0).getDate(); // Obtener número de días del mes anterior

                // Generar las celdas de asistencia para cada día
                for (let dia = 1; dia <= numeroDias; dia++) {
                    const asistencia = asistencias.find(a => a.dia == dia && a.idPofmh == item.idPofmh) || null;
                    let tipo = 'A';
                    let cellClass = '';

                    if (asistencia) {
                        tipo = asistencia.tipoAsistencia;
                        switch (tipo) {
                            case 1: cellClass = 'attendance-P'; tipo = 'P'; break;
                            case 2: cellClass = 'attendance-FJ'; tipo = 'FJ'; break;
                            case 7: cellClass = 'attendance-FI'; tipo = 'FI'; break;
                            case 3: cellClass = 'attendance-R'; tipo = 'R'; break;
                            case 4: cellClass = 'attendance-L'; tipo = 'L'; break;
                            case 5: cellClass = 'attendance-O'; tipo = 'O'; break;
                            case 6: cellClass = 'attendance-A'; tipo = 'A'; break;
                        }
                    }

                    const abreviatura = noviembre[dia]; // Asegúrate de definir `noviembre` antes de usarlo
                    //const fechaFormateada = `${today.getFullYear()}-11-${String(dia).padStart(2, '0')}`;
                    const fechaFormateada = `${mesAnterior.getFullYear()}-${String(mesAnterior.getMonth() + 1).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;

                    //const fechaFormateada = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;
                   
                    const fechaDia = new Date(today.getFullYear(), 10, dia);
                    //console.log("fechadia:"+fechaDia)
                    if (diasBloqueados.has(fechaFormateada)) {
                        // Si es un día bloqueado
                        rowHtml += `<td class="attendance-cell" data-day="${dia}" id="nolaboral" title="Día Bloqueado">*</td>`;
                    } else if ( abreviatura === 'D') {
                        // Fines de semana
                        rowHtml += `<td class="attendance-cell" data-day="${dia}" id="finde">-</td>`;
                    } else if (fechaDia > today) { 
                        // No se permite el modal en días futuros
                        if(tipo === 'A'){
                            rowHtml += `<td class="attendance-cell ${cellClass}" data-day="${dia}" id="laboral"></td>`;

                        }else{
                            rowHtml += `<td class="attendance-cell ${cellClass}" data-day="${dia}" id="laboral">${tipo}</td>`;

                        }
                    } else {
                        // Se permite el modal en días hasta hoy
                        if(tipo === 'A') {
                            rowHtml += `<td class="attendance-cell ${cellClass}" data-day="${dia}" id="laboral"></td>`;
                        } else {
                            rowHtml += `<td class="attendance-cell ${cellClass}" data-day="${dia}" id="laboral">${tipo}</td>`;
                        }
                    }
                }

                rowHtml += `
                    <td class="total-presentes">${item.totalPresentes || 0}</td>
                    <td class="total-relevos">${item.totalRelevos || 0}</td>
                    <td class="total-licencia">${item.totalLicencias || 0}</td>
                    <td class="total-faltas-just">${item.totalFaltas || 0}</td>
                    <td class="total-faltas-injust">${item.totalFaltas || 0}</td>
                    <td class="total-otros">${item.totalOtros || 0}</td>
                    <td class="attendance-percentage">${item.porcentajeAsistencia || '0%'}</td>
                </tr>
                `;

                $('#POFMHAsistencia tbody').append(rowHtml); // Agregar la fila a la tabla
            }
        });
    }
  });

    function confirmAttendance(status) {
            // Mostrar SweetAlert2 de confirmación
            Swal.fire({
                title: '¿Confirma asistencia?',
                text: "Esta acción actualizará la asistencia",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, llama a la función updateAttendance con el status
                    updateAttendance(status);
                    
                    // Muestra un mensaje de éxito opcional
                    Swal.fire(
                        'Asistencia confirmada',
                        'La asistencia ha sido registrada.',
                        'success'
                    )
                }
            });
    }

    // Array con los nombres de los meses en español
    const meses = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    // Obtener la fecha actual
    const fechaActual = new Date();

    // Lista de nombres de los meses
    const nombresMeses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    // Obtener el nombre del mes actual
    const mesActualNombre = nombresMeses[fechaActual.getMonth()];

    // Restar un mes para obtener el mes anterior
    const mesAnterior = new Date(fechaActual);
    mesAnterior.setMonth(fechaActual.getMonth() - 1);

    // Obtener el nombre del mes anterior
    const mesAnteriorNombre = nombresMeses[mesAnterior.getMonth()];

    // Formatear la fecha actual (día, mes, año) para mostrarla
    const fechaFormateada = fechaActual.toLocaleDateString("es-ES");

    // Mostrar el título con el mes actual y la fecha actual
    document.getElementById("tituloAsistencia").innerText = `CONTROL - Planilla de Asistencia - Mes: ${mesAnteriorNombre} - Fecha Actual: ${fechaFormateada}`;

    

    function imprimirTabla() {
        const tabla = document.getElementById("POFMHAsistencia");
        const nuevaVentana = window.open('', '_blank');
        nuevaVentana.document.write('<html><head><title>Imprimir Tabla</title>');
        nuevaVentana.document.write('<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid black; padding: 8px; text-align: left; } </style>');
        nuevaVentana.document.write('</head><body>');
        nuevaVentana.document.write(tabla.outerHTML);
        nuevaVentana.document.write('</body></html>');
        nuevaVentana.document.close();
        nuevaVentana.print();
    }

    function exportarExcel() {
        const tabla = document.getElementById("POFMHAsistencia");
        const hoja = XLSX.utils.table_to_sheet(tabla);
        const libro = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(libro, hoja, "Asistencia");
        XLSX.writeFile(libro, "asistencia.xlsx");
    }
</script>
@endsection
