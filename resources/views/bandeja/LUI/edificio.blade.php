@extends('layout.app')

@section('Titulo', 'Sage2.0 - Edificio')

@section('ContenidoPrincipal')

<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Información del Edificio
                        <div class="alert alert-success alert-block fade in">
                            <h4>
                                <i class="fa fa-ok-sign"></i>
                                INFORMACIÓN
                            </h4>
                            <p>Aquí mas detalle del edificio</p>
                        </div>
                    </header>
                    <div class="panel-body">
                          <div class="adv-table">
                              <table  class="display table table-bordered table-striped" id="example">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>CUE</th>
                                    <th>Nombre</th>
                                    <th>Domicilio</th>
                                    <th>Localidad</th>
                                    <th>Es Privada</th>
                                    <th>Ver Mas...</th>
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
                                            <a href="{{route('verSubOrg',$unaOrg->idorganizaciones)}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            
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
                <section class="panel">
                    <div class="panel-body">
                        <div class="alert alert-success alert-block fade in">
                            <button data-dismiss="alert" class="close close-sm" type="button">
                                <i class="fa fa-times"></i>
                            </button>
                            <h4>
                                <i class="fa fa-ok-sign"></i>
                                Success!
                            </h4>
                            <p>PROXIMAMENTE MAS INFO DE LAS ESCUELAS AQUÍ</p>
                        </div>
    
                    </div>
                </section>
        </div>
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