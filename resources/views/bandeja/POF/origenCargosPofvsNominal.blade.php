@extends('layout.app')

@section('Titulo', 'Sage2.0 - Divisiones')
@section('LinkCSS')
  <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
  <style>
    .row-active {
        background-color: #d4edda !important; /* Verde claro */
        color: #155724; /* Texto verde oscuro */
    }
    .row-inactive {
        background-color: #f8daf6 !important; /* Verde claro */
        color: #155724; /* Texto verde oscuro */
        /* display: none; */
    }
</style>
@endsection
@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            @if ($Institucion->PermiteEditarTodo==1)
            <div class="row">
                <div class="alert alert-success alert-dismissible">
                    <h4><i class="icon fas fa-exclamation-triangle"></i> POF CONFIRMADA</h4>
                    <h5>La POF fue confirmada, en los próximos Dias sera controlada por la entidad correspondiente. gracias</h5>
                    <h6>Ultima Actualización de la Institución: {{ \Carbon\Carbon::parse($Institucion->updated_at)->format('d-m-Y H:i') }}</h6>
                </div>  
            </div>
            @endif
            {{-- <!-- Mensaje ALERTA -->
            <div class="alert alert-info alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> Paso N&deg; 1</h4>
                <h5>Seleccione el Cargo en la lista y agregue, aparece a su derecha en la tabla, en caso de no corresponder, solo borre de la tabla y proceda a cargarlo nuevamente</h5>
            </div> --}}
            <!-- Inicio Selectores -->
            <div class="row">    
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
                <!-- Inicio Tabla-Card -->
                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Aulas y Divisiones Declaradas</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="example4" class="table table-bordered table-striped" class="aulasTable">
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
{{-- <!-- Mensaje ALERTA -->
<div class="alert alert-info alert-dismissible">
    <h4><i class="icon fas fa-exclamation-triangle"></i> En Mantenimiento..../h4>
    <h5>Esta sección se encuentra en mantenimiento, se habilitara durante el dia</h5>
</div>
<!-- Inicio Selectores --> --}}
            {{-- aqui comenzamos con la relacion --}}
            <div class="row">
                
                @foreach ($CargosCreados as $cargo)
                @php
                   // primero voy a traer todos los cargos
                @endphp
                    <div class="col-md-12">
                        <div class="card card-primary collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title">{{ $cargo->nombre_cargo_origen }}</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" style="display: none;">
                                @php
                                    //vamos a recorrer y mostrar por cues
                                    //cuando ya tengamos el cue, vamos a ir filtrando por aula enontraday obvio por su cargo

                                @endphp
                                @foreach ($SoloCUES as $cue)
                                    <h6 style="background-color: lightblue;padding:0.5rem">CUE Analizado: {{$cue->CUECOMPLETO}}</h6>
                                    @php
                                        //extraigo solo los que me interesa
                                        $AulasCargosCreados = DB::connection('DB7')->table('tb_padt')->where('CUECOMPLETO',  $cue->CUECOMPLETO)
                                        ->select(
                                            'tb_padt.idOrigenCargo',
                                            'tb_padt.idAula',
                                            'tb_padt.idDivision',
                                            'tb_padt.idTurno',
                                            'tb_padt.CUECOMPLETO',
                                        )
                                        ->distinct()
                                        ->orderBy('CUECOMPLETO', 'ASC')
                                        ->get();
                                    @endphp
                                    @foreach ($AulasCargosCreados as $infoaulas)
                                
                                        @if ($infoaulas->idOrigenCargo == $cargo->idOrigenCargo)
                                            @php
                                                $aula = DB::connection('DB7')->table('tb_aulas')->where('idAula',$infoaulas->idAula)->first();
                                                $division = DB::connection('DB7')->table('tb_divisiones')->where('idDivision',$infoaulas->idDivision)->first();
                                            @endphp
                                            <h5>Ubicación: {{ $aula->nombre_aula?$aula->nombre_aula:"S/D" }}-{{ $division->nombre_division?$division->nombre_division:"S/D" }}</h5>
                                            <br>
                                            <table id="POFMH">
                                                <thead class="card-header">
                                                    <tr >
                                                        <th class="custom-5rem" id="tablaarriba">#ID</th>
                                                        <th class="custom-5rem" id="tablaarriba">CUE Asignado</th>
                                                        <th class="custom-5rem">Orden</th>
                                                        <th class="custom-8rem">DNI</th>
                                                        <th class="custom-15rem">Apellido y Nombre</th>
                
                                                        <th class="custom-15rem">Cargo de Origen en la Institución</th>
                                                        
                                                        <th class="custom-15rem">Sit.Rev</th>
                                                        <th class="custom-5rem">Horas</th>
                                                        <th class="custom-5rem">Turno</th>
                                                        <th class="custom-13rem">Condición</th>
                                                        <th class="custom-13rem">¿En el Aula?</th>
                

                                                    </tr>
                                                </thead>
                                                <tbody class="card-body">
                                                @if ($infoPofMH->isNotEmpty())
            
                                                    @foreach ($infoPofMH as $fila)
                                                
                                                        @if (isset($infoaulas->idOrigenCargo) 
                                                        && $infoaulas->idOrigenCargo == $fila->Origen
                                                        && $infoaulas->idAula == $fila->Aula
                                                        && $infoaulas->idDivision == $fila->Division
                                                        && $infoaulas->idTurno == $fila->Turno
                                                        && $infoaulas->CUECOMPLETO == $fila->CUECOMPLETO)
                                                            <tr class="fila">
                                                                <td>{{$fila->idPofmh}}</td>
                                                                <td>{{$fila->CUECOMPLETO}}</td>
                                                                <td>{{$fila->orden?$fila->orden:"S/D"}}</td>
                                                                <td>{{$fila->Agente?$fila->Agente:"S/D"}}</td>
                                                                <td>{{$fila->ApeNom?$fila->ApeNom:"S/D"}}</td>
                                                                <td>{{$cargo->nombre_cargo_origen}}</td>
                                                                <td>
                                                                    @if($fila->SitRev)
                                                                        @foreach($SitRev as $key => $o)
                                                                            @if ($o->idSituacionRevista == $fila->SitRev)
                                                                                {{$o->Descripcion}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    @else
                                                                        Sin Datos
                                                                    @endif
                                                                </td>
                                                                <td>{{$fila->Horas?$fila->Horas:"S/D"}}</td>
                                                                <td>
                                                                    @php
                                                                        $turno = DB::connection('DB7')->table('tb_turnos')->where('idTurno',$infoaulas->idTurno)->first();
                                                                    @endphp
                                                                    {{$turno->nombre_turno?$turno->nombre_turno:"S/D"}}
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $condicionId = trim($fila->Condicion);
                                                                        $condicion = DB::connection('DB7')->table('tb_condiciones')->where('idCondicion', $condicionId)->first();
                                                                    @endphp

                                                                    {{ $condicion ? $condicion->Descripcion : "S/D" }}
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $activo = DB::connection('DB7')->table('tb_activos')->where('idActivo',$fila->Activo)->first();
                                                                    @endphp
                                                                    {{$activo?$activo->nombre_activo:"S/D"}}
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        
                                                    @endforeach
                                                @endif
                                                    <!-- Inicialmente vacío -->
                                                </tbody>
                                            </table>
                                            <br><br>
                                        @endif
                                        
                                    @endforeach
                                    {{-- hasta aqui sub tarjetas --}}
                                    <br>
                                @endforeach
                              

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            
            
            
        </section>
    </section>
</section>


@endsection

@section('Script')

<script>
    const permiteEditarTodo = {{ json_encode($Institucion->PermiteEditarTodo) }};

    const modo = {{ (session('Modo') === 1 || session('Modo') === 3) ? 'true' : 'false' }} === true;
console.log('permiteEditarTodo:', permiteEditarTodo);
console.log('modo:', modo);
</script>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#example').dataTable( {
            "aaSorting": [[ 1, "asc" ]],
            "oLanguage": {
                "sLengthMenu": "Escuelas _MENU_ por página",
                "search": "Buscar:",
                "oPaginate": {
                    "sPrevious": "Anterior",
                    "sNext": "Siguiente"
                }
            }
        } );
    } );

</script>


<script src="{{ asset('js/funcionesvarias.js') }}"></script>

    <script>
        $(document).ready(function () {
          
            // Función para cargar los cargos
            function loadCargos() {
                $.ajax({
                    url: "{{ route('verCargosCreados', ['idExt' => $idExt]) }}",
                    data: { tipo: 'Cargos' },
                    method: 'GET',
                    success: function (data) {
                        if ($.fn.DataTable.isDataTable('#example')) {
                            $('#example').DataTable().destroy();  // Destruye la instancia previa si existe
                        }
                        $('#example tbody').empty();
                        if (data.CargosCreados && Array.isArray(data.CargosCreados)) {
                            // Lleno la tabla con los datos cargados
                            data.CargosCreados.forEach(function (cargo) {
                                let infoExisteCargo = data.AulasCargosCreados.some(o => o.idOrigenCargo === cargo.idOrigenCargo);
                                let rowContent = `
                                    <tr>
                                        <td>${cargo.idCargos_Pof_Origen}</td>
                                        <td>${cargo.nombre_cargo_origen}</td>
                                        <td>
                                            
                                        </td>
                                    </tr>
                                `;
                                $('#example tbody').append(rowContent);
                            });
                            
                            $('#combocargoSelect').empty();
                            data.CargosCreados.forEach(function (cargo) {
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
                    error: function () {
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
                    data: { tipo: 'AulasCargos' },
                    method: 'GET',
                    success: function (data) {
                        if ($.fn.DataTable.isDataTable('#example4')) {
                            $('#example4').DataTable().destroy(); // Destruye la instancia previa si existe
                        }
                        $('#example4 tbody').empty();

                        if (data.AulasCargosCreados && Array.isArray(data.AulasCargosCreados)) {
                            // Llena la tabla con los datos cargados
                            data.AulasCargosCreados.forEach(function (cargo) {
                                let aulaInfo = data.Aulas.find(aula => aula.idAula === cargo.idAula);
                                let divisionInfo = data.Divisiones.find(division => division.idDivision === cargo.idDivision);
                                let cargoInfo = data.CargosCreados.find(c => c.idOrigenCargo === cargo.idOrigenCargo);
                                let turnoInfo = data.TurnosTodos.find(turno => turno.idTurno === cargo.idTurno);
                                let infoCue = data.Extensiones.find(ext => ext.CUECOMPLETO === cargo.CUECOMPLETO);
                                $('#example4 tbody').append(`
                                    <tr>
                                        <td>${cargoInfo ? cargoInfo.nombre_cargo_origen : 'N/A'}</td>
                                        <td>${aulaInfo ? aulaInfo.nombre_aula : 'N/A'}</td>
                                        <td>${divisionInfo ? divisionInfo.nombre_division : 'N/A'}</td>
                                         <td>${turnoInfo ? turnoInfo.nombre_turno : 'N/A'}</td>
                                        <td>${infoCue.CUECOMPLETO}-${infoCue.Nombre_Institucion}-${infoCue.Localidad}</td>
                                        <td>
                                           
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
                    error: function () {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al cargar las Aulas.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }

            // Cargar cargos cuando se carga la página
            loadCargos();
            loadAulas();
        
        });

        //manejo del area en la cabecera
        $(document).ready(function () {
    $('.card-header').on('click', function (e) {
        if ($(e.target).closest('.btn-tool').length) {
            return;
        }

        const card = $(this).closest('.card'); 
        const cardBody = card.find('.card-body');
        const icon = $(this).find('.fas'); 

        if (card.hasClass('collapsed-card')) {
            card.removeClass('collapsed-card'); 
            cardBody.slideDown(); 
            icon.removeClass('fa-plus').addClass('fa-minus');
        } else {
            card.addClass('collapsed-card'); 
            cardBody.slideUp(); 
            icon.removeClass('fa-minus').addClass('fa-plus'); 
        }
    });
});

$(document).ready(function () {
    $('#POFMH tbody tr').each(function () {
        const condicionValue = $(this).find('td:nth-child(10)').text().trim(); 

        if (condicionValue === "ACTUAL" || condicionValue === "VOLANTE" || condicionValue === "ITINERANTE" || condicionValue === "DESDOBLAMIENTO") {
            $(this).addClass('row-active'); // Agrega clase activa
            console.log('Fila activa:', $(this)); // Para depuración
        } else {
            $(this).addClass('row-inactive'); // Agrega clase inactiva solo si no cumple la condición
            console.log('Fila inactiva:', $(this)); // Para depuración
        }
    });
});


    </script>

@endsection