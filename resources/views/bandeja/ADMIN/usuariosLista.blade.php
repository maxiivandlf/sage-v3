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
                    <h4 class="text-center display-4">Panel de Usuarios y T&eacute;cnicos</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Usuarios en el Sistema(Admin y Técnicos)</h3>&nbsp; 
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>COD</th>
                                    <th>Apellido y Nombre</th>
                                    <th>Usuario(ALIAS)</th>
                                    <th>Clave</th>
                                    <th>Rol</th>
                                    <th>Correo Electrónico</th>
                                    <th>Activo</th>
                                    <th>CUE Base</th>
                                    <th>CUE Completo</th>
                                    <th>Turno</th>
                                    <th>Fecha Alta</th>
                                    <th>Habilitado?</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($UsuariosLista as $nag)
                                    <tr>
                                        <td>{{$nag->idUsuario}}</td>
                                        <td>{{$nag->Nombre}}</td>
                                        <td>{{$nag->Usuario}}</td>
                                        <td>{{$nag->Clave}}</td>
                                        <td>{{$nag->Modo}}</td>
                                        <td>{{$nag->email}}</td>
                                        <td class="text-center">{{$nag->Activo}}</td>
                                        <td>{{$nag->CUE}}</td>
                                        <td>{{$nag->CUECOMPLETO}}</td>
                                        <td>{{$nag->Turno}}</td>
                                        <td>{{$nag->created_at}}</td>
                                        <td>
                                            <a href="{{route('editarUsuario',$nag->idUsuario)}}" title="Editar Usuario">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            {{-- <a href="{{route('agregarCUEUsuario',$nag->idUsuario)}}" title="Agregar CUE a Usuario">
                                                <i class="fa fa-sitemap"></i>
                                            </a>
                                        
                                            <a>
                                                <i class="fa fa-eraser" style="color:red"></i>
                                            </a> --}}
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
