@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Usuario ADMIN')

@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Buscador Agente -->
                    <h4 class="text-center display-4">Agregar Fechas al Calendario</h4>
                    <!-- Agregar Nuevo Agente -->
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <!-- general form elements -->
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Agregar Nueva Fecha
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                            
                                <form method="POST" action="{{ route('FormNuevaFecha') }}" class="FormNuevaFecha form-group">
                                @csrf
                                    <div class="card-body" id="NuevoFecha" style="display:visible">
                                        
                                        <!--  -->
                                        <div class="form-group row">
                                            <div class="col-4">
                                                <label for="titulo">Titulo: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Titulo" name="Titulo" placeholder="Ingrese una descripción corta">
                                            </div>
                                            <div class="col-3">
                                                <label for="Fecha">Fecha: </label>
                                                <input type="date" autocomplete="off" class="form-control" id="Fecha" name="Fecha" placeholder="Ingrese Fecha del Evento">
                                            </div>                                            
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-4">
                                                <label for="titulo">Tipo de Fecha: </label>
                                                <select class="form-control" name="tipoCalendario" id="tipoCalendario">
                                                    @foreach ($TiposCalendario as  $t)
                                                        <option value="{{$t->idTipoCalendario}}">{{$t->nombre_tipo_calendario}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label for="Sexo">Es Feriado: </label>
                                                <select class="form-control" name="esFeriado" id="esFeriado">
                                                    <option value="S">SI</option>
                                                    <option value="N" selected="selected">NO</option>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="form-group row">
                                           
                                            <div class="col-6">
                                                <label for="TL">Descripción:</label>
                                                <br>
                                                <textarea name="Descripcion" id="Descripcion"  rows="5" style="width: 100%"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                       
                                    <!-- /.card-body -->

                                    <div class="card-footer bg-transparent" id="NuevoAgenteContenido2" style="display:visible">
                                        <button type="submit" class="btn btn-primary btn-block">Agregar</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
         
                <br>
                <div class="container-fluid">
                    <!-- Buscador Agente -->
                    <h5 class="text-center display-4">Lista de Fechas Agregadas</h5>
                    <!-- Agregar Nuevo Agente -->
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <!-- general form elements -->
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Fechas Agregadas
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body" id="listaDeFechas">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>COD</th>
                                                <th>Titulo</th>
                                                <th>Fecha</th>
                                                <th>Tipo de Fecha</th>
                                                <th>Es Feriado?</th>
                                                <th>Descripción</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <!-- /.card -->
                        </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->

                
            </section>
            <!-- /.content -->
        </section>
    </section>
</section>
@endsection

@section('Script')
<script>
    $(document).ready(function() {
        loadTable(); // Cargar la tabla al inicio

        // Manejar el formulario de agregar fecha
        $('.FormNuevaFecha').on('submit', function(e) {
            e.preventDefault(); // Evita el envío normal del formulario

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas agregar esta fecha al calendario?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, agregar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Obtener los datos del formulario
                    let form = $(this);
                    let formData = form.serialize(); // Serializa los datos del formulario

                    // Enviar la solicitud AJAX
                    $.ajax({
                        url: form.attr('action'), // Obtiene la URL del atributo 'action' del formulario
                        method: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    '¡Agregado!',
                                    'La fecha ha sido agregada exitosamente.',
                                    'success'
                                );

                                // Recargar la tabla
                                loadTable();

                                // Limpiar el formulario
                                form.trigger('reset');
                            } else {
                                Swal.fire(
                                    'Error',
                                    response.message || 'Hubo un problema al agregar la fecha.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error',
                                'Ocurrió un error al procesar tu solicitud.',
                                'error'
                            );
                            console.error('Error:', xhr.responseText);
                        }
                    });
                }
            });
        });

        // Manejar la eliminación de fechas
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "La fecha será eliminada de manera permanente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/calendario/eliminar/${id}`, // Ajusta la URL según tu ruta para eliminar
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    'La fecha ha sido eliminada con éxito.',
                                    'success'
                                );
                                $(`button[data-id="${id}"]`).closest('tr').remove(); // Eliminar la fila de la tabla
                            } else {
                                Swal.fire(
                                    'Error',
                                    response.message || 'Hubo un problema al eliminar la fecha.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error',
                                'Ocurrió un error al procesar tu solicitud.',
                                'error'
                            );
                            console.error('Error:', xhr.responseText);
                        }
                    });
                }
            });
        });
    });

    function loadTable() {
        $.ajax({
            url: '/calendario/lista', // La URL debe coincidir con tu ruta
            method: 'GET',
            success: function(data) {
                let tbody = $('#example1 tbody');
                tbody.empty(); // Limpia cualquier contenido existente en el tbody

                // Iterar sobre los datos y agregarlos a la tabla
                data.forEach(function(fecha) {
                    if (!fecha || !fecha.fecha) {
                        console.error('El objeto "fecha" o la propiedad "fecha" están indefinidos:', fecha);
                        return; // Salta esta iteración si el objeto está mal definido
                    }

                    let esFeriado = fecha.es_feriado === 'S' ? 'Sí' : 'No';

                    // Formatear la fecha a día/mes/año
                    let fechaObj = new Date(fecha.fecha);
                    let dia = String(fechaObj.getDate()).padStart(2, '0');
                    let mes = String(fechaObj.getMonth() + 1).padStart(2, '0'); // Los meses son base 0
                    let anio = fechaObj.getFullYear();
                    let fechaFormateada = `${dia}/${mes}/${anio}`;

                    let row = `
                        <tr>
                            <td>${fecha.idCalendario}</td>
                            <td>${fecha.titulo}</td>
                            <td>${fechaFormateada}</td>
                            <td>${fecha.nombre_tipo_calendario}</td>
                            <td>${esFeriado}</td>
                            <td>${fecha.descripcion}</td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${fecha.idCalendario}">Eliminar</button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            },
            error: function(xhr) {
                console.error('Error al obtener los datos:', xhr.responseText);
            }
        });
    }

    function loadTable() {
        $.ajax({
            url: '/calendario/listaEsc', // La URL debe coincidir con tu ruta
            method: 'GET',
            success: function(data) {
                let tbody = $('#example1 tbody');
                tbody.empty(); // Limpia cualquier contenido existente en el tbody

                // Iterar sobre los datos y agregarlos a la tabla
                data.forEach(function(fecha) {
                    if (!fecha || !fecha.fecha) {
                        console.error('El objeto "fecha" o la propiedad "fecha" están indefinidos:', fecha);
                        return; // Salta esta iteración si el objeto está mal definido
                    }

                    let esFeriado = fecha.es_feriado === 'S' ? 'Sí' : 'No';

                    // Formatear la fecha a día/mes/año
                    let fechaObj = new Date(fecha.fecha);
                    let dia = String(fechaObj.getDate()).padStart(2, '0');
                    let mes = String(fechaObj.getMonth() + 1).padStart(2, '0'); // Los meses son base 0
                    let anio = fechaObj.getFullYear();
                    let fechaFormateada = `${dia}/${mes}/${anio}`;

                    let row = `
                        <tr>
                            <td>${fecha.idCalendario}</td>
                            <td>${fecha.titulo}</td>
                            <td>${fechaFormateada}</td>
                            <td>${fecha.nombre_tipo_calendario}</td>
                            <td>${esFeriado}</td>
                            <td>${fecha.descripcion}</td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${fecha.idCalendario}">Eliminar</button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            },
            error: function(xhr) {
                console.error('Error al obtener los datos:', xhr.responseText);
            }
        });
    }
</script>



@endsection
