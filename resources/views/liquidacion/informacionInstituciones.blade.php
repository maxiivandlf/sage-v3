@extends('layout.liquidacion')

@section('Titulo', 'Liquidación - Información Instituciones')
@section('LinkCSS')
    <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
    <style>
        td {
            text-align: center;
        }
    </style>
@endsection
@section('ContenidoPrincipal')
    {{-- <div class="loader">
    <h2>Por favor, espere...</h2>
    <div id="clock"></div>
  </div> --}}
    <section id="container">
        <section id="main-content">
            <section class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Información Instituciones Educativas</h3>
                            </div>
                            <div class="card-body" style="overflow-x: auto;">
                                {{-- BUSCADOR --}}
                                <div class="container-fluid m-1">
                                    <form id="searchForm" class="row col-12">
                                        <div class="input-group">
                                            <input type="search" id="searchInput" class="form-control"
                                                placeholder="Ingrese el texto a buscar" aria-label="Search">
                                        </div>
                                    </form>
                                </div>
                                <form id="form-actualizar">
                                    @csrf
                                    <table id="tabla-completa" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>ID_CUEA</th>
                                                <th>CUEA</th>
                                                <th>Institución</th>
                                                <th>Nivel</th>
                                                <th>Modalidad</th>
                                                <th>Zona</th>
                                                <th>Código Zona</th>
                                                <th>Escu</th>
                                                <th>Desc Escu</th>
                                                <th>Area</th>
                                                <th>NoIPE</th>
                                                <th>Estado</th>
                                                <th>Acción</th> <!-- Columna nueva -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($datos as $dato)
                                                <tr data-id="{{ $dato->ID_inst_area_liq }}">
                                                    <td>{{ $dato->ID_inst_area_liq }}</td>
                                                    <td contenteditable="true">{{ $dato->ID_CUEA ?? 'S/D' }}</td>
                                                    <td contenteditable="true">{{ $dato->CUEA ?? 'S/D' }}</td>
                                                    <td contenteditable="true">{{ $dato->nombreInstitucion ?? 'S/D' }}</td>
                                                    <td contenteditable="true">{{ $dato->nivel ?? 'S/D' }}</td>
                                                    <td contenteditable="true">{{ $dato->modalidad ?? 'S/D' }}</td>
                                                    <td contenteditable="true">{{ $dato->zonaLiq ?? 'S/D' }}</td>
                                                    <td contenteditable="true">{{ $dato->codZonaLiq ?? 'S/D' }}</td>
                                                    <td contenteditable="true">{{ $dato->escu ?? 'S/D' }}</td>
                                                    <td contenteditable="true">{{ $dato->desc_escu ?? 'S/D' }}</td>
                                                    <td contenteditable="true">{{ $dato->area ?? 'S/D' }}</td>
                                                    <td contenteditable="true">{{ $dato->NoIPE ?? 'S/D' }}</td>
                                                    <td contenteditable="true">{{ $dato->ESTADO ?? 'S/D' }}</td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-success btn-sm guardar-fila">Guardar</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
                    "search": "Buscar:",
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente"
                    }
                }
            });
        });
    </script>

    <script src="{{ asset('js/searchliq.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.guardar-fila', function() {
                var fila = $(this).closest('tr');
                var id = fila.data('id');
                var celdas = fila.find('td');

                var datos = {
                    ID_inst_area_liq: id,
                    ID_CUEA: $(celdas[1]).text().trim(),
                    CUEA: $(celdas[2]).text().trim(),
                    nombreInstitucion: $(celdas[3]).text().trim(),
                    nivel: $(celdas[4]).text().trim(),
                    modalidad: $(celdas[5]).text().trim(),
                    zonaLiq: $(celdas[6]).text().trim(),
                    codZonaLiq: $(celdas[7]).text().trim(),
                    escu: $(celdas[8]).text().trim(),
                    desc_escu: $(celdas[9]).text().trim(),
                    area: $(celdas[10]).text().trim(),
                    NoIPE: $(celdas[11]).text().trim(),
                    ESTADO: $(celdas[12]).text().trim()
                };

                console.log(datos); // Verificamos que los datos estén bien

                $.ajax({
                    url: '{{ route('actualizar_instarealiq') }}',
                    method: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        cambios: [datos] // 👈 Mandamos como array de 1 solo objeto
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Guardado!',
                            text: response.message
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo guardar esta fila.'
                        });
                        console.error(xhr.responseText);
                    }
                });
            });
        });
        $(document).ready(function() {
            // Buscador en vivo sobre la tabla completa
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();

                $('#tabla-completa tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>

@endsection
