@extends('layout.liquidacion')

@section('Titulo', 'Liquidacion - Control')
@section('LinkCSS')
    <style>
        .custom-select {
            padding: 20px
        }
    </style>

@endsection
@section('ContenidoPrincipal')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Liquidación de Agentes</h5>
                    </div>
                    <div class="card-body">

                        <table id="tablaTemporales" class="table table-striped table-bordered " style="width:100%">
                            <thead>
                                <tr>
                                    <th>DNI</th>
                                    <th>Nombre</th>
                                    <th>Trabajo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Aquí puedes iterar sobre los datos que envíes desde el controlador --}}

                                @foreach ($datos['temporales'] as $datoTemporal)
                                    <tr>
                                        <td>{{ $datoTemporal->docu }}</td>
                                        <td>{{ $datoTemporal->nomb }}</td>
                                        <td>{{ $datoTemporal->trab }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm"
                                                onclick="editarRegistro({{ $datoTemporal->id }})">Editar</button>
                                            <button class="btn btn-warning btn-sm"
                                                onclick="cambiarEstado({{ $datoTemporal->id }})">Cambiar Estado</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
                $('#tablaTemporales').dataTable({
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

    @endsection
