@extends('layout.app')

@section('Titulo', 'Sage2.0 - Asignaturas')

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <div class="row">
                @php
                    //consulto los esp cur cargados en cualquier turno por su cue
                    $infoEspCur = DB::table('tb_espacioscurriculares')
                    ->join('tb_tiposespacioscurriculares','tb_tiposespacioscurriculares.idTipoEspacioCurricular','=','tb_espacioscurriculares.Tipo')
                    ->join('tb_pof_regimendictado','tb_pof_regimendictado.idRegimenDictado','=','tb_espacioscurriculares.RegimenDictado')
                    ->join('tb_tiposhora','tb_tiposhora.idTipoHora','=','tb_espacioscurriculares.TipoHora')

                    ->where('CUE',session('CUE'))
                    ->where('idEspacioCurricular',$idEspCur)
                    ->first();
                @endphp
             
                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title"> <i class="fas fa-book mr-2"></i>Panel de Control - Espacios Curriculares</h3>
                        </div>
                        <!-- /.card-header -->
                    <form method="POST" action="{{ route('formularioEspCurAct') }}" class="formularioEspCurAct">
                    @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                    <label for="Asignatura" class="col-auto align-self-center">Asignaturas</label>
                                    <div class="col-6">
                                        <input type="text" class="form-control" id="DescripcionAsignatura" name="DescripcionAsignatura" value="{{$infoEspCur->Descripcion}}" autocomplete="off">
                                        <input type="hidden" class="form-control" id="Asignatura" name="Asignatura" value="{{$infoEspCur->Asignatura}}">
                                    </div>
                                    <a class="btn btn-success" data-toggle="modal" href="#modalAsignatura">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    
                                    <div class="modal fade" id="modalAsignatura" style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Lista de Asignatura Cargadas</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                
                                                <div class="modal-body">
                                                    <div class="card card-olive">
                                                        <div class="card-header">
                                                            <div class="input-group">
                                                                <label class="col-auto col-form-label" for="Referencia">Buscar Asignatura: </label>
                                                                <input class="form-control form-control-sm" type="text" id="btAsignatura" onkeyup="getAsignatura()" placeholder="Ingrese Nombre de la Carrera" autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <table id="" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>Descripción</th>
                                                                        <th>OPCIÓN</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="contenidoAsignatura">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-end">
                                                    <button type="button" class="btn bg-olive btn-default" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.fin modal -->
                            </div> 
                        {{-- <div class="form-group">
                                <label for="Asignatura">Asignaturas</label>
                                <select class="form-control" name="Asignatura" id="Asignatura">
                                @foreach($Asignaturas as $key => $o)
                                    <option value="{{$o->idAsignatura}}">{{$o->Descripcion}}</option>
                                @endforeach
                                </select>
                            </div>  --}}
                            <div class="form-group">
                                <label for="Carrera">Carreras Disponibles  - <b>Genérico</b></label>
                                <input type="text" class="form-control" id="Carrera" name="Carrera" placeholder="Carrera" value="Carrera Genérica" style="background-color:darksalmon">

                                {{-- <select class="form-control" name="Carrera" id="Carrera">
                                @foreach($CarrerasRelSubOrg as $key => $o)
                                    <option value="{{$o->idCarrera}}">{{$o->Descripcion}}</option>
                                @endforeach
                                </select> --}}
                            </div>
                            <div class="form-group">
                                <label for="Planes">Planes de Estudio - <b>Genérico</b></label>
                                <input type="text" class="form-control" id="Planes" name="Planes" placeholder="Plan de Estudio" value="Plan Genérico"  style="background-color:darksalmon">


                                {{-- <select class="form-control" name="Planes" id="Planes"> --}}
                                {{-- @foreach($Planes as $key => $o)
                                    <option value="{{$o->idPlanEstudio}}">{{$o->DescripcionPlan}}</option>
                                @endforeach --}}
                                {{-- </select> --}}
                            </div>
                            {{-- <div class="form-group">
                                <label for="CursoDivision">Cursos Disponibles</label>
                                <select class="form-control" name="CursoDivision" id="CursoDivision">
                                @foreach($Divisiones as $key => $o)
                                    <option value="{{$o->Curso}}">{{$o->Descripcion}}</option>
                                @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group">
                                <label for="TipoHora">Tipo de Hora</label>
                                <select class="form-control" name="TipoHora" id="TipoHora">
                                @foreach($TiposHora as $key => $o)
                                    @if($o->idTipoHora == $infoEspCur->TipoHora)
                                        <option value="{{$o->idTipoHora}}" selected="selected">{{$o->Descripcion_tipohora}}</option>
                                    @else
                                        <option value="{{$o->idTipoHora}}">{{$o->Descripcion_tipohora}}</option>
                                    @endif
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="CantHoras">Cantidad de Horas</label>
                                <input type="text" class="form-control" id="CantHoras" name="CantHoras" placeholder="Horas del cargo" value="{{$infoEspCur->Horas}}">
                            </div>
                            <div class="form-group">
                                <label for="RegimenDictado">Regimen de Dictado</label>
                                <select class="form-control" name="RegimenDictado" id="RegimenDictado">
                                    @foreach($RegimenDictado as $key => $o)
                                        @if($o->idRegimenDictado == $infoEspCur->RegimenDictado)
                                            <option value="{{$o->idRegimenDictado}}" selected="selected">{{$o->Descripcion_regimen}}</option>
                                        @else
                                            <option value="{{$o->idRegimenDictado}}">{{$o->Descripcion_regimen}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="TiposDeEspacioCurricular">Tipo de Esp. Curricular</label>
                                <select class="form-control" name="TiposDeEspacioCurricular" id="TiposDeEspacioCurricular">
                                @foreach($TiposDeEspacioCurricular as $key => $o)
                                    @if($o->idTipoEspacioCurricular == $infoEspCur->Tipo)
                                        <option value="{{$o->idTipoEspacioCurricular}}" selected="selected">{{$o->TiposDeEspacio}}</option>
                                    @else
                                        <option value="{{$o->idTipoEspacioCurricular}}">{{$o->TiposDeEspacio}}</option>
                                    @endif
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Observacion">Observación</label><br>
                                <textarea class="form-control" name="Observaciones" rows="5" cols="100%">{{$infoEspCur->Observaciones}}</textarea>
                            </div>
                        </div>
                           
                        <div class="card-footer bg-transparent">
                            <input type="hidden" name="iec" value="{{$idEspCur}}"/>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                            
                        
                    
                    </form>
                    <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- Inicio Tabla-Card -->
                
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
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#example3').dataTable( {
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

    $('.formularioEspCurAct').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer actualizar un Espacio Curricular a su Institución?',
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