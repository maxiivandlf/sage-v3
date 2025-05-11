@extends('layout.app')

@section('Titulo', 'Sage2.0 - Reestructura')

@section('ContenidoPrincipal')

<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Reestructuras
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
            <div class="col-lg-6">
                <section class="panel">
                    <header class="panel-heading">
                        Basic Forms
                    </header>
                    <div class="panel-body">
                        <form role="form">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <input type="file" id="exampleInputFile">
                                <p class="help-block">Example block-level help text here.</p>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Check me out
                                </label>
                            </div>
                            <button type="submit" class="btn btn-info">Submit</button>
                        </form>

                    </div>
                </section>
            </div>
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
                    "sLengthMenu": "Información _MENU_ por página",
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