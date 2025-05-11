@extends('layout.app')

@section('Titulo', 'Sage2.0 - Agentes')

@section('ContenidoPrincipal')
<style>
    input:hover{
        background-color: rgb(231, 199, 199);
    }
</style>
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Mensaje ALERTA -->
            <div class="alert alert-warning alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                Aquí texto para agregar info del agente<br>
                Ejemplo: <b>Perez, Juan, domicilio en....</b>
            </div>
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">
                            <i class="fas fa-book"></i>
                            Panel de Control - Agentes
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('formularioAgentesAlta') }}" class="formularioAgentesAlta">
                            @csrf
                            <div class="card-body">
                                <h3>Datos del Agente/Docente/etc</h3>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="Apellido">Apellido/s</label>
                                        <input type="text" class="form-control" id="Apellido" name="Apellido" placeholder="Ingrese apellido" value="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="Nombre">Nombre/s</label>
                                        <input type="text" class="form-control" id="Nombre" name="Nombre" placeholder="Ingrese nombre" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-3">
                                        <label for="DNI">DNI</label>
                                        <input type="text" class="form-control" id="DNI" name="DNI" placeholder="Ingrese dni" value="">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="FechaNacimiento">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="FechaNacimiento" name="FechaNacimiento"  value="">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="Ciudad">Ciudad de Nacimiento</label>
                                        <input type="text" class="form-control" id="Ciudad" name="Ciudad" placeholder="Ingrese ciudad de nacimiento" value="">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="Telefono">Teléfono/Celular</label>
                                        <input type="text" class="form-control" id="Telefono" name="Telefono" placeholder="Ingrese telefono" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-3">
                                        <label for="Nacionalidad">Nacionalidad</label>
                                        <input type="text" class="form-control" id="Nacionalidad" name="Nacionalidad" placeholder="Ingrese nacionalidad" value="">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="Provincia">Provincia</label>
                                        <input type="text" class="form-control" id="Provincia" name="Provincia" placeholder="Ingrese provincia" value="">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="Localidad">Localidad</label>
                                        <input type="text" class="form-control" id="Localidad" name="Localidad" placeholder="Ingrese Localidad"  value="">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="DomicilioActual">Domicilio Actual</label>
                                        <input type="text" class="form-control" id="DomicilioActual" name="DomicilioActual" placeholder="Ingrese domicilio Actual" value="">
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="correo">Correo Electrónico</label>
                                        <input type="text" class="form-control" id="Correo" name="Correo" placeholder="Ingrese correo electrónico" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Observacion">Observación</label><br>
                                        <textarea class="form-control" name="Observaciones" rows="5" cols="100%"></textarea>
                                    </div>
                                </div>
                            <div class="card-footer bg-transparent">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                            
                        </form>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                    
             
            </div>
            
        </section>
    </section>
</section>

@endsection

@section('Script')


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
        @if (session('ConfirmarNuevoAgente')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se cargo correctamente un nuevo Agente',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.formularioAgentesAlta').submit(function(e){
        if($("#DNI").val()=="" ||
        $("#Apellido").val()=="" ||
        $("#Nombre").val() == ""){
        console.log("error")
         e.preventDefault();
          Swal.fire(
            'Error',
            'No se pudo agregar, hay datos incompletos',
            'error'
                )
      }else{
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de querer agregar un Agente Nuevo?',
            text: "Recuerde colocar datos verdaderos",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, guardo el registro!'
          }).then((result) => {
            if (result.isConfirmed) {
              this.submit();
            }
          })
        }
    })
    
    
</script>
 <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarEliminarDivision')=='OK')
            <script>
            Swal.fire(
                'Registro Eliminado Exitosamente',
                'Se desvinculo correctamente',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarEliminarDivisionFallida')=='OK')
        <script>
        Swal.fire(
            'Error al borrar Registro',
            'No se puede borrar, debido a que esta vinculado a docente/s',
            'error'
                )
        </script>
    @endif


    <script>
        function validarFecha() {
            var fechaInput = document.getElementById('FechaNacimiento').value;
            var regex = /^\d{4}-\d{2}-\d{2}$/;
            if (!regex.test(fechaInput)) {
                //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
                document.getElementById('FechaNacimiento').focus();
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato Día-Mes-Año",
      
                });
                return false; // Retorna false si el formato de fecha es inválido
            }
      
            // Dividir la fecha en sus componentes
            var partesFecha = fechaInput.split("-");
            var año = parseInt(partesFecha[0]);
            var mes = parseInt(partesFecha[1]);
            var dia = parseInt(partesFecha[2]);
      
            // Verificar si el año es válido (entre 1000 y 9999)
            if (año < 1000 || año > 9999) {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Año inválido. Por favor, ingrese un año válido entre 1000 y 9999",
      
                });
                return false;
            }
      
            // Verificar si el mes es válido (entre 1 y 12)
            if (mes < 1 || mes > 12) {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
      
                });
                return false;
            }
      
            // Verificar si el día es válido
            var diasEnMes = new Date(año, mes, 0).getDate();
            if (dia < 1 || dia > diasEnMes) {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
      
                });
                return false;
            }
      
            // Si pasa todas las validaciones, retorna true
            return true;
        }
        document.getElementById('FechaNacimiento').addEventListener('blur', validarFecha);
      </script>
      <script>
        document.getElementById('DNI').addEventListener('input', function (e) {
            // Eliminar caracteres no numéricos
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
@endsection
