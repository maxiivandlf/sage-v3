@extends('layout.app')

@section('Titulo', 'Sage2.0 - Divisiones')

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Mensaje ALERTA -->
            <div class="alert alert-warning alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                En esta sección se darán de alta novedades individuales, se irán agregando según la necesidad</b>
            </div>
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">
                            <i class="fas fa-book"></i>
                            Panel de Novedades  - Extras para Técnicos
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('verNovedadesParticulares') }}" class="verNovedadesParticulares">
                            @csrf
                            <div class="card-body flex" style="100%">
                                <div class="form-row" style="width: 100%">
                                    <div class="form-group" style="width: 450px">
                                        <label for="DNI">CUE</label>
                                        <input type="text" class="form-control" id="DNI" name="CUE" placeholder="Ingrese el CUE" value="{{$cueUsado}}">
                                    </div>
                                  
                                    <div class="form-group" style="margin-left: 20px">
                                        <label for="TL">Turno </label>
                                        <select name="turno" class="form-control custom-select">
                                            @foreach($Turnos as $key => $o)
                                                <option value="{{$o->idTurnoUsuario}}">({{$o->Descripcion}})</option>
                                            @endforeach 
                                        </select>
                                      </div>
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
            <div class="row">
                <div class="col-12">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">
                            <i class="fas fa-book"></i>
                            Panel de Control - Novedades Particulares - Mes Actual: {{$MesActual}}
                            </h3>
                        </div>
                        <div class="card-body">
                         <!-- /.card-header -->
                         <div class="card-body">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align:center">DNI</th>
                                        <th rowspan="2" style="text-align:center">Apellido y Nombres</th>
                                        
                                        <th colspan="3" style="text-align:center">Fecha Novedad</th>
                                        
                                        <th rowspan="2" style="text-align:center">Observaciones</th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center">Fecha Desde</th>
                                        <th style="text-align:center">Fecha Hasta</th>
                                        <th style="text-align:center">Total Días</th>
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                 @foreach($Novedades as $key => $n)
                                        <tr class="gradeX">
                                            @php
                                                $infoDocu = DB::table('tb_desglose_agentes')
                                                    ->where('tb_desglose_agentes.docu', $n->Agente)
                                                    ->first();
                                                //dd($infoDocu);
                                            @endphp
                                            @if ($infoDocu)
                                            <td>{{$infoDocu->docu}}</td>
                                            <td>{{$infoDocu->nomb}}</td>
                                            @else
                                            <td>Sin datos</td>
                                            <td>Sin datos</td>
                                            @endif             
                                           
                                            
                                            <td class="text-center">{{ \Carbon\Carbon::parse($n->FechaDesde)->format('d-m-Y')}}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($n->FechaHasta)->format('d-m-Y')}}</td>
                                            
                                            <td class="text-center">{{($n->TotalDiasLicencia)?$n->TotalDiasLicencia:'DIA'}}</td>
                                            @php
                                                $infoNovedadExtra = DB::table('tb_novedades_extras')
                                                ->where('tb_novedades_extras.idNovedadExtra', $n->idNovedadExtra)
                                                ->first();
                                            //dd($infoDocu);
                                        @endphp
                                            <td>{{$infoNovedadExtra->tipo_novedad}} : {{$n->Observaciones}}</td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    <!-- /.card-body -->
                        </div>
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
                    "sLengthMenu": "Escuelas _MENU_ por pagina",
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
        @if (session('ConfirmarActualizarDivisiones')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se actualizó correctamente',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.formularioDivisiones').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer agregar una División a su Institución?',
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
    })
    
    $('.formularioNovedadParticular').submit(function(e){
        e.preventDefault();
        var dni = $('#DNI').val(); 
        if (!dni) {
        // Muestra un mensaje de error si el input está vacío
        Swal.fire({
            title: 'Error',
            text: 'El campo DNI debe estar completo.',
            icon: 'error'
        });
        return; // Detiene el proceso de envío del formulario
    }
        Swal.fire({
            title: '¿Está seguro de querer agregar una novedad para el Agente?',
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
    })
</script>
 <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarEliminarDivision')=='OK')
            <script>
            Swal.fire(
                'Registro Eliminado Exitosamente',
                'Se desvinculó correctamente',
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
         @if (session('ConfirmarNuevaNovedadParticular')=='OK')
            <script>
            Swal.fire(
                'Novedades',
                'Se agrego una novedad con éxito',
                'success'
                    )
            </script>
         @endif

    <script>
        $(document).ready(function() {
            $('#DNI').on('input', function() { // Detecta cuando el usuario sale del campo DNI
                var dni = $(this).val(); // Obtiene el valor del campo DNI

                $.ajax({
                    type: 'POST', // Método HTTP utilizado
                    url: '/buscar_agente', // URL del script PHP que manejará la búsqueda en la base de datos
                    data: { dni: dni }, // Datos que se enviarán al servidor (en este caso, el DNI)
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(response) {
                        $('#ApeNom').val(response.msg); // Actualiza el campo "Apellido y Nombre" con la respuesta del servidor
                    }
                });
            });
        });



        document.getElementById('DNI').addEventListener('input', function(event) {
            // Remover puntos y comas en tiempo real
            this.value = this.value.replace(/[.,]/g, '');
        });
    </script>

<script>
function validarFecha() {
        var fechaInput = document.getElementById('FechaInicio').value;
        var regex = /^\d{4}-\d{2}-\d{2}$/;
        if (!regex.test(fechaInput)) {
            //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
            document.getElementById('FechaInicio').focus();
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
        var fechaInput = document.getElementById('FechaHasta').value;
        var regex = /^\d{4}-\d{2}-\d{2}$/;
        if (!regex.test(fechaInput)) {
            //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
            document.getElementById('FechaHasta').focus();
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
    document.getElementById('FechaInicio').addEventListener('blur', validarFecha);
    document.getElementById('FechaHasta').addEventListener('blur', validarFecha2);
</script>

@endsection