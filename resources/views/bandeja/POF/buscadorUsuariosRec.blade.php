@extends('layout.app')

@section('Titulo', 'Sage2.0 - Altas')
@section('LinkCSS')
  <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
@endsection
@section('ContenidoPrincipal')
{{-- <div class="loader">
    <h2>Por favor, espere...</h2>
    <div id="clock"></div>
  </div> --}}
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <div class="alert alert-warning alert-dismissible">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Importante!</h5>
                Trabajando para recuperar usarios en CUE: <h3 id="valCUE">{{$CUECOMPLETO}}</h3>
            </div>
            <div class="row">
                <div class="card card-info  col-lg-12">
                    <div class="card-header">
                      <h3 class="card-title">Busqueda por DNI</h3>
                    </div>
                    <form action="{{ route('buscar_dni_ajax') }}"  class="buscar_dni_cue" id="buscar_dni_cue" method="POST" >
                    @csrf
                    <div class="card-body  col-lg-12">
                      <div class="row  col-lg-12">
                        
                          <div class="col-6">
                            <input type="text" class="form-control" placeholder="DNI del agente o parte del nombre" name="dni">
                          </div>
                          <div class="col-6">
                            <input type="submit" class="form-control btn-success" value="Consultar DNI" name="btnDNI">
                          </div>
                        
                        
                      </div>
                    </div>
                    </form>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Inicio Tabla-Card -->
                    
                    <div class="card card-lightblue">
                        <div class="card-header ">
                            
                            <h3 class="card-title">Usuarios Encontrados</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example-pof" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th rowspan="1" style="text-align:center">ID</th>
                                        <th rowspan="1" style="text-align:center">DNI</th>
                                        <th rowspan="1" style="text-align:center">Apellido y Nombres</th>
                                        <th rowspan="1" style="text-align:center">CODIGO LIQ</th>
                                        <th rowspan="1" style="text-align:center">Nombre Institución</th>
                                        <th rowspan="1" style="text-align:center">Nivel</th>
                                        <th rowspan="1" style="text-align:center">Situacion Revista</th>
                                        <th rowspan="1" style="text-align:center">Antiguedad</th>
                                        <th rowspan="1" style="text-align:center">Horas</th>
                                        <th rowspan="1" style="text-align:center">Agrupamiento</th>
                                        <th rowspan="1" style="text-align:center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                       
                        
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

               
            </div>
            
        </section>
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
                        <div id="alertaDiferencia" class="alert alert-info mt-3" role="alert" style="display: none;">
                          <i class="bi bi-clock"> </i> 
                          <span id="mensajeDiferencia"></span>
                          <div class="botonera-derecha">
                            <label>Prueba Activada: </label>
                            {{-- <button type="button" class="confirm-btn" id="confirmBtn">
                                <i class="fas fa-file-export"></i> Generar PDF/Excel (Falta Pulir Mas)
                            </button> --}}
                          </div>
                        </div>
                      
                    </div>
                    
                    
                        @csrf <!-- Agregar el token CSRF de Laravel -->
                    
                        <!-- Botón para agregar la primera fila -->
                        {{-- <div class="botonera">
                            <div class="botonera-izquierda">
                              <button type="button" class="add-first-row-btn" id="addFirstRowBtn">
                                <i class="fas fa-plus"></i> Crear primera fila
                              </button>
                              <button type="button" class="add-ultimo-row-btn" id="addLastRow">
                                  <i class="fas fa-plus-circle"></i> Crear Fila al Último
                              </button>
                            </div>
                        </div> --}}
                        <table id="POFMH">
                            <thead>
                                <tr>
                                    <th class="custom-5rem" id="tablaarriba">#ID</th>
                                    <th class="custom-5rem">Orden</th>
                                    <th class="custom-8rem">DNI</th>
                                    <th class="custom-13rem">Apellido y Nombre</th>
        
                                    <th class="custom-30rem">Cargo de Origen en la Institución</th>
                                    
                                    <th class="custom-15rem">Sit.Rev</th>
                                    <th class="custom-5rem">Horas</th>
                                    <th class="custom-13rem">Antigüedad Docente</th>
                                    <th class="custom-33rem">Código Cargo</th>
                                    <th class="custom-8rem">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                              @if ($infoPofMH->isNotEmpty())
                                @foreach ($infoPofMH as $fila)
                                  <tr data-id="{{$fila->idPofmh}}">
                                    <td>{{$fila->idPofmh}}</td>
                                    <td>
                                        <input type="text" name="dato1[]" value="{{$fila->orden}}" class="orden-input" data-id="{{$fila->idPofmh}}" disabled>
                                      </td>
                                    <td>
                                        <input type="text" name="dato2[]" value="{{$fila->Agente}}" class="dni-input" id="dni-input-{{ $fila->idPofmh }}" data-id="{{$fila->idPofmh}}" disabled>
                                    </td>
                                    <!-- Otros campos -->
                                    <td>
                                      <input type="text" name="dato3[]" value="{{$fila->ApeNom}}" class="apenom-input" id="apenom-input-{{ $fila->idPofmh }}" data-id="{{$fila->idPofmh}}" disabled>
                                    </td>
                                    <td>
                                      <select class="form-control origen-input" name="Origen"  data-id="{{$fila->idPofmh}}" id="Origen">
                                      @foreach ($CargosCreados as $cargo)
                                        @if ($cargo->idOrigenCargo == $fila->Origen)
                                          <option value="{{$cargo->idOrigenCargo}}" selected>{{$cargo->nombre_origen}}</option>
                                        @else
                                          <option value="{{$cargo->idOrigenCargo}}">{{$cargo->nombre_origen}}</option>
                                        @endif   
                                      @endforeach
                                    </select>
                                    </td>
                                    
                                    <td>
                                      <select class="form-control sitrev-input" name="SitRev" id="SitRev" data-id="{{$fila->idPofmh}}">
                                        @foreach($SitRev as $key => $o)
                                            @if ($o->idSituacionRevista == $fila->SitRev)
                                                <option value="{{$o->idSituacionRevista}}" selected="selected">{{$o->Descripcion}}</option>
                                            @else
                                                <option value="{{$o->idSituacionRevista}}">{{$o->Descripcion}}</option>
                                            @endif
                                        @endforeach
                                      </select>
                                    </td>
                                    <td>
                                      <input type="text" name="Horas" value="{{$fila->Horas}}" class="horas-input" data-id="{{$fila->idPofmh}}" disabled>
                                    </td>
                                    <td>
                                      <input type="text" name="Antiguedad" value="{{$fila->Antiguedad}}" class="antiguedad-input" data-id="{{$fila->idPofmh}}" disabled>
                                    </td>
                                    <td>
                                      <select class="form-control cargo-input" name="CargoSalarial"  data-id="{{$fila->idPofmh}}" id="CargoSalarial">
                                        @foreach ($CargosSalariales as $cargo)
                                          @if ($cargo->idCargo == $fila->Cargo)
                                            <option value="{{$cargo->idCargo}}" selected>{{$cargo->Cargo}}<b>({{$cargo->Codigo}})</b></option>
                                          @else
                                            <option value="{{$cargo->idCargo}}">{{$cargo->Cargo}}<b>({{$cargo->Codigo}})</b></option>
                                          @endif
                                        @endforeach
                                      </select>
                                    </td>
                                    
                                    <td>
                                        <span class="add-row">
                                            <i class="fas fa-plus-circle"></i>
                                        </span>
                                        <span class="confirm-row">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <span class="delete-row">
                                            <i class="fas fa-eraser"></i>
                                        </span>
                                    </td>
        
                                    
                                </tr>
                                @endforeach
                              @endif
                                <!-- Inicialmente vacío -->
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
                                    <div class="form-group" style="margin-left: 20px">
                                        <label for="TL">Tipo de Novedad </label>
                                        <select name="TipoNovedad" class="form-control custom-select">
                                            @foreach($NovedadesExtras as $key => $o)
                                                <option value="{{$o->idNovedadExtra}}">({{$o->tipo_novedad}})</option>
                                            @endforeach 
                                        </select>
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
    </section>
</section>

@endsection

@section('Script')


    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#example').dataTable( {
                "aaSorting": [[ 1, "asc" ]],
                "oLanguage": {
                    "sLengthMenu": "Escuelas _MENU_ por página",
                    "search": "Buscar:",
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente"
                    }
                }
            } );
        } );
  </script>


<script src="{{ asset('js/funcionesvarias.js') }}"></script>
<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/pofmh.js') }}"></script>
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
@endsection