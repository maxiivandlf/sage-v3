@extends('layout.app')

@section('Titulo', 'Sage2.0 - Bajas')

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Inicio Tabla-Card -->
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Importante!</h5>
                        Agregar mas info sobre las novedades</b>
                    </div>
                    <div class="card card-lightblue">
                        <div class="card-header ">
                            
                            <h3 class="card-title">Novedades - BAJAS CON ALTAS COMPLETADAS</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align:center">DNI</th>
                                        <th rowspan="2" style="text-align:center">Apellido y Nombres</th>
                                        {{-- <th rowspan="2" style="text-align:center">Cargo</th>
                                        <th rowspan="2" style="text-align:center">Caracter</th> --}}
                                        <th rowspan="2" style="text-align:center">Turno</th>
                                        <th colspan="3" style="text-align:center">Servicios en el Mes</th>
                                        <th rowspan="2" style="text-align:center">Motivo</th>
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
                                            <td>{{ !empty($infoDocu->docu) ? $infoDocu->docu : 'Valor Dato' }}</td>
                                            <td>{{ !empty($infoDocu->nomb) ? $infoDocu->nomb : 'Valor Dato' }}</td>
                                            {{-- <td class="text-center">{{$n->Cargo}}<b>({{$n->CodCar}})</b></td>
                                            <td class="text-center">{{$n->SitRev}}</td> --}}
                                            <td class="text-center"><b>{{$n->nombre_turno}}</b></td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($n->FechaDesde)->format('d-m-Y')}}</td>
                                            @if ($n->FechaHasta==null)
                                                <td class="text-center">{{$n->FechaHasta}}</td>
                                            @else
                                                <td class="text-center">{{ \Carbon\Carbon::parse($n->FechaHasta)->format('d-m-Y')}}</td>
                                            @endif
                                            <td class="text-center">{{($n->CantidadDiasTrabajados != 0) ? $n->CantidadDiasTrabajados : "1"}}</td>
                                            {{-- <td class="text-center">{{$n->Codigo}} - {{$n->Nombre_Licencia}} - {{$n->F3}}</td> --}}
                                            <td>{{$n->Observaciones}}</td>
                                            
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
            title: '¿Está seguro de querer vincular un Plan de Estudio a la carrera Seleccionada?',
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