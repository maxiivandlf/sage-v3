@extends('layout.app')

@section('Titulo', 'Sage2.0 - Carreras y Planes')

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Importante!</h5>
                        Aquí debe buscar la carrera.<br>
                        Ejemplo: Para Jardines debe agregar <b>Nivel inicial</b>
                    </div>
                    <div class="card card-lightblue">
                    <div class="card-header">

                        <h3 class="card-title">
                        <i class="fas fa-book"></i>
                        Panel de Control - Carreras
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <form method="POST" action="{{ route('formularioCarreras') }}" class="formularioCarreras">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="carrera" class="col-auto align-self-center">Carreras Disponibles </label>
                            <div class="col-6">
                                <input type="text" class="form-control" id="DescripcionCarreras" name="DescripcionCarreras" value="" autocomplete="off">
                                <input type="hidden" class="form-control" id="Carreras" name="Carreras" value="">
                            </div>
                            <a class="btn btn-success" data-toggle="modal" href="#modalCarrera">
                                <i class="fa fa-ellipsis-h"></i>
                            </a>
                            <!--MODAL-->
                            <div class="modal fade" id="modalCarrera" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Lista de Carreras Cargadas</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <div class="card card-olive"> 
                                                <div class="card-header">
                                                    <div class="input-group">
                                                        <label class="col-auto col-form-label" for="Referencia">Buscar Carrera: </label>
                                                        <input class="form-control form-control-sm" type="text" id="btCarreras" onkeyup="getCarrerasTodas()" autocomplete="off" placeholder="Ingrese Nombre de la Carrera">
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table id="" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>DESCRIPCIÓN</th>
                                                                <th>TÍTULO</th>
                                                                <th>OPCIÓN</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="contenidoCarreras">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.fin modal -->
                        </div>                    
                    </div>   
                        <div class="card-footer bg-transparent">
                            <button type="submit" class="btn btn-primary">Seleccionar</button>
                        </div>
                    
                    </form>
                    <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- Inicio Tabla-Card -->
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Carreras Activas</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Carrera</th>
                                        <th>Duración(Años)</th>
                                        <th>Instrumento Legal</th>
                                        <th>Fecha de Alta</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($CarrerasRelSubOrg as $key => $o)
                                        <tr class="gradeX">
                                            <td>{{$o->idCarrera_SubOrg}}</td>
                                            <td>
                                                {{$o->Descripcion}}<br>
                                                <span>Titulo: {{$o->Titulo}}</span>
                                            </td>
                                            <td>{{$o->Duracion}}</td>
                                            <td>{{$o->InstrumentoLegal}}</td>
                                            <td>{{$o->FechaAlta}}</td>
                                            <td>
                                                <a class="d-flex justify-content-center" href="{{route('desvincularCarrera',$o->idCarrera_SubOrg)}}">
                                                    <i class="fa fa-trash"></i>
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

                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">
                            <i class="fas fa-book"></i>
                            Panel de Control - Modalidades
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('formularioPlanes') }}" class="formularioPlanes">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="Carrera">Carreras Disponibles</label>
                                    <select class="form-control" name="Carrera" id="Carrera">
                                    @foreach($CarrerasRelSubOrg as $key => $o)
                                        <option value="{{$o->idCarrera}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
                                </div> 
                                <div class="form-group">
                                    <label for="Plan">Modalidades Disponibles</label>
                                    <select class="form-control" name="Plan" id="Plan">
                                    @foreach($PlanesDeEstudio as $key => $p)
                                        <option value="{{$p->idPlanEstudio}}">{{$p->Descripcion}}</option>
                                    @endforeach
                                    </select>
                                </div> 
                            </div>
                            @if(count($CarrerasRelSubOrg)>0)
                                <div class="card-footer bg-transparent">
                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                </div>
                            @endif 
                        </form>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- Inicio Tabla-Card -->
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Modalidades Activas</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descripción de la Modalidad</th>
                                        <th>Fecha de Alta</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($PlanesRelSubOrg as $key => $o)
                                        <tr class="gradeX">
                                            <td>{{$o->PlanEstudio}}</td>
                                            <td>{{$o->Descripcion}}</td>
                                            <td>{{$o->FechaAlta}}</td>
                                            <td>
                                                <a class="d-flex justify-content-center" href="{{route('desvincularPlan',$o->idRelSuborganizacionPlan)}}">
                                                    <i class="fa fa-trash"></i>
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


@endsection