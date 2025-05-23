@extends('layout.liquidacion')

@section('Titulo', 'Liquidacion - Agentes por DNI')
@section('LinkCSS')
    <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
    <style>
        td,
        th {
            text-align: center;
            vertical-align: middle;
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .table {
            display: none;
        }



        .table th {
            background-color: #f8f9fa;
        }

        #documentos-lista thead {
            height: 50px;
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
        }

        #documentos-lista thead th {
            background-color: #007bff;
            color: white;
            padding: 10px;
        }

        /* Loader para el botón */
        .spinner-border {
            display: none;
            width: 1rem;
            height: 1rem;
            border-width: 2px;
        }


        /* Esqueleto de carga */
        .skeleton {

            background-color: #e0e0e0;
            border-radius: 4px;
            animation: shimmer 1.5s infinite;
        }

        .text-skeleton {
            display: none;
            font-size: 1.5rem;
            color: #6c757d;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        @keyframes shimmer {
            0% {
                background-color: #e0e0e0;
            }

            50% {
                background-color: #a7a4a4;
            }

            100% {
                background-color: #3f4d33;
            }
        }

        .skeleton-row {
            display: none;
            height: 20px;
            margin-bottom: 10px;
        }
    </style>
@endsection
@section('ContenidoPrincipal')

    <section id="container">
        <section id="main-content">
            <section class="container-fluid" style="height: 300px !important">
                @php
                    $dni = '';
                    if (session('AgenteDuplicadoBuscado')) {
                        $dni = session('AgenteDuplicadoBuscado');
                    }
                @endphp
                <div class="row">
                    <div class="card card-info  col-lg-12">
                        <div class="card-header">
                            <h3 class="card-title">Busqueda por DNI</h3>
                        </div>
                        <form action="{{ route('buscar_dni_ajax_liq') }}" class="buscar_dni_cue" id="buscar_dni_cue"
                            method="POST">
                            @csrf
                            <div class="card-body  col-lg-12">
                                <div class="row align-items-end col-lg-12 g-3">

                                    <div class="">
                                        <label for="dni">Ingrese el DNI del agente para comenzar la busqueda:</label>
                                        <input type="text" class="form-control"
                                            placeholder="Ingrese aquí el número de DNI" name="dni"
                                            value="{{ $dni }}">
                                    </div>
                                    <div class="">
                                        <button type="submit" class="btn btn-success btn-block" id="consultar-btn">
                                            Buscar Agente
                                            <i class="fas fa-search" id="icon-search"></i>
                                            <span id="loader-circle" class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
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
                            <div class="card-header">
                                <h3 class="card-title">Información Completa del Agente</h3>
                            </div>
                            <div class="card-body">
                                <!-- Pestañas -->
                                <ul class="nav nav-tabs" id="infoTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal"
                                            role="tab" aria-controls="personal" aria-selected="true">Información
                                            Personal</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pof-tab" data-toggle="tab" href="#pof" role="tab"
                                            aria-controls="pof" aria-selected="false">Información POF</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="institucional-tab" data-toggle="tab" href="#institucional"
                                            role="tab" aria-controls="institucional" aria-selected="false">Información
                                            Institucional</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="aulica-tab" data-toggle="tab" href="#aulica" role="tab"
                                            aria-controls="aulica" aria-selected="false">Información
                                            Aúlica</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="novedadesAgente-tab" data-toggle="tab"
                                            href="#novedadesAgente" role="tab" aria-controls="aulica"
                                            aria-selected="false">Novedades Agente</a>
                                    </li>
                                </ul>

                                <div id="feedback-inicial" class="text-center m-2">
                                    <p class="fs-3">Por favor, ingrese un DNI y presione "Buscar Agente" para
                                        comenzar.</p>
                                </div>

                                <div id="skeleton-loader" class="text-center " style="height: fit-content;">
                                    <p id="text-skeleton" class="text-muted text-skeleton">Buscando agente...</p>
                                    @for ($i = 0; $i < 5; $i++)
                                        <div id="skeleton-row" class="skeleton skeleton-row"></div>
                                    @endfor
                                </div>

                                <!-- Contenido de las pestañas -->
                                <div class="tab-content flex-none" id="infoTabsContent">
                                    <!-- Información Personal -->
                                    <div class="tab-pane fade show active" id="personal" role="tabpanel"
                                        aria-labelledby="personal-tab">
                                        <table id="tabla-personal" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Apellido y Nombre</th>
                                                    <th>DNI</th>
                                                    <th>CUIL</th>
                                                    <th>Sexo</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <!-- Información POF -->
                                    <div class="tab-pane fade" id="pof" role="tabpanel" aria-labelledby="pof-tab">
                                        <table id="tabla-pof" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Situación Revista</th>
                                                    <th>Antigüedad</th>
                                                    <th>Hora</th>
                                                    <th>Cargo Salarial</th>
                                                    <th>Código Salarial</th>
                                                    <th>Posesión Cargo</th>
                                                    <th>Designado Cargo</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <!-- Información Institucional -->
                                    <div class="tab-pane fade" id="institucional" role="tabpanel"
                                        aria-labelledby="institucional-tab">
                                        <table id="tabla-institucional" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>CUE</th>
                                                    <th>Unidad Liquidación</th>
                                                    <th>Institución</th>
                                                    <th>Nivel</th>
                                                    <th>Zona</th>
                                                    <th>Domicilio</th>
                                                    <th>Localidad</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <!-- Información Aúlica -->
                                    <div class="tab-pane fade" id="aulica" role="tabpanel"
                                        aria-labelledby="aulica-tab">
                                        <table id="tabla-aulica" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Institución</th>
                                                    <th>Aula</th>
                                                    <th>División</th>
                                                    <th>Turno</th>
                                                    <th>Espacio Curricular</th>
                                                    <th>Matrícula</th>
                                                    <th>Condición</th>
                                                    <th>En Función</th>
                                                    <th>Observación</th>
                                                    <th>Asist. Total</th>
                                                    <th>Asist. Justificada</th>
                                                    <th>Asist. Injustificada</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
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
            </section>

            {{-- MODAL VER DOCUMENTOS --}}
            <div class="modal fade" id="modalVerDocumentosNovedades" tabindex="-1" role="dialog"
                aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Documentos de las Novedades del Agente</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="documentos-container " class="container-fluid d-flex justify-content-center ">
                                <table id="documentos-lista" class="table-bordered table-striped border-primary ">
                                    <thead>
                                        <tr>
                                            <th>DNI Agente</th>
                                            <th>Id Documento</th>
                                            <th>Archivo</th>
                                            <th>Ver</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Aquí se cargarán los documentos dinámicamente -->
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
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
                    "search": "Buscar:",
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente"
                    }
                }
            });
        });
    </script>


    <script src="{{ asset('js/liquidacion/buscarAgenteDni-liq.js') }}"></script>
    <script src="{{ asset('js/liquidacion/verDocumentosNovedades.js') }}"></script>
    {{-- <script src="{{ asset('js/liquidacion/cargarNovedades.js') }}"></script> --}}


@endsection
