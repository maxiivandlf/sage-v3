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


        /* .table th,
                                                                                                                                                                                                                    .table td {
                                                                                                                                                                                                                        border-radius: 10px;
                                                                                                                                                                                                                    } */
    </style>

@endsection
@section('ContenidoPrincipal')

    <div class="container">
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
                                    role="tab" aria-controls="no-cobran-ipe" aria-selected="false">Agentes sin IPE</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="sin-institucion-tab" data-toggle="tab" href="#sin-institucion"
                                    role="tab" aria-controls="sin-institucion" aria-selected="false">Agentes sin
                                    Institución</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="novedadesAgente-tab" data-toggle="tab" href="#novedadesAgente"
                                    role="tab" aria-controls="aulica" aria-selected="false">Novedades Agente</a>
                            </li>
                        </ul>

                        {{-- <div id="feedback-inicial" class="text-center m-2">
                            <p class="fs-3">Por favor, ingrese un DNI y presione "Buscar Agente" para
                                comenzar.</p>
                        </div> --}}

                        {{-- <div id="skeleton-loader" class="text-center " style="height: fit-content;">
                            <p id="text-skeleton" class="text-muted text-skeleton">Buscando agente...</p>
                            @for ($i = 0; $i < 5; $i++)
                                <div id="skeleton-row" class="skeleton skeleton-row"></div>
                            @endfor
                        </div> --}}

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
                                                <td>{{ $agente['agente']['lcat'] }}-{{ $agente['agente']['ncat'] }}</td>
                                                <td>
                                                    <button class="float-right btn btn-info">ver
                                                        cargos</button>
                                                </td>

                                            </tr>
                                            <!-- Fila colapsable con los cargos -->
                                            <tr class="collapse" id="collapse-{{ $index }}">
                                                <td colspan="8">
                                                    <table class="table table-bordered">
                                                        <buttom type="button" id="btnEditarAgente"
                                                            class="btn btn-warning view-agente" data-toggle="modal"
                                                            data-target="#modalEditarAgente"
                                                            data-docu="{{ $agente['agente']['docu'] }}"
                                                            data-nomb="{{ $agente['agente']['nomb'] }}"
                                                            data-trab="{{ $agente['agente']['trab'] }}"
                                                            data-sexo="{{ $agente['agente']['sexo'] }}"
                                                            data-escu="{{ $agente['agente']['escu'] }}"
                                                            data-area="{{ $agente['agente']['area'] }}"
                                                            data-lcat="{{ $agente['agente']['lcat'] }}"
                                                            data-ncat="{{ $agente['agente']['ncat'] }}">
                                                            <i class="fas fa-edit"></i>Editar
                                                        </buttom>
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
                                                <td>{{ $agente['agente']['escu'] }}-{{ $agente['agente']['area'] }}</td>
                                                <td>{{ $agente['agente']['lcat'] }}-{{ $agente['agente']['ncat'] }}</td>
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
                                                <td>{{ $agente['agente']['escu'] }}-{{ $agente['agente']['area'] }}</td>
                                                <td>{{ $agente['agente']['lcat'] }}-{{ $agente['agente']['ncat'] }}</td>
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
                                                <td>{{ $agente['agente']['escu'] }}-{{ $agente['agente']['area'] }}</td>
                                                <td>{{ $agente['agente']['lcat'] }}-{{ $agente['agente']['ncat'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $datos['paginacion']->links('pagination::bootstrap-4') }}
                                </div>
                            </div>

                            <!-- Novedades Agente -->
                            <div class="tab-pane fade" id="novedadesAgente" role="tabpanel"
                                aria-labelledby="novedadesAgente-tab">
                                <table id="novedadesAgente" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="text-align:center">DNI</th>
                                            <th colspan="3" style="text-align:center">Fecha Novedad</th>
                                            <th rowspan="2" style="text-align:center">Tipo Novedad</th>
                                            <th rowspan="2" style="text-align:center">Tipo Motivo</th>
                                            <th rowspan="2" style="text-align:center">Observaciones</th>
                                            <th rowspan="2" style="text-align:center">Ver Adjuntos</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align:center">Fecha Desde</th>
                                            <th style="text-align:center">Fecha Hasta</th>
                                            <th style="text-align:center">Total Días</th>



                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="modalEditarAgente" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarAgenteLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form id="formEditarAgente">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditarAgenteLabel">Editar
                                Información del Agente</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_docu" name="docu">
                            <div class="form-group">
                                <label for="edit_nomb">Apellido y
                                    Nombre</label>
                                <input type="text" class="form-control" id="edit_nomb" name="nomb"
                                    value="{{ $agente['agente']['nomb'] }}">
                            </div>
                            <div class="form-group">
                                <label for="edit_trab">Trabajo</label>
                                <input type="text" class="form-control" id="edit_trab" name="trab">
                            </div>
                            <div class="form-group">
                                <label for="edit_sexo">Sexo</label>
                                <input type="text" class="form-control" id="edit_sexo" name="sexo">
                            </div>
                            <div class="form-group">
                                <label for="edit_escu">Unidad de
                                    Liquidacion de Cobro</label>
                                <input type="text" class="form-control" id="edit_escu" name="escu">
                            </div>
                            <div class="form-group">
                                <label for="edit_area">Área</label>
                                <input type="text" class="form-control" id="edit_area" name="area">
                            </div>
                            <div class="form-group">
                                <label for="edit_lcat">Categoría</label>
                                <input type="text" class="form-control" id="edit_lcat" name="lcat">
                            </div>
                            <div class="form-group">
                                <label for="edit_ncat">Subcategoría</label>
                                <input type="text" class="form-control" id="edit_ncat" name="ncat">
                            </div>
                            <!-- Agrega más campos según sea necesario -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar
                                cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @endsection


    @section('Script')

        {{-- SCRIPT PARA CAMBIAR ESTADO --}}
        <script type="text/javascript">
            function cambiarEstado(id) {
                Swal.fire({
                    title: '¿Está seguro de cambiar el estado?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cambiarlo!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Alert.fire({
                            title: 'Cambiando estado...',
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Alert.showLoading()
                            }
                        })

                    }
                })
            }
        </script>

        {{-- SCRIPT PARA EDITAR REGISTRO --}}
        <script type="text/javascript">
            function editarRegistro(id) {
                Swal.fire({
                    title: '¿Está seguro de editar este registro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, editarlo!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Alert.fire({
                            title: 'Editando registro...',
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Alert.showLoading()
                            }
                        })
                    }
                })
            }
        </script>

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
            // function abrirModalEditar(agente) {
            //     $('#edit_docu').val(agente.docu);
            //     $('#edit_nomb').val(agente.nomb);
            //     $('#edit_trab').val(agente.trab);
            //     $('#edit_sexo').val(agente.sexo);
            //     $('#edit_escu').val(agente.escu);
            //     $('#edit_area').val(agente.area);
            //     $('#edit_lcat').val(agente.lcat);
            //     $('#edit_ncat').val(agente.ncat);
            //     // Agrega más campos si es necesario
            //     $('#modalEditarAgente').modal('show');
            // }

            // // // Opcional: Manejo del submit del formulario
            // // $('#formEditarAgente').on('submit', function(e) {
            // //     e.preventDefault();
            // //     // Aquí puedes hacer un AJAX para guardar los cambios o enviar el formulario
            // //     Swal.fire('Guardado', 'Los cambios han sido guardados.', 'success');
            // //     $('#modalEditarAgente').modal('hide');
            // });

            $(document).on("click", "#btnEditarAgente", function(event) {
                event.preventDefault();

                $('#edit_docu').val($(this).data('docu'));
                $('#edit_nomb').val($(this).data('nomb'));
                $('#edit_trab').val($(this).data('trab'));
                $('#edit_sexo').val($(this).data('sexo'));
                $('#edit_escu').val($(this).data('escu'));
                $('#edit_area').val($(this).data('area'));
                $('#edit_lcat').val($(this).data('lcat'));
                $('#edit_ncat').val($(this).data('ncat'));

            })
        </script>
    @endsection
