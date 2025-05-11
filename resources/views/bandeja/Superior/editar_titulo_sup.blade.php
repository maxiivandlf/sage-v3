@extends('layout.app')

@section('Titulo', 'Sage2.0 - Editar Usuario ADMIN')

@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
                <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <a  href="/editarUsuarioSupDocumentos/{{$Titulo->idAgente}}" class="btn btn-outline-info"  title="Volver a Lista"  >
                        <span class="material-symbols-outlined">
                            reply_all
                        </span> VOLVER A Directorio Títulos y Certificados
                    </a>
                    <!-- Buscador Agente -->
                    <h4 class="text-center display-4">Editar Titulo(Superior)</h4>
                    <!-- Agregar Nuevo Agente -->
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <!-- general form elements -->
                            <div class="card card-green">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Editar Titulo
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                            
                                <form method="POST" action="{{ route('FormActualizarTituloSup') }}" class="FormActualizarTituloSup">
                                @csrf
                                    <div class="card-body" id="NuevoAgenteContenido1" style="display:visible">
                                        <!-- Fila Apellido, Nombre y Sexo -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="op">Operación: </label>
                                                {{-- falta tabla --}}
                                                <select class="form-control" name="tipo_operacion">
                                                    @foreach ($Operacion as $op)
                                                        @if ($op->idOperacion_titulo == $Titulo->tipo_operacion)
                                                            <option value="{{$op->idOperacion_titulo}}" selected="selected">{{$op->nombre_operacion}}</option>
                                                        @else
                                                        <option value="{{$op->idOperacion_titulo}}" >{{$op->nombre_operacion}}</option>
                                                        @endif
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label for="Documento">Nombre del Titulo/Certificado: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Documento" name="nombre_titulo" placeholder="Ingrese Descripción" value="{{$Titulo->nombre_titulo}}" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="fechaegreso">Fecha de Egreso: </label>
                                                <input type="date" autocomplete="off" class="form-control" id="fechaegreso" name="fecha_egreso" placeholder="Ingrese fecha de egreso" value="{{$Titulo->fecha_egreso}}" required>
                                            </div>
                                            
                                        </div>

                                        <!-- Fila CUIL, Tipo de Agente -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="fecharegistro">Fecha de Registro: </label>
                                                <input type="date" autocomplete="off" class="form-control" id="fecharegistro" name="fecha_registro" placeholder="Ingrese fecha de registro" value="{{$Titulo->fecha_registro}}" required>
                                            </div>
                                            <div class="col-6">
                                                <label for="ins">Institución: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="ins" name="institucion" placeholder="Ingrese institución" value="{{$Titulo->institucion}}" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="nreg">N&deg; Registro/Resolución: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="nreg" name="num_registro" placeholder="Ingrese numero registro" value="{{$Titulo->num_registro}}" required>
                                            </div>
                                            
                                        </div>

                                        <!-- Fila datos legajo -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="ncat">N&deg; Horas Cat.: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="ncat" name="ncat" placeholder="Ingrese horas catedra" value="{{$Titulo->num_horas_catedras_curso}}" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="op">Tipo Curso: </label>
                                                {{-- falta tabla --}}
                                                <select class="form-control" name="tipo_curso">
                                                    @foreach ($Tipo_Curso as $op)
                                                        @if ($op->idTipo_curso == $Titulo->tipo_curso)
                                                            <option value="{{$op->idTipo_curso}}" selected="selected">{{$op->nombre_tipo_curso}}</option>
                                                        @else
                                                        <option value="{{$op->idTipo_curso}}" >{{$op->nombre_tipo_curso}}</option>
                                                        @endif
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label for="otros">Otros: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="otros" name="otros" placeholder="Ingrese otro valor" value="{{$Titulo->otros}}" required>
                                            </div>
                                            
                                        </div>

                                       

                                       

                                        <!-- Fila observaciones -->
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label for="oee">Observaciones: </label>
                                                <textarea class="form-control" rows="10" cols="5" name="observacion">{{$Titulo->observacion}}</textarea>
                                            </div>
                                        </div>
                                    <!-- /.card-body -->
                                    <input type="hidden" name="u" value="{{$Titulo->idTitulo_curso}}"/>
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
        @if (session('ConfirmarEditarTituloSup')=='OK')
            <script>
            Swal.fire(
                'Registro Actualizado',
                'Se actualizó registro de un Titulo',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.FormActualizarTituloSup').submit(function(e){
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


    function validarFecha4(event) {
        var fechaInput = event.target.value;
        var regex = /^\d{4}-\d{2}-\d{2}$/;
        var valid = true;
        var elemento = event.target;

        if (!regex.test(fechaInput)) {
            valid = false;
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD",
            }).then(function() {
                elemento.focus();
            });
            return false;
        }

        // Dividir la fecha en sus componentes
        var partesFecha = fechaInput.split("-");
        var año = parseInt(partesFecha[0]);
        var mes = parseInt(partesFecha[1]);
        var dia = parseInt(partesFecha[2]);

        // Verificar si el año es válido (entre 1950 y el año actual)
        var añoActual = new Date().getFullYear();
        if (año < 1950 || año > (añoActual+4)) {
            valid = false;
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Año inválido. Por favor, ingrese un año válido entre 1950 y " + (añoActual+4),
            }).then(function() {
                elemento.focus();
            });
            return false;
        }

        // Verificar si el mes es válido (entre 1 y 12)
        if (mes < 1 || mes > 12) {
            valid = false;
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
            }).then(function() {
                elemento.focus();
            });
            return false;
        }

        // Verificar si el día es válido
        var diasEnMes = new Date(año, mes, 0).getDate();
        if (dia < 1 || dia > diasEnMes) {
            valid = false;
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
            }).then(function() {
                elemento.focus();
            });
            return false;
        }

        return true;
    }
    document.getElementById('fechaeva').addEventListener('blur', validarFecha4);
</script>

@endsection
