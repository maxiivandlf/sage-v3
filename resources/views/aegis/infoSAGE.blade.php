@extends('layout.app')

@section('Titulo', 'Sage2.0 - Legajo Docente y F2')

@section('ContenidoPrincipal')
<style>
    input:hover{
        background-color: rgb(231, 199, 199);
    }
    .form-group {
    display: flex;
    align-items: center; 
}

label {
    margin-right: 10px;
    width: 75px;
}
.inforecibo{
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: nowrap;
    align-content: stretch;
    align-items: center;
}

</style>
@section('LinkCSS')
  <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
@endsection
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Mensaje ALERTA -->
            <div class="alert alert-info alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                <h5>Sr/a Docente, si usted encuentra algún dato que no concuerda con su situación de revista, por favor debe concurrir a la Institución donde usted trabaja y solicitar la corrección de sus datos...</h5>
                <hr>
            <h4><b>Datos de Ejemplo, de un Recibo de Sueldo</b></h4>
            
            @php
                $infoUsuario = session('InfoUsuario');
            @endphp
                <h4>Datos del Usuario que Registro: <b>{{$infoUsuario->Nombre}}</b><br>
                <h4>DNI del Usuario que Registro: <b>{{$infoUsuario->Documento}} <span style="color:red"><<---CONTROLE SI ES CORRECTO</span><br>
                    <p style="color:purple">En caso de no ser correcto, puede llegar a mostrar información invalida o no deseada</p>
            <div class="inforecibo">

                <img src="{{asset('img/recibo3.png')}}" alt="recibo3" style="width: 40%">
                <img src="{{asset('img/recibo1.png')}}" alt="recibo1" style="width: 30%">
                <img src="{{asset('img/recibo2.png')}}" alt="recibo2" style="width: 40%">
                    
            </div>
            al final de cada fila cargada por la escuela, debera completar con los datos del recibo.
            </div>
            <!-- Inicio Selectores -->
            <div class="alert alert-danger alert-dismissible">
                
                <h4><img src="{{asset('img/alarma.png')}}" alt="error" style="width: 40px"> Lista de errores observados en las cargas docentes!</h4>
                <h5>
                    <ol>
                        <li>El CUE, no es código de unidad de liquidación</li>
                        <li>El Código de Cargo Salarial, no es código de unidad de liquidación</li>
                        <li>Abreviaturas de su Colegio, no es código de unidad de liquidación</li>
                        <li>Su primer trabajo es 1 o 001, no es 000</li>
                    </ol>
                </h5>
            </div>
            <div class="row">
                <!-- Inicio Tabla-Card class="col-md-6-->
                <div class="">
                    @foreach($TrabajosCUES as $institucion)
                    <div class="card card-primary">
                        <div class="card-header">
                            @php
                                $infoEscuela = DB::table('tb_institucion_extension')
                                ->where('CUECOMPLETO',$institucion->CUECOMPLETO)
                                ->first();

                                //buscar los unid liq
                                $infoUnidLiq = DB::connection('DB8')->table('instarealiq')
                                ->where('instarealiq.CUEA',$institucion->CUECOMPLETO)
                                ->groupBy('instarealiq.escu')
                                ->select('instarealiq.escu')
                                ->get();
                                //dd($infoUnidLiq);
                            @endphp
                            
                            <div class="mizquierda" style="width: 600px">
                                <h4>Datos Institucionales</h4>
                                <h3 class="card-title">
                                    CUE+Ext: {{ $infoEscuela->CUECOMPLETO }} - Institución: {{   $infoEscuela->Nombre_Institucion }} - Zona: {{ $infoEscuela->Zona }} - Localidad: {{ $infoEscuela->Localidad }}
                                    <br><span style="color: yellow">Unidad de Liquidaciones Relacionadas al CUE: 
                                    @php
                                     $liqText = '';
                                       foreach($infoUnidLiq as $unidliq){
                                            // Validación más explícita para asegurarse de que 'escu' no sea vacío ni nulo
                                            $liqText .= !empty($unidliq->escu) ? $unidliq->escu : 'S/D';
                                            $liqText .= " / ";  // Añadir un separador solo si no es el último
                                        }
                                        echo rtrim($liqText, ' / '); // Remueve el último " / "
                                    @endphp
                                    </span>
                                </h3>
                            </div>
                           
                            <div class="card-tools" style="width: 500px">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                          <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" style="display: block;">
                            <table id="POFMH">
                                <thead class="card-header">
                                    <tr>
                                        {{-- <th class="custom-5rem" id="tablaarriba">#ID</th>
                                        <th class="custom-5rem">Orden</th> --}}
                                        <th class="custom-15rem" id="tablaarriba">Datos Personales</th>
                                        <th class="custom-25rem">Datos Salariales</th>
                                        <th class="custom-25rem">Datos Institucionales</th>
                                        <th class="custom-35rem">Datos de Situación</th>
                                        <th class="custom-25rem">Datos de Licencia</th>
                                        <th class="custom-25rem">Datos de Control</th>
                                    </tr>
                                </thead>
                                <tbody class="card-body">
                                    @php
                                        $infoUsuario = session('InfoUsuario');
                                        $infoPofmh = DB::connection('DB7')->table('tb_pofmh as pof')
                                        ->select('pof.*')
                                        ->where('CUECOMPLETO', $institucion->CUECOMPLETO)
                                        ->where('Agente', $infoUsuario->Documento)
                                        ->distinct()
                                        ->get();
                                    @endphp
                                    @if ($infoPofmh->isNotEmpty())
                                        @foreach ($infoPofmh as $fila)
                                        <tr data-id="{{$fila->idPofmh}}" class="fila " data-bg-color="default">
                                            {{-- <td>{{$fila->idPofmh}}</td>
                                            <td>{!! $fila->orden ?? '<span style="color: red;">Falta completar</span>' !!}</td> --}}
                                            <td>
                                                <p>ID: {{$fila->idPofmh}}</p>
                                                <p><b>DNI:</b> {!! $fila->Agente ?? '<span style="color: red;">Falta completar</span>' !!}
                                                </p>
                                                <p><b>Agente: </b>{!! $fila->ApeNom ?? '<span style="color: red;">Falta completar</ span>' !!}
                                                </p>
                                                @php
                                                    if ($fila && $fila->created_at) {
                                                        $ultimaFecha = $fila->updated_at ? \Carbon\Carbon::parse($fila->updated_at) : null;
                                                        $diferenciaDias = $ultimaFecha ? $ultimaFecha->diffInDays(\Carbon\Carbon::now()) : null;
                                                    } else {
                                                        $ultimaFecha = null;
                                                        $diferenciaDias = "Usuario Para Verificar Dias";
                                                    }
                                                @endphp

                                                <br><b>Registrado en Sistema<b><br>
                                                <span style="color:blue;background-color:yellowgreen">
                                                    {{ \Carbon\Carbon::parse($fila->created_at)->format('d/m/Y H:i') }}
                                                </span>
                                                <br><b>Ultima Actualización<b><br>
                                                    <span style="color:blue;background-color:yellowgreen">
                                                        {{ \Carbon\Carbon::parse($fila->updated_at)->format('d/m/Y H:i') }}<br>
                                                        (Hace {{ $diferenciaDias }} días)
                                                    </span>
                                            </td>
                                            <td>
                                                @php
                                                    $CargosCreados = DB::connection('DB7')->table('tb_origenes_cargos')
                                                    ->where('tb_origenes_cargos.CUECOMPLETO', $fila->CUECOMPLETO)
                                                    ->join('tb_cargos_pof_origen', 'tb_cargos_pof_origen.idCargos_Pof_Origen', '=', 'tb_origenes_cargos.nombre_origen')
                                                    ->get();
                                                @endphp     
                                                <p><b>Cargo de Origen en la Institución:</b> {!! $CargosCreados->firstWhere('idOrigenCargo', $fila->Origen)->nombre_cargo_origen ?? '<span style="color: red;">Falta completar</span>' !!}
                                                </p>
                                                <p><b>Situación de Revista: </b>{!! $SitRev->firstWhere('idSituacionRevista', $fila->SitRev)->Descripcion ?? '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Horas: </b>{!! $fila->Horas ?? '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Antigüedad: </b>{!! $fila->Antiguedad ?? '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Cargo Salarial: </b>{!! $CargosSalariales->firstWhere('idCargo', $fila->Cargo)->Cargo ?? '<span style="color: red;">Falta completar</span>' !!} 
                                                    <b>{!! $CargosSalariales->firstWhere('idCargo', $fila->Cargo)->Codigo ?? '' !!}</b></p>
                                            </td>
                                            
                                            
                                            <td>
                                                <p><b>Aula: </b>{!! $Aulas->firstWhere('idAula', $fila->Aula)->nombre_aula ?? '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Division: </b>{!! $Divisiones->firstWhere('idDivision', $fila->Division)->nombre_division ?? '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Turno: </b>{!! $Turnos->firstWhere('idTurno', $fila->Turno)->nombre_turno ?? '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Esp. Cur: </b>{!! $fila->EspCur ?? '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Matricula: </b>{!! $fila->Matricula ?? '<span style="color: red;">Falta completar</span>' !!}</p>
                                            </td>
                                           
                                            <td>
                                                <p><b>Fecha de Alta: </b>{!! $fila->FechaAltaCargo ? \Carbon\Carbon::parse($fila->FechaAltaCargo)->format('d-m-Y') : '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Fecha Designado: </b>{!! $fila->FechaDesignado ? \Carbon\Carbon::parse($fila->FechaDesignado)->format('d-m-Y') : '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Condición: </b>{!! $Condiciones->firstWhere('idCondicion', $fila->Condicion)->Descripcion ?? '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Activo Frente al Aula: </b>{!! $Activos->firstWhere('idActivo', $fila->Activo)->nombre_activo ?? '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Motivo: </b>{!! $Motivos->firstWhere('idMotivo', $fila->Motivo)->Nombre_Licencia ?? '<span style="color: red;">Falta completar</span>' !!} 
                                                    <b>{!! $Motivos->firstWhere('idMotivo', $fila->Motivo)->Codigo ?? '' !!}</b></p>
                                                <p><b>Datos por Condición: </b>{!! $fila->DatosPorCondicion ?? '<span style="color: red; table-layout: auto;">Falta completar</span>' !!}</p>
                                            </td>
                                            <td style="white-space: normal; overflow-wrap: break-word; word-wrap: break-word;">
                                                <p><b>Desde: </b>{!! $fila->FechaDesde ? \Carbon\Carbon::parse($fila->FechaDesde)->format('d-m-Y') : '<span style="color: red;">Falta completar</span>' !!}</p>
                                                <p><b>Hasta: </b>{!! $fila->FechaHasta ? \Carbon\Carbon::parse($fila->FechaHasta)->format('d-m-Y') : '<span style="color: red;">Falta completar</span>' !!}</p>
                                                
                                                <p><b>Suplente: </b>{!! $fila->AgenteR ?? '<span style="color: red;">Falta completar</span>' !!}</p>
                                                @php
                                                    if(!empty($fila->AgenteR)) {
                                                        $datosSuplente = DB::table('tb_agentes')
                                                            ->where('Documento', $fila->AgenteR)
                                                            ->first();
                                                        
                                                        if($datosSuplente) {
                                                            echo "-" . $datosSuplente->ApeNom;
                                                        } else {
                                                            echo "- Datos del suplente no encontrados";
                                                        }
                                                    }
                                                @endphp
                                                <p><b>Observaciones: </b>{!! $fila->Observaciones ?? '<span style="color: red; ">Falta completar</span>' !!}</p>
                                            </td>
                                           <td>
                                            <h4>Datos Del Recibo de Sueldo</h4>
                                            <form id="actualizarForm">
                                                @csrf <!-- Token de seguridad CSRF -->
                                                
                                                <div class="form-group">
                                                    <label>Cod. Escuela</label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control" id="codliq" name="codliq" placeholder="Cod. Escuela" value="{{$fila->Unidad_Liquidacion_Recibo}}">
                                                        
                                                        <span>Ejemplo: 408</span><br>
                                                        <span>Ejemplo: 1(sera convertido) a 001</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Desc. Escuela</label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control" id="descescuela" name="descescuela" placeholder="Descripción de Escuela" value="{{$fila->Descripcion_Recibo}}">
                                                        <span>Ejemplo: E.P.E.T. N.?</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Cod. Trabajo</label>
                                                    <div class="col-8">
                                                        <input type="number" min="1" class="form-control" id="codtrabajo" name="codtrabajo" placeholder="Cod. Trabajo" value="{{$fila->Trabajo_Recibo}}">
                                                        <span>Ejemplo: 1(sera convertido) a 001</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Cod. Area</label>
                                                    <select class="form-control col-8" name="codarea" id="codarea">
                                                        @foreach ($CodArea as $codarea)
                                                            <option value="{{$codarea->descripcion_area}}" @if($codarea->descripcion_area == $fila->Codigo_Area_Recibo) selected @endif>{{$codarea->descripcion_area}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input type="hidden" id="idPof" name="idPof" value="{{$fila->idPofmh}}">
                                                <button type="button" class="btn btn-success btnActualizar">Actualizar</button>

                                            </form>
                                            
                                           </td>
                                        
                                            
                                        </tr>
                                        @endforeach
                                    @else
                                        <div class="alert alert-warning alert-dismissible">
                                            <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                                            No se encontró información  del DNI en el sistema SAGE.
                                        </div>
                                    @endif
                                </tbody>
                                
                                
                            </table>
                            
                        </div>
                        
                        <!-- /.card-body -->
                      </div>
                        
                    @endforeach
                </div>
                <div class="col-md-6">

                @php
                                $infoUsuario = session('InfoUsuario');
                                $infoPofmh = DB::connection('DB7')->table('tb_pofmh as pof')
                                ->select('pof.*')
                                ->where('Agente', $infoUsuario->Documento)
                                ->distinct()
                                ->get();
                            @endphp
                            @if ($infoPofmh->isEmpty())
                            
                            <div class="alert alert-warning alert-dismissible">
                                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                                <h5>No se encontró información  del DNI en el sistema SAGE.
                                Consulte en su Escuela o a los Agentes de SAGE para mas información</h5>
                            </div>
                            @endif
                </div>
                <div class="col-md-3">
                    <div class="alert alert-info alert-dismissible">
                        <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                        Declaro bajo mi responsabilidad que al descargar el siguiente archivo, reconozco su veracidad, legalidad y acepto que cumple con las condiciones establecidas de corrección y conformidad.
                    </div>
                    {{-- <button class="btn btn-success">
                        <a href="{{asset("f2.pdf")}}" target="_blank" style="color:white">
                            <i class="fas fa-print"></i> F2 Actualizado
                        </a>
                    </button> --}}
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
<script>
$(document).ready(function() {
    $(document).on('click', '.btnActualizar', function(e) {
        e.preventDefault(); 

        let form = $(this).closest('form');

        let formData = {
            _token: form.find('input[name="_token"]').val(),
            codliq: form.find('#codliq').val(),
            descescuela: form.find('#descescuela').val(),
            codtrabajo: form.find('#codtrabajo').val(),
            codarea: form.find('#codarea').val(),
            idPof: form.find('#idPof').val()
        };

        function padLeft(value, length) {
            if (isNaN(value)) {
                return value; // Si no es numérico, devuelve el valor sin cambios
            }
            return value.toString().padStart(length, '0');
        }

        $.ajax({
            url: "{{ route('ActualizarPofmhRecibo') }}",
            method: "POST",
            data: formData,
            success: function(response) {
                Swal.fire(
                'Registro Actualizado Exitosamente',
                'Periodicamente controle estos datos, hasta que queden sincronizados. Gracias',
                'success'
                    )
                
                let codliqValue = form.find('#codliq').val(); 
                let codtrabajoValue = form.find('#codtrabajo').val(); 

                let formattedCodliq = padLeft(codliqValue, 3); 
                let formattedCodtrabajo = padLeft(codtrabajoValue, 3); 

                
                form.find('#codliq').val(formattedCodliq);
                form.find('#codtrabajo').val(formattedCodtrabajo);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    alert("Errores: " + Object.values(errors).join("\n"));
                } else {
                    alert("Error al actualizar los datos.");
                }
            }
        });
    });
});
</script>

@endsection