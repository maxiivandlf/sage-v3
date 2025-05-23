@extends('layout.liquidacion')

@section('Titulo', 'Liquidacion - Control')
@section('LinkCSS')
    <style>
        .custom-select {
            padding: 20px
        }

        .nav-tabs .nav-link {
            background-color: #90D1CA;
            color: #495057;
            border: 1px solid #8DD8FF;
            border-radius: 0.25rem 0.25rem 0 0;

        }

        .nav-tabs .nav-link.active {
            background-color: #096B68;
            color: #ffffff;
            border: 1px solid #096B68;

        }

        .nav-tabs .nav-link:hover {
            background-color: #BBFBFF;
            color: #495057;
            border: 1px solid #8DD8FF;
        }

        .table {
            border-radius: 15px;
            overflow: hidden;
        }

        .table thead th {
            position: sticky;
            top: 0;
            z-index: 2;

        }

        .collapse table {
            background-color: #EEEEEE
        }


        /* .table th,.table td {border-radius: 10px;} */
    </style>

@endsection
@section('ContenidoPrincipal')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header " style="background-color: #732255;">
                        <h3 class="card-title">Información Completa del Agente</h3>
                    </div>
                    <div class="container m-3">
                        <form method="GET" action="{{ route('controlIpe') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Buscar por DNI o Nombre" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Buscar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <!-- Pestañas -->
                        <ul class="nav nav-tabs" id="infoTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="inconsistencia-tab" data-toggle="tab" href="#inconsistencia"
                                    role="tab" aria-controls="inconsistencia" aria-selected="true">Inconsistencia</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cobran-ipe-tab" data-toggle="tab" href="#cobran-ipe" role="tab"
                                    aria-controls="cobran-ipe" aria-selected="false">Agentes con IPE</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="no-cobran-ipe-tab" data-toggle="tab" href="#no-cobran-ipe"
                                    role="tab" aria-controls="no-cobran-ipe" aria-selected="false">Agentes sin
                                    IPE</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="sin-institucion-tab" data-toggle="tab" href="#sin-institucion"
                                    role="tab" aria-controls="sin-institucion" aria-selected="false">Agentes sin
                                    Institución</a>
                            </li>

                        </ul>

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content flex-none" id="infoTabsContent">
                            <!-- Información inconsistencias -->
                            <div class="tab-pane fade show active" id="inconsistencia" role="tabpanel"
                                aria-labelledby="inconsistencia-tab">
                                <table style="max-height: 19px" id="tabla-inconsistencia"
                                    class="table table-bordered table-dt">
                                    <thead style="background-color: #7F55B1;border-radius: 30%">
                                        <tr style="color: white">
                                            <th>Documento </th>
                                            <th>Apellido y Nombre</th>
                                            <th>Trabajo</th>
                                            <th>Sexo</th>
                                            <th>Unidad de Liquidacion de Cobro</th>
                                            <th>Categoria</th>
                                            <th>Ver Cargos</th>

                                        </tr>
                                    </thead>
                                    <tbody style="position: relative;">



                                        @foreach ($datos['inconsistencias'] as $index => $agente)
                                            <!-- Fila principal del agente -->
                                            <tr data-toggle="collapse" data-target="#collapse-{{ $index }}"
                                                aria-expanded="false" aria-controls="collapse-{{ $index }}"
                                                style="{{ $index % 2 == 0 ? 'background-color: #B2C6D5' : 'background-color: #E7F2E4' }}">
                                                <td>{{ $agente['agente']['docu'] }}</td>
                                                <td>{{ $agente['agente']['nomb'] }}</td>
                                                <td>{{ $agente['agente']['trab'] }}</td>
                                                <td>{{ $agente['agente']['sexo'] }}</td>
                                                <td>
                                                    {{ $agente['agente']['escu'] }}-{{ $agente['agente']['area'] }}

                                                </td>
                                                <td>{{ $agente['agente']['lcat'] }}-{{ $agente['agente']['ncat'] }}
                                                </td>
                                                <td>
                                                    <button class="float-right btn btn-info">ver
                                                        cargos</button>
                                                </td>

                                            </tr>
                                            <!-- Fila colapsable con los cargos -->
                                            <tr class="collapse" id="collapse-{{ $index }}">

                                                <td colspan="8">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Institución</th>
                                                                <th>CUE</th>
                                                                <th>Situación Revista</th>
                                                                <th>Origen</th>
                                                                <th>Unidad Liquidacion Recibo</th>
                                                                <th>Otras Unidades Declaradas</th>
                                                                <th>Condición</th>
                                                                <th>Activo</th>
                                                                <th>Total Horas</th>
                                                                <th>Novedades</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @foreach ($agente['agrup'] as $cargo)
                                                                <tr>
                                                                    <td>{{ $cargo['nombreInstitucion'] }}</td>
                                                                    <td>{{ $cargo['CUECOMPLETO'] }}</td>
                                                                    <td>{{ $cargo['SitRev'] }}</td>
                                                                    <td>{{ $cargo['Origen'] }}</td>
                                                                    <td>{{ $cargo['Unidad_Liquidacion_Recibo'] }}-{{ $cargo['Codigo_Area_Recibo'] }}
                                                                    </td>
                                                                    <td>
                                                                        <p style="margin: 0">
                                                                            {{ $cargo['escu1'] }}-{{ $cargo['area1'] ?? 'S/D' }}
                                                                        </p>
                                                                        <p style="margin: 0">
                                                                            {{ $cargo['escu2'] }}-{{ $cargo['area2'] ?? 'S/D' }}
                                                                        </p>
                                                                        <p style="margin: 0">
                                                                            {{ $cargo['escu3'] }}-{{ $cargo['area3'] ?? '' }}
                                                                        </p>


                                                                    </td>
                                                                    <td>{{ $cargo['Condicion'] }}</td>
                                                                    <td>{{ $cargo['Activo'] }}</td>
                                                                    <td>{{ $cargo['TotalHoras'] }}</td>
                                                                    <td>
                                                                        <buttom type="button" id="btnVerNovedades"
                                                                            class="btn btn-warning view-agente btnVerNovedades"
                                                                            data-toggle="modal"
                                                                            data-target="#modalVerNovedades"
                                                                            data-docu="{{ $agente['agente']['docu'] }}"
                                                                            data-cuecompleto="{{ $cargo['CUECOMPLETO'] }}">
                                                                            <i class="fas fa-clipboard"></i> ver
                                                                        </buttom>
                                                                    </td>

                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        {{-- modal editar agente --}}

                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                {{-- Controles de paginación --}}
                                <div class="d-flex justify-content-center">
                                    {{ $datos['paginacion']->links('pagination::bootstrap-4') }}
                                </div>
                            </div>

                            <!-- Información agentes cobran ipe  -->
                            <div class="tab-pane fade" id="cobran-ipe" role="tabpanel" aria-labelledby="cobran-ipe-tab">
                                <table id="tabla-cobran-ipe" class="table table-bordered table-dt">
                                    <thead style="background-color: #7F55B1;border-radius: 30%">
                                        <tr style="color: white">
                                            <th>Documento </th>
                                            <th>Apellido y Nombre</th>
                                            <th>Trabajo</th>
                                            <th>Sexo</th>
                                            <th>Unidad de Liquidacion</th>
                                            <th>Categoria</th>
                                            <th>Ver Cargos</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($datos['agentesConIpe'] as $index => $agente)
                                            <!-- Fila principal del agente -->
                                            <tr data-toggle="collapse" data-target="#collapse-{{ $index }}"
                                                aria-expanded="false" aria-controls="collapse-{{ $index }}"
                                                style="{{ $index % 2 == 0 ? 'background-color: #B2C6D5' : 'background-color: #E7F2E4' }}">
                                                <td>{{ $agente['agente']['docu'] }}</td>
                                                <td>{{ $agente['agente']['nomb'] }}</td>
                                                <td>{{ $agente['agente']['trab'] }}</td>
                                                <td>{{ $agente['agente']['sexo'] }}</td>
                                                <td>{{ $agente['agente']['escu'] }}-{{ $agente['agente']['area'] }}
                                                </td>
                                                <td>{{ $agente['agente']['lcat'] }}-{{ $agente['agente']['ncat'] }}
                                                </td>
                                                <td>
                                                    <button class="float-right btn btn-info">Clic para ver
                                                        cargos</button>
                                                </td>
                                            </tr>
                                            <!-- Fila colapsable con los cargos -->
                                            <tr class="collapse" id="collapse-{{ $index }}">
                                                <td colspan="8">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Institución</th>
                                                                <th>CUE</th>
                                                                <th>Situación Revista</th>
                                                                <th>Origen</th>
                                                                <th>IPE según escuela</th>
                                                                <th>Condición</th>
                                                                <th>Activo</th>
                                                                <th>Total Horas</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($agente['agrup'] as $cargo)
                                                                <tr>
                                                                    <td>{{ $cargo['nombreInstitucion'] }}</td>
                                                                    <td>{{ $cargo['CUECOMPLETO'] }}</td>
                                                                    <td>{{ $cargo['SitRev'] }}</td>
                                                                    <td>{{ $cargo['Origen'] }}</td>
                                                                    <td>{{ $cargo['IPE'] }}</td>
                                                                    <td>{{ $cargo['Condicion'] }}</td>
                                                                    <td>{{ $cargo['Activo'] }}</td>
                                                                    <td>{{ $cargo['TotalHoras'] }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $datos['paginacion']->links('pagination::bootstrap-4') }}
                                </div>
                            </div>

                            <!-- Información Agentes sin IPE -->
                            <div class="tab-pane fade" id="no-cobran-ipe" role="tabpanel"
                                aria-labelledby="cobran-ipe-tab">
                                <table id="tabla-no-cobran-ipe" class="table table-bordered">
                                    <thead style="background-color: #7F55B1;border-radius: 30%">
                                        <tr style="color: white">
                                            <th>Documento </th>
                                            <th>Apellido y Nombre</th>
                                            <th>Trabajo</th>
                                            <th>Sexo</th>
                                            <th>Unidad de Liquidacion</th>
                                            <th>Categoria</th>
                                            <th>Ver Cargos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos['agentesSinIpe'] as $index => $agente)
                                            <!-- Fila principal del agente -->
                                            <tr data-toggle="collapse" data-target="#collapse-{{ $index }}"
                                                aria-expanded="false" aria-controls="collapse-{{ $index }}"
                                                style="{{ $index % 2 == 0 ? 'background-color: #B2C6D5' : 'background-color: #E7F2E4' }}">
                                                <td>{{ $agente['agente']['docu'] }}</td>
                                                <td>{{ $agente['agente']['nomb'] }}</td>
                                                <td>{{ $agente['agente']['trab'] }}</td>
                                                <td>{{ $agente['agente']['sexo'] }}</td>
                                                <td>{{ $agente['agente']['escu'] }}-{{ $agente['agente']['area'] }}
                                                </td>
                                                <td>{{ $agente['agente']['lcat'] }}-{{ $agente['agente']['ncat'] }}
                                                </td>
                                                <td>
                                                    <button class="float-right btn btn-info">Clic para ver
                                                        cargos</button>
                                                </td>
                                            </tr>
                                            <!-- Fila colapsable con los cargos -->
                                            <tr class="collapse" id="collapse-{{ $index }}">
                                                <td colspan="8">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Institución</th>
                                                                <th>CUE</th>
                                                                <th>Situación Revista</th>
                                                                <th>Origen</th>
                                                                <th>IPE</th>
                                                                <th>Condición</th>
                                                                <th>Activo</th>
                                                                <th>Total Horas</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($agente['agrup'] as $cargo)
                                                                <tr>
                                                                    <td>{{ $cargo['nombreInstitucion'] }}</td>
                                                                    <td>{{ $cargo['CUECOMPLETO'] }}</td>
                                                                    <td>{{ $cargo['SitRev'] }}</td>
                                                                    <td>{{ $cargo['Origen'] }}</td>
                                                                    <td>{{ $cargo['IPE'] }}</td>
                                                                    <td>{{ $cargo['Condicion'] }}</td>
                                                                    <td>{{ $cargo['Activo'] }}</td>
                                                                    <td>{{ $cargo['TotalHoras'] }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $datos['paginacion']->links('pagination::bootstrap-4') }}
                                </div>
                            </div>

                            <!-- Información Agentes sin Institucion -->
                            <div class="tab-pane fade" id="sin-institucion" role="tabpanel"
                                aria-labelledby="aulica-tab">
                                <table id="tabla-sin-institucion" class="table table-bordered">
                                    <thead style="background-color: #7F55B1;border-radius: 30%">
                                        <tr style="color: white">
                                            <th>Documento </th>
                                            <th>Apellido y Nombre</th>
                                            <th>Trabajo</th>
                                            <th>Sexo</th>
                                            <th>Unidad de Liquidacion</th>
                                            <th>Categoria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos['sinInstitucion'] as $index => $agente)
                                            <tr
                                                style="{{ $index % 2 == 0 ? 'background-color: #B2C6D5' : 'background-color: #E7F2E4' }}">
                                                <td>{{ $agente['agente']['docu'] }}</td>
                                                <td>{{ $agente['agente']['nomb'] }}</td>
                                                <td>{{ $agente['agente']['trab'] }}</td>
                                                <td>{{ $agente['agente']['sexo'] }}</td>
                                                <td>{{ $agente['agente']['escu'] }}-{{ $agente['agente']['area'] }}
                                                </td>
                                                <td>{{ $agente['agente']['lcat'] }}-{{ $agente['agente']['ncat'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $datos['paginacion']->links('pagination::bootstrap-4') }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <x-modal-component modalId="modalVerDocumentosNovedades" title="Documentos de Novedades">
            <div id="documentos-lista" class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Agente</th>
                            <th>ID Documento</th>
                            <th>URL</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </x-modal-component>

        {{-- Ejemplo con una tabla --}}
        <x-modal-component modalId="modalVerNovedades" title="Tabla de novedades">
            @include('liquidacion.partials.tablaNovedades')
        </x-modal-component>
    </div>
@endsection


@section('Script')


    {{-- SCRIPT DATATABLES --}}
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#tabla-cobran-ipe').dataTable({
                "aaSorting": [
                    [1, "asc"]
                ],
                "oLanguage": {
                    "sLengthMenu": "Agentes por página _MENU_",
                    "sZeroRecords": "No se encontraron resultados",
                    "sInfo": "Mostrando de _START_ a _END_ de _TOTAL_ Agentes",
                    "sInfoEmpty": "Mostrando de 0 a 0 de 0 Agentes",
                    "sInfoFiltered": "(filtrado de _MAX_ total Agentes)",

                    "sSearch": "Buscar:",
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
            $(".btnVerNovedades").on("click", function(event) {
                event.preventDefault();

                var dni = $(this).data("docu");
                var cuecompleto = $(this).data("cuecompleto");
                var token = $('meta[name="csrf-token"]').attr("content");

                $.ajax({
                    url: "/liquidacion/verNovedadesAgente_Cue",
                    method: "POST",
                    data: {
                        _token: token,
                        dni: dni,
                        cuecompleto: cuecompleto,
                    },

                    success: function(novedadesAgente) {
                        $("#tb_novedadesAgente tbody").empty();
                        if (novedadesAgente.length > 0) {
                            $.each(novedadesAgente, function(key, novedad) {
                                var row = `
                                    <tr class="gradeX" data-id="${novedad.idNovedad}">
                                        <td>${novedad.Agente || "Sin datos"}</td>
                                        <td class="text-center">${new Date(
                                            novedad.FechaDesde
                                        ).toLocaleDateString("es-ES")}</td>
                                        <td class="text-center">${new Date(
                                            novedad.FechaHasta
                                        ).toLocaleDateString("es-ES")}</td>
                                        <td class="text-center">${
                                            novedad.TotalDiasLicencia || "1"
                                            }</td>
                                            <td class="text-center">${
                                                novedad.tipo_novedad || "Sin novedad"
                                        }</td>
                                        <td class="text-center">${novedad.Motivo}</td>
                                        <td>${
                                            novedad.Observaciones || "Sin observaciones"
                                            }</td>
                                            <td>
                                                <buttom type="button" id="btnVerDocumentosNovedades" class="btn btn-default view-novedades" data-toggle="modal" data-target="#modal-novedades" 
                                                data-idnovedad="${novedad.Agente}">
                                                <i class="fas fa-paperclip"></i>
                                                </buttom>
                                                
                                            </td>
                                            </tr>`;
                                $("#tb_novedadesAgente tbody").append(row);
                            });

                        } else {

                            $("#tb_novedadesAgente tbody").append(
                                `<tr><td colspan='7' class='text-center'>${novedadesAgente.message}</td></tr>`
                            );
                        }
                    },
                    complete: function() {},
                    error: function(xhr) {
                        console.error(
                            "Hubo un error al obtener los datos de novedades del agente."
                        );
                    },
                });
            });
        });
    </script>


    <script src="{{ asset('js/liquidacion/verDocumentosNovedades.js') }}"></script>
@endsection
