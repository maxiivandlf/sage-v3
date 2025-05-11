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
                    <h4 class="text-center display-4">Agregar Usuario Nuevo</h4>
                    <!-- Agregar Nuevo Agente -->
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <!-- general form elements -->
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Agregar Nuevo Usuario
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                            
                                <form method="POST" action="{{ route('FormNuevoUsuario') }}" class="formularioNuevoUsuario form-group">
                                @csrf
                                    <div class="card-body" id="NuevoAgenteContenido1" style="display:visible">
                                        
                                        <!-- Fila Apellido, Nombre y Sexo -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="Apellido">Apellido: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Apellido" name="Apellido" placeholder="Ingrese apellido">
                                            </div>
                                            <div class="col-3">
                                                <label for="Nombre">Nombre: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Nombre" name="Nombre" placeholder="Ingrese nombre">
                                            </div>
                                            <div class="col-3">
                                                <label for="Sexo">Activo: </label>
                                                <select class="form-control" name="Activo" id="Activo">
                                                    <option value="S" selected="selected">SI</option>
                                                    <option value="N">NO</option>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label for="TL">Rol:</label>
                                                <select name="TipoRol" class="form-control custom-select">
                                                    @foreach ($Modos as $modo)
                                                        <option value='{{$modo->idModo}}'>{{$modo->Descripcion}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Fila CUIL, Tipo de Agente -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="Usuario">Usuario(ALIAS): </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Usuario" name="Usuario" placeholder="Ingrese un nombre para su ALIAS">
                                            </div>
                                            <div class="col-3">
                                                <label for="Clave">Clave: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Clave" name="Clave" placeholder="Ingrese una clave para autentíficarse">
                                            </div>
                                             <div class="col-3">
                                                <label for="Correo">Correo Electrónico: </label>
                                                <input type="email" autocomplete="off" class="form-control" id="Correo" name="Correo" placeholder="Ingrese Correo Electrónico">
                                            </div>
                                            <div class="col-3">
                                                <label for="Usuario">Turno: </label><br>
                                                <select class="form-control" name="Turno" id="Turno" style="display: inline">
                                                  @foreach ($TurnosUsuario as $t)
                                                        <option value="{{$t->idTurnoUsuario}}">{{$t->Descripcion}}</option>
                                                    @endforeach ()
                                                </select>
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

        @if (session('ConfirmarNuevoUsuarioError')=='OK')
            <script>
            Swal.fire(
                'Registro Fallido',
                'No se puede crear porque el correo ya esta siendo usado',
                'error'
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
