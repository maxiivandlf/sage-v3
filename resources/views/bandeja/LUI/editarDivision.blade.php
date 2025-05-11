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
                    <div class="card card-green">
                        <div class="card-header">
                            <h3 class="card-title">
                            <i class="fas fa-book"></i>
                            Panel de Control - Editando Division 
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('formularioActualizarDivisiones') }}" class="formularioActualizarDivisiones">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="Descripcion">Descripción (sala, curso, etc) Ej: "Sala de 3 "A"</label>
                                    <input type="text" class="form-control" id="Descripcion" name="Descripcion" placeholder="Ingrese Descripción" value="{{$Divisiones->Descripcion}}">
                                </div>
                                <div class="form-group">
                                    <label for="Curso">Sala/Curso/Etc</label>
                                    <select class="form-control" name="Curso" id="Curso">
                                    @foreach($Cursos as $key => $o)
                                        @if($o->idCurso == $Divisiones->Curso)
                                            <option value="{{$o->idCurso}}" selected="selected">{{$o->DescripcionCurso}}</option>
                                        @else
                                            <option value="{{$o->idCurso}}">{{$o->DescripcionCurso}}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                </div>  
                                <div class="form-group">
                                    <label for="Division">División</label>
                                    <select class="form-control" name="Division" id="Division">
                                    @foreach($Division as $key => $o)
                                        @if($o->idDivisionU == $Divisiones->Division)
                                            <option value="{{$o->idDivisionU}}" selected="selected">{{$o->DescripcionDivision}}</option>
                                        @else
                                            <option value="{{$o->idDivisionU}}">{{$o->DescripcionDivision}}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="Turno">Turno</label>
                                    <select class="form-control" name="Turno" id="Turno">
                                    @foreach($Turnos as $key => $o)
                                        @if($o->idTurno == $Divisiones->Turno)
                                            <option value="{{$o->idTurno}}" selected="selected">{{$o->Descripcion}}</option>
                                        @else
                                            <option value="{{$o->idTurno}}">{{$o->Descripcion}}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                </div> 
                            </div>
                            <div class="card-footer bg-transparent">
                                <input type="hidden" name="idDiv" value="{{$Divisiones->idDivision}}">
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                            
                        </form>
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

    $('.formularioActualizarDivisiones').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de querer editar una División a su Institución?',
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