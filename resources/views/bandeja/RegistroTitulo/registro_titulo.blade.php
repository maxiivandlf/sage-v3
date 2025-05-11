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
                Aqui texto para cargar títulos, cualquier aviso importante colocar aquí<br>
                Ejemplo: <b>Polimodal etc etc etc</b>
            </div>
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">
                            <i class="fas fa-book"></i>
                            Panel de Control - Registro de Títulos
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('formularioTituloYCertificado') }}" class="formularioTituloYCertificado">
                            @csrf
                            <div class="card-body">
                                <div class="row" style="display: flex;justify-content: center;gap: 100px;">
                                    <div class="form-group">
                                        <label for="fechaRegistro">Fecha de Registro</label>
                                        <input required type="date" class="form-control" id="fechaRegistro" name="fechaRegistro" placeholder="Ingrese Descripción" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div> 
                                    <div class="form-group">
                                        <label for="ultimoTitulo">Titulo - Registro Siguiente</label>
                                        <input required type="text" class="form-control" id="ultimoTitulo" name="ultimoTitulo" placeholder="Ingrese Descripción" value="{{($ultimoRegistroTitulo && $ultimoRegistroTitulo->idRegistroTitulo)?$ultimoRegistroTitulo->idRegistroTitulo + 1: 1}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="ultimoCertificado">Certificado - Registro Siguiente</label>
                                        <input required type="text" class="form-control" id="ultimoCertificado" name="ultimoCertificado" placeholder="Ingrese Descripción" value="{{($ultimoRegistroCertificado && $ultimoRegistroCertificado->idRegistroCertificado)?$ultimoRegistroCertificado->idRegistroCertificado + 1: 1}}">
                                    </div>
                                </div>
                                <h3>Datos del Agente</h3>
                                <div class="form-group col-12">
                                    <label for="Descripcion">Nombre y Apellido: <b>{{$Agentes->apellido_nombre}}</b></label>
                                    <label for="Descripcion">DNI: <b>{{$Agentes->dni}}</b></label>
                                </div>
                                <br>
                                <h3>Datos a inscribir en Titulo/ Certificado</h3>
                                <div class="row col-12">
                                    <div class="col-1" style="border-left:3px solid rgb(86, 160, 194);display: flex;align-items: center;">Primero:</div>
                                    <div class="form-group col-3">
                                        <div style="font-size: 15px;">
                                            <label for="Fecha">Tipo de Operación</label><br>
                                            <input style="width: auto; margin-right: 5px;" type="radio" id="opcionTitulo" name="opcion" value="Titulo" checked>
                                            <label for="opcionTitulo">Titulo</label><br>
                                            <input style="width: auto; margin-right: 5px;" type="radio" id="opcionCertificado" name="opcion" value="Certficado">
                                            <label for="opcionCertificado">Certficado</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-12"> 
                                    <div class="col-1"  style="border-left:3px solid rgb(216, 138, 48);display: flex;align-items: center;">Segundo:</div>  
                                    <div class="form-group col-3">
                                        <label for="Fecha">Fecha del Titulo / Certificado</label>
                                        <input required type="date" class="form-control" id="fecha" name="fecha" placeholder="Ingrese fecha descriptiva" value="">
                                    </div>
                                    {{-- <div class="form-group col-6">
                                        <label for="DescripcionOperacion"> Detalle Titulo / Certificado</label>
                                        <input required type="text" class="form-control" id="DescripcionOperacion" name="DescripcionOperacion" placeholder="Ingrese Descripción" value="">
                                    </div> --}}
                                </div>
                                <div class="row col-12">
                                    <div class="col-1"  style="border-left:3px solid rgb(216, 46, 46);display: flex;align-items: center;">Tercero:</div>
                                    <div class="form-group col-6">
                                        <label for="Curso">Titulos</label>
                                        <select class="form-control" name="titulo" id="titulo">
                                        @foreach($Titulos as $key => $o)
                                            <option value="{{$o->nombre_titulo}}">{{$o->nombre_titulo}}</option>
                                        @endforeach
                                        </select>
                                    </div> 
                                    <div class="form-group col-5">
                                        <label for="Curso">Certificado</label>
                                        <select class="form-control" name="certificado" id="certificado">
                                        @foreach($Certificados as $key => $o)
                                            <option value="{{$o->nombre_certificado}}">{{$o->nombre_certificado}}</option>
                                        @endforeach
                                        </select>
                                    </div> 
                                </div>
                                <div class="row col-12">
                                    <div class="col-1"  style="border-left:3px solid rgb(48, 223, 62);display: flex;align-items: center;">Cuarto:</div>
                                    <div class="form-group col-5">
                                        <label for="Curso">Otorgado Por</label>
                                        <select class="form-control" name="Establecimiento" id="Establecimiento">
                                        @foreach($Establecimiento as $key => $o)
                                            <option value="{{$o->nombre_establecimiento}}">{{$o->nombre_establecimiento}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="row col-12">
                                    <div class="col-1"  style="border-left:3px solid rgb(206, 203, 17);display: flex;align-items: center;">Quinto:</div>
                                    <div class="form-group col-6">
                                        <label for="FechaEgrego">Fecha de Egreso</label>
                                        <input required type="date" class="form-control" id="fechaEgreso" name="fechaEgreso" placeholder="Ingrese fecha descriptiva" value="">
                                    </div>
                                </div>
    
                                <div class="row col-12">
                                    <div class="col-1"  style="border-left:3px solid rgb(202, 108, 80);display: flex;align-items: center;">Sexto:</div>
                                    <div class="form-group col-6">
                                        <h3>URL de Titulo Online - Digital</h3>
                                        <div class="form-group col-12">
                                            <label for="url">Url del titulo / Certificado:</label>
                                            <input type="text" class="form-control" id="url" name="url" placeholder="Ingrese URL" value="">

                                        </div>
                                    </div>
                                </div>
                                <div class="row col-12">
                                    <div class="col-1"  style="border-left:3px solid rgb(202, 108, 80);display: flex;align-items: center;">Septimo:</div>
                                    <div class="form-group col-6">
                                        <h3>URL extra Documento</h3>
                                        <div class="form-group col-12">
                                            <label for="url2">Url del titulo / Certificado:</label>
                                            <input type="text" class="form-control" id="url2" name="url2" placeholder="Ingrese URL" value="">

                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="card-footer bg-transparent">
                                <input type="hidden" name="idU" value="{{$Agentes->dni}}">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                            
                        </form>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                    
             
            </div>
            <div class="row">
                <!-- Inicio Tabla-Card -->
                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Títulos Cargados</h3>
                        </div>
                        @php
                            use Illuminate\Support\Facades\DB;
                    
                            $Registro_Titulo = DB::connection('DB2')->table('tb_registro_de_titulos')
                            ->where('dni', $Agentes->dni)
                            ->orderby('idRegistroTitulo','desc')
                            ->get();
                        @endphp
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="titulosTab" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descripción</th>
                                        <th>Otorgado</th>
                                        <th>Fecha Gen.</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Registro_Titulo as $key => $o)
                                    <tr class="gradeX">
                                        <td>{{$o->idRegistroTitulo}}</td>
                                        <td>{{$o->nombre_titulo}}</td>
                                        <td>{{$o->otorgado_por}}</td>
                                        <td>{{ \Carbon\Carbon::parse($o->created_at)->format('d-m-Y H:i') }}</td>
                                        <td style="display: flex;justify-content: space-between;align-items: center;">
                                            @if ($o->URL_doc != "")
                                                <a target="_blank" class="d-flex justify-content-center" href="{{ asset('storage/TITCERT/' . $o->URL_doc) }}" title="Download" data-id="{{$o->idRegistroTitulo}}">
                                                    <i class="fa fa-download" style="color: green"></i>
                                                </a>
                                            @endif
                                            @if ($o->URL_titulo_Online != "")
                                                |  <a target="_blank" class="d-flex justify-content-center" href="{{ $o->URL_titulo_Online }}" title="ver Titulo Digital">
                                                    <i class="fa fa-eye" style="color: green"></i>
                                                </a>
                                            @endif
                                            |
                                            <a style="margin-right: 5px"  class="subir-doc" href="{{route('agregarDocAgenteTitulo',$Agentes->idAgente)}}" title="Agregar Mas Documentos" data-id="{{$Agentes->idAgente}}">
                                                <i class="fa fa-upload" style="color: green"></i>
                                            </a>
                                            |
                                            <a style="margin-right: 5px"  class="agregar-enlace" href="#" title="Borrar" data-id="{{$o->idRegistroTitulo}}" data-tipo="titulo">
                                                <i class="fa fa-eraser" style="color: red"></i>
                                            </a>
                                           
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
                <!-- Inicio Tabla-Card -->
                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Certificados Cargados</h3>
                        </div>
                        @php
                            $Registro_Certificados = DB::connection('DB2')->table('tb_registro_de_certificados')
                            ->where('dni', $Agentes->dni)
                            ->orderby('idRegistroUnicoCertificado','desc')
                            ->get();
                        @endphp
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="certificadosTab" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descripción</th>
                                        <th>Otorgado</th>
                                        <th>Fecha Gen.</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Registro_Certificados as $key => $o)
                                    <tr class="gradeX">
                                        <td>{{$o->idRegistroCertificado}}</td>
                                        <td>{{$o->nombre_certificado}}</td>
                                        <td>{{$o->otorgado_por}}</td>
                                        <td>{{ \Carbon\Carbon::parse($o->created_at)->format('d-m-Y H:i') }}</td>
                                        <td style="display: flex;justify-content: space-between;align-items: center;">
                                            @if ($o->URL_doc != "")
                                                <a target="_blank" class="d-flex justify-content-center" href="{{ asset('storage/TITCERT/' . $o->URL_doc) }}" title="Download" data-id="{{$o->idRegistroCertificado}}">
                                                    <i class="fa fa-download" style="color: green"></i>
                                                </a> |
                                               
                                            @endif
                                            |
                                            <a style="margin-right: 5px"  class="subir-doc" href="{{route('agregarDocAgenteTitulo',$Agentes->idAgente)}}" title="Agregar Mas Documentos" data-id="{{$Agentes->idAgente}}">
                                                <i class="fa fa-upload" style="color: green"></i>
                                            </a>
                                            |
                                            <a style="margin-right: 5px"  class="agregar-enlace" href="#" title="Borrar" data-id="{{$o->idRegistroCertificado}}" data-tipo="certificado">
                                                <i class="fa fa-eraser" style="color: red"></i>
                                            </a>
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
<script>
    // Agregar evento al enlace con clase "agregar-enlace"
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.agregar-enlace').forEach(function (enlace) {
            enlace.addEventListener('click', function (event) {
                event.preventDefault(); // Evitar que el enlace navegue

                // Obtener datos del enlace
                const id = this.getAttribute('data-id');
                const tipo = this.getAttribute('data-tipo');

                // Mostrar SweetAlert2
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Esto eliminará el registro con ID: ${id}. Esta acción no se puede deshacer.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí puedes agregar la lógica para eliminar (por ahora está preparado)
                        console.log(`Eliminar registro con ID: ${id}, Tipo: ${tipo}`);
                        Swal.fire(
                            'Eliminado!',
                            'El registro ha sido eliminado, mensaje de prueba, todavía no borra',
                            'success'
                        );
                    }
                });
            });
        });
    });
</script>

@endsection