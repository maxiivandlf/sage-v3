@extends('layout.app')

@section('Titulo', 'Sage2.0 - Movimientos')

@section('ContenidoPrincipal')

<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Inicio Tabla-Card -->
            <div class="card card-lightblue">
              <div class="card-header">
                <h3 class="card-title">ORGANIZACIONES</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>ID</th>
                        <th>CUE</th>
                        <th>Nombre</th>
                        <th>Domicilio</th>
                        <th>Localidad</th>
                        <th>Es Privada</th>
                        <th>Info Anexo</th>
                    </tr>
                  </thead>
                  <tbody>
                   @foreach($Organizaciones as $key => $unaOrg)
                        <tr class="gradeX">
                            <td>{{$unaOrg->idorganizaciones}}</td>
                            <td>{{$unaOrg->cue}}</td>
                            <td>{{$unaOrg->nombre}}</td>
                            <td>{{$unaOrg->domicilio}}</td>
                            <td>{{$unaOrg->localidad}}</td>
                            <td>{{$unaOrg->esprivada}}</td>
                            <td>
                                <a href="{{route('verSubOrg')}}">
                                    <i class="fa fa-eye"></i>
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