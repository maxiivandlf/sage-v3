@extends('layout.app')

@section('Titulo', 'Sage2.0 - Altas')

@section('LinkCSS')
<link rel="stylesheet" href="{{ asset('css/high-charts.css') }}">
<style>
  th,tr{
    text-align: center
  }
  th{
    background-color: lightblue
  }
 
  .card-body {
    padding: 0;
  }

  .table-container {
    overflow-x: auto; /* Permite el desplazamiento horizontal si el contenido es más ancho */
  }
  .card-body {
  padding: 0;
  overflow-x: auto; /* Permite el desplazamiento horizontal si el contenido es más ancho */
}

.table-container {
  width: 100%;
  overflow-x: auto; /* Asegura que la tabla pueda desplazarse horizontalmente */
}

.table {
  width: 100%;
  table-layout: fixed; /* Ajusta el ancho de las columnas */
}
</style>

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
                Estado de la Consulta: <h3>{{$estado}}</h3>
            </div>
            <div class="row">
                <div class="w-100">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Zonas Activas</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->
                      @php
                        $ZonasLiq = DB::table('tb_zonas_liq')->get();

                        $Instituciones = DB::table('tb_institucion_extension')
                        ->join('tb_turnos_usuario','tb_turnos_usuario.idTurnoUsuario','=','tb_institucion_extension.idTurnoUsuario')
                        //->selectRaw('tb_institucion_extension.*, COALESCE("Zona", "Sin Zona") as Zona')
                        //->orderBy('Zona', 'ASC')
                        ->get();

                        //dd($Instituciones);
                      @endphp
                      <div id="accordion">
                        @foreach ($ZonasLiq as $z)
                          <div class="card card-primary">
                            <div class="card-header">
                              <h4 class="card-title w-100">
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse{{$z->idZona}}">
                                  Zona <b>"{{$z->codigo_letra}}"</b> - nombre de la zona: <b>({{$z->nombre_loc_zona}})</b>
                                </a>
                              </h4>
                            </div>
                            <div id="collapse{{$z->idZona}}" class="collapse" data-parent="#accordion">
                              <div class="card-body">
                                <!-- Aquí se cargará la tabla con AJAX -->
                                <div id="tablaZona{{$z->idZona}}">Cargando datos...</div>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
  
              </div>
            <!-- Inicio Selectores -->
           
            
        </section>
    </section>
    
</section>
<!-- Modal para mostrar el gráfico -->
<div class="modal fade" id="modal-default">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Gráfico</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="chart-container" style="width: 100%; height: 400px;"></div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-user">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Gráfico</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="user-container" style="width: 100%;"></div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@endsection

@section('Script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>


<script>
  $(document).ready(function() {
    // Nombres de las series en el orden correspondiente
    var seriesNames = [
      'Cant.Agentes',
      'Titular', 
      'Interino', 
      'Suplente', 
      'Contratado', 
      'Personal Temporario', 
      'Pl.Pte.-Transf.OSC', 
      'Pl.Pte.-Transf.BCO', 
      'Personal Temporario', 
      'Planta Permanente', 
      'Normalizador', 
      'Volante', 
      'Desdoblamiento', 
      'Becados', 
      'Afectacion Externa', 
      'Cambio de Funcion', 
      'Administrativo', 
      'Afectacion Interna', 
      'Itinerante', 
      'Vac. por Jubilacion'
    ];

    // Inicializa el gráfico
    var chart = Highcharts.chart('chart-container', {
      chart: {
        type: 'column'
      },
      title: {
        text: 'Datos Dinámicos'
      },
      xAxis: {
        categories: [], // Se llenará con los datos de la fila clickeada
        crosshair: true
      },
      yAxis: {
        min: 0,
        title: {
          text: 'Valor'
        }
      },
      tooltip: {
        valueSuffix: ' unidades'
      },
      plotOptions: {
        column: {
          pointPadding: 0.2,
          borderWidth: 0
        }
      },
      series: seriesNames.map(function(name) {
        return {
          name: name,
          data: [] // Inicialmente vacío
        };
      })
    });

    // Maneja el clic en el botón para mostrar el gráfico
    $(document).on('click', '.view-details', function() {
    
      var row = $(this).closest('tr');
      var categories = [];
      var seriesData = Array(seriesNames.length).fill(0); // Inicializa el array de datos de series con 0s

      // Extrae los datos para las categorías y las series
      row.find('td').each(function(index, cell) {
        var cellValue = $(cell).text().trim();
        //console.log('Index:', index, 'Cell Value:', cellValue);
        if (index === 1) {
          // La categoría está en la columna 2 (index 1)
          categories.push(cellValue);
        } else if (index === 4) {
          // La columna 5 (index 4) es la cantidad de agentes
          // Puedes usar esta cantidad si es necesario
          // Por ahora, no la estamos utilizando directamente en el gráfico
        } else if (index >= 5 && index <= 24) {
          // Los datos de la serie están en las columnas 6 a 24 (indexes 5 a 23)
          var seriesIndex = index - 5; // Calcula el índice de la serie
          if (seriesIndex < seriesNames.length) {
            seriesData[seriesIndex] = parseFloat(cellValue) || 0; // Agrega un valor 0 si la celda está vacía
          }
        }
      });

      // Actualiza el gráfico con los datos de la fila clickeada
      chart.update({
        xAxis: {
          categories: categories
        },
        series: seriesNames.map(function(name, index) {
          
          return {
            name: name+"("+seriesData[index]+")",
            data: [seriesData[index]] // Asigna un solo valor para la categoría actual
          };
        })
      });

      // Muestra el modal
      $('#modal-default').modal('show');
    });
  });
</script>






<script>
$(document).ready(function(){
  // Captura el evento cuando se abre un acordeón
  $('#accordion').on('show.bs.collapse', function(e) {
    var zonaId = $(e.target).attr('id').replace('collapse', '');
    // Verifica si ya se cargaron los datos
    if ($('#tablaZona' + zonaId).children().length === 0) {
      // Realiza la petición AJAX para traer los datos desde el servidor
      $.ajax({
        url: '/cargar_zona/' + zonaId,
        method: 'GET',
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
            },
        success: function(data) {
          // Rellena el div con la tabla devuelta por el controlador
          $('#tablaZona' + zonaId).html(data);
        },
        error: function() {
          $('#tablaZona' + zonaId).html('<p>Error al cargar los datos.</p>');
        }
      });
    }
  });

  // Limpia los datos del acordeón cuando se cierra
  $('#accordion').on('hide.bs.collapse', function(e) {
    var zonaId = $(e.target).attr('id').replace('collapse', '');
    $('#tablaZona' + zonaId).html('Cargando datos...');
  });
});



$(document).on('click', '.view-users', function () {
    // Obtén el id de la institución
    var idInstitucion = $(this).data('id');
    console.log(idInstitucion)
    // Limpia el contenido previo del modal
    $('#user-container').html('');

    // Llama a la función que hará la petición Ajax
    cargarPersonas(idInstitucion);
});

function cargarPersonas(idInstitucion) {
    $.ajax({
        url: '/traerPersonasIdInstExt',  // URL de la ruta que manejará la solicitud
        method: 'GET',
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
            },
        data: { id: idInstitucion },  // Parámetros enviados al servidor
        success: function(response) {
            // Procesa la respuesta del servidor y actualiza el modal
            $('#user-container').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener los datos:', error);
            $('#user-container').html('<p>Error al cargar la información.</p>');
        }
    });
}
</script>
@endsection