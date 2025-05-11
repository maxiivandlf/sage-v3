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
    height: 60px;
    margin: 0 0 10px 10px;
    min-width: 80px;
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
                    @php
                        $infoModoZona = DB::table('tb_zonasupervision')->where('idZonaSupervision',$valorModo)->first();
                    @endphp
                    <h4 class="text-center display-4">Panel de Escuelas Vinculadas a mi Cuenta</h4>
                    
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
                                                {{$nag->ZonaSuper}}-{{$nag->ZonaSuperCodigo}}
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
                                                <a class="btn btn-app"  href="{{route('verPofMhidExtSuper',$nag->idInstitucionExtension)}}">
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
                                                <a class="btn btn-app"  href="{{route('controlDeIpeSuper', $nag->idInstitucionExtension)}}" class="nav-link">
                                                    <i class="fas fa-check-square"></i> Ver Control IPE
                                                </a>
                                                <a class="btn btn-app"  href="{{route('agregarNovedadParticular', $nag->idInstitucionExtension)}}" class="nav-link">
                                                    <i class="fas fa-bell"></i> Novedades Generales
                                                </a>
                                                {{-- <div>
                                                    <a href="{{route('verPofMhidExtSuper',$nag->idInstitucionExtension)}}">
                                                        <div style="display: flex; gap: 1rem; justify-content: space-between;margin-top:5px">
                                                            <i class="fas fa-eye"></i>
                                                            Observar
                                                        </div>
                                                    </a>
                                                </div> --}}
                                            
                                                {{-- <div>
                                                    <a href="{{route('verCargosCreados',$nag->idInstitucionExtension)}}" class="nav-link">
                                                        <i class="fa fa-edit"></i>
                                                        Ver Pof
                                                    </a>
                                                </div> --}}
                                                {{-- <div>
                                                    <a href="{{route('asistencias_pofmh',$nag->idInstitucionExtension)}}" class="nav-link">
                                                        <i class="fa fa-edit"></i>
                                                        Ver Asistencia
                                                      </a>
                                                </div> --}}
                                                {{-- <div>
                                                    <a href="{{route('verCargosPofvsNominal',$nag->idInstitucionExtension)}}" class="nav-link">
                                                        <i class="fa fa-edit"></i>
                                                        Ver Pof Vs Nominal
                                                      </a>
                                                </div> --}}
                                                {{-- <div>
                                                    <a href="{{route('ver_novedades',['valor' => 'Todo', 'id' => $nag->idInstitucionExtension])}}" class="nav-link">
                                                        <i class="fa fa-edit"></i>
                                                        Novedades(Todas)
                                                      </a>
                                                </div> --}}
                                                {{-- <div>
                                                    <a href="{{route('adjuntar_novedad', $nag->idInstitucionExtension)}}" class="nav-link">
                                                        <i class="fa fa-edit"></i>
                                                        Adjuntar Novedades
                                                      </a>
                                                </div> --}}
                                                @php
                                                    $relacion = DB::table('tb_super_cue_relacion')->where('idInstitucionExtension',$nag->idInstitucionExtension)
                                                        ->where('idUsuarioSuper',session('idUsuario'))->first();
                                                @endphp
                                                <div class="form-group">
                                                    <button class="btn toggle-button {{!empty($relacion) ? 'btn-success' : 'btn-danger'}}" 
                                                        data-cue="{{$nag->CUECOMPLETO}}" 
                                                        data-id="{{$nag->idInstitucionExtension}}" 
                                                        data-super="{{session('idUsuario')}}" 
                                                        @if(!empty($relacion)) 
                                                            data-status="1" 
                                                        @else 
                                                            data-status="0" 
                                                        @endif
                                                    >
                                                        <i class="fas 
                                                            @if(!empty($relacion)) 
                                                                fa-toggle-on 
                                                            @else 
                                                                fa-toggle-off 
                                                            @endif
                                                        "></i> 
                                                        @if(!empty($relacion)) 
                                                                DESVINCULAR 
                                                            @else 
                                                                VINCULAR 
                                                            @endif
                                                        
                                                    </button>
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
<script>
$(document).ready(function() {
        // Delegación de eventos para el botón toggle en todas las filas
        $(document).on('click', '.toggle-button', function() {
            console.log("Botón toggle clickeado");

            // Obtener el estado actual del botón
            var isToggled = !$(this).hasClass('btn-success'); // Si no tiene la clase btn-success, está desactivado

            // Obtener los datos asociados al botón
            var cue = $(this).data('cue');
            var id = $(this).data('id');
            var superId = $(this).data('super');

            console.log("Estado toggle:", isToggled);
            console.log("Datos enviados:", cue, id, superId);

            // Cambiar el color y el icono del botón según el estado
            if (isToggled) {
                $(this).removeClass('btn-danger').addClass('btn-success'); // Verde
                $(this).find('i').removeClass('fa-toggle-off').addClass('fa-toggle-on'); // Icono activado
                $(this).html(' <i class="fa fa-toggle-on"></i> DESVINCULAR');
            } else {
                $(this).removeClass('btn-success').addClass('btn-danger'); // Rojo
                $(this).find('i').removeClass('fa-toggle-on').addClass('fa-toggle-off'); // Icono desactivado
                $(this).html(' <i class="fa fa-toggle-off"></i> VINCULAR');
            }

            // Preparar los datos a enviar
            var dataToSend = {
                cue: cue,
                id: id,
                super_id: superId,
                status: isToggled ? 1 : 0 // 1 cuando está activado (verde), 0 cuando está desactivado (rojo)
            };

            // Realizar la solicitud AJAX para guardar la relación
            $.ajax({
                url: isToggled ? '/agregar_relacion_cue_super' : '/eliminar_relacion_cue_super',
                type: 'POST',
                data: dataToSend,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
                success: function(response) {
                    console.log("Respuesta del servidor:", response);
                    if (response.success) {
                        Swal.fire({
                            title: 'Éxito',
                            text: isToggled ? 'Relación CUE-Supervisor activada.' : 'Relación CUE-Supervisor eliminada.',
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al actualizar la relación.',
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText); // Ver el error del servidor
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo conectar con el servidor.',
                        icon: 'error'
                    });
                }
            });
        });
    });
</script>
@endsection
