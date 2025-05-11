@extends('layout.app')

@section('Titulo', 'Sage2.0 - Divisiones')

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Mensaje ALERTA -->
            <div class="alert alert-warning alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                En esta sección debe crear todas las secciones / cursos que tiene su Institución, además de determinar que valores usarán para los Servicios Generales, etc<br>
                Ejemplo: <b>Sala de 3 A&ntilde;os, P.S.G o Servicios Generales, etc</b>
            </div>
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">
                            <i class="fas fa-book"></i>
                            Panel de Control - Divisiones
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('formularioDivisiones') }}" class="formularioDivisiones">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="Descripcion">Descripción (sala, curso, etc) Ej: "Sala de 3 "A"</label>
                                    <input type="text" class="form-control" id="Descripcion" name="Descripcion" placeholder="Ingrese Descripción" value="">
                                </div>
                                <div class="form-group">
                                    <label for="Curso">Sala/Curso/Etc</label>
                                    <select class="form-control" name="Curso" id="Curso">
                                    @foreach($Cursos as $key => $o)
                                        <option value="{{$o->idCurso}}">{{$o->DescripcionCurso}}</option>
                                    @endforeach
                                    </select>
                                </div>  
                                <div class="form-group">
                                    <label for="Division">División</label>
                                    <select class="form-control" name="Division" id="Division">
                                    @foreach($Division as $key => $o)
                                        <option value="{{$o->idDivisionU}}">{{$o->DescripcionDivision}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="Turno">Turno</label>
                                    <select class="form-control" name="Turno" id="Turno">
                                    @foreach($Turnos as $key => $o)
                                        <option value="{{$o->idTurno}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
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
                    
                <!-- Inicio Tabla-Card -->
                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Cursos/Salas/Divisiones Activas</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descripción</th>
                                        <th>Curso/División</th>
                                        <th>Turno</th>
                                        <th>Fecha de Alta</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($Divisiones as $key => $o)
                                        <tr class="gradeX">
                                            <td>{{$o->idDivision}}</td>
                                            <td>{{$o->Descripcion}}</td>
                                            <td>
                                                {{$o->DescripcionCurso}} - "{{$o->DescripcionDivision}}"</span>
                                            </td>
                                            <td>{{$o->DescripcionTurno}}</td>
                                            <td>{{$o->FechaAlta}}</td>
                                            <td style="display: flex;">
                                                <a class="d-flex justify-content-center" href="{{route('desvincularDivision',$o->idDivision)}}" style="margin-right: 10px">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                @if ($Institucion->PermiteEditarTodo==1)
                                                    <a class="d-flex justify-content-center" href="{{route('editarDivision',$o->idDivision)}}" style="margin-right: 10px">
                                                        <i class="fa fa-edit"></i>
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
            title: 'Esta seguro de querer agregar una División a su Institución?',
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



@endsection