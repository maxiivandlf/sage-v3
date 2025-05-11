@extends('layout.app')

@section('Titulo', 'Sage2.0 - Altas')
@section('LinkCSS')
  <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
  <style>
    #tabla-completa {
    font-size: 1rem;
    }

    #tabla-completa td, 
    #tabla-completa th {
        font-size: inherit;
    }
    #tabla-completa {
        table-layout: auto;
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
            <div class="row">
                <div class="card card-info  col-lg-12">
                    <div class="card-header">
                      <h3 class="card-title">Busqueda por CUE</h3>
                    </div>
                    <form action="{{ route('buscar_cue_ajax_liq') }}"  class="buscar_cue" id="buscar_cue" method="POST" >
                    @csrf
                   
                    <div class="card-body  col-lg-12">
                      <div class="row  col-lg-12">
                        <div class="col-2">
                            <select class="form-control" name="escu" id="escu">
                                <option value="">Seleccione escu</option>
                                @foreach ($instarealiq_escu as $e)
                                    <option value="{{ $e->escu }}">{{ $e->escu }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <select class="form-control" name="area" id="area">
                                <option value="">Seleccione area</option>
                                
                            </select>
                        </div>
                          <div class="col-2">
                            <select class="form-control" name="CUE" id="CUE">
                                <option value="">Seleccione CUE+Institución</option>
                                {{-- aqui debe completar con el cue como val --}}
                            </select>
                          </div>
                          <div class="col-6">
                            <input type="submit" class="form-control btn-success" value="Consultar CUE" name="btnCUE">
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
        <div id="botones-acciones" class="card card-secondary" style="margin-top: 10px;">
            <!-- Aquí se inyectarán los botones dinámicamente -->
        </div>
    </div>
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">INFORMACIÓN COMPLETA</h3>
        </div>
        <div class="card-body" style="overflow-x: auto;">
            <table id="tabla-completa" class="table table-bordered table-striped" style="font-size: 1rem">
                <thead>
                    <tr>
                        <!-- Información Personal -->
                        <th>APELLIDO Y NOMBRE</th>
                        <th>DNI</th>
                        <th>CUIL</th>
                        <th>SEXO</th>
    
                        <!-- Información POF -->
                        <th>AGENTE</th>
                        <th>SITUACIÓN REVISTA</th>
                        <th>ANTIGÜEDAD</th>
                        <th>HORA</th>
                        <th>CARGO SALARIAL</th>
                        <th>CÓDIGO SALARIAL</th>
                        <th>POSESIÓN CARGO</th>
                        <th>DESIGNADO CARGO</th>
    
                        <!-- Información Institucional -->
                        <th>CUE</th>
                        <th>CÓDIGO LIQ</th>
                        <th>AREA LIQ</th>
                        <th>INSTITUCIÓN LIQ</th>
                        <th>NIVEL</th>
                        <th>ZONA</th>
                        <th>DOMICILIO</th>
                        <th>LOCALIDAD</th>
    
                        <!-- Información Aúlica -->
                        <th>INSTITUCIÓN AÚLICA</th>
                        <th>AULA</th>
                        <th>DIVISION</th>
                        <th>TURNO</th>
                        <th>ESPACIO CURRICULAR</th>
                        <th>MATRICULA</th>
                        <th>CONDICIÓN</th>
                        <th>EN FUNCIÓN?</th>
                        <th>COND. OBSERVACIÓN</th>
                        <th>ASIST. TOTAL</th>
                        <th>ASIST. JUST.</th>
                        <th>ASIST. INJUST.</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Se llenará dinámicamente -->
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
<script>
   $(document).ready(function() {
    // Cuando selecciona ESCU
    $('#escu').on('change', function() {
        var escuSeleccionado = $(this).val();
        var token = $('input[name="_token"]').val(); // Si necesitás CSRF

        // Limpiar los combos dependientes
        $('#area').html('<option value="">Seleccione área</option>');
        $('#CUE').html('<option value="">Seleccione CUE+Institución</option>');

        if (escuSeleccionado !== '') {
            $.ajax({
                url: '{{ route('buscar.areas') }}',
                type: 'POST',
                data: {
                    _token: token,
                    escu: escuSeleccionado
                },
                success: function(areas) {
                    $.each(areas, function(index, area) {
                        $('#area').append('<option value="' + area.area + '">' + area.area + '</option>');
                    });
                },
                error: function(xhr) {
                    alert('Error al cargar las áreas.');
                }
            });
        }
    });

    // Cuando selecciona AREA
    $('#area').on('change', function() {
        var escuSeleccionado = $('#escu').val();
        var areaSeleccionada = $(this).val();
        var token = $('input[name="_token"]').val();

        $('#CUE').html('<option value="">Seleccione CUE+Institución</option>');

        if (escuSeleccionado !== '' && areaSeleccionada !== '') {
            $.ajax({
                url: '{{ route('buscar.cues') }}',
                type: 'POST',
                data: {
                    _token: token,
                    escu: escuSeleccionado,
                    area: areaSeleccionada
                },
                success: function(cues) {
                    $.each(cues, function(index, cue) {
                        $('#CUE').append('<option value="' + cue.CUEA + '">' + cue.CUEA + ' - ' + cue.nombreInstitucion + '</option>');
                    });
                },
                error: function(xhr) {
                    alert('Error al cargar los CUE.');
                }
            });
        }
    });
});

</script>
<script src="{{ asset('js/searchliq.js') }}"></script>

@endsection