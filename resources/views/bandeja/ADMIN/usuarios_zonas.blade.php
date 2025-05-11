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
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Zonas Activas</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->
                      <div id="accordion">
                        <div class="card card-primary">
                          <div class="card-header">
                            <h4 class="card-title w-100" >
                              <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false">
                                Zona XXX
                              </a>
                            </h4>
                          </div>
                          <div id="collapseOne" class="collapse" data-parent="#accordion" style="">
                            <div class="card-body">
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
                                                            <th rowspan="1" style="text-align:center">Área</th>
                                                            <th rowspan="1" style="text-align:center">Cargo/Función</th>
                                                            <th rowspan="1" style="text-align:center">Agrupamiento</th>
                                                            <th rowspan="1" style="text-align:center">Cant. Horas</th>
                                                            <th rowspan="1" style="text-align:center">Nomenclatura</th>
                                                            <th rowspan="1" style="text-align:center">Zona</th>
                                                            <th rowspan="1" style="text-align:center">Localidad</th>
                                                            <th rowspan="1" style="text-align:center">Días Trab.</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                        //dd($indoDesglose);
                                                        @endphp
                                                    @foreach($indoDesglose as $key => $n)
                                                            <tr class="gradeX">
                                                                <td>{{ isset($n->docu) ? $n->docu : '' }}</td>
                                                                <td>{{ isset($n->cuil) ? $n->cuil : '' }}</td>
                                                                <td>{{ isset($n->nomb) ? $n->nomb : '' }}</td>
                                                                <td>{{ isset($n->sexo) ? $n->sexo : '' }}</td>
                                                                <td>{{ isset($n->CUE) ? $n->CUE : '' }}</td>
                                                                <td>{{ isset($n->desc_escu) ? $n->desc_escu : '' }}</td>
                                                                <td>{{ isset($n->area) ? $n->area : '' }}</td>
                                                                <td>{{ isset($n->desc_plan) ? $n->desc_plan : '' }}</td>
                                                                <td>{{ isset($n->desc_agru) ? $n->desc_agru : '' }}</td>
                                                                <td>{{ isset($n->hora) ? $n->hora : '' }}</td>
                                                                <td>{{ isset($n->nomencla) ? $n->nomencla : '' }}(<b>{{ isset($n->codigo) ? $n->codigo : '' }}</b>)</td>
                                                                <td>{{ isset($n->zona) ? $n->zona : '' }}</td>
                                                                <td>{{ isset($n->desc_zona) ? $n->desc_zona : '' }}</td>
                                                                <td>{{ isset($n->dias) ? $n->dias : '' }}</td>
                
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
                          </div>
                        </div>
                        <div class="card card-danger">
                          <div class="card-header">
                            <h4 class="card-title w-100">
                              <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                Zona ZZZ
                              </a>
                            </h4>
                          </div>
                          <div id="collapseTwo" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                             aqui mas info
                            </div>
                          </div>
                        </div>
                        <div class="card card-success">
                          <div class="card-header">
                            <h4 class="card-title w-100">
                              <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                                Zona ABC
                              </a>
                            </h4>
                          </div>
                          <div id="collapseThree" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                             aqui mas info
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
  
              </div>
            <!-- Inicio Selectores -->
           
            
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