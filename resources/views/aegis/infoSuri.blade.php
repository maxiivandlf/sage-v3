@extends('layout.app')

@section('Titulo', 'Sage2.0 - Registro de Títulos y Certificado')

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
             <div class="alert alert-info alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
               Aquí aparecen las inscripciones realizadas en SURI</b>
            </div>
            <!-- Inicio Selectores -->
            
            <div class="row">
                <!-- Inicio Tabla-Card -->
                <div class="col-md-12">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Documentos Cargados</h3>
                        </div>
                        @php
                            use Carbon\Carbon;

                            $DocSuri = DB::connection('DB6')->table('admin_registraciones_convocatorias')
                            ->join('admin_convocatorias','admin_convocatorias.id_convocatoria','=','admin_registraciones_convocatorias.id_convocatoria')
                            ->where('admin_registraciones_convocatorias.id_persona', '51515')
                            ->orderby('admin_registraciones_convocatorias.id_registracion','desc')
                            ->get();
                        @endphp
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="titulosTab" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>IDRegConvocatoria</th>
                                        <th>IDPersona</th>
                                        <th>Convocatoria</th>
                                        <th>Desde/Hasta/Estado</th>
                                        <th>Fecha de Registro</th>
                                        <th>Acepto los terminos?</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($DocSuri as $key => $o)
                                    <tr class="gradeX">
                                        <td>{{$o->id_registracion}}</td>
                                        <td>{{$o->id_persona}}</td>
                                        <td>{{$o->denominacion}}</td>
                                        <td>
                                            {{Carbon::parse($o->fecha_inicio)->format('d/m/Y')." -- ".Carbon::parse($o->fecha_fin)->format('d/m/Y')}}
                                            <br>{{"Estado: ".$o->id_estado}}</td>
                                        <td>{{ Carbon::parse($o->fecha_alta)->format('d/m/Y') }}</td>
                                        @if ($o->terminos_condiciones)
                                            <td style="color:green">Aceptados <i class="fa fa-check"</td>  
                                        @else
                                            <td style="color:salmon">No Aceptados <i class="fa fa-ban"></td>
                                        @endif
                                        
                                        
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
    
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
              
            </div>
            <br>
            <!-- Mensaje ALERTA -->
            <div class="alert alert-info alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
               Aquí aparecen los capacitaciones / titulos o certificados que se acreditan en SURI</b>
            </div>
            <!-- Inicio Selectores -->
            
            <div class="row">
                <!-- Inicio Tabla-Card -->
                <div class="col-md-8">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Documentos Cargados</h3>
                        </div>
                        @php
                            use Illuminate\Support\Facades\DB;
                    
                            $DocSuri = DB::connection('DB6')->table('admin_personas_certificaciones')
                            ->where('id_persona', '51515')
                            ->orderby('id','desc')
                            ->get();
                        @endphp
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="titulosTab" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>IDPersona</th>
                                        <th>IDESTADO</th>
                                        <th>NomArchivo.</th>
                                        <th>Presentado.</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($DocSuri as $key => $o)
                                    <tr class="gradeX">
                                        <td>{{$o->id}}</td>
                                        <td>{{$o->id_persona}}</td>
                                        <td>{{$o->id_estado}}</td>
                                        <td>{{$o->archivo}}</td>
                                        <td>{{ \Carbon\Carbon::parse($o->fecha_operacion)->format('d-m-Y H:i') }}</td>
                                        
                                        
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
    
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
              
            </div>
            <br>

        </section>
    </section>
</section>

@endsection

@section('Script')


    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#titulosTab').dataTable( {
                "aaSorting": [[ 0, "desc" ]],
                "oLanguage": {
                    "sLengthMenu": "Ob _MENU_ por página",
                    "search": "Buscar:",
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente"
                    }
                }
            } );
        } );
        $(document).ready(function() {
            $('#certificadosTab').dataTable( {
                "aaSorting": [[ 0, "desc" ]],
                "oLanguage": {
                    "sLengthMenu": "Ob _MENU_ por página",
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
        @if (session('ConfirmarAgregarTitCer')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se cargo correctamente un nuevo titulo/certificado',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.formularioTituloYCertificado').submit(function(e){
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
            title: 'Esta seguro de querer agregar un Titulo?',
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
            var fechaInput = document.getElementById('fecha').value;
            var regex = /^\d{4}-\d{2}-\d{2}$/;
            if (!regex.test(fechaInput)) {
                //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
                document.getElementById('fecha').focus();
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
        function validarFecha2() {
            var fechaInput = document.getElementById('fechaEgreso').value;
            var regex = /^\d{4}-\d{2}-\d{2}$/;
            if (!regex.test(fechaInput)) {
                //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
                document.getElementById('fechaEgreso').focus();
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
        document.getElementById('fecha').addEventListener('blur', validarFecha);
        document.getElementById('fechaEgreso').addEventListener('blur', validarFecha2);
      </script>


@endsection