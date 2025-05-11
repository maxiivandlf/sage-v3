@extends('layout.app')

@section('Titulo', 'Sage2.0 - Editar Usuario ADMIN')

@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
                <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Buscador Agente -->
                    <h4 class="text-center display-4">Editar Usuario</h4>
                    <!-- Agregar Nuevo Agente -->
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <!-- general form elements -->
                            <div class="card card-green">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Editar Usuario
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                            
                                <form method="POST" action="{{ route('FormActualizarUsuario') }}" class="formularioActualizarUsuario form-group">
                                @csrf
                                    <div class="card-body" id="NuevoAgenteContenido1" style="display:visible">
                                        <!-- Fila Apellido, Nombre y Sexo -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="Nombre">Nombre Completo: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Nombre" name="Nombre" placeholder="Ingrese nombre" value="{{$Usuario[0]->Nombre}}">
                                            </div>
                                            <div class="col-3">
                                                <label for="Sexo">Activo: </label>
                                                <select class="form-control" name="Activo" id="Activo">
                                                @if ($Usuario[0]->Activo == 'S')
                                                    <option value="S" selected="selected">SI</option>
                                                    <option value="N">NO</option>
                                                @else
                                                    <option value="S">SI</option>
                                                    <option value="N" selected="selected">NO</option>
                                                @endif
                                                    
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label for="TL">Rol:</label>
                                                <select name="TipoRol" class="form-control custom-select">
                                                    @foreach ($Modos as $modo)
                                                        @if($modo->idModo == $Usuario[0]->Modo)
                                                            <option value='{{$modo->idModo}}' selected="selected">{{$modo->Descripcion}}</option>
                                                        @else
                                                            <option value='{{$modo->idModo}}'>{{$modo->Descripcion}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Fila CUIL, Tipo de Agente -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="Usuario">Usuario(ALIAS): </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Usuario" name="Usuario" placeholder="Ingrese un nombre para su ALIAS" value="{{$Usuario[0]->Usuario}}">
                                            </div>
                                            <div class="col-3">
                                                <label for="Clave">Clave: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Clave" name="Clave" placeholder="Ingrese una clave para autentíficarse" value="{{$Usuario[0]->Clave}}">
                                            </div>
                                             <div class="col-3">
                                                <label for="Correo">Correo Electrónico: </label>
                                                <input type="email" autocomplete="off" class="form-control" id="Correo" name="Correo" placeholder="Ingrese Correo Electrónico" value="{{$Usuario[0]->email}}">
                                            </div>
                                            <div class="col-3">
                                                <label for="Usuario">Turno: </label><br>
                                                <select class="form-control" name="Turno" id="Turno" style="display: inline">
                                                  @foreach ($TurnosUsuario as $t)
                                                    @if ($t->idTurnoUsuario == $Usuario[0]->Turno)
                                                        <option value="{{$t->idTurnoUsuario}}" selected="selected">{{$t->Descripcion}}</option>
                                                    @else
                                                        <option value="{{$t->idTurnoUsuario}}">{{$t->Descripcion}}</option>
                                                    @endif 
                                                    @endforeach ()
                                                </select>
                                              </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <input type="hidden" name="us" value="{{$Usuario[0]->idUsuario}}"/>
                                    <div class="card-footer bg-transparent" id="NuevoAgenteContenido2" style="display:visible">
                                        <button type="submit" class="btn btn-primary btn-block bg-success">Actualizar Información</button>
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
        @if (session('ConfirmarActualizarUsuario')=='OK')
            <script>
            Swal.fire(
                'Registro Actualizado',
                'Se actualizó registro de un Usuario',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.formularioActualizarUsuario').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer actualizar el Usuario?',
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
