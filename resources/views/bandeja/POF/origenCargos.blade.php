@extends('layout.app')

@section('Titulo', 'Sage2.0 - Divisiones')

@section('ContenidoPrincipal')
    <section id="container">
        <section id="main-content">
            <section class="content-wrapper">
                @if ($Institucion->PermiteEditarTodo == 1)
                    <div class="row">
                        <div class="alert alert-success alert-dismissible">
                            <h4><i class="icon fas fa-exclamation-triangle"></i> POF CONFIRMADA</h4>
                            <h5>La POF fue confirmada, en los próximos Dias sera controlada por la entidad correspondiente.
                                gracias</h5>
                            <h6>Ultima Actualización de la Institución:
                                {{ \Carbon\Carbon::parse($Institucion->updated_at)->format('d-m-Y H:i') }}</h6>
                        </div>
                    </div>
                @endif
                <!-- Mensaje ALERTA -->
                <div class="alert alert-info alert-dismissible">
                    <h4><i class="icon fas fa-exclamation-triangle"></i> Paso N&deg; 1</h4>
                    <h5>Seleccione el Cargo en la lista y agregue, aparece a su derecha en la tabla, en caso de no
                        corresponder, solo borre de la tabla y proceda a cargarlo nuevamente</h5>
                </div>
                <!-- Inicio Selectores -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-book"></i>
                                    Panel de Control - Lista de Cargos
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <form id="addCargoForm">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Curso">Cargos con Código</label>
                                        <select name="Cargo" id="cargoSelect" class="form-control">
                                            @foreach ($ListaCargos as $cargo)
                                                <option value="{{ $cargo->idCargos_Pof_Origen }}">
                                                    {{ $cargo->nombre_cargo_origen }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($Institucion->PermiteEditarTodo == 0 || session('Modo') == 1 || session('Modo') == 3)
                                    <div class="card-footer bg-transparent">
                                        <input type="hidden" name="id" value="{{ $idExt }}">
                                        <input type="hidden" name="Turno" value="{{ $Turno->idTurno }}">
                                        <button type="submit" class="btn btn-primary">Agregar Cargo</button>
                                    </div>
                                @endif
                            </form>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                    <!-- Inicio Tabla-Card -->
                    <div class="col-md-6">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Cargos Declarados</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table id="example" class="table table-bordered table-striped" class="cargosTable">
                                    <thead>
                                        <tr>
                                            <th>#Codigo</th>
                                            <th>Descripción</th>
                                            <th>Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <br>
                <!-- Mensaje ALERTA -->
                <div class="alert alert-info alert-dismissible">
                    <h4><i class="icon fas fa-exclamation-triangle"></i> Paso N&deg; 2</h4>
                    <h5>Seleccione Cargo, Aula y Division, el Turno es colocado automáticamente según la configuración de su
                        cuenta.</h5>
                </div>
                <!-- Inicio Selectores -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-book"></i>
                                    Panel de Control - Usar Cargos para Definir Aulas/Div/Turno
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <form id="addAulaCargoForm">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Curso">Cargos con Código</label>
                                        <select name="Cargo" id="combocargoSelect" class="form-control">
                                            {{-- autocompleto --}}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Aulas">Aulas Predefinidas</label>
                                        <select name="Aula" id="Aula" class="form-control">
                                            @foreach ($Aulas as $a)
                                                <option value="{{ $a->idAula }}">{{ $a->nombre_aula }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Divisiones">Divisiones Predefinidas</label>
                                        <select name="Division" id="Division" class="form-control">
                                            @foreach ($Divisiones as $d)
                                                <option value="{{ $d->idDivision }}">{{ $d->nombre_division }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Turno">Turno:</label>
                                        <select class="form-control" name="Turno" id="Turno">
                                            @foreach ($TurnosTodos as $turno)
                                                <option value="{{ $turno->idTurno }}">{{ $turno->nombre_turno }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Cue">Base/Extension:</label>
                                        <select class="form-control" name="Cue" id="Cue">
                                            @foreach ($Extensiones as $extension)
                                                @php
                                                    // Obtener la modalidad si CUECOMPLETO tiene 11 dígitos
                                                    $modalidad = '';
                                                    if (strlen($extension->CUECOMPLETO) == 11) {
                                                        $codigoModalidad = substr($extension->CUECOMPLETO, -2); // Obtiene los últimos 2 dígitos
                                                        switch ($codigoModalidad) {
                                                            case '00':
                                                                $modalidad = 'Inicial';
                                                                break;
                                                            case '01':
                                                                $modalidad = 'Primario';
                                                                break;
                                                            case '02':
                                                                $modalidad = 'Secundario';
                                                                break;
                                                            case '03':
                                                                $modalidad = 'Superior';
                                                                break;
                                                            case '04':
                                                                $modalidad = 'Adultos';
                                                                break;
                                                            case '05':
                                                                $modalidad = 'Especial';
                                                                break;
                                                            default:
                                                                $modalidad = 'Desconocida'; // Opcional, en caso de que no coincida con ningún caso
                                                                break;
                                                        }
                                                    }
                                                @endphp

                                                @auth
                                                    {{-- Aquí puedes agregar contenido adicional para usuarios autenticados si es necesario --}}
                                                @endauth

                                                <option value="{{ $extension->CUECOMPLETO }}">
                                                    {{ $extension->CUECOMPLETO }} - {{ $extension->Nombre_Institucion }} -
                                                    {{ $extension->Localidad }} @if ($modalidad)
                                                        - {{ $modalidad }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($Institucion->PermiteEditarTodo == 0 || session('Modo') == 1 || session('Modo') == 3)
                                    <div class="card-footer bg-transparent">
                                        <input type="hidden" name="id" value="{{ $idExt }}">
                                        <button type="submit" class="btn btn-primary">Vincular Aula</button>
                                    </div>
                                @endif
                            </form>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                    <!-- Inicio Tabla-Card -->
                    <div class="col-md-6">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Aulas y Divisiones Declaradas</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Cargo</th>
                                            <th>Aula</th>
                                            <th>Division</th>
                                            <th>Turno</th>
                                            <th>CUE</th>
                                            <th>Opcion</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <!-- Mensaje ALERTA -->
                <div class="alert alert-warning alert-dismissible">
                    <h4><i class="icon fas fa-exclamation-triangle"></i> Paso N&deg; 3</h4>
                    <h5>Deberá confirmar la creación de la POF y de la creación de SALAS, luego de confirmado el botón
                        desaparecerá y sera reactivado solo con petición formal de los Supervisores o Personal Autorizado.
                    </h5>
                </div>
                @if ($Institucion->PermiteEditarTodo == 0)
                    <section>
                        <button id="confirmButton" class="btn btn-block btn-large btn-success">CONFIRME CERRAR POF
                            ORIGINAL</button>
                    </section>
                @else
                    <h3>POF- Confirmada - Ultima Actualización de la Institución:
                        {{ \Carbon\Carbon::parse($Institucion->updated_at)->format('d-m-Y H:i') }}</h3>
                @endif
            </section>
        </section>
    </section>


@endsection

@section('Script')

    <script>
        const permiteEditarTodo = {{ json_encode($Institucion->PermiteEditarTodo) }};

        const modo = {{ session('Modo') === 1 || session('Modo') === 3 ? 'true' : 'false' }} === true;
        console.log('permiteEditarTodo:', permiteEditarTodo);
        console.log('modo:', modo);
    </script>
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


    <script src="{{ asset('js/funcionesvarias.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Función para agregar un cargo
            $('#addCargoForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('formularioCargosOriginales') }}",
                    method: 'POST',
                    data: formData,
                    success: function() {
                        Swal.fire({
                            title: 'Cargo agregado',
                            text: 'El cargo ha sido agregado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                        loadCargos(); // Recargar la tabla
                        loadAulas();
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al agregar el cargo.',
                            icon: 'error',
                            confirmButtonText: 'Intentar nuevamente'
                        });
                    }
                });
            });

            // Función para vincular una Aula a un cargo especifico
            $('#addAulaCargoForm').on('submit', function(e) {
                e.preventDefault();

                // Obtener los valores de los combos
                const cargo = $('#combocargoSelect').val();
                const aula = $('#Aula').val();
                const division = $('#Division').val();

                // Validar que todos los campos tengan un valor seleccionado
                if (!cargo) {
                    Swal.fire({
                        title: 'Campo incompleto',
                        text: 'Por favor, seleccione un cargo.',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    });
                    return; // Detener la ejecución si el campo está vacío
                }

                if (!aula) {
                    Swal.fire({
                        title: 'Campo incompleto',
                        text: 'Por favor, seleccione un aula.',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                if (!division) {
                    Swal.fire({
                        title: 'Campo incompleto',
                        text: 'Por favor, seleccione una división.',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                // Si todos los campos están seleccionados, proceder con la solicitud AJAX
                const formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('formularioAulaCargosOriginales') }}",
                    method: 'POST',
                    data: formData,
                    success: function() {
                        Swal.fire({
                            title: 'Aula Vinculada',
                            text: 'Se vinculo correctamente un Aula y Cargo.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                        loadAulas(); // Recargar la tabla
                        //loadCargos();
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al agregar el Aula.',
                            icon: 'error',
                            confirmButtonText: 'Intentar nuevamente'
                        });
                    }
                });
            });


            // Función para cargar los cargos
            function loadCargos() {
                $.ajax({
                    url: "{{ route('verCargosCreados', ['idExt' => $idExt]) }}",
                    data: {
                        tipo: 'Cargos'
                    },
                    method: 'GET',
                    success: function(data) {
                        if ($.fn.DataTable.isDataTable('#example')) {
                            $('#example').DataTable()
                                .destroy(); // Destruye la instancia previa si existe
                        }
                        $('#example tbody').empty();
                        if (data.CargosCreados && Array.isArray(data.CargosCreados)) {
                            // Lleno la tabla con los datos cargados
                            data.CargosCreados.forEach(function(cargo) {
                                let infoExisteCargo = data.AulasCargosCreados.some(o => o
                                    .idOrigenCargo === cargo.idOrigenCargo);
                                let rowContent = `
                                    <tr>
                                        <td>${cargo.idCargos_Pof_Origen}</td>
                                        <td>${cargo.nombre_cargo_origen}</td>
                                        <td>
                                            ${infoExisteCargo ? '<i class="fas fa-lock text-danger" title="Bloqueado"></i>':
                                              permiteEditarTodo === 0 ?
                                                `<button onclick="deleteCargo(${cargo.idOrigenCargo})" style="border:none; background:none;">
                                                            <i class="fas fa-trash text-danger"></i>
                                                        </button>` :
                                                modo === true? `<button onclick="deleteCargo(${cargo.idOrigenCargo})" style="border:none; background:none;">
                                                            <i class="fas fa-trash text-danger"></i>
                                                        </button>` : '<i class="fas fa-lock text-danger" title="Bloqueado"></i>' 
                                                
                                            }
                                        </td>
                                    </tr>
                                `;
                                $('#example tbody').append(rowContent);
                            });

                            $('#combocargoSelect').empty();
                            data.CargosCreados.forEach(function(cargo) {
                                $('#combocargoSelect').append(`
                                    <option value="${cargo.idOrigenCargo}">${cargo.nombre_cargo_origen}</option>
                                `);
                            });

                            // Inicializa el DataTable después de cargar los datos
                            $('#example').DataTable({
                                paging: true,
                                searching: true,
                                ordering: true
                            });

                        } else {
                            console.error("CargosCreados no está definido o no es un array");
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al cargar los cargos.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }


            function loadAulas() {
                $.ajax({
                    url: "{{ route('verCargosCreados', ['idExt' => $idExt]) }}",
                    data: {
                        tipo: 'AulasCargos'
                    },
                    method: 'GET',
                    success: function(data) {
                        if ($.fn.DataTable.isDataTable('#example4')) {
                            $('#example4').DataTable()
                                .destroy(); // Destruye la instancia previa si existe
                        }
                        $('#example4 tbody').empty();

                        if (data.AulasCargosCreados && Array.isArray(data.AulasCargosCreados)) {
                            // Llena la tabla con los datos cargados
                            data.AulasCargosCreados.forEach(function(cargo) {
                                let aulaInfo = data.Aulas.find(aula => aula.idAula === cargo
                                    .idAula);
                                let divisionInfo = data.Divisiones.find(division => division
                                    .idDivision === cargo.idDivision);
                                let cargoInfo = data.CargosCreados.find(c => c.idOrigenCargo ===
                                    cargo.idOrigenCargo);
                                let turnoInfo = data.TurnosTodos.find(turno => turno.idTurno ===
                                    cargo.idTurno);
                                let infoCue = data.Extensiones.find(ext => ext.CUECOMPLETO ===
                                    cargo.CUECOMPLETO);
                                $('#example4 tbody').append(`
                                    <tr>
                                        <td>${cargoInfo ? cargoInfo.nombre_cargo_origen : 'N/A'}</td>
                                        <td>${aulaInfo ? aulaInfo.nombre_aula : 'N/A'}</td>
                                        <td>${divisionInfo ? divisionInfo.nombre_division : 'N/A'}</td>
                                         <td>${turnoInfo ? turnoInfo.nombre_turno : 'N/A'}</td>
                                        <td>${infoCue.CUECOMPLETO}-${infoCue.Nombre_Institucion}-${infoCue.Localidad}</td>
                                        <td>
                                            ${permiteEditarTodo === 0? 
                                                `<button onclick="deleteAulaCargo(${cargo.idPadt})" style="border:none; background:none;">
                                                            <i class="fas fa-trash text-danger"></i>
                                                        </button>`:
                                                modo===true? `<button onclick="deleteAulaCargo(${cargo.idPadt})" style="border:none; background:none;">
                                                            <i class="fas fa-trash text-danger"></i>
                                                        </button>`:'<i class="fas fa-lock text-danger" title="Bloqueado"></i>'
                                            }
                                        </td>
                                    </tr>
                                `);
                            });

                            // Inicializa el DataTable después de cargar los datos
                            $('#example4').DataTable({
                                paging: true,
                                searching: true,
                                ordering: true
                            });

                        } else {
                            console.error("AulasCargosCreados no está definido o no es un array");
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al cargar las Aulas.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }

            // Función para eliminar un cargo con confirmación SweetAlert
            window.deleteCargo = function(idCargo) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/desvincularOrigenCargo/" + idCargo,
                            method: 'GET',
                            success: function() {
                                Swal.fire({
                                    title: 'Eliminado',
                                    text: 'El cargo ha sido eliminado correctamente.',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                });
                                loadCargos(); // Recargar la tabla
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un problema al eliminar el cargo.',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        });
                    }
                });
            }
            window.deleteAulaCargo = function(idPadt) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/desvincularAulaOrigenCargo/" + idPadt,
                            method: 'GET',
                            success: function() {
                                Swal.fire({
                                    title: 'Eliminado',
                                    text: 'El cargo ha sido eliminado correctamente.',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                });
                                loadAulas(); // Recargar la tabla
                                loadCargos();
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un problema al eliminar el cargo.',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        });
                    }
                });
            }
            // Cargar cargos cuando se carga la página
            loadCargos();
            loadAulas();
        });
    </script>

@endsection
