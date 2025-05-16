@extends('layout.app')
@section('Titulo', 'Sage2.0 - Nivel Superior - Crear LOM')
@section('ContenidoPrincipal')
@section('LinkCSS')
    {{-- para superior --}}
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="{{ asset('css/superior/tablallamado.css') }}">  
    <!--fin superior -->
@endsection
    <section id="container" class="col-12">
        <section id="main-content">
            <section class="content-wrapper">
                <div class="row mb-3">
                    <button id="btnCrearLlamado" type="button" class="btn btn-primary">Crear LOM</button>
                </div>
                <div class="form-wrapper mx-auto bg-light p-4 rounded shadow-sm">                   
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif                   
                    <div id="formularioLlamado" style="display: none;">
                        <form action="{{ route('llamado.guardarLom') }}" method="POST" id="formActualizarLlamado" enctype="multipart/form-data">
                            @csrf
                            <h1 class="text-primary text-center mb-4">Crear LOM</h1>
                            <div class="mb-3">
                                <label for="idtb_zona">Zona:</label>
                                <select name="idtb_zona" id="idtb_zona" class="form-control" required>
                                    <option value="">Seleccione una zona</option>
                                    @foreach($zonas as $zona)
                                        <option value="{{ $zona->idtb_zona }}">{{ $zona->nombre_zona }}</option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <div class="mb-3">
                                <label for="id_instituto_superior">Instituto:</label>
                                <select name="id_instituto_superior" id="id_instituto_superior" class="form-control selectInstituto" required>
                                    <option value="">Seleccione un instituto</option>
                                    @foreach($institutos as $instituto)
                                        <option value="{{ $instituto->id_instituto_superior }}">{{ $instituto->nombre_instsup }}</option>
                                    @endforeach
                                </select>
                            </div>                    
                            <div class="mb-3">
                                <label for="idCarrera">Carrera:</label>
                                <select name="idCarrera" id="idCarrera" class="form-control selectCarrera" required>
                                    <option value="">Seleccione una carrera</option>
                                    @foreach($carreras as $carrera)
                                        <option value="{{ $carrera->idCarrera }}">{{ $carrera->nombre_carrera }}</option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <div class="mb-3">
                                <label for="idtipo_llamado">Tipo de llamado:</label>
                                <select name="idtipo_llamado" id="idtipo_llamado" class="form-control" required>
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($tiposLlamado as $tipo)
                                        <option value="{{ $tipo->idtipo_llamado }}">{{ $tipo->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>                      
                            <!-- imagen -->
                            <div class="form-group mb-3">                           
                                <label for="nombre_img" class="font-weight-bold mt-3">Imagen:</label>
                                <input type="file" name="imagen" id="imagen" class="form-control">                          
                            </div>
                               <!-- pdf -->
                               <div class="form-group mb-3">                           
                                <label for="pdf" class="font-weight-bold mt-3">PDF:</label>
                                <input type="file" name="pdf" id="pdf" class="form-control">                          
                            </div>
                           
                            
                            <input type="hidden" name="llamado_id" id="llamado_id">                        
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                       
                    </div>
                </div>
            </section>
        </section>
    </section>

    <section id="container" class="col-12">
            <section id="main-content">
               <section class="content-wrapper">                               
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif                   
                    <div class="row">
                        <h3 style="display: block">LOM Cargados</h3>
                        <table class="table table-striped table-bordered dt-responsive nowrap tablaEspacios" style="width:100%">
                            <tr>
                                <th>N°</th>
                                <th>LOM</th>
                                <th>Zona</th>
                                <th>Institución</th>
                                <th>Carrera</th>
                                <th>Unidad / Cargo</th>
                            </tr>
                            <tr>
                                <td colspan="8">Sin Información</td>
                            </tr>
                        </table>
                    </div>                  
                </section>               
           </section>
    </section>   
    {{-- modales --}}
    {{-- modal para editar --}}
    <div class="modal fade" id="modalEditarEspacio" tabindex="-1" role="dialog" aria-labelledby="modalEditarEspacioLabel">        
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar LOM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('llamado.editarEspacio') }}" method="POST" id="formEditarEspacio">
                        @csrf
                        <input type="hidden" name="idEspacioEditar" id="idEspacioEditar">
            
                        <div class="row">
                            <div class="col-md-6">
                                <label class="font-weight-bold">Unidad Curricular:</label>
                                <select name="idEspacioCurricular_modal" class="form-control espacioSelect" id="espacioSelectEditar">
                                @foreach($espacios as $espacio)
                                    <option value="{{ $espacio->idEspacioCurricular }}">{{ $espacio->nombre_espacio }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>                      
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="font-weight-bold">Horario:</label>
                                <textarea name="horario_modal" class="form-control" id="horarioEditar"></textarea>
                            </div>                       
                        </div>            
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  
  
@endsection

@section('Script')     
   
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <!-- Librerías necesarias para exportación -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="{{ asset('js/superior/tablaLlamado.js') }}"></script>  
    <script src="{{ asset('js/superior/tipoLlamado.js') }}"></script>

    <script>
          // Variables globales
          let modo = 'agregar'; 
          let filaActual = null;
          let idEspacioEditar = null; 
          let idCargoEditar = null; // Para editar el cargo
    $(document).ready(function () {            

            $('#btnCrearLlamado').on('click', function () {
                Swal.fire({
                    title: '¿Estás segura?',
                    text: "¿Querés crear un nuevo llamado?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, crear',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("llamado.crear") }}',
                            type: 'POST',
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            success: function (response) {
                                console.log(response);
                                $('#llamado_id').val(response.id);
                                $('#formularioLlamado').show();

                                // Agregar el ID al data-id de los botones
                                $('#btnCargo').attr('data-id', response.id);
                                $('#btnEspacio').attr('data-id', response.id);

                                Swal.fire(
                                    '¡Llamado creado!',
                                    'Se creó un nuevo llamado con ID: ' + response.id,
                                    'success'
                                );
                                llenarTablaEspacio($('#llamadoIdEspacio').val()); // <--- ACÁ
                                llenarTablaCargo($('#llamadoIdCargo').val());

                            },
                            error: function () {
                                Swal.fire(
                                    'Error',
                                    'Hubo un problema al crear el llamado.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $('#formActualizarLlamado').on('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Actualizar llamado?',
                    text: "Se guardarán los cambios de este llamado.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(this); // 👈 Aca usamos FormData correctamente

                        $.ajax({
                            url: '{{ route("llamado.actualizar") }}',
                            type: 'POST',
                            data: formData,
                            processData: false, // 👈 Importante para que jQuery NO convierta los datos
                            contentType: false, // 👈 Importante para que se envíe como multipart/form-data
                            success: function () {
                                Swal.fire(
                                    '¡Actualizado!',
                                    'Los datos del llamado se actualizaron correctamente.',
                                    'success'
                                );
                            },
                            error: function (xhr) {
                                console.log(xhr.responseText); // Para debug si da error
                                Swal.fire(
                                    'Error',
                                    'No se pudo actualizar el llamado.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        //para modales
     });
            // Abrir modal de Cargo
            $('#btnCargo').on('click', function () {
                const llamadoId = $(this).data('id');
                $('#llamadoIdCargo').val(llamadoId);
                $('#mostrarIdCargo').text(llamadoId);
                $('#modalCargo').modal('show');

                modo = 'agregar';
                filaActual = null;
                idCargoEditar = null;
                          
                $('#btnSubmitCargo').text('Agregar');
            });

            // Abrir modal de Espacio Curricular
            $('#btnEspacio').on('click', function () {
                const llamadoId = $(this).data('id');
                $('#llamadoIdEspacio').val(llamadoId);
                $('#mostrarIdEspacio').text(llamadoId);
                $('#modalEspacio').modal('show');

                modo = 'agregar';
                filaActual = null;
                idEspacioEditar = null;
                          
                $('#btnSubmitEspacio').text('Agregar');
             });
        

            // BOTÓN BORRAR ESPACIOS
            $(document).on('click', '.btn-borrar', function () {
                const fila = $(this).closest('tr');
                const idEspacio = fila.data('idespacio');
                console.log(idEspacio);
                Swal.fire({
                    title: '¿Seguro de eliminar?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, borrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("llamado.eliminarEspacio") }}', // RUTA para borrar
                            type: 'POST',
                            data: {
                                id: idEspacio,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire('¡Borrado!', response.message, 'success');
                                    llenarTablaEspacio($('#llamadoIdEspacio').val());
                                } else {
                                    Swal.fire('Error', 'No se pudo eliminar.', 'error');
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('Error', 'Error en el servidor.', 'error');
                            }
                        });
                    }
                });
            });

            //FORMULARIO AGREGAR ESPACIOS
            $('#formAgregarEspacio').on('submit', function (e) {
            e.preventDefault();

                Swal.fire({
                    title: modo === 'agregar' ? '¿Agregar espacio curricular?' : '¿Guardar cambios?',
                    text: modo === 'agregar' ? "Se agregará un nuevo espacio." : "Se actualizará el espacio.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $(this);
                        const formData = form.serialize();

                        let url = '';
                        if (modo === 'agregar') {
                            url = form.attr('action'); // guardar normal
                        } else if (modo === 'editar') {
                            url = '{{ route("llamado.editarEspacio") }}'; // una nueva ruta para editar
                        }

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: modo === 'agregar' ? formData : formData + '&idEspacio=' + idEspacioEditar,
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire(
                                        '¡Correcto!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        $('#modalEspacio').modal('hide');
                                        form[0].reset();
                                        llenarTablaEspacio($('#llamadoIdEspacio').val());

                                        modo = 'agregar'; // Volver a agregar
                                        filaActual = null;
                                        idEspacioEditar = null;
                                    });
                                } else {
                                    Swal.fire('Error', 'Ocurrió un error.', 'error');
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('Error', 'No se pudo procesar.', 'error');
                            }
                        });
                    }
                });
            });

            //formulario agregar cargos
            $('#formAgregarCargo').on('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: modo === 'agregar' ? '¿Agregar Cargo?' : '¿Guardar cambios?',
                        text: modo === 'agregar' ? "Se Agregará Un Nuevo Cargo." : "Se actualizará El Cargo.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = $(this);
                            const formData = form.serialize();

                            let url = '';
                            if (modo === 'agregar') {
                                url = form.attr('action'); // guardar normal
                            } else if (modo === 'editar') {
                                url = '{{ route("llamado.editarCargo") }}'; // una nueva ruta para editar
                            }
                            
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: modo === 'agregar' ? formData : formData + '&idCargo=' + idCargoEditar,
                                success: function (response) {
                                    if (response.success) {
                                        Swal.fire(
                                            '¡Correcto!',
                                            response.message,
                                            'success'
                                        ).then(() => {
                                            $('#modalCargo').modal('hide');
                                            form[0].reset();
                                            llenarTablaCargo($('#llamadoIdCargo').val());

                                            modo = 'agregar'; // Volver a agregar
                                            filaActual = null;
                                            idEspacioEditar = null;
                                        });
                                    } else {
                                        Swal.fire('Error', 'Ocurrió un error.', 'error');
                                    }
                                },
                                error: function (xhr) {
                                    Swal.fire('Error', 'No se pudo procesar.', 'error');
                                }
                            });
                        }
                    });
             });

             function llenarTablaCargo(idLlamado) {
                $.ajax({
                    url: '{{ route("llamado.obtenerCargos") }}',
                    type: 'POST',
                    data: { idLlamado: idLlamado, _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        let tabla = $('.tablaCargos tbody'); // Clase diferente para cargos
                        tabla.empty();
                        console.log(response);
                        if (response.cargos.length > 0) {
                            tabla.append(`
                            <tr>
                                <th>N° Llamado</th>
                                <th>Cargo</th>
                                <th>Horas Catedra</th>
                                <th>Situación de Revista</th>
                                <th>Horario</th>                       
                                <th>Turno</th>                      
                                <th>Periodo</th>
                                <th>Perfil</th>                      
                                <th>Editar / Borrar</th>
                            </tr>
                        `);
                            response.cargos.forEach(function (cargo) {
                                tabla.append(`
                                    <tr
                                        data-idllamado="${cargo.idllamado}"
                                        data-idcargorel="${cargo.idrel_cargo_por_llamado}"
                                        data-idcargo="${cargo.idtb_cargos}"
                                        data-horacat="${cargo.horacat_cargo}"
                                        data-situacionrevista="${cargo.idtb_situacion_revista}"
                                        data-horario="${cargo.horario_cargo}"
                                        data-turno="${cargo.idTurno}"
                                        data-periodo="${cargo.idtb_periodo_cursado}"
                                        data-perfil="${cargo.idtb_perfil}">
                                        <td>${cargo.idllamado ?? ''}</td>
                                        <td>${cargo.nombre_cargo ?? ''}</td>
                                        <td>${cargo.horacat_cargo ?? ''}</td>
                                        <td>${cargo.nombre_situacion_revista ?? ''}</td>
                                        <td>${cargo.horario_cargo ?? ''}</td>
                                        <td>${cargo.nombre_turno ?? ''}</td>
                                        <td>${cargo.nombre_periodo ?? ''}</td>
                                        <td>${cargo.nombre_perfil ?? ''}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm btn-editar-cargo">Editar</button>
                                            <button class="btn btn-danger btn-sm btn-borrar-cargo">Borrar</button>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            tabla.append(`<tr><td colspan="8" class="text-center">No hay cargos cargados.</td></tr>`);
                        }
                    },
                    error: function (xhr) {
                        console.error('Error al cargar cargos:', xhr.responseText);
                    }
                });
            }
              // BOTÓN BORRAR CARGOS
            $(document).on('click', '.btn-borrar-cargo', function () {
                const fila = $(this).closest('tr');
                const idCargoRel = fila.data('idcargorel'); // ← clave: este es el id de la tabla rel

                if (!idCargoRel) {
                    console.error("No se encontró el ID del cargo para borrar.");
                    return;
                }

                Swal.fire({
                    title: '¿Eliminar cargo?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("llamado.eliminarCargo") }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: idCargoRel // <- lo que espera el controller
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire('¡Eliminado!', response.message, 'success');
                                    llenarTablaCargo($('#llamadoIdCargo').val());
                                } else {
                                    Swal.fire('Error', 'No se pudo eliminar.', 'error');
                                }
                            },
                            error: function (xhr) {
                                console.error("Error al eliminar:", xhr.responseText);
                                Swal.fire('Error', 'Error en el servidor.', 'error');
                            }
                        });
                    }
                });
            });


            // Submit Editar Cargo
            $(document).on('click', '.btn-editar-cargo', function () {
                const fila = $(this).closest('tr');
                 // Debug para ver los datos
                console.log("Datos fila:", fila.data());

                // Cargar los datos al modal
                $('#idCargoEditar').val(fila.data('idcargorel'));
                $('#cargoSelectEditar').val(fila.data('idcargo')).trigger('change');
                $('#horacatEditar').val(fila.data('horacat'));
                $('#idSituacionRevistaEditar').val(fila.data('situacionrevista'));
                $('#horarioEditar').val(fila.data('horario'));
                $('#idTurnoEditar').val(fila.data('turno'));
                $('#idPeriodoEditar').val(fila.data('periodo'));
                $('#idPerfilEditar').val(fila.data('perfil'));

                // Asegurarse de pasar el id del llamado (usamos el del botón de abrir modal original)
                $('#llamadoIdCargo').val($('#btnCargo').data('id'));

                // Mostrar el modal
                $('#modalEditarCargo').modal('show');
            });

            $('#formEditarCargo').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Guardar cambios?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = $(this).serialize();
                        const formArray = $(this).serializeArray();
                        const formJson = {};

                        formArray.forEach(({ name, value }) => {
                            formJson[name] = value;
                        });

                        console.log(formJson);
                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'POST',
                            data: formData,
                            headers: {
                                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                                    },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('¡Actualizado!', response.message, 'success');
                                    $('#modalEditarCargo').modal('hide');
                                    llenarTablaCargo($('#llamadoIdCargo').val());
                                } else {
                                    Swal.fire('Error', 'Ocurrió un error.', 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'Error en el servidor.', 'error');
                            }
                        });
                    }
                });
            })

        function llenarTablaEspacio(idLlamado) {
            $.ajax({
                url: '{{ route("llamado.obtenerEspacios") }}',
                type: 'POST',
                data: {
                    idLlamado: idLlamado,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    let tabla = $('.tablaEspacios tbody');
                    tabla.empty(); // Vaciar la tabla antes de llenar

                    if (response.espacios.length > 0) {
                        tabla.append(`
                            <tr>
                                <th>N° Llamado</th>
                                <th>Unidad Curricular</th>
                                <th>Horas Catedra</th>
                                <th>Situación de Revista</th>
                                <th>Horario</th>                       
                                <th>Turno</th>                      
                                <th>Periodo</th>
                                <th>Perfil</th>                      
                                <th>Editar / Borrar</th>
                            </tr>
                        `);
                        response.espacios.forEach(function (espacio) {
                            tabla.append(`
                                <tr
                                    data-idllamado="${espacio.idllamado}"
                                    data-idespacio="${espacio.idrel_espacios_por_llamado}" 
                                    data-idespaciocurricular="${espacio.idEspacioCurricular}" 
                                    data-horacat="${espacio.horacat_espacio}" 
                                    data-situacionrevista="${espacio.idtb_situacion_revista}" 
                                    data-horario="${espacio.horario_espacio}" 
                                    data-turno="${espacio.idTurno}" 
                                    data-periodo="${espacio.idtb_periodo_cursado}" 
                                    data-perfil="${espacio.idtb_perfil}">
                                    <td>${espacio.idllamado ?? ''}</td>
                                    <td>${espacio.nombre_espacio ?? ''}</td>
                                    <td>${espacio.horacat_espacio ?? ''}</td>
                                    <td>${espacio.nombre_situacion_revista ?? ''}</td>
                                    <td>${espacio.horario_espacio ?? ''}</td>
                                    <td>${espacio.nombre_turno ?? ''}</td>                          
                                    <td>${espacio.nombre_periodo ?? ''}</td>
                                    <td>${espacio.nombre_perfil ?? ''}</td>                          
                                    <td>
                                        <button class="btn btn-sm btn-warning btn-editar">Editar</button>
                                        <button class="btn btn-sm btn-danger btn-borrar">Borrar</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        tabla.append(`
                            <tr>
                                <td colspan="8" class="text-center">No hay espacios cargados.</td>
                            </tr>
                        `);
                    }
                },
                error: function (xhr) {
                    console.error('Error al cargar espacios:', xhr.responseText);
                }
            });
        }

        $(document).on('click', '.btn-editar', function () {
            const fila = $(this).closest('tr');

            // Cargar los datos en el modal de editar
            $('#idEspacioEditar').val(fila.data('idespacio'));
            $('#espacioSelectEditar').val(fila.data('idespaciocurricular'));
            $('#horacatEditar').val(fila.data('horacat'));
            $('#idSituacionRevistaEditar').val(fila.data('situacionrevista'));
            $('#horarioEditar').val(fila.data('horario'));
            $('#idTurnoEditar').val(fila.data('turno'));
            $('#idPeriodoEditar').val(fila.data('periodo'));
            $('#idPerfilEditar').val(fila.data('perfil'));
            console.log({
                idEspacio: fila.data('idespacio'),
                idEspacioCurricular: fila.data('idespaciocurricular'),
                horacat: fila.data('horacat'),
                situacionrevista: fila.data('situacionrevista'),
                horario: fila.data('horario'),
                turno: fila.data('turno'),
                periodo: fila.data('periodo'),
                perfil: fila.data('perfil')
            });
                        // Mostrar el modal de edición
            $('#modalEditarEspacio').modal('show');
        });
        // Submit Editar Espacio
        $('#formEditarEspacio').on('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Guardar cambios?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = $(this).serialize();
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                                },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('¡Actualizado!', response.message, 'success');
                                $('#modalEditarEspacio').modal('hide');
                                llenarTablaEspacio($('#llamadoIdEspacio').val());
                            } else {
                                Swal.fire('Error', 'Ocurrió un error.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Error en el servidor.', 'error');
                        }
                    });
                }
            });
        });

    </script>
@endsection
