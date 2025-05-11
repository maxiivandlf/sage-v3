@extends('layout.app')

@section('Titulo', 'Sage2.0 - Editar Usuario ADMIN')

@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
                <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <a  href="/usuariosListaSupRegistrado" class="btn btn-outline-info"  title="Volver a Lista"  >
                        <span class="material-symbols-outlined">
                            reply_all
                        </span> VOLVER A Directorio Docentes Registrados
                    </a>
                    <!-- Buscador Agente -->
                    <h4 class="text-center display-4">Registrar Usuario(Superior)</h4>
                    <!-- Agregar Nuevo Agente -->
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <!-- general form elements -->
                            <div class="card card-green">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Crear Nuevo Agente
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                            
                                <form method="POST" action="{{ route('FormRegUsuarioSuperior') }}" class="FormRegUsuarioSuperior">
                                @csrf
                                    <div class="card-body" id="NuevoAgenteContenido1" style="display:visible">
                                        <!-- Fila Apellido, Nombre y Sexo -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="ApeNom">Apellido y Nombre: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="ApeNom" name="ApeNom" placeholder="Ingrese nombre completo" value="" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="sexo">Sexo: </label>
                                                {{-- falta tabla --}}
                                                <select class="form-control" name="sexo">
                                                    @foreach ($Sexos as $sexo)
                                                            <option value="{{$sexo->idSexo}}">{{$sexo->Descripcion}}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label for="Documento">Documento: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Documento" name="Documento" placeholder="Ingrese documento de identidad" value="" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="cuil">Cuil: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="cuil" name="cuil" placeholder="Ingrese numero de cuil" value="" required>
                                            </div>
                                        </div>

                                        <!-- Fila CUIL, Tipo de Agente -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="email">Correo Electrónico: </label>
                                                <input type="email" autocomplete="off" class="form-control" id="email" name="email" placeholder="Ingrese correo electrónico" value="" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="domicilio">Domicilio: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="domicilio" name="domicilio" placeholder="Ingrese domicilio vigente" value="" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="localidad">Localidad: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="localidad" name="localidad" placeholder="Ingrese localidad donde vive" value="" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="telefono">Teléfono: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="telefono" name="telefono" placeholder="Ingrese numero de teléfono" value="" required>
                                            </div>
                                        </div>

                                        <!-- Fila datos legajo -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="sexo">Legajo: </label>
                                                {{-- falta tabla --}}
                                                <select class="form-control" name="legajo">
                                                    @foreach ($Respuestas as $r)
                                                        <option value="{{$r->idSino}}">{{$r->respuesta}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label for="sexo">F2: </label>
                                                {{-- falta tabla --}}
                                                <select class="form-control" name="f2">
                                                    @foreach ($Respuestas as $r)
                                                        <option value="{{$r->idSino}}">{{$r->respuesta}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label for="fechaeva">Fecha de Evaluación: </label>
                                                <input type="date" autocomplete="off" class="form-control" id="fechaeva" name="fechaeva" placeholder="Ingrese fecha de evaluación" value="" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="tituloeva">Titulo Evaluación: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="tituloeva" name="titulo_evaluacion" placeholder="Ingrese titulo" value="0.0" required>
                                            </div>
                                        </div>

                                        <!-- Fila puntaje -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="ate">Antigüedad Titulo Evaluación: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="ate" name="antiguedad_titulo_evaluacion" placeholder="Ingrese Antigüedad de titulo evaluación" value="0.0" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="ane">Antigüedad Nivel Evaluación: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="ane" name="antiguedad_nivel_evaluacion" placeholder="Ingrese Antigüedad de nivel evaluación" value="0.0" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="auce">Antigüedad UC Evaluación: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="auce" name="antiguedad_uc_evaluacion" placeholder="Ingrese Antigüedad de uc evaluación" value="0.0" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="ae">Antecedentes Evaluación: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="ae" name="antecedentes_evaluacion" placeholder="Ingrese Antecedentes de evaluación" value="0.0" required>
                                            </div>
                                        </div>

                                        <!-- Fila puntaje -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="oee">Otros Estudios Evaluación: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="oee" name="otros_estudios_evaluacion" placeholder="Ingrese otros estudios de evaluación" value="0.0" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="oe">Otros Evaluación: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="oe" name="otros_evaluacion" placeholder="Ingrese otros evaluaciónes" value="0.0" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="to">Total de Evaluación: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="te" name="total_evaluacion" placeholder="Ingrese total de evaluaciónes" value="0.0" required>
                                            </div>
                                        </div>

                                        <!-- Fila observaciones -->
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label for="oee">Observaciones: </label>
                                                <textarea class="form-control" rows="10" cols="5" name="observacion"></textarea>
                                            </div>
                                        </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer bg-transparent" id="NuevoAgenteContenido2" style="display:visible">
                                        <button type="submit" class="btn btn-primary btn-block bg-success">Registrar Nuevo Agente</button>
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
        @if (session('ConfirmarRegistrarUsuarioSup')=='OK')
            <script>
            Swal.fire(
                'Registro Actualizado',
                'Se actualizó registro de un Agente',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.FormRegUsuarioSuperior').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer registrar el Usuario?',
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
