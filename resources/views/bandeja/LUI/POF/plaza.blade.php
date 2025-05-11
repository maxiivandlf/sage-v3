@extends('layout.app')

@section('Titulo', 'Sage2.0 - Plazas')

@section('ContenidoPrincipal')

<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <a href="{{route('verSubOrg',$idOrg)}}" type="button" class="btn btn-info block"><i class="fa fa-mail-reply-all"></i> Volver a Organizaciones</a>

                    <header class="panel-heading">
                        Plazas
                        <div class="alert alert-success alert-block fade in">
                            
                            <h4>
                                <i class="fa fa-ok-sign"></i>
                                INFORMACIÓN
                            </h4>
                            <p>aquí como cargar reestructuras</p>
                        </div>
                    </header>
                    <div class="panel-body">
                          <div class="adv-table">
                              <table  class="display table table-bordered table-striped" id="example">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>CUE/CUISE</th>
                                    <th>Nombre</th>
                                    <th>Fecha de Alta</th>
                                    <th>Cargo Salarial</th>
                                    <th>Asignatura</th>
                                    <th>Función</th>
                                    <th>Ver Mas...</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($infoPlazas as $key => $plaza)
                                    <tr class="gradeX">
                                        <td>{{$plaza->idPlaza}}</td>
                                        <td>{{$plaza->CUISE}}</td>
                                        <td>{{$plaza->CUPOF}}</td>
                                        <td>{{$plaza->FechaAlta}}</td>
                                        <td>{{$plaza->CargoSalarialDefault}}</td>
                                        <td>{{$plaza->Asignatura}}</td>
                                        <td>{{$plaza->TipoDeFuncion}}</td>
                                        <td>
                                            {{--  <a href="{{route('verSubOrg',$plaza->idPlaza)}}">
                                                <i class="fa fa-eye"></i>
                                            </a>  --}}
                                            NADA POR AHORA
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                    </table>
                          </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->

        <div class="row">
            <div class="col-lg-6">

                <form method="POST" action="{{ route('AltaPlaza') }}" class="formularioAlta">
                    @csrf
                    <section class="panel">
                        <header class="panel-heading">
                            Alta de Nueva Plaza
                        </header>
                        <div class="panel-body">
                            <form role="form">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label for="suborg">SubOrganización </label> 
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="SubOrg"  name="SubOrg" value="{{$infoSubOrg[0]->Descripcion}}" autocomplete="off">
                                        <input type="text" class="form-control" id="idSubOrg"  name="idSubOrg" value="{{$infoSubOrg[0]->idSubOrganizacion}}">
                                        <input type="hidden" name="CUE" value="{{$infoSubOrg[0]->CUE}}">
                                    </div>
                                </div>
                                <div class="form-inline form-group">
                                    <label for="carrera">Carrera (*)</label>
                                    <input type="text" class="form-control" id="DescripcionCarrera" name="DescripcionCarrera" value="" autocomplete="off">
                                    <input type="text" class="form-control" id="idCarrera"  name="idCarrera" value="">
                                    <a class="btn btn-success" data-toggle="modal" href="#modalCarrera">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <div class="modal fade" id="modalCarrera" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    <h4 class="modal-title">Carreras</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- page start-->
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <section class="panel">
                                                                <header class="panel-heading">
                                                                    Lista de Carreras
                                                                    <div class="alert alert-success alert-block fade in">
                                                                        <h4>
                                                                            <i class="fa fa-ok-sign"></i>
                                                                            INFORMACIÓN
                                                                            <button type="button" id="traerCarreras" onclick="getCarreras()">traer<i class="fa fa-refresh"></i></button>
                                                                        </h4>
                                                                        <p>aquí explicar</p>
                                                                    </div>
                                                                </header>
                                                                <div class="panel-body" >
                                                                      <div class="adv-table">
                                                                          <table  class="display table table-bordered table-striped" id="example">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>ID</th>
                                                                                <th>Carrera</th>
                                                                                <th>Título</th>
                                                                                <th>Duración</th>
                                                                                <th>Opciones</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody id="contenidoCarreras">

                                                                            </tbody>
                                                                </table>
                                                                      </div>
                                                                </div>
                                                            </section>
                                                        </div>
                                                    </div>
                                                    <!-- page end-->
      
                                                </div>
                                                <div class="modal-footer">
                                                    <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-inline form-group">
                                    <label for="carrera">Planes de Estudio (*)</label>
                                    <input type="text" class="form-control" id="DescripcionPlanDeEstudio" name="DescripcionPlanDeEstudio" value="" autocomplete="off">
                                    <input type="text" class="form-control" id="idPlanEstudio" name="idPlanEstudio" value="">
                                    <a class="btn btn-success" data-toggle="modal" href="#modalPlanEstudio">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <div class="modal fade" id="modalPlanEstudio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    <h4 class="modal-title">Planes de Estudio</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- page start-->
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <section class="panel">
                                                                <header class="panel-heading">
                                                                    Lista de Planes de Estudio
                                                                    <div class="alert alert-success alert-block fade in">
                                                                        <h4>
                                                                            <i class="fa fa-ok-sign"></i>
                                                                            INFORMACIÓN
                                                                            <button type="button" id="traerPlanes" onclick="getPlanes()">traer<i class="fa fa-refresh"></i></button>
                                                                        </h4>
                                                                        <p>aquí explicar</p>
                                                                    </div>
                                                                </header>
                                                                <div class="panel-body" >
                                                                      <div class="adv-table">
                                                                          <table  class="display table table-bordered table-striped" id="example">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>ID</th>
                                                                                <th>Plan de Estudio</th>
                                                                                <th>Estado</th>
                                                                                <th>Opciones</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody id="contenidoPlanes">

                                                                            </tbody>
                                                                </table>
                                                                      </div>
                                                                </div>
                                                            </section>
                                                        </div>
                                                    </div>
                                                    <!-- page end-->
      
                                                </div>
                                                <div class="modal-footer">
                                                    <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-inline form-group">
                                    <label for="carrera">División (*)</label>
                                    <input type="text" class="form-control" id="DescripcionDivision" name="DescripcionDivision" value="" autocomplete="off">
                                    <input type="text" class="form-control" id="idDivision" name="idDivision" value="">
                                    <a class="btn btn-success" data-toggle="modal" href="#modalDivision">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <div class="modal fade" id="modalDivision" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    <h4 class="modal-title">Divisiones</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- page start-->
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <section class="panel">
                                                                <header class="panel-heading">
                                                                    Lista de Divisiones
                                                                    <div class="alert alert-success alert-block fade in">
                                                                        <h4>
                                                                            <i class="fa fa-ok-sign"></i>
                                                                            INFORMACIÓN
                                                                            <button type="button" id="traerPlanes" onclick="getDivisiones()">traer<i class="fa fa-refresh"></i></button>
                                                                        </h4>
                                                                        <p>aquí explicar</p>
                                                                    </div>
                                                                </header>
                                                                <div class="panel-body" >
                                                                      <div class="adv-table">
                                                                          <table  class="display table table-bordered table-striped" id="example">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>ID</th>
                                                                                <th>Descripción</th>
                                                                                <th>Turno</th>
                                                                                <th>Opciones</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody id="contenidoDivision">

                                                                            </tbody>
                                                                </table>
                                                                      </div>
                                                                </div>
                                                            </section>
                                                        </div>
                                                    </div>
                                                    <!-- page end-->
      
                                                </div>
                                                <div class="modal-footer">
                                                    <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-inline form-group">
                                    <label for="carrera">Espacio Curricular (*)</label>
                                    <input type="text" class="form-control" id="DescripcionEspacioCurricular" name="DescripcionEspacioCurricular" value="" autocomplete="off">
                                    <input type="text" class="form-control" id="idEspacioCurricular" name="idEspacioCurricular" value="">
                                    <a class="btn btn-success" data-toggle="modal" href="#modalEspacioCurricular">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <div class="modal fade" id="modalEspacioCurricular" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    <h4 class="modal-title">Espacio Curricular</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- page start-->
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <section class="panel">
                                                                <header class="panel-heading">
                                                                    Lista de Espacios Curriculares
                                                                    <div class="alert alert-success alert-block fade in">
                                                                        <h4>
                                                                            <i class="fa fa-ok-sign"></i>
                                                                            INFORMACIÓN
                                                                            <button type="button" id="traerEspacioCurricular" onclick="getEspacioCurriculares()">traer<i class="fa fa-refresh"></i></button>
                                                                        </h4>
                                                                        <p>aquí explicar</p>
                                                                    </div>
                                                                </header>
                                                                <div class="panel-body" >
                                                                      <div class="adv-table">
                                                                          <table  class="display table table-bordered table-striped" id="example">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>ID</th>
                                                                                <th>Espacio Curricular</th>
                                                                                <th>Curso</th>
                                                                                <th>Horas</th>
                                                                                <th>Regimen de Dictado</th>
                                                                                <th>Opciones</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody id="contenidoEspacioCurricular">

                                                                            </tbody>
                                                                </table>
                                                                      </div>
                                                                </div>
                                                            </section>
                                                        </div>
                                                    </div>
                                                    <!-- page end-->
      
                                                </div>
                                                <div class="modal-footer">
                                                    <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="turnos">Turno (*)</label>
                                    <select class="form-control" name="Turno" id="Turno">
                                    @foreach($infoTurnos as $key => $o)
                                        <option value="{{$o->idTurno}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="FA">Fecha de Alta (*)</label>
                                    <input type="date" class="form-control" id="FechaAlta" name="FechaAlta" placeholder="Ingrese Fecha">
                                </div>

                                <div class="form-group">
                                    <label for="Horas">Horas (*)</label>
                                    <input type="text" class="form-control" id="Horas" name="Horas" placeholder="Ingrese cantidad de Horas">
                                </div>

                                <div class="form-group">
                                    <label for="NC">Norma de Creación (*)</label>
                                    <input type="text" class="form-control" id="NormaDeCreacion" name="NormaDeCreacion" placeholder="Ingrese Norma de Creación">
                                </div>

                                <div class="form-group">
                                    <label for="turnos">Regimen Laboral (*)</label>
                                    <select class="form-control" name="RegimenLaboral" id="RegimenLaboral">
                                    @foreach($infoRegimenLaboral as $key => $o)
                                        <option value="{{$o->idRegimenLaboral}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="turnos">Tipo de Función (*)</label>
                                    <select class="form-control" name="TipoDeFuncion" id="TipoDeFuncion">
                                    @foreach($infoTiposDeFuncion as $key => $o)
                                        <option value="{{$o->idTipoFuncion}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="turnos">Regimen Salarial (*)</label>
                                    <select class="form-control" name="RegimenSalarial" id="RegimenSalarial">
                                    @foreach($infoRegimenSalarial as $key => $o)
                                        <option value="{{$o->idRegimenSalarial}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-inline form-group">
                                    <label for="carrera">Cargo Salarial Default (*)</label>
                                    <input type="text" class="form-control" id="DescripcionCargoSalarialDefault" name="DescripcionCargoSalarialDefault" value="" autocomplete="off">
                                    <input type="text" class="form-control" id="idCargoSalarial" name="idCargoSalarial" value="">
                                    <a class="btn btn-success" data-toggle="modal" href="#modalCargoSalarial">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <div class="modal fade" id="modalCargoSalarial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    <h4 class="modal-title">Cargos Salariales</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- page start-->
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <section class="panel">
                                                                <header class="panel-heading">
                                                                    Lista de Cargos Salariales
                                                                    <div class="alert alert-success alert-block fade in">
                                                                        <h4>
                                                                            <i class="fa fa-ok-sign"></i>
                                                                            INFORMACIÓN
                                                                            <button type="button" id="traerPlanes" onclick="getCargosSalariales()">traer<i class="fa fa-refresh"></i></button>
                                                                        </h4>
                                                                        <p>aqui explicar</p>
                                                                    </div>
                                                                </header>
                                                                <div class="panel-body" >
                                                                      <div class="adv-table">
                                                                          <table  class="display table table-bordered table-striped" id="example">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Código</th>
                                                                                <th>Cargo</th>
                                                                                <th>Puntos</th>
                                                                                <th>Sueldo Básico</th>
                                                                                <th>Opciones</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody id="contenidoCargosSalariales">

                                                                            </tbody>
                                                                </table>
                                                                      </div>
                                                                </div>
                                                            </section>
                                                        </div>
                                                    </div>
                                                    <!-- page end-->
      
                                                </div>
                                                
                                                <div class="modal-footer">
                                                    <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="Horarios">Horarios (*)</label>
                                    <input type="text" class="form-control" id="Horarios" name="Horarios" value="lun: 08:00 a 09:30; mar: 08:00 a 09:30; mie: 08:00 a 09:30; jue: 08:00 a 09:30; vie: 08:00 a 09:30;">
                                </div>

                                <div class="form-group">
                                    <label for="Observacion">Observación (*)</label><br>
                                    <textarea class="form-control" name="Observacion" rows="5" cols="100%"></textarea>
                                </div>
                                <button type="submit" class="btn btn-info">Crear Registro</button>
                            </form>

                        </div>
                    </section>
                <form>
            </div>
        </div>
        </section>
    </section>
</section>
@endsection

@section('Script')
<script src="{{ asset('js/funcionesvarias.js');}}"></script>
<script>

    //owl carousel

    $(document).ready(function() {
        $("#owl-demo").owlCarousel({
            navigation : true,
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem : true,
            autoPlay:true

        });
    });

    //custom select box

    $(function(){
        $('select.styled').customSelect();
    });

</script>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#example').dataTable( {
            "aaSorting": [[ 0, "desc" ]],
            "oLanguage": {
                "sLengthMenu": "Info _MENU_ por página",
                "search": "Buscar:",
                "oPaginate": {
                    "sPrevious": "Anterior",
                    "sNext": "Siguiente"
                }
            }
        } );
    } );
</script>

@if(session('AltaPlaza')=='OK')
    <script>
        Swal.fire(
                'Registro guardado',
                'Se creo un nuevo registro de plaza',
                'success'
              )
    </script>
@endif
<script>
    $('.formularioAlta').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer crear la Plaza?',
            text: "Este cambio no puede ser borrado luego!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, crear el registro!'
          }).then((result) => {
            if (result.isConfirmed) {
              this.submit();
            }
          })
    })
    
</script>
 
@endsection