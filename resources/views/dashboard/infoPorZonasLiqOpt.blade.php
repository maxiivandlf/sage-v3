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
                      <h3 class="card-title">Zonas Activas Liquidación OPT</h3>
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
        url: '/cargar_zona_liq_opt/' + zonaId,
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

$(document).ready(function() {
    // Delegación de eventos
    $(document).on('click', '.btn-cargar-agentes', function() {
       
        var cue = $(this).data('cue');
        var filaAgentes = $('#fila-agentes-' + cue);
        var contenedorAgentes = $('#contenedor-agentes-' + cue);
        console.log(cue);
        if (contenedorAgentes.is(':empty')) {
            // Si no se han cargado los agentes aún, hacer la solicitud AJAX
            $.ajax({
                url: '/cargarAgentes',  // Aquí defines la ruta que va a procesar la solicitud
                method: 'GET',
                data: { cue: cue },
                success: function(response) {
                    // Inserta los agentes en el contenedor
                    contenedorAgentes.html(response);
                    filaAgentes.show();  // Mostrar la fila con los agentes

                    // Agregar el evento de nuevo después de la carga con AJAX
                    recargarEventosAgentes();
                },
                error: function() {
                    alert('Error al cargar los agentes.');
                }
            });
        } else {
            // Si ya están cargados, simplemente alternar la visibilidad
            filaAgentes.toggle();
        }
    });

    // Función para asegurar que los botones funcionen tras cargar el contenido por AJAX
    function recargarEventosAgentes() {
        $('.btn-cargar-agentes').off('click').on('click', function() {
            console.log("apretando después de cargar");
        });
    }
});


$(document).ready(function() {
    // Delegación de eventos
    $(document).on('click', '.btn-cargar-pof', function() {
       
        var cue = $(this).data('cue');
        var idExt = $(this).data('idext');
        var inst = $(this).data('inst');
        var filaAgentes = $('#fila-agentes-' + cue);
        var contenedorAgentes = $('#contenedor-agentes-' + cue);
        let titulo= $('#modal-pof .modal-title');
        titulo.html("Aulas Asociadas a la Institución: <b>" + inst + "</b><br>CUE: <b>"+ cue + "</b>");
       $.ajax({
            url: '/verInfoInstitucion/' + idExt + '/pof', // URL correcta
            type: 'GET',
            success: function(response) {
                if (response && response.success) {
                  var tbody = $('#modal-pof .modal-body .cuerpo-pof');
                  tbody.empty();
                  orden=1
                  response.data.forEach(function(cargo) {
                        var row = `
                            <tr>
                                <td>${orden}</td>
                                <td>
                                  <button class="abrirAulas" data-id="${cargo.idOrigenCargo}" data-cue="${cargo.CUECOMPLETO}">
                                      <i class="fa fa-plus"></i>
                                  </button>
                                </td>
                                <td>
                                  ${cargo.nombre_cargo_origen}
                                </td>
                                <td  style="text-align: left" class="columna-aulas" data-id="${cargo.nombre_origen}" data-cue="${cargo.CUECOMPLETO}">
                                    
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                        orden++
                    });

                    // Muestra el modal
                    $('#modal-pof').modal('show');
                } else {
                    console.log("No se encontró información.");
                }
            },
            error: function(xhr, status, error) {
                console.log("Error al realizar la solicitud AJAX:", error);
            }
        });
            
    });

    // Delegación para manejar el evento al hacer clic en el ícono abrirAulas
    $(document).on('click', '.abrirAulas', function() {
    var id = $(this).data('id');
    var cue = $(this).data('cue');
    var columnaAulas = $(this).closest('tr').find('.columna-aulas');
    var icon = $(this).find('i'); // Guarda el ícono dentro del botón

    // Verifica si ya se ha cargado el contenido
    if (columnaAulas.is(':empty')) {
        // Realiza la solicitud AJAX al servidor para obtener los aulas asociados
        $.ajax({
            url: '/obtenerAulasPOFMH/' + cue + '/' + id, // URL para obtener los aulas asociados
            type: 'GET',
            success: function(response) {
                if (response && response.success && Array.isArray(response.aulasAsociadas)) {
                    // Inserta los datos traídos en la columna correspondiente
                    columnaAulas.html(
                        response.aulasAsociadas.map(info => {
                            // Buscar en los arrays el nombre correspondiente para cada campo
                            const infoAula = response.Aulas.find(aula => aula.idAula === info.Aula);
                            const infoDivision = response.Divisiones.find(division => division.idDivision === info.Division);
                            const infoTurno = response.Turnos.find(turno => turno.idTurnoUsuario === info.Turno);

                            // Obtener el nombre o algún valor predeterminado en caso de que no exista
                            const nombreAula = infoAula ? infoAula.nombre_aula : "Aula desconocida";
                            const nombreDivision = infoDivision ? infoDivision.nombre_division : "División desconocida";
                            const nombreTurno = infoTurno ? infoTurno.Descripcion : "Turno desconocido";
                            const totalHoras = info.total_horas ? info.total_horas : "0"
                            return `<div>${nombreAula} - ${nombreDivision} - ${nombreTurno} - H:${totalHoras}</div>`;
                        }).join('')
                    );

                    // Cambia el ícono a menos
                    icon.removeClass('fa-plus').addClass('fa-minus');
                } else {
                  columnaAulas.html('<span style="color: red;">No se encontraron aulas asociadas</span>');
                    icon.removeClass('fa-plus').addClass('fa-minus');
                }
            },
            error: function(xhr, status, error) {
                console.log("Error al obtener las aulas asociadas:", error);
            }
        });
    } else {
        // Si ya hay contenido, limpia el contenido y cambia el ícono a más
        columnaAulas.html('');
        icon.removeClass('fa-minus').addClass('fa-plus');
    }
});



    // Función para asegurar que los botones funcionen tras cargar el contenido por AJAX
    function recargarEventosAgentes() {
        $('.btn-cargar-pof').off('click').on('click', function() {
            console.log("apretando después de cargar");
        });
    }
});
</script>

@endsection
