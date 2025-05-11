@extends('layout.app')

@section('Titulo', 'Sage2.1 - POF MH')

@section('LinkCSS')
  <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
@endsection
@section('ContenidoPrincipal')

<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <div class="alert alert-warning alert-dismissible" id="mensajeprincipalescuela">
              @php
                  // Obtener la fecha actuals
                  \Carbon\Carbon::setLocale('es');
                  $fechaActual = \Carbon\Carbon::now();
                  $fechaFormateada = $fechaActual->format('d/m/Y');
                  $mesDeControl = $fechaActual->translatedFormat('F'); // Formato de mes en español
              @endphp
                <h5><i class="icon fas fa-exclamation-triangle"></i> Importante!</h5>
                <h3 id="datosInst">Institución: <b>{{$institucionExtension->Nombre_Institucion}}</b> - CUE: <b>{{$institucionExtension->CUE}}</b> - Turno: <b>{{$institucionExtension->Descripcion}}</b></h3>
                <input type="hidden" name="valCUE" id="valCUE" value="{{$institucionExtension->CUECOMPLETO}}">
                <input type="hidden" name="valTurno" id="valTurno" value="{{$institucionExtension->idTurnoUsuario}}">
                <input type="hidden" name="valIdExt" id="valIdExt" value="{{$institucionExtension->idInstitucionExtension}}">
                <h4>Nivel: <b>{{$institucionExtension->NivelEnsenanza}}</b></h4>
                <h4>Cue Ext: <b>{{$institucionExtension->CUECOMPLETO}}</b></h4>
                <h4>Fecha: <b>{{ $fechaFormateada }}</b></h4>
                <h4>Mes de Control: <b>{{ ucfirst($mesDeControl) }}</b></h4>
               {{-- <div id="alertaDiferencia" class="alert alert-info mt-3" role="alert" style="display: none;">
                  <i class="bi bi-clock"> </i> 
                  <span id="mensajeDiferencia"></span>
                  <div class="botonera-derecha">
                    <label>Prueba Activada: </label>
                     <button type="button" class="confirm-btn" id="confirmBtn">
                        <i class="fas fa-file-export"></i> Generar PDF/Excel (Falta Pulir Mas)
                    </button> 
                  </div>
              </div>--}}
             {{-- <div class="alert alert-danger mt-3" id="acuse" role="alert">
                    <div class="botonera-derecha">
                    <label>Conformidad: </label>
                    <p>
                        Esta declaración se realiza en cumplimiento de las normativas vigentes, incluyendo la Ley N° 25.164 de Ética en la Función Pública, la Ley N° 25.326 de Protección de Datos Personales y en conformidad con la Ley N° 27.275 de Acceso a la Información Pública. Autorizo ​​al Director/a de [NOMBRE DE LA INSTITUCIÓN] a registrador y digitalizar los datos consignados en esta declaración, los cuales serán tratados exclusivamente en el marco de lo establecido por las normativas antes mencionadas.
                    </p>
                    <input type="checkbox" value="SI">Acepto las Condiciones 
                    </div>
                </div>--}}
            </div>
            
            <form id="excelForm row">
                @csrf <!-- Agregar el token CSRF de Laravel -->
            
                <!-- Botón para agregar la primera fila -->
                <div class="botonera">
                    <i class="fas fa-ban"></i> 
                    <label>CONTROLES DESHABILITADOS</label>
                </div>
                


                <div class="content m-0">
                    <div class="container-fluid">
                        <div class="card table-responsive" id="cardPOFMH">
                            <table id="POFMH">
                                <thead class="card-header">
                                    <tr>
                                        <th class="custom-5rem" id="tablaarriba">#ID</th>
                                        <th class="custom-5rem">Orden</th>
                                        <th class="custom-8rem">DNI</th>
                                        <th class="custom-15rem">Apellido y Nombre</th>

                                        <th class="custom-30rem">Cargo de Origen en la Institución</th>
                                        
                                        <th class="custom-15rem">Sit.Rev</th>
                                        <th class="custom-5rem">Horas</th>
                                        <th class="custom-13rem">Antigüedad Docente</th>
                                        <th class="custom-33rem">Código Cargo</th>

                                        <th class="custom-20rem">Aula</th>
                                        <th class="custom-8rem">Division</th>
                                        <th class="custom-15rem">Turno</th>

                                        <th class="custom-18rem">Esp.Cur</th>
                                        <th class="custom-5rem">Matricula</th>
                                        <th class="custom-9rem">Posesión del Cargo</th>
                                        <th class="custom-9rem">Designado al cargo</th>
                                        <th class="custom-13rem">Condición</th>
                                        <th class="custom-13rem">¿En función en el cargo?</th>

                                        <th class="custom-33rem">Tipo-Motivo-Art.Licenica</th>
                                        <th class="custom-20rem">Otros Datos por Condición</th>
                                        <th class="custom-9rem">Desde</th>
                                        <th class="custom-9rem">Hasta</th>

                                        <th class="custom-8rem">DNI Suplente</th>

                                        <th class="custom-5rem">Novedad</th>
                                        {{--<th class="custom-5rem">Asistencia</th>
                                        <th class="custom-5rem">Justificada</th>
                                        <th class="custom-5rem">Injustificada</th>--}}
                                        
                                        <th class="custom-33rem">Observaciones</th>

                                        <th class="custom-33rem">Carrera</th>
                                        <th class="custom-33rem">Orientación</th>
                                        <th class="custom-33rem">Titulo</th>

                                        <th class="custom-8rem">Acción</th>
                                        <th class="custom-33rem">Observación Supervisión</th>

                                    </tr>
                                </thead>
                                <tbody class="card-body direct-chat-messages">
                                    @if ($infoPofMH->isNotEmpty())
                                    @foreach ($infoPofMH as $fila)
                                    <tr data-id="{{$fila->idPofmh}}" class="fila " data-bg-color="default">
                                        <td  data-id="{{$fila->idPofmh}}">{{$fila->idPofmh}}</td>
                                        <td>{!! $fila->orden ?? '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>
                                            {!! $fila->Agente ?? '<span style="color: red;">Falta completar</span>' !!}
                                            <input type="hidden" name="dato2[]" value="{{$fila->Agente}}" class="dni-input" id="dni-input-{{ $fila->idPofmh }}" data-id="{{$fila->idPofmh}}" disabled>
                                            </td>
                                        <td>
                                            {!! $fila->ApeNom ?? '<span style="color: red;">Falta completar</span>' !!}
                                            <input type="hidden" name="dato3[]" value="{{$fila->ApeNom}}" class="apenom-input" id="apenom-input-{{ $fila->idPofmh }}" data-id="{{$fila->idPofmh}}" disabled>
                                        </td>
                                        <td>
                                            {!! $CargosCreados->firstWhere('idOrigenCargo', $fila->Origen)->nombre_cargo_origen ?? '<span style="color: red;">Falta completar</span>' !!}
                                        </td>
                                        <td>
                                            {!! $SitRev->firstWhere('idSituacionRevista', $fila->SitRev)->Descripcion ?? '<span style="color: red;">Falta completar</span>' !!}
                                        </td>
                                        <td>{!! $fila->Horas ?? '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->Antiguedad ?? '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>
                                            {!! $CargosSalariales->firstWhere('idCargo', $fila->Cargo)->Cargo ?? '<span style="color: red;">Falta completar</span>' !!} 
                                            <b>{!! $CargosSalariales->firstWhere('idCargo', $fila->Cargo)->Codigo ?? '' !!}</b>
                                        </td>
                                        <td>
                                            {!! $Aulas->firstWhere('idAula', $fila->Aula)->nombre_aula ?? '<span style="color: red;">Falta completar</span>' !!}
                                        </td>
                                        <td>
                                            {!! $Divisiones->firstWhere('idDivision', $fila->Division)->nombre_division ?? '<span style="color: red;">Falta completar</span>' !!}
                                        </td>
                                        <td>
                                            {!! $Turnos->firstWhere('idTurno', $fila->Turno)->nombre_turno ?? '<span style="color: red;">Falta completar</span>' !!}
                                        </td>
                                        <td>{!! $fila->EspCur ?? '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->Matricula ?? '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->FechaAltaCargo ? \Carbon\Carbon::parse($fila->FechaAltaCargo)->format('Y-m-d') : '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->FechaDesignado ? \Carbon\Carbon::parse($fila->FechaDesignado)->format('Y-m-d') : '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>
                                            {!! $Condiciones->firstWhere('idCondicion', $fila->Condicion)->Descripcion ?? '<span style="color: red;">Falta completar</span>' !!}
                                        </td>
                                        <td>
                                            {!! $Activos->firstWhere('idActivo', $fila->Activo)->nombre_activo ?? '<span style="color: red;">Falta completar</span>' !!}
                                        </td>
                                        <td>
                                            {!! $Motivos->firstWhere('idMotivo', $fila->Motivo)->Nombre_Licencia ?? '<span style="color: red;">Falta completar</span>' !!} 
                                            <b>{!! $Motivos->firstWhere('idMotivo', $fila->Motivo)->Codigo ?? '' !!}</b>
                                        </td>
                                        <td style="white-space: normal; word-wrap: break-word;">{!! $fila->DatosPorCondicion ?? '<span style="color: red; table-layout: auto;">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->FechaDesde ? \Carbon\Carbon::parse($fila->FechaDesde)->format('Y-m-d') : '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->FechaHasta ? \Carbon\Carbon::parse($fila->FechaHasta)->format('Y-m-d') : '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->AgenteR ?? '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>
                                            <button type="button" class="btn btn-default view-novedades" data-toggle="modal" data-target="#modal-novedades" data-id="{{ $fila->idPofmh }}">
                                                <i class="fas fa-newspaper"></i>
                                            </button>
                                        </td>
                                        {{--<td>{!! $fila->Asistencia ?? '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->Justificada ?? '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->Injustificada ?? '<span style="color: red;">Falta completar</span>' !!}</td>--}}
                                        <td style="white-space: normal; word-wrap: break-word;">{!! $fila->Observaciones ?? '<span style="color: red; ">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->Carrera ?? '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->Orientacion ?? '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>{!! $fila->Titulo ?? '<span style="color: red;">Falta completar</span>' !!}</td>
                                        <td>
                                            Sin Acciones
                                        </td>
                                        <td>
                                            <textarea name="ZonaSupervision"  class="zonasupervision-input" data-id="{{$fila->idPofmh}}">{{$fila->ZonaSupervision}}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                
                                
                            </table>
                        </div>
                    </div>    
                </div>

            </form>
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
                  <a class="nav-link active" href="#tab_1" data-toggle="tab">
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
                    <div class="tab-pane active" id="tab_1">
                        <form method="POST" action="{{ route('pofmhformularioNovedadParticular') }}" class="pofmhformularioNovedadParticular" id="pofmhformularioNovedadParticular">
                            @csrf
                            <div class="card-body">
                                <div class="form-row">
                                    {{--<div class="form-group">
                                        <label for="FechaInicio">Fecha Inicio</label>
                                        <input type="date" class="form-control" id="FechaInicio" name="FechaInicio" placeholder="Fecha Inicio" value="" disabled>
                                    </div>
                                    <div class="form-group" style="margin-left: 20px">
                                        <label for="FechaHasta">Fecha Hasta</label>
                                        <input type="date" class="form-control" id="FechaHasta" name="FechaHasta" placeholder="Fecha Hasta" value="" disabled>
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
                                    <div class="form-group" style="margin-left: 20px">
                                        <label for="TL">Tipo de Novedad </label>
                                        <select name="TipoNovedad" class="form-control custom-select">
                                            @foreach($NovedadesExtras as $key => $o)
                                                <option value="{{$o->idNovedadExtra}}">({{$o->tipo_novedad}})</option>
                                            @endforeach 
                                        </select>
                                    </div>--}}
                                </div>
                                <input type="hidden" id="DNI" name="DNI" value="">
                                <input type="hidden" id="novedad_dni" name="novedad_dni" value="">
                                <input type="hidden" id="novedad_apenom" name="novedad_apenom" value="">
                                <input type="hidden" id="novedad_cue" name="novedad_cue" value="">
                                <input type="hidden" id="novedad_turno" name="novedad_turno" value="">
                                <div class="form-group">
                                    {{--<label for="Observacion">Observación</label><br>
                                    <textarea class="form-control" name="Observaciones" id="novedad_observacion" rows="5" cols="100%"></textarea>--}}
                                </div>
                                
                                
                            
                            </div>
                            <div class="card-footer bg-transparent"> </div>
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

<script src="{{ asset('js/pofmh.js') }}"></script>

<!-- Incluye la librería SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

// document.getElementById('confirmBtn').addEventListener('click', function () {
//     generarExcel();
// });

function generarExcel() {
    // Simulamos la obtención de datos del servidor (puedes adaptar esto según tus necesidades)
    const infoPofMH = @json($infoPofMH); // Asumiendo que la variable está disponible en JavaScript

    // Cabecera de la institución
    const nombreInstitucion = "{{ $institucionExtension->Nombre_Institucion }}";
    const cue = "{{ $institucionExtension->CUECOMPLETO }}";
    const turno = "{{ $institucionExtension->Descripcion }}";
    // Datos del POF
    const mes = "Octubre"; // Puedes cambiar el mes según sea necesario
    const datosPOFHeader = `Datos del POF 2024 - Mes ${mes}`;

    // Convertir los datos a un formato adecuado para Excel
    const worksheetData = [];

    // Agregar la cabecera de la institución
    worksheetData.push([`Institución: ${nombreInstitucion} - CUE: ${cue} - Turno: ${turno}`]);
    worksheetData.push([datosPOFHeader]); // Agregar la línea con datos del POF

    // Encabezados de las columnas
    const headers = [
        'ID', 'Orden', 'DNI', 'Apellido y Nombre', 
        'Cargo', 'Sala/Curso/Division', 'Esp.Cur', 
        'Turno', 'Horas', 'Origen', 
        'Sit.Rev', 'Alta Cargo?', 'Designado', 
        'Condición', 'Desde', 'Hasta', 
        'Tipo', 'Otros Datos por Condición', 
        'Antigüedad Cargo c/SitRev', 'Agente / DNI Reemplazo', 
        'Novedad', 'Asistencia', 'Justificada', 
        'Injustificada', 'Observaciones', 'Acción'
    ];
    worksheetData.push(headers); // Añadir los encabezados al array

    // Recorre los datos para agregar cada fila
    infoPofMH.forEach(fila => {
        // Obtener el texto visible de cada select usando data-id en lugar de id
        const cargoSelect = document.querySelector(`select[name="CargoSalarial"][data-id="${fila.idPofmh}"]`);
        const divisionSelect = document.querySelector(`select[name="Division"][data-id="${fila.idPofmh}"]`);
        const espCurSelect = document.querySelector(`select[name="EspCur"][data-id="${fila.idPofmh}"]`);
        const turnoSelect = document.querySelector(`select[name="Turno"][data-id="${fila.idPofmh}"]`);
        const sitRevSelect = document.querySelector(`select[name="SitRev"][data-id="${fila.idPofmh}"]`);
        const motivosSelect = document.querySelector(`select[name="Motivos"][data-id="${fila.idPofmh}"]`);
        const condicionSelect = document.querySelector(`select[name="Condicion"][data-id="${fila.idPofmh}"]`);

        const cargoText = cargoSelect ? cargoSelect.options[cargoSelect.selectedIndex].text : fila.Cargo;
        const divisionText = divisionSelect ? divisionSelect.options[divisionSelect.selectedIndex].text : fila.SalaCursoDivision;
        const espCurText = espCurSelect ? espCurSelect.options[espCurSelect.selectedIndex].text : fila.EspCur;
        const turnoText = turnoSelect ? turnoSelect.options[turnoSelect.selectedIndex].text : fila.Turno;
        const sitRevText = sitRevSelect ? sitRevSelect.options[sitRevSelect.selectedIndex].text : fila.SitRev;
        const motivosText = motivosSelect ? motivosSelect.options[motivosSelect.selectedIndex].text : fila.Motivos;
        const condicionText = condicionSelect ? condicionSelect.options[condicionSelect.selectedIndex].text : fila.Condicion;

        // Obtener valores de los otros campos que no son selects, pero están en la fila usando data-id
        const altaCargoInput = document.querySelector(`input[name="AltaCargo"][data-id="${fila.idPofmh}"]`);
        const designadoInput = document.querySelector(`input[name="Designado"][data-id="${fila.idPofmh}"]`);
        // const condicionInput = document.querySelector(`input[name="Condicion"][data-id="${fila.idPofmh}"]`);
        const desdeInput = document.querySelector(`input[name="Desde"][data-id="${fila.idPofmh}"]`);
        const hastaInput = document.querySelector(`input[name="Hasta"][data-id="${fila.idPofmh}"]`);
        const otrosDatosCondicionInput = document.querySelector(`input[name="DatosPorCondicion"][data-id="${fila.idPofmh}"]`);
        const antiguedadCargoInput = document.querySelector(`input[name="Antiguedad"][data-id="${fila.idPofmh}"]`);
        const agenteDNICuandoReemplazoInput = document.querySelector(`input[name="AgenteR"][data-id="${fila.idPofmh}"]`);
        const novedadesInput = document.querySelector(`input[name="Novedades"][data-id="${fila.idPofmh}"]`);
        // Obtener los valores (o usar el valor en fila si no se encuentra el campo)
        const altaCargo = altaCargoInput ? altaCargoInput.value : fila.AltaCargo;
        const designado = designadoInput ? designadoInput.value : fila.Designado;
        // const condicion = condicionInput ? condicionInput.value : fila.Condicion;
        const desde = desdeInput ? desdeInput.value : fila.Desde;
        const hasta = hastaInput ? hastaInput.value : fila.Hasta;
        
        const otrosDatosCondicion = otrosDatosCondicionInput ? otrosDatosCondicionInput.value : fila.DatosPorCondicion;
        const antiguedadCargo = antiguedadCargoInput ? antiguedadCargoInput.value : fila.Antiguedad;
        const agenteDNICuandoReemplazo = agenteDNICuandoReemplazoInput ? agenteDNICuandoReemplazoInput.value : fila.AgenteR;
        const novedades = novedadesInput ? novedadesInput.value : fila.Novedades;

        // Agregar los datos al Excel
        worksheetData.push([
            fila.idPofmh,              // ID del registro
            fila.orden,                // Orden
            fila.Agente,               // DNI
            fila.ApeNom,               // Apellido y nombre del agente
            cargoText,                 // Texto visible del select 'Cargo'
            divisionText,              // Sala/Curso/Division
            espCurText,                // Especialidad Curricular
            turnoText,                 // Texto visible del select 'Turno'
            fila.Horas,                // Horas
            fila.Origen,               // Origen
            sitRevText,                // Texto visible del select 'Sit.Rev'
            altaCargo,                 // Alta Cargo
            designado,                 // Designado
            condicionText,                 // Condición
            desde,                     // Desde
            hasta,                     // Hasta
            motivosText,                      // Tipo
            otrosDatosCondicion,        // Otros Datos por Condición
            antiguedadCargo,           // Antigüedad Cargo c/SitRev
            agenteDNICuandoReemplazo,   // Agente / DNI Reemplazo
            novedades,              // Novedad
            fila.Asistencia,           // Asistencia
            fila.Justificada,          // Justificada
            fila.Injustificada,        // Injustificada
            fila.Observaciones,        // Observaciones
            fila.Accion                // Acción
        ]);
    });

    // Crear un libro de trabajo y una hoja
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(worksheetData);
    // Definir los anchos de las columnas
    ws['!cols'] = [
        { wpx: 50 },  // ID
        { wpx: 50 },  // Orden
        { wpx: 80 },  // DNI
        { wpx: 130 }, // Apellido y Nombre
        { wpx: 300 }, // Cargo
        { wpx: 300 }, // Sala/Curso/Division
        { wpx: 180 }, // Esp.Cur
        { wpx: 150 }, // Turno
        { wpx: 50 },  // Horas
        { wpx: 300 }, // Origen
        { wpx: 150 }, // Sit.Rev
        { wpx: 90 },  // Alta Cargo?
        { wpx: 90 },  // Designado
        { wpx: 90 },  // Condición
        { wpx: 90 },  // Desde
        { wpx: 90 },  // Hasta
        { wpx: 330 }, // Tipo
        { wpx: 200 }, // Otros Datos por Condición
        { wpx: 80 },  // Antigüedad Cargo c/SitRev
        { wpx: 80 },  // Agente / DNI Reemplazo
        { wpx: 200 }, // Novedad
        { wpx: 50 },  // Asistencia
        { wpx: 50 },  // Justificada
        { wpx: 50 },  // Injustificada
        { wpx: 330 }, // Observaciones
        { wpx: 80 }   // Acción
    ];
    

    // Definir estilos para las filas
    const headerStyle = {
        fill: { fgColor: { rgb: "00FF00" } },
        font: { bold: true }
    };
    
    const borderStyle = {
        border: {
            top: { style: "thin", color: { rgb: "0000FF" } },
            bottom: { style: "thin", color: { rgb: "0000FF" } }
        }
    };

    const lightBlueStyle = {
        fill: { fgColor: { rgb: "ADD8E6" } }
    };
    
    const whiteStyle = {
        fill: { fgColor: { rgb: "FFFFFF" } }
    };

    // Aplicar estilos a las filas
    for (let i = 0; i < worksheetData.length; i++) {
        const row = ws[`A${i + 1}`]; // A is the first column
        if (i === 1 || i === 2) {
            // Cabeceras
            Object.assign(row, headerStyle);
        } else if (i % 2 === 1) {
            // Filas impares
            Object.assign(row, lightBlueStyle);
        } else {
            // Filas pares
            Object.assign(row, whiteStyle);
        }
        // Enmarcar fila
        for (let j = 0; j < worksheetData[i].length; j++) {
            const cell = ws[XLSX.utils.encode_cell({ c: j, r: i })];
            if (cell) {
                Object.assign(cell, borderStyle);
            }
        }
    }

    // Agregar la hoja al libro de trabajo
    XLSX.utils.book_append_sheet(wb, ws, "Datos");

    // Generar un archivo Excel y descargarlo
    XLSX.writeFile(wb, "Datos_POFMH_Combos.xlsx");
}



</script>
<script>
  // Obtener la fecha actual
  const fechaActual = new Date();

  // Fecha objetivo: 18 de octubre del año actual
  const fechaObjetivo = new Date(fechaActual.getFullYear(), 9, 18); // Octubre es el mes 9

  // Calcular la diferencia en milisegundos
  const diferenciaMilisegundos = fechaObjetivo - fechaActual;

  // Convertir la diferencia de milisegundos a días
  const diferenciaDias = Math.ceil(diferenciaMilisegundos / (1000 * 60 * 60 * 24));

  // Mostrar el mensaje en la alerta
  const mensajeDiferencia = `En ${diferenciaDias} días hay que generar la POF.`;
  document.getElementById('mensajeDiferencia').innerText = mensajeDiferencia;

  // Mostrar la alerta si la diferencia es positiva
  if (diferenciaDias > 0) {
      document.getElementById('alertaDiferencia').style.display = 'block';
  } else if (diferenciaDias === 0) {
      document.getElementById('mensajeDiferencia').innerText = "¡Hoy es el 18 de octubre!";
      document.getElementById('alertaDiferencia').style.display = 'block';
  } else {
      document.getElementById('mensajeDiferencia').innerText = "El 18 de octubre ya ha pasado.";
      document.getElementById('alertaDiferencia').style.display = 'block';
  }

</script>
<script>
  function validarFecha() {
          var fechaInput = document.getElementById('FechaInicio').value;
          var regex = /^\d{4}-\d{2}-\d{2}$/;
          if (!regex.test(fechaInput)) {
              //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
              document.getElementById('FechaInicio').focus();
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato Día-Mes-Año",
    
              });
              return false; // Retorna false si el formato de fecha es inválido
          }
    
          // Dividir la fecha en sus componentes
          var partesFecha = fechaInput.split("-");
          var año = parseInt(partesFecha[0]);
          var mes = parseInt(partesFecha[1]);
          var dia = parseInt(partesFecha[2]);
    
          // Verificar si el año es válido (entre 1000 y 9999)
          if (año < 1000 || año > 9999) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Año inválido. Por favor, ingrese un año válido entre 1000 y 9999",
    
              });
              return false;
          }
    
          // Verificar si el mes es válido (entre 1 y 12)
          if (mes < 1 || mes > 12) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
    
              });
              return false;
          }
    
          // Verificar si el día es válido
          var diasEnMes = new Date(año, mes, 0).getDate();
          if (dia < 1 || dia > diasEnMes) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
    
              });
              return false;
          }
    
          // Si pasa todas las validaciones, retorna true
          return true;
      }
      function validarFecha2() {
          var fechaInput = document.getElementById('FechaHasta').value;
          var regex = /^\d{4}-\d{2}-\d{2}$/;
          if (!regex.test(fechaInput)) {
              //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
              document.getElementById('FechaHasta').focus();
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato Día-Mes-Año",
    
              });
              return false; // Retorna false si el formato de fecha es inválido
          }
    
          // Dividir la fecha en sus componentes
          var partesFecha = fechaInput.split("-");
          var año = parseInt(partesFecha[0]);
          var mes = parseInt(partesFecha[1]);
          var dia = parseInt(partesFecha[2]);
    
          // Verificar si el año es válido (entre 1000 y 9999)
          if (año < 1000 || año > 9999) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Año inválido. Por favor, ingrese un año válido entre 1000 y 9999",
    
              });
              return false;
          }
    
          // Verificar si el mes es válido (entre 1 y 12)
          if (mes < 1 || mes > 12) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
    
              });
              return false;
          }
    
          // Verificar si el día es válido
          var diasEnMes = new Date(año, mes, 0).getDate();
          if (dia < 1 || dia > diasEnMes) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
    
              });
              return false;
          }
    
          // Si pasa todas las validaciones, retorna true
          return true;
      }
      document.getElementById('FechaInicio').addEventListener('blur', validarFecha);
      document.getElementById('FechaHasta').addEventListener('blur', validarFecha2);
  </script>
 
 <script>
  $(document).ready(function() {
      // Función para cargar datos en la tabla de novedades
      function cargarNovedades() {
          var dni = $('#DNI').val();
          var cue = $('#valCUE').val();
          console.log(dni,cue);
          $.ajax({
              url: "/pofmhNovedades/" + dni + "/" + cue, // Ruta definida en web.php
              method: "GET",
              dataType: "json",
              success: function(data) {
                  // Limpiar la tabla antes de llenarla
                  console.log(data)
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
                            Sin acciones
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
                          console.log(response);
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
                    console.log(response);
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

  </script>

@endsection
