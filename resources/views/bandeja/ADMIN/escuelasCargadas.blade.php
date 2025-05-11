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
                    <h4 class="text-center display-4">Panel de Escuelas con Datos Incompletos</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Escuelas sin datos</h3>&nbsp; 
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
                                        <td>
                                            @if(empty($nag->Nivel))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->Nivel}}
                                            @endif
                                        </td>
                                        <td>
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
                                        <td>
                                            @if(empty($nag->Zona))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->Zona}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(empty($nag->ZonaSupervision))
                                                <span style="color: red;">Completar</span>
                                            @else
                                                {{$nag->ZonaSupervision}}
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
