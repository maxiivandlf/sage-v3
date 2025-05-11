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
            <div class="alert alert-warning alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                Aqui texto para cargar documentos, cualquier aviso importante colocar aquí<br>
                Ejemplo: <b>Polimodal etc etc etc</b>
            </div>
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">
                            <i class="fas fa-book"></i>
                            Panel de Control - Agregar Documentos
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('formAgregarDocRegTitulo') }}" class="formAgregarDocRegTitulo" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    
                                    <div class="form-group col-6">
                                        <label for="descripcion">Descripción del Documento</label>
                                        <input required type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Ingrese Descripción" value="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="documento">Descripción del Documento</label>
                                        <input required type="file" class="form-control" id="file" name="file" placeholder="Ingrese Descripción" value="">
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <input type="hidden" name="dni" value="{{$Usuario->dni}}">
                                <button type="submit" class="btn btn-primary">Agregar Documento</button>
                            </div>
                            
                        </form>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                    
             
            </div>
            <div class="row">
                <!-- Inicio Tabla-Card -->
                <div class="col-md-8">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Documentos Cargados</h3>
                        </div>
                        @php
                            use Illuminate\Support\Facades\DB;
                    
                            $Registro_Titulo = DB::connection('DB2')->table('tb_registros_doc')
                            ->where('dni', $Usuario->dni)
                            ->orderby('idRegistros_doc','desc')
                            ->get();
                        @endphp
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descripción</th>
                                        <th>URL</th>
                                        <th>Fecha</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Registro_Titulo as $key => $o)
                                    <tr class="gradeX">
                                        <td>{{$o->idRegistros_doc}}</td>
                                        <td>{{$o->descripcion}}</td>
                                        <td>{{$o->URL}}</td>
                                        <td>{{ \Carbon\Carbon::parse($o->created_at)->format('d-m-Y H:i') }}</td>
                                        <td>
                                            @if ($o->URL != "")
                                                <a target="_blank" class="d-flex justify-content-center" href="{{ asset('storage/TITCERT/'.$o->dni."/".$o->URL) }}" title="Download" target="_BLANK">
                                                    <i class="fa fa-download" style="color: green"></i>
                                                </a>
                                            @endif
                                           
                                        </td>
                                        
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
        </section>
    </section>
</section>

@endsection

@section('Script')


    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#example').dataTable( {
                "aaSorting": [[ 0, "desc" ]],
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
        @if (session('ConfirmarAgregarTitCer')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se cargo correctamente un nuevo titulo/certificado',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarSubirDocOk')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se cargo correctamente un nuevo Documento',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarSubirDocFail')=='OK')
            <script>
            Swal.fire(
                'Registro Error',
                'No se pudo cargar el documento',
                'error'
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
    $('.formAgregarDocRegTitulo').submit(function(e){
        
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de querer agregar un Documento?',
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
        });
    
    
    
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