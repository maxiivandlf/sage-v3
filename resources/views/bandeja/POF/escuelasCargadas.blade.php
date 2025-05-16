@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Usuario ADMIN')
@section('LinkCSS')
<style>
    .btn-app {
        border-radius: 3px;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    color: #6c757d;
    font-size: 12px;
    height: 70px;
    margin: 0 0 10px 10px;
    min-width: 90px;
    padding: 15px 5px;
    position: relative;
    text-align: center;
    }
    .custom-switch.custom-switch-off-danger .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #28a745; /* Verde para el estado "on" */
    }
    .custom-switch.custom-switch-off-danger .custom-control-input:not(:checked) ~ .custom-control-label::before {
        background-color: #dc3545; /* Rojo para el estado "off" */
    }
 
    .custom-control-input:checked ~ .custom-control-label::before {
        transform: scale(1.5);
    }

    .custom-control-label::before {
        transform: scale(1.5);
        width: 3rem;
        height: 1.75rem;
    }

    .custom-control-label {
        font-size: 1.2rem;
    }
    /* Establecer el color verde al estar activado */
.btn-app.btn-success {
    background-color: #28a745 !important; /* Verde */
    border-color: #28a745 !important;
}

/* Evitar que el foco cambie el color del botón */
.btn-app:focus, .btn-app:active, .btn-app:focus-visible {
    box-shadow: none !important; /* Eliminar cualquier sombra */
    background-color: #28a745 !important; /* Verde cuando se hace foco */
    border-color: #28a745 !important; /* Asegurarse de que el borde sea verde */
}

/* Mantener el color cuando el botón está en estado inactivo */
.btn-app.btn-danger {
    background-color: #dc3545 !important; /* Rojo */
    border-color: #dc3545 !important;
}

/* Evitar que el foco cambie el color cuando está inactivo */
.btn-app.btn-danger:focus, .btn-app.btn-danger:active {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    box-shadow: none !important; /* Eliminar cualquier sombra */
}

/* Aplicar transición suave para el cambio de color */
.btn-app {
    transition: background-color 0.3s ease, border-color 0.3s ease;
}
</style>
@endsection
@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
                <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Buscador Agente -->
                    <h4 class="text-center display-4">Panel de Escuelas en POFMH</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Escuelas</h3>&nbsp; 
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>COD</th>
                                    <th>CUE</th>
                                    <th>Turno</th>
                                    <th>Nombre Inst.</th>
                                    <th>Nivel</th>
                                    <th>Categoria</th>
                                    <th>Localidad</th>
                                    <th>Departamento</th>
                                    <th>Zona</th>
                                    <th>Zona Supervision</th>
                                    <th>Jornada</th>
                                    <th>Ambito</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Escuelas as $nag)
                                    <tr>
                                        <td>{{$nag->idInstitucionExtension}}</td>
                                        <td>{{$nag->CUECOMPLETO}}</td>
                                        <td>{{$nag->Descripcion}}</td>
                                        <td>
                                            @if(empty($nag->Nombre_Institucion))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->Nombre_Institucion}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @if(empty($nag->Nivel))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->Nivel}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @if(empty($nag->Categoria))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->Categoria}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(empty($nag->Localidad))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->Localidad}}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(empty($nag->Departamento))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->Departamento}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @if(empty($nag->Zona))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->Zona}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @if(empty($nag->ZonaSupervision))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->ZonaSuper}} - {{$nag->ZonaSuperCodigo}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(empty($nag->Jornada))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->Jornada}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(empty($nag->Ambito))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->nombreAmbito}}
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 1rem; justify-content: space-between;margin-top:5px">
                                                <a class="btn btn-app"  href="{{route('verPofMhidExt',$nag->idInstitucionExtension)}}">
                                                    <i class="fas fa-eye"></i> Pof Nominal
                                                </a>
                                                <a class="btn btn-app"  href="{{route('verCargosCreados',$nag->idInstitucionExtension)}}" class="nav-link">
                                                    <i class="fas fa-search"></i> Ver Cargos POF
                                                </a>
                                                <a class="btn btn-app"  href="{{route('asistencias_pofmh',$nag->idInstitucionExtension)}}" class="nav-link">
                                                    <i class="fas fa-users"></i> Asistencias
                                                </a>
                                                <a class="btn btn-app"  href="{{route('verCargosPofvsNominal',$nag->idInstitucionExtension)}}" class="nav-link">
                                                    <i class="fas fa-list-ol"></i> Pof vs Nominal
                                                </a>
                                                <a class="btn btn-app"  href="{{route('ver_novedades',['valor' => 'Todo', 'id' => $nag->idInstitucionExtension])}}" class="nav-link">
                                                    <i class="fas fa-bell"></i> Novedades(Todas)
                                                </a>
                                                <a class="btn btn-app"  href="{{route('adjuntar_novedad', $nag->idInstitucionExtension)}}" class="nav-link">
                                                    <i class="fas fa-paperclip"></i> Adjuntar Novedades
                                                </a>
                                                <a class="btn btn-app"  href="{{route('controlDeIpeSuperAnterior', $nag->idInstitucionExtension)}}" class="nav-link" style="background-color: yellowgreen">
                                                    <i class="fas fa-check-square"></i> Ver Control IPE -Anterior
                                                </a>
                                                <a class="btn btn-app"  href="{{route('controlDeIpeSuper', $nag->idInstitucionExtension)}}" class="nav-link">
                                                    <i class="fas fa-check-square"></i> Ver Control IPE -Actual
                                                </a>
                                                <a class="btn btn-app"  href="{{route('controlDeIpeTec', $nag->idInstitucionExtension)}}" class="nav-link">
                                                    <i class="fas fa-trash"></i> Control Ipe - Borrados
                                                </a>
                                                <div>
                                                    @php
                                                        $infoUsuario = DB::table('tb_usuarios')->where('CUECOMPLETO', $nag->CUECOMPLETO)->first();
                                                    @endphp
                                                
                                                    @if ($infoUsuario)
                                                        @if ($infoUsuario->created_at)
                                                            Creado en: <span style="color:green">{{ \Carbon\Carbon::parse($infoUsuario->created_at)->format('d/m/Y H:i') }}</span>
                                                        @else
                                                            Creado en: <span style="color:red">Datos muy viejos</span>
                                                        @endif
                                                
                                                        @php
                                                            // En caso de que exista realmente, traigo su log
                                                            $infoLogs = DB::table('tb_logs')->where('idUsuario', $infoUsuario->idUsuario)->orderBy('idLog', 'desc')->first();
                                                            
                                                            if ($infoLogs && $infoLogs->idUsuario) {
                                                                $ultimaFecha = $infoLogs->updated_at ? \Carbon\Carbon::parse($infoLogs->updated_at) : null;
                                                                $diferenciaDias = $ultimaFecha ? $ultimaFecha->diffInDays(\Carbon\Carbon::now()) : null;
                                                            } else {
                                                                $ultimaFecha = null;
                                                                $diferenciaDias = "Usuario sin log";
                                                            }
                                                        @endphp
                                                
                                                        @if ($infoLogs)
                                                            | Último Ingreso: 
                                                            <span style="color:blue;background-color:yellowgreen">
                                                                {{ \Carbon\Carbon::parse($infoLogs->updated_at)->format('d/m/Y H:i') }} 
                                                                (Hace {{ $diferenciaDias }} días)
                                                            </span>
                                                        @else
                                                            | Último Ingreso: <span style="color:red">No disponible</span>
                                                        @endif
                                                    @else
                                                        Creado en: <span style="color:red">Usuario no encontrado</span>
                                                    @endif
                                                </div>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                     
                                    @endforeach
                                
                                </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            </div>
                        </div>
                    </div>    
            </section>
            <!-- /.content -->
        </section>
    </section>
</section>
@endsection

@section('Script')
    <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarNuevoUsuario')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se creo un nuevo registro de un Usuario',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.formularioNuevoUsuario').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer agregar el Usuario?',
            text: "Este cambio no puede ser borrado luego, y deberá ser validado por RRHH!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, crear el registro!'
          }).then((result) => {
            if (result.isConfirmed) {
              this.submit();
            }
          })
    })
    
</script>

@endsection
