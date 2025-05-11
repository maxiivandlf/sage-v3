@extends('layout.app')

@section('Titulo', 'Sage2.0 - Altas')
@section('LinkCSS')
  <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
  <style>
    td{
      text-align: center;
    }
  </style>
@endsection
@section('ContenidoPrincipal')
{{-- <div class="loader">
    <h2>Por favor, espere...</h2>
    <div id="clock"></div>
  </div> --}}
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper" style="height: 300px !important">
            <div class="alert alert-warning alert-dismissible">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Importante!</h5>
                Trabajando para recuperar usarios en CUE: <h3 id="valCUE">{{1}}</h3>
            </div>
            @php
            $dni="";
                if(session('AgenteDuplicadoBuscado')){
                    $dni=session('AgenteDuplicadoBuscado');
                }
            @endphp
            <div class="row">
                <div class="card card-info  col-lg-12">
                    <div class="card-header">
                      <h3 class="card-title">Busqueda por DNI</h3>
                    </div>
                    <form action="{{ route('buscar_dni_ajax') }}"  class="buscar_dni_cue" id="buscar_dni_cue" method="POST" >
                    @csrf
                    <div class="card-body  col-lg-12">
                      <div class="row  col-lg-12">
                        
                          <div class="col-6">
                            <input type="text" class="form-control" placeholder="DNI del agente o parte del nombre" name="dni" value="{{$dni}}">
                          </div>
                          <div class="col-6">
                            <input type="submit" class="form-control btn-success" value="Consultar DNI" name="btnDNI">
                          </div>
                        
                        
                      </div>
                    </div>
                    </form>
                    <!-- /.card-body -->
                </div>

            </div>
            <!-- Tablas Detalladas -->
<div class="row">
  <div class="col-md-12">
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">INFORMACIÓN COMPLETA DEL AGENTE</h3>
        </div>
        <div class="card-body" style="overflow-x: auto;">
            <table id="tabla-completa" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <!-- Informacion Personal -->
                        <th>APELLIDO Y NOMBRE</th>
                        <th>DNI</th>
                        <th>CUIL</th>
                        <th>SEXO</th>
    
                        <!-- Informacion POF -->
                        <th>SITUACIÓN REVISTA</th>
                        <th>ANTIGÜEDAD</th>
                        <th>HORA</th>
                        <th>CARGO SALARIAL</th>
                        <th>CÓDIGO SALARIAL</th>
                        <th>POSESIÓN CARGO</th>
                        <th>DESIGNADO CARGO</th>
                        <th>ACCIÓN</th>
    
                        <!-- Informacion Institucional -->
                        <th>CUEA LIQ</th>
                        <th>CÓDIGO LIQ</th>
                        <th>AREA LIQ</th>
                        <th>INSTITUCIÓN LIQ</th>
                        <th>NIVEL LIQ</th>
                        <th>ZONA LIQ</th>
                        <th>DOMICILIO</th>
                        <th>LOCALIDAD</th>
    
                        <!-- Informacion Aúlica -->
                        <th>INSTITUCIÓN AULICA</th>
                        <th>AULA</th>
                        <th>DIVISIÓN</th>
                        <th>TURNO</th>
                        <th>ESPACIO CURRICULAR</th>
                        <th>MATRÍCULA</th>
                        <th>CONDICIÓN</th>
                        <th>EN FUNCIÓN?</th>
                        <th>COND. OBSERVACIÓN</th>
                        <th>ASIST. TOTAL</th>
                        <th>ASIST. JUSTIFICADA</th>
                        <th>ASIST. INJUSTIFICADA</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Datos llenados por AJAX -->
                </tbody>
            </table>
        </div>
    </div>
  </div>
</div>
        </section>
        <section class="content-wrapper">



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

<script src="{{ asset('js/searchliq.js') }}"></script>

@endsection