@extends('layout.app')

@section('Titulo', $mensajeNAV)

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Inicio Tabla-Card -->
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Importante!</h5>
                        Agregar mas info sobre las novedades</b>
                        <input type="hidden" name="valCUE" id="valCUE" value="4524555">
                        @php
                            //{{$InstitucionExtension}}
                        @endphp
                    </div>
                    <div>
                        <a class="btn btn-app"  target="modal" data-toggle="modal" data-target="#modal-novedades">
                            <i class="fas fa-eye"></i> Agregar novedad
                        </a>
                    </div>
                    <div class="card card-lightblue">
                        <div class="card-header ">
                            
                            <h3 class="card-title">Novedades Inasistencias - Mes {{$mensaje}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align:center">DNI</th>
                                        <th rowspan="2" style="text-align:center">Apellido y Nombres</th>
                                        {{-- <th rowspan="2" style="text-align:center">Cargo</th>
                                        <th rowspan="2" style="text-align:center">Caracter</th> --}}
                                        <th rowspan="2" style="text-align:center">Turno</th>
                                        <th colspan="3" style="text-align:center">Servicios en el Mes</th>
                                        <th rowspan="2" style="text-align:center">Tipo de Novedad</th>
                                        <th rowspan="2" style="text-align:center">Motivo</th>
                                        <th rowspan="2" style="text-align:center">Adjuntos</th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center">Fecha Desde</th>
                                        <th style="text-align:center">Fecha Hasta</th>
                                        <th style="text-align:center">Total Días<small style="display: block; font-size:10px">(Calculo Fecha Actual -  <b><?php echo  Carbon\Carbon::now()->format('d-m-Y');?></b>)</small></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                @if($Novedades)
                                @foreach($Novedades as $key => $n)
                                        <tr data-id="{{$n->idNovedad}}" class="fila">
                                            @php
                                                $infoDocu = DB::table('tb_agentes')
                                                    ->where('tb_agentes.Documento', $n->Agente)
                                                    ->first();
                                                //dd($infoDocu);
                                            @endphp             
                                           <td data-id="{{$n->idNovedad}}">
                                            {{ !empty($infoDocu->Documento) ? $infoDocu->Documento : 'Valor Dato' }}
                                            <input type="hidden" name="dato2[]" value="{{ !empty($infoDocu->Documento) ? $infoDocu->Documento : null }}" class="dni-input" id="dni-input-{{ $n->idNovedad }}" data-id="{{$n->idNovedad}}" disabled>
                                            </td>
                                           <td>{{ !empty($infoDocu->ApeNom) ? $infoDocu->ApeNom : 'Valor Dato' }}
                                            <input type="hidden" name="dato3[]" value="{{ !empty($infoDocu->ApeNom) ? $infoDocu->ApeNom : null }}" class="apenom-input" id="apenom-input-{{ $n->idNovedad }}" data-id="{{$n->idNovedad}}" disabled>
                                           </td>
                                           
                                            {{-- <td class="text-center">{{$n->Cargo}}<b>({{$n->CodCar}})</b></td>
                                            <td class="text-center">{{$n->SitRev}}</td> --}}
                                            <td class="text-center"><b>{{$n->nombre_turno}}</b></td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($n->FechaDesde)->format('d-m-Y')}}</td>
                                            @if ($n->FechaHasta==null)
                                                <td class="text-center"><i style="color:green" class="fas fa-question-circle" title="Sin Determinar"></i></td>
                                            @else
                                                <td class="text-center">{{ \Carbon\Carbon::parse($n->FechaHasta)->format('d-m-Y')}}</td>
                                            @endif
                                            @php
                                                // Crear objetos DateTime
                                                $fechaInicialObj = new DateTime($n->FechaDesde); // 12 de octubre
                                                $fechaFinalObj = new DateTime($n->FechaHasta);   // 21 de octubre

                                                // Incluir el día final en el cálculo sumando un día
                                                $fechaFinalObj->modify('+1 day');

                                                // Calcular la diferencia
                                                $intervalo = $fechaInicialObj->diff($fechaFinalObj);

                                                // Obtener la cantidad de días
                                                $cantidadDias = $intervalo->days; // Resultado: 10 días
                                            @endphp    
                                            <td class="text-center">{{$cantidadDias}}</td>
                                            {{-- <td class="text-center">{{$n->Codigo}} - {{$n->Nombre_Licencia}} - {{$n->F3}}</td> --}}
                                            <td>
                                                @foreach ($TipoNovedades as  $t)
                                                    @if($t->idNovedadExtra == $n->idNovedadExtra)
                                                        {{$t->tipo_novedad}}
                                                    @endif
                                                    
                                                @endforeach
                                            </td>
                                            <td>{{$n->Observaciones}}</td>
                                            <td>
                                                <button type="button" class="btn btn-default view-novedades" data-toggle="modal" data-target="#modal-novedades" data-id="{{ $n->idNovedad }}">
                                                  <i class="fas fa-newspaper"></i>
                                                </button>
                                                @php
                                                   // data-id="{{ $n->idPofmh }}"
                                                @endphp
                                              </td>
                                        </tr>
                                        
                                    @endforeach    
                                @endif
                                 
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
    </section>
</section>
<!-- Modal para mostrar el gráfico -->
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
                              <th rowspan="2" style="text-align:center">Motivo</th>
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


<script type="text/javascript" charset="utf-8">
   $(document).ready(function() {
    // Configuración para el primer DataTable con los botones añadidos y en español
    $('#example').DataTable({
        "aaSorting": [[ 3, "asc" ]],
        "oLanguage": {
            "sLengthMenu": "Escuelas _MENU_ por página",
            "search": "Buscar:",
            "oPaginate": {
                "sPrevious": "Anterior",
                "sNext": "Siguiente"
            }
        },
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        "buttons": [
            {
                extend: "copy",
                text: "Copiar"
            },
            {
                extend: "csv",
                text: "CSV"
            },
            {
                extend: "excel",
                text: "Excel"
            },
            {
                extend: "pdf",
                text: "PDF"
            },
            {
                extend: "print",
                text: "Imprimir"
            },
            {
                extend: "colvis",
                text: "Visibilidad de Columnas"
            }
        ]
    }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
});

        
</script>
<script src="{{ asset('js/funcionesvarias.js') }}"></script>

<script>
$("#example").on("click", ".view-novedades", async function (event) {
    event.preventDefault(); // Evita comportamientos automáticos

    const currentRow = $(this).closest("tr"); // Encontrar la fila que contiene el botón
    const dataId = currentRow.data("id"); // Obtener el data-id de la fila

    if (!dataId) {
        console.error("No se encontró el ID de la fila.");
        return;
    }

    // Obtener valores específicos de la fila usando el data-id
    const dni = $("#dni-input-" + dataId).val(); // Obtener el valor del DNI
    console.log(dni)
    const apenom = $("#apenom-input-" + dataId).val(); // Obtener el valor de apenom
    const valCue = $("#valCUE").val(); // Asumimos que valCue es único
    const valTurno = $("#Turno").val(); // Asumimos que valTurno es único

    console.log("ID de la fila seleccionada:", dataId); // Verificar que se obtiene el ID correctamente
    console.log("DNI:", dni);
    console.log("ApeNom:", apenom);

    // Asignar valores al modal solo después de confirmar
    $("#DNI").val(dni);
    $("#novedad_dni").val(dni);
    $("#ApeNom").val(apenom);
    $("#novedad_apenom").val(apenom);
    $("#novedad_cue").val(valCue);
    $("#novedad_turno").val(valTurno);

    traerArchivos2(); // Llama a la función para traer archivos asociados
    $("#modal-novedades").modal("show"); // Muestra el modal solo después de la confirmación
});
function traerArchivos2() {
    // Obtén el valor de los elementos HTML usando jQuery
    var agente = $("#DNI").val();
    var cueX = $("#novedad_cue").val();

    // Genera el objeto de datos que contiene los parámetros
    var data = {
        _token: "{{ csrf_token() }}", // Obtén el token CSRF de Laravel
        Agente: agente,
        CueX: cueX,
    };

    // Realiza la solicitud AJAX
    $.ajax({
        url: "/traerArchivospofmh",
        type: "GET",
        data: data, // Pasa los parámetros en el objeto de datos
        success: function (data) {
            // Actualiza el contenido del modal con los archivos recibidos en la respuesta
            $("#modalBody").html(data);
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        },
    });
    //console.log("trayendo")
}
    $(document).ready(function() {
       
        // Función para cargar datos en la tabla de novedades
        function cargarNovedades() {
            console.log("dentro de carga")
            var dni = $('#DNI').val();
            var cue = $('#valCUE').val();
            console.log(dni,cue)
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
                              Sin Acciones
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
<script>
        let selectedCell;
function resetModal() {
    //document.getElementById('pofmhformularioNovedadParticular').reset(); // Resetea todos los campos del formulario
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
    const selectedDate = `${year}-${month}-${day}`;

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
</script>
@endsection