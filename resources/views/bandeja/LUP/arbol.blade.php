@extends('layout.app')

@section('Titulo', 'Sage2.0 - Conf. Agentes')

@section('ContenidoPrincipal')
<?php
    $conexion = mysqli_connect('localhost','root','djmov669','sage2');
    

?>
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <a href="{{route('verSubOrg',$idOrg)}}" type="button" class="btn btn-info block"><i class="fa fa-mail-reply-all"></i> Volver a Organizaciones</a>
                </section>
                <section class="panel">
                    <header class="panel-heading tab-bg-dark-navy-blue ">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a data-toggle="tab" href="#Arbol">Arbol</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#Cursos">Cursos</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#Divisiones">Divisiones</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#Agentes">Agentes</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#Plazas">Plazas</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#Reportes">Reportes - FALTA</a>
                            </li>
                        </ul>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div id="Arbol" class="tab-pane active">
                                <button id="AgregarBloque" class="label label-inverse"><i class="fa fa-plus-circle" style="color: white; font-size:14px;"><span>Agregar Bloque</span></i></button>
                                
                                <hr>
                                <!-- arbol -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!--timeline start-->
                                        <section class="panel">
                                            <div class="panel-body">
                                                    <div class="text-center mbot30">
                                                        <h3 class="timeline-title">Línea del TIEMPO - CUE: {{$CueOrg}} -  {{$NombreOrg}}</h3>
                                                        <p class="t-info">información de una organización / suborg</p>
                                                    </div>
                        
                                                    <div id="timebase" class="timeline" style="text-align: left; margin-left:-450px;">
                                                        
                                                    @foreach($infoSubOrganizaciones as $key => $so)
                                                    <article class="timeline-item">
                                                        <div class="timeline-desk">
                                                            <div class="panel"  style="width:250px;border:1px solid red;">
                                                                <div class="panel-body" >
                                                                    <span class="arrow"></span>
                                                                    <span class="timeline-icon red"></span>
                                                                    <span class="timeline-date">5to 5ta - TM</span>
                                                                    <h1 class="red">DOCENTE: {{$so->Nombres}} <i class="fa fa-ellipsis-h" data-toggle="modal" href="#myModal"></i></h1>
                                                                    <p>Asignatura: {{$so->DesAsc}}</p>
                                                                    <p>Sit Rev: {{$so->SR}} (horas: {{$so->CargaHoraria}})</p>
                                                                    <i class="fa fa-plus-circle" style="color: green; font-size:14px;"><span>Agregar Licencia</span></i>
                                                                
                                                                    <!-- Modal -->
                                                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                        <h4 class="modal-title">Modal Tittle</h4>
                                                                                    </div>
                                                                                    <div class="modal-body">

                                                                                        Body goes here...

                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                                                                        <button class="btn btn-success" type="button">Save changes</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- modal -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </article>
                                                    @endforeach
                                                       
                                                       
                                                    </div>
                        
                                                    <div class="clearfix">&nbsp;</div>
                                                </div>
                                        </section>
                                        <!--timeline end-->
                                    </div>
                               
                                </div>
                            </div>
                            <div id="Cursos" class="tab-pane">Cursos sadsadassadsadasd</div>
                            <div id="Divisiones" class="tab-pane">Divisiones</div>
                            <div id="Agentes" class="tab-pane">
                               
                            </div>
                            <div id="Plazas" class="tab-pane">
                                <!-- page start-->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <section class="panel">
                                            <header class="panel-heading">
                                                Lista de Plazas en SubOrganización
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
                                                            <th>Asignatura / Funcion</th>
                                                            <th>Agente Actual</th>
                                                            <th>Ocupada</th>
                                                            
                                                            
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($infoPlazas as $key => $plaza)
                                                            <tr class="gradeX">
                                                                <td>{{$plaza->idPlaza}}</td>
                                                                <td>{{$plaza->CUISE}}</td>
                                                                <td>{{$plaza->CUPOF}}</td>
                                                                <td>{{$plaza->FechaAlta}}</td>
                                                                @foreach($CargosSalariales as $key => $value)
                                                                    @if($value->idCargo==$plaza->CargoSalarialDefault)
                                                                        <td>{{$value->Cargo}}</td>
                                                                    @endif
                                                                @endforeach
                                                                @foreach($Asignaturas as $key => $value)
                                                                    @if($value->idAsignatura==$plaza->Asignatura)
                                                                        <td>{{$value->Descripcion}}</td>
                                                                    @endif
                                                                @endforeach
                                                                @foreach($tiposDeFuncion as $key => $value)
                                                                    @if($value->idTipoFuncion==$plaza->TipoDeFuncion)
                                                                        <td>{{$value->Descripcion}}</td>
                                                                    @endif
                                                                @endforeach
                                                                @if($plaza->DuenoActual != "")
                                                                    <td style="background-color: chartreuse">{{$plaza->Nombres}}({{$plaza->Documento}})</td>
                                                                @else
                                                                    <td style="background-color: darksalmon">{{$plaza->Nombres}}({{$plaza->Documento}})</td>
                                                                @endif
                                                                <td>
                                                                    <a href="{{route('verAgentes',$plaza->idPlaza)}}" title="Lista de Agentes">
                                                                        <i class="fa fa-user"></i>
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
                            </div>
                            <div id="Reportes" class="tab-pane">Reportes</div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->

      
        </section>
    </section>
</section>
@endsection

@section('Script')
<script src="{{ asset('js/arbol.js');}}"></script>
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

 
@endsection