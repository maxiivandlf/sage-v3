@extends('layout.app')

@section('Titulo', 'Sage2.0 - Altas')


@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            @php
                //busco todos los niveles que se van cargando y los agrupo
                $InstitucionesxNivel = DB::table('tb_institucion_extension')
                ->select(
                    'tb_institucion_extension.Nivel',
                    DB::raw('COUNT(*) as Cantidad')
                )
                ->groupBy('Nivel')
                ->get(); 
                
            @endphp
        <div class="row">
            @foreach ($InstitucionesxNivel as $fn)
                <div class="col-md-3 inline-block">
                    <div class="info-box shadow-lg">
                    <span class="info-box-icon bg-success"><i class="fas fa-school"></i></span>
        
                    <div class="info-box-content">
                        <span class="info-box-text">Nivel: {{ !empty($fn->Nivel) ? $fn->Nivel : 'Sin Determinar' }}</span>
                        <span class="info-box-number">Creados: {{$fn->Cantidad}}</span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            @endforeach
        </div>    
            <!-- Inicio Selectores -->
            {{-- <div class="row">
                <div class="col-md-12">
                    <!-- Inicio Tabla-Card -->
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Importante!</h5>
                        Agregar mas info sobre las novedades</b>
                    </div>
                    <div class="card card-lightblue">
                        <div class="card-header ">
                            
                            <h3 class="card-title">Novedades - CUES</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="6" style="text-align:center">Asignados</th>
                                        <th colspan="3" style="text-align:center">Progreso</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="1" style="text-align:center">CUE</th>
                                        <th rowspan="1" style="text-align:center">CUECOMPLETO</th>
                                        <th rowspan="1" style="text-align:center">Instituci&oacute;n</th>
                                        <th rowspan="1" style="text-align:center">Turno</th>
                                        <th rowspan="1" style="text-align:center">Fecha de Inicio</th>
                                        <th rowspan="1" style="text-align:center">Fecha de Fin</th>
                                        <th rowspan="1" style="text-align:center">Estado</th>
                                        <th rowspan="1" style="text-align:center">Cantidad de Personas en POF</th>  
                                        <th rowspan="1" style="text-align:center" >%</th>    
                                    </tr>
                                </thead>
                                <tbody>
                                 @foreach($NovedadesCUE as $key => $n)
                                        <tr class="gradeX">
                                            @php
                                                /*$infoDocu = DB::table('tb_desglose_agentes')
                                                    ->where('tb_desglose_agentes.docu', $n->Agente)
                                                    ->first();*/
                                                //dd($infoDocu);
                                            @endphp             
                                            <td>{{$n->CUE}}</td>
                                            <td>{{$n->CUECOMPLETO}}</td>
                                            <td>{{$n->Nombre_Institucion}}</td>
                                            <td>
                                                @foreach ($Turnos as $e)
                                                    @if ($e->idTurnoUsuario == $n->idTurnoUsuario)
                                                        {{$e->Descripcion}}
                                                    @endif
                                                @endforeach  
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="project-state">
                                                <span class="badge badge-success">INICIADO</span>
                                            </td>
                                            <td align="center">
                                                @php
                                                    $cantidadPersonas = DB::table('tb_nodos')->where('CUECOMPLETO',$n->CUECOMPLETO)->count();
                                                    echo $cantidadPersonas;

                                                    $MaximoValor = DB::table('tb_institucion_extension')
                                                    ->join('tb_institucion','tb_institucion.idInstitucion', '=', 'tb_institucion_extension.idInstitucion')
                                                    ->join('tb_desglose_agentes','tb_desglose_agentes.escu', '=', 'tb_institucion.Unidad_Liquidacion')
                                                    ->where('tb_institucion_extension.CUE',$n->CUE)
                                                    ->count();
                                                    //$MaximoValor = 50;//$n->CantidadSubidos;
                                                    // Calcula el porcentaje
                                                    if($MaximoValor !=0){
                                                        $porcentaje = intval(($cantidadPersonas / $MaximoValor) * 100);
                                                    }else{
                                                        $porcentaje = 0;
                                                    }
                                                    
                                                @endphp
                                            </td>
                                            <td class="project_progress">
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{$porcentaje}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$porcentaje}}%">
                                                    </div>
                                                </div>
                                                <small>

                                                    {{$porcentaje}}% completado
                                                </small>
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

               
            </div> --}}
            
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