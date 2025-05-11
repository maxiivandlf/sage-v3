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
                BUSCADOR PROVISORIO TECNICOS</h3>
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
                    <form action="{{ route('buscar_dni_ajax') }}"  class="buscar_dni_cue_tec" id="buscar_dni_cue_tec" method="POST" >
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
                <!-- Información Personal -->
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">INFORMACION LIQSEP</h3>
                    </div>
                    <div class="card-body">
                        <!-- Primera tabla: Documento, Cuil, ApeNom, Escuela, Descuento_Escuela -->
                        <table id="tabla-personal" class="table table-bordered table-striped"">
                            <thead>
                                <tr>
                                    <th>ID LIQ</th>
                                    <th>Documento</th>
                                    <th>Cuil</th>
                                    <th>ApeNom</th>
                                    <th>Nivel</th>
                                    <th>Escuela</th>
                                    <th>Descuento Escuela</th>
                                    <th>Codigo</th>
                                    <th>Cargo</th>
                                    <th>Horas</th>
                                    <th>Antiguedad</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenarán los datos -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      <!-- Información POF -->
      <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">INFORMACION AGENTE POF</h3>
                </div>
                <div class="card-body">
                    <!-- Segunda tabla: Agente Información -->
                    <table id="tabla-agente" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID POF</th>
                                <th>Documento</th>
                                <th>Cuil</th>
                                <th>ApeNom</th>
                                <th>Nivel</th>
                                <th>Escuela</th>
                                <th>Institución</th>
                                <th>Turno</th>
                                <th>CUECMPLETO</th>
                                <th>Código</th>
                                <th>Cargo</th>
                                <th>Horas</th>
                                <th>Antigüedad</th>
                                <th>Esp.Cur.</th>
                                <th>Condición</th>
                                <th>Activo</th>
                                <th>Obs.Condición</th>
                                <th>Motivo</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se llenarán los datos -->
                        </tbody>
                    </table>
                </div>
            </div>
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