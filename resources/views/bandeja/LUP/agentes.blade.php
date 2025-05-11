@extends('layout.app')

@section('Titulo', 'Sage2.0 - Agentes')

@section('ContenidoPrincipal')

<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <a href="{{route('verArbol',$idSubOrg)}}" type="button" class="btn btn-info block"><i class="fa fa-mail-reply-all"></i> Volver a Organizaciones</a>

        <!-- page start-->
        <div class="row">
            <div class="col-lg-8">
                <section class="panel">
                    <header class="panel-heading">
                        Buscar Agente
                    </header>
                    <div class="panel-body">
                            <div class="form-group form-inline">
                                <label class="sr-only" for="buscarAgente">DNI del Agente</label>
                                <input type="text" class="form-control" id="buscarAgente" placeholder="Ingrese DNI sin Puntos">
                                <button type="button" class="btn btn-success" onclick="getAgenteDNI()"><i class="fa fa-search"></i></button>

                            </div>                           
                    </div>
                </section>

            </div>
        </div>
        <!-- page end-->
        <div class="row">
        
        </div>
        <div class="row">
            <div class="col-lg-8">

                <form method="POST" action="{{ route('AltaPlaza') }}" class="formularioAlta">
                    @csrf
                    <section class="panel">
                        <header class="panel-heading">
                            Alta de Agente en Plaza 
                            <div class="alert alert-success alert-block fade in">
                            
                                <h4>
                                    <i class="fa fa-info-circle"></i>
                                    Detalles de la plaza:<br>
                                    Plaza: {{$infoPlaza[0]->idPlaza}}<br>
                                    SubOrganizacion: {{$infoPlaza[0]->SubOrganizacion}}<br>


                                </h4>
                                <p>Breve información de la plaza seleccionada</p>
                            </div>
                        </header>
                        <div class="panel-body" id="divinfoagente">
                        </div>
                        <div class="panel-body">
                            <form role="form">
                                <div class="form-group">
                                    <label for="FA">Fecha de Alta (*)</label>
                                    <input type="date" class="form-control" id="FechaAlta" name="FechaAlta" placeholder="Ingrese Fecha">
                                </div>
               
                                <div class="form-group">
                                    <label for="turnos">Causa Alta (*)</label>
                                    <select class="form-control" name="CausaAlta" id="CausaAlta">
                                    @foreach($CausaAltas as $key => $o)
                                        <option value="{{$o->idCausaAlta}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="turnos">Situación de Revista (*)</label>
                                    <select class="form-control" name="SituacionDeRevista" id="SituacionDeRevista">
                                    @foreach($SituacionDeRevista as $key => $o)
                                        <option value="{{$o->idSituacionRevista}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="Horas">Horas (*)</label>
                                    <input type="text" class="form-control" id="Horas" name="Horas" placeholder="Ingrese cantidad de Horas" value="0">
                                </div>

                                <div class="form-group">
                                    <label for="FA">Incluye Baja?</label>
                                    <input type="checkbox" class="form-control" id="checkBaja" name="checkBaja">
                                </div>

                                <div class="form-group">
                                    <label for="FA">Fecha de Baja (*)</label>
                                    <input type="date" class="form-control" id="FechaBaja" name="FechaBaja" placeholder="Ingrese Fecha" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="turnos">Causa Alta (*)</label>
                                    <select class="form-control" name="CausaBaja" id="CausaBaja">
                                    @foreach($CausaBajas as $key => $o)
                                        <option value="{{$o->idCausaBaja}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="NC">Norma de Creación (*)</label>
                                    <input type="text" class="form-control" id="NormaDeCreacion" name="NormaDeCreacion" placeholder="Ingrese Norma de Creación">
                                </div>

                                <div class="form-group">
                                    <label for="turnos">Regimen Laboral (*)</label>
                                    <select class="form-control" name="RegimenLaboral" id="RegimenLaboral">
                                        @foreach($CausaBajas as $key => $o)
                                        <option value="{{$o->idCausaBaja}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="turnos">Tipo de Función (*)</label>
                                    <select class="form-control" name="TipoDeFuncion" id="TipoDeFuncion">
                                        @foreach($CausaBajas as $key => $o)
                                        <option value="{{$o->idCausaBaja}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="turnos">Regimen Salarial (*)</label>
                                    <select class="form-control" name="RegimenSalarial" id="RegimenSalarial">
                                        @foreach($CausaBajas as $key => $o)
                                        <option value="{{$o->idCausaBaja}}">{{$o->Descripcion}}</option>
                                    @endforeach
                                    </select>
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