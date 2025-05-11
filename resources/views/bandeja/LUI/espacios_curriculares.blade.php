@extends('layout.app')

@section('Titulo', 'Sage2.0 - Espacios Curriculares')

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <a href="{{route('getCarrerasPlanes')}}" type="button" class="btn btn-info block"><i class="fa fa-mail-reply-all"></i> Volver a Carreras Y Planes</a>
            <!-- Inicio Tabla-Card -->
            <div class="card card-lightblue">
              <div class="card-header">
                <h3 class="card-title">Espacios Curriculares</h3>
                <button class="btn btn-success">Solicitar Agregar Espacio Curricular y etc etc</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Curso/Grado/Sala</th>
                        <th>Horas</th>
                        <th>Regimen de Dictado</th>
                        <th>Solicitar Cambio</th>
                    </tr>
                  </thead>
                  <tbody>
                   @foreach($EspaciosCurriculares as $key => $e)
                        <tr class="gradeX">
                            <td>{{$e->idEspacioCurricular}}</td>
                            <td>{{$e->EspacioCurricular}}</td>
                            <td>{{$e->Curso}}</td>
                            <td>{{$e->Horas}}</td>
                            <td>{{$e->RegimenDictado}}</td>
                            <td>
                                <a href="#">
                                    <i class="fa fa-edit"></i>
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
        </section>
    </section>
</section>

@endsection

@section('Script')

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

 
@endsection