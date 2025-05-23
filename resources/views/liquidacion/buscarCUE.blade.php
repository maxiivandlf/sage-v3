@extends('layout.liquidacion')

@section('Titulo', 'Liqudación- Buscar por CUE')
@section('LinkCSS')
    <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
    <style>
        #tabla-completa {
            font-size: 1rem;
        }

        #tabla-completa td,
        #tabla-completa th {
            font-size: inherit;
        }

        #tabla-completa {
            table-layout: auto;
        }
    </style>
@endsection
@section('ContenidoPrincipal')

    <section id="container">
        <section id="main-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card card-info  col-lg-12">
                        <div class="card-header">
                            <h3 class="card-title">Agentes por unidad de liquidación</h3>
                        </div>
                        <form class="buscar_cue">
                            @csrf

                            <div class="card-body  col-lg-12">
                                <div class="row align-items-end col-lg-12">
                                    <div class="col-2">
                                        <label for="escu">Escu</label>
                                        <select class="form-control" name="escu" id="escu">
                                            <option value="">Seleccione</option>
                                            @foreach ($instarealiq_escu as $e)
                                                <option value="{{ $e->escu }}">{{ $e->escu }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label for="area">Area</label>
                                        <select class="form-control" name="area" id="area">
                                            <option value="">Seleccione</option>

                                        </select>
                                    </div>

                                    <div class="col-2">
                                        <button class="btn btn-success" id="buscar_cue">
                                            Consultar CUE
                                        </button>
                                    </div>




                                </div>
                            </div>
                        </form>
                        <!-- /.card-body -->
                    </div>

                </div>
                <!-- Tablas Detalladas -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div id="botones-acciones" class="card card-secondary" style="margin-top: 10px;">
                                <!-- Aquí se inyectarán los botones dinámicamente -->
                            </div>
                        </div>
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">INFORMACIÓN COMPLETA</h3>
                            </div>
                            <div class="card-body" style="overflow-x: auto;">
                                <table id="tabla-completa" class="table table-bordered table-striped"
                                    style="font-size: 1rem">
                                    <thead>
                                        <tr>
                                            <!-- Información Personal -->
                                            <th>APELLIDO Y NOMBRE</th>
                                            <th>DNI</th>
                                            <th>CUIL</th>
                                            <th>SEXO</th>

                                            <!-- Información POF -->
                                            <th>AGENTE</th>
                                            <th>SITUACIÓN REVISTA</th>
                                            <th>ANTIGÜEDAD</th>
                                            <th>HORA</th>
                                            <th>CARGO SALARIAL</th>
                                            <th>CÓDIGO SALARIAL</th>
                                            <th>POSESIÓN CARGO</th>
                                            <th>DESIGNADO CARGO</th>

                                            <!-- Información Institucional -->
                                            <th>CUE</th>
                                            <th>CÓDIGO LIQ</th>
                                            <th>AREA LIQ</th>
                                            <th>INSTITUCIÓN LIQ</th>
                                            <th>NIVEL</th>
                                            <th>ZONA</th>
                                            <th>DOMICILIO</th>
                                            <th>LOCALIDAD</th>

                                            <!-- Información Aúlica -->
                                            <th>INSTITUCIÓN AÚLICA</th>
                                            <th>AULA</th>
                                            <th>DIVISION</th>
                                            <th>TURNO</th>
                                            <th>ESPACIO CURRICULAR</th>
                                            <th>MATRICULA</th>
                                            <th>CONDICIÓN</th>
                                            <th>EN FUNCIÓN?</th>
                                            <th>COND. OBSERVACIÓN</th>
                                            <th>ASIST. TOTAL</th>
                                            <th>ASIST. JUST.</th>
                                            <th>ASIST. INJUST.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Se llenará dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <section class="content-wrapper">



            </section>
        </section>
    </section>

@endsection

@section('Script')


    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#example').dataTable({
                "aaSorting": [
                    [1, "asc"]
                ],
                "oLanguage": {
                    "sLengthMenu": "Escuelas _MENU_ por página",
                    "oSearch": "Buscar:",
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente"
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            var cuesearch = null;
            // Cuando selecciona ESCU
            $('#escu').on('change', function() {
                cuesearch = null;
                var escuSeleccionado = $(this).val();
                var token = $('input[name="_token"]').val(); // Si necesitás CSRF

                // Limpiar los combos dependientes
                $('#area').html('<option value="">Seleccione área</option>');
                $('#CUE').html('<option value="">Seleccione CUE+Institución</option>');

                if (escuSeleccionado !== '') {
                    $.ajax({
                        url: '{{ route('buscar.areas') }}',
                        type: 'POST',
                        data: {
                            _token: token,
                            escu: escuSeleccionado
                        },
                        success: function(areas) {
                            $.each(areas, function(index, area) {
                                $('#area').append('<option value="' + area.area + '">' +
                                    area.area + '</option>');
                            });
                        },
                        error: function(xhr) {
                            alert('Error al cargar las áreas.');
                        }
                    });
                }
            });

            // Cuando selecciona AREA
            $('#area').on('change', function() {
                cuesearch = null;
                var escuSeleccionado = $('#escu').val();
                var areaSeleccionada = $(this).val();
                var token = $('input[name="_token"]').val();

                $('#CUE').html('<option value="">Seleccione CUE+Institución</option>');

                if (escuSeleccionado !== '' && areaSeleccionada !== '') {
                    $.ajax({
                        url: '{{ route('buscar.cues') }}',
                        type: 'POST',
                        data: {
                            _token: token,
                            escu: escuSeleccionado,
                            area: areaSeleccionada
                        },
                        success: function(cues) {
                            $.each(cues, function(index, cue) {
                                $('#CUE').append('<option value="' + cue.CUEA + '">' +
                                    cue.CUEA + ' - ' + cue.nombreInstitucion +
                                    '</option>');
                                cuesearch = cue.CUEA;
                            });
                        },
                        error: function(xhr) {
                            alert('Error al cargar los CUE.');
                        }
                    });
                }
            });



            $("#buscar_cue").on("click", function(event) {
                event.preventDefault();

                var token = $('input[name="_token"]').val();

                if (!cuesearch) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Por favor, selecciona un Escu y un Area antes de buscar.",
                    });
                    return;
                }

                $.ajax({
                    url: "/buscar_cue_ajax_liq",
                    type: "POST",
                    data: {
                        _token: token,
                        cue: cuesearch,
                    },
                    success: function(response) {
                        console.log(response); // Ver respuesta completa en consola

                        $("#tabla-completa tbody").empty();
                        $("#botones-acciones").empty();

                        if (Object.keys(response).length === 0) {
                            alert("No se encontraron usuarios.");
                            return;
                        }

                        // Obtener el idInstitucionExtension
                        var idInstitucionExtension = response.idInstitucionExtension;

                        if (!idInstitucionExtension) {
                            alert("No se pudo obtener el ID de la Institución.");
                            return;
                        }

                        // Construir los botones dinámicamente
                        var botones = `
                                    <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: space-between; margin-top: 10px;">
                                        <a class="btn btn-app" href="/verPofMhidExtSuper/${idInstitucionExtension}" target="_blank">
                                            <i class="fas fa-eye"></i> Pof Nominal
                                        </a>
                                        <a class="btn btn-app" href="/verCargosCreados/${idInstitucionExtension}" target="_blank">
                                            <i class="fas fa-search"></i> Ver Cargos POF
                                        </a>
                                        <a class="btn btn-app" href="/verCargosPofvsNominal/${idInstitucionExtension}" target="_blank">
                                            <i class="fas fa-list-ol"></i> Pof vs Nominal
                                        </a>
                                        <a class="btn btn-app" href="/ver_novedades/Todo/${idInstitucionExtension}" target="_blank">
                                            <i class="fas fa-bell"></i> Novedades (Todas)
                                        </a>
                                        <a class="btn btn-app" href="/adjuntar_novedad/${idInstitucionExtension}" target="_blank">
                                            <i class="fas fa-paperclip"></i> Adjuntar Novedades
                                        </a>
                                        <a class="btn btn-app" href="/agregarNovedadParticular/${idInstitucionExtension}" target="_blank">
                                            <i class="fas fa-bell"></i> Novedades Generales
                                        </a>
                                        <a class="btn btn-app"  href="/controlDeIpeSuper/${idInstitucionExtension}" target="_blank">
                                            <i class="fas fa-check-square"></i> Ver Control IPE
                                        </a>
                                    </div>
                                `;

                        $("#botones-acciones").html(botones);

                        // Ahora iterar sobre cada grupo de datos devueltos
                        $.each(response.datos, function(dni, datos) {
                            if (dni === "idInstitucionExtension") {
                                // saltar si la clave es el ID, no un agente
                                return;
                            }

                            var institucional = datos.institucional[0] || {};
                            var aulica = datos.aulica[0] || {};

                            $.each(datos.pof, function(index, pof) {
                                var row = `
                                        <tr>
                                            <!-- Información Personal -->
                                            <td>${datos.personal?.ApeNom || "-"}</td>
                                            <td>${datos.personal?.Documento || "-"}</td>
                                            <td>${datos.personal?.Cuil || "-"}</td>
                                            <td>${
                                                datos.personal?.Sexo === "M"
                                                    ? "MASCULINO"
                                                    : datos.personal?.Sexo === "F"
                                                    ? "FEMENINO"
                                                    : "-"
                                            }</td>

                                            <!-- Información POF -->
                                            <td>${pof?.Agente || "-"}</td>
                                            <td>${pof?.Situacion_Revista || "-"}</td>
                                            <td>${pof?.Antiguedad || "-"}</td>
                                            <td>${pof?.Hora || "-"}</td>
                                            <td>${pof?.Cargo_Salarial || "-"}</td>
                                            <td>${pof?.Codigo_Salarial || "-"}</td>
                                            <td>${pof?.Posesion_Cargo || "-"}</td>
                                            <td>${pof?.Designado_Cargo || "-"}</td>

                                            <!-- Información Institucional -->
                                            <td>${institucional?.CUE || "-"}</td>
                                            <td>${institucional?.Codigo_Liq || "-"}</td>
                                            <td>${institucional?.Area_Liq || "-"}</td>
                                            <td>${
                                                institucional?.Nombre_Institucion || "-"
                                            }</td>
                                            <td>${institucional?.Nivel || "-"}</td>
                                            <td>${institucional?.Zona || "-"}</td>
                                            <td>${institucional?.Domicilio || "-"}</td>
                                            <td>${institucional?.Localidad || "-"}</td>

                                            <!-- Información Aúlica -->
                                            <td>${aulica?.Nombre_Institucion || "-"}</td>
                                            <td>${aulica?.Aula || "-"}</td>
                                            <td>${aulica?.Division || "-"}</td>
                                            <td>${aulica?.Turno || "-"}</td>
                                            <td>${aulica?.EspCur || "-"}</td>
                                            <td>${aulica?.Matricula || "-"}</td>
                                            <td>${aulica?.Condicion || "-"}</td>
                                            <td>${aulica?.EnFuncion || "-"}</td>
                                            <td>${aulica?.observacion_cond || "-"}</td>
                                            <td>${aulica?.AsisTotal || "-"}</td>
                                            <td>${aulica?.AsistJust || "-"}</td>
                                            <td>${aulica?.AsistInjust || "-"}</td>
                                        </tr>
                                    `;

                                $("#tabla-completa tbody").append(row);
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 404) {
                            alert("No se encuentra cargado ese Agente con ese CUE.");
                        } else {
                            console.error(xhr.responseText);
                            alert(
                                "Error al procesar la solicitud. Inténtalo nuevamente."
                            );
                        }

                        $("#tabla-completa tbody").empty();
                    },
                });
            });
        });
    </script>


@endsection
