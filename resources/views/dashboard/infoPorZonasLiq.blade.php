@extends('layout.app')

@section('Titulo', 'Sage2.0 - Altas')

@section('LinkCSS')
<link rel="stylesheet" href="{{ asset('css/high-charts.css') }}">
<style>
  th, tr {
    text-align: center;
  }
  th {
    background-color: lightblue;
  }

  .card-body {
    padding: 0;
  }

  .table-container {
    width: 100%;
    overflow-x: auto; /* Permite el desplazamiento horizontal si el contenido es más ancho */
  }

  .table {
    width: auto; /* Permitir que las columnas se ajusten automáticamente */
    min-width: 100%; /* Asegura que la tabla ocupe al menos el 100% del contenedor */
  }
</style>
@endsection

@section('ContenidoPrincipal')
<section id="container" style="width: 4000px">
    <section id="main-content"  style="width: 4000px">
        <section class="content-wrapper"  style="width: 4000px">
            <div class="alert alert-warning alert-dismissible">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Importante!</h5>
                Estado de la Consulta: <h3>{{$estado}}</h3>
            </div>
            <div class="row w-100"  style="width: 4000px">
                <div class="w-100"  style="width: 4000px">
                  <div class="card"  style="width: 4000px">
                    <div class="card-header">
                      <h3 class="card-title">Zonas Activas Liquidación</h3>
                    </div>
                    <div class="card-body" style="width: 4000px">
                      <div id="accordion" style="width: 4000px">
                        @foreach ($ZonasLiq as $z)
                          <div class="card card-primary" style="width: 4000px">
                            <div class="card-header" style="width: 4000px">
                              <h4 class="card-title w-100">
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse{{$z->idZona}}">
                                  Zona <b>"{{$z->codigo_letra}}"</b> - nombre de la zona: <b>({{$z->nombre_loc_zona}})</b>
                                </a>
                              </h4>
                            </div>
                            <div id="collapse{{$z->idZona}}" class="collapse" data-parent="#accordion">
                              <div class="card-body">
                                <!-- Aquí se cargará la tabla con AJAX -->
                                <div class="table-container" style="width: 4000px">
                                  <div id="tablaZona{{$z->idZona}}">Cargando datos...</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </section>
    </section>
</section>
@endsection

@section('Script')
<script>
$(document).ready(function(){
  $('#accordion').on('show.bs.collapse', function(e) {
    var zonaId = $(e.target).attr('id').replace('collapse', '');
    if ($('#tablaZona' + zonaId).children().length === 0) {
      $.ajax({
        url: '/cargar_zona_liq/' + zonaId,
        method: 'GET',
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
            },
        success: function(data) {
          $('#tablaZona' + zonaId).html(data);
        },
        error: function() {
          $('#tablaZona' + zonaId).html('<p>Error al cargar los datos.</p>');
        }
      });
    }
  });

  $('#accordion').on('hide.bs.collapse', function(e) {
    var zonaId = $(e.target).attr('id').replace('collapse', '');
    $('#tablaZona' + zonaId).html('Cargando datos...');
  });
});

$(document).on('click', '.view-users', function () {
    var idInstitucion = $(this).data('id');
    $('#user-container').html('');
    cargarPersonas(idInstitucion);
});

function cargarPersonas(idInstitucion) {
    $.ajax({
        url: '/traerPersonasIdInstExt',
        method: 'GET',
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
            },
        data: { id: idInstitucion },
        success: function(response) {
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
