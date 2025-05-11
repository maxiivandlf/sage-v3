@extends('layout.app')

@section('Titulo', 'Sage2.0 - Altas')

@section('ContenidoPrincipal')
{{-- <div class="loader">
    <h2>Por favor, espere...</h2>
    <div id="clock"></div>
  </div> --}}
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <div class="alert alert-warning alert-dismissible">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Importante!</h5>
                Estado de la Consulta: <h3>{{$estado}}</h3>
            </div>
            <div class="row">
                <div class="card card-info  col-lg-8">
                    <div class="card-header">
                      <h3 class="card-title">Busqueda por CUE</h3>
                    </div>
                    <form action="{{ route('buscar_cue_liq') }}"  class="buscar_cue_liq" id="buscar_cue_liq" method="POST" >
                        @csrf
                    <div class="card-body  col-lg-12">
                      <div class="row  col-lg-12">
                        
                          <div class="col-6">
                            <input type="text" class="form-control" placeholder="Ingrese CUE" name="cue">
                          </div>
                          <div class="col-6">
                            <input type="submit" class="form-control btn-success" value="Consultar CUE" name="btnCUE">
                          </div>
                        
                        
                      </div>
                    </div>
                    </form>
                    <!-- /.card-body -->
                </div>
            
            </div>
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Inicio Tabla-Card -->
                    
                    <div class="card card-lightblue">
                        <div class="card-header ">
                            
                            <h3 class="card-title">Novedades - CUE</h3>
                        </div>
                        
                        @if (isset($indoDesglose) && !$indoDesglose->isEmpty())
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th colspan="4" style="text-align:center">Datos Personales</th>
                                            <th colspan="9" style="text-align:center">Datos Institucionales</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="1" style="text-align:center">DNI</th>
                                            <th rowspan="1" style="text-align:center">CUIL</th>
                                            <th rowspan="1" style="text-align:center">Apellido y Nombres</th>
                                            <th rowspan="1" style="text-align:center">Sexo</th>
                                            <th rowspan="1" style="text-align:center">CUE</th>
                                            <th rowspan="1" style="text-align:center">Nombre Institución</th>
                                            <th rowspan="1" style="text-align:center">Zona</th>
                                            <th rowspan="1" style="text-align:center">Cargo/Función</th>
                                            <th rowspan="1" style="text-align:center">Nivel</th>
                                            <th rowspan="1" style="text-align:center">Cant. Horas</th>
                                            <th rowspan="1" style="text-align:center">Localidad</th>
                                            <th rowspan="1" style="text-align:center">Días Trab.</th>
                                            <th rowspan="1" style="text-align:center">Observaciones</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        //dd($indoDesglose);
                                        @endphp
                                    @foreach($indoDesglose as $key => $n)
                                            <tr class="gradeX">
                                                <td align="center">{{ isset($n->Documento) ? $n->Documento : '' }}</td>
                                                <td align="center">{{ isset($n->Cuil) ? $n->Cuil : '' }}</td>
                                                <td>{{ isset($n->ApeNom) ? $n->ApeNom : '' }}</td>
                                                <td align="center">{{ isset($n->Sexo) ? $n->Sexo : '' }}</td>
                                                <td align="center">{{ isset($n->CUECOMPLETO) ? $n->CUECOMPLETO : '' }}</td>
                                                <td>{{ isset($n->Nombre_Institucion) ? $n->Nombre_Institucion : '' }}</td>
                                                <td align="center">{{ isset($n->Zona) ? $n->Zona : '' }}</td>
                                                <td align="center">{{ isset($n->CargoSalarial) ? $n->CargoSalarial : '' }}</td>
                                                <td align="center">{{ isset($n->Nivel) ? $n->Nivel : '' }}</td>
                                                <td align="center">{{ isset($n->CantidadHoras) ? $n->CantidadHoras : '' }}</td>
                                                <td>{{ isset($n->iloc) ? $n->iloc : '' }}</td>
                                                <td align="center">{{ isset($n->CantidadAsistencia) ? $n->CantidadAsistencia : '' }}</td>
                                                <td>{{ isset($n->Observaciones) ? $n->Observaciones : '' }}</td>

                                            </tr>
                                        @endforeach 
                                    </tbody>
                                </table>
                            </div>
                        <!-- /.card-body -->
                        @endif
                        
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
        @if (session('ConfirmarActualizarCarrera')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se actualizó correctamente',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.formularioCarreras').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer agregar una carrera a su Institución?',
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
        @if (session('ConfirmarEliminarCarrera')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se desvinculó correctamente',
                'success'
                    )
            </script>
        @endif
    <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarActualizarPlanes')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se actualizó correctamente',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.formularioPlanes').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer vincular un Plan de Estudio a la carrera Seleccionada??',
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
        @if (session('ConfirmarActualizarAsignatura')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se actualizó correctamente',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.formularioAsignaturas').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer agregar una nueva asignatura al listado de SAGE??',
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
           @if (session('ConfirmarEliminarEspCur')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se desvinculó correctamente',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarActualizarEspCur')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se actualizó correctamente',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.formularioEspCur').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer agregar un Espacio Curricular a su Institución?',
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
@endsection