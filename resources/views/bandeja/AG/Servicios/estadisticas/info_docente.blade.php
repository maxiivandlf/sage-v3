@extends('layout.app')

@section('Titulo', 'Sage2.0 - Altas')
@section('LinkCSS')
<link rel="stylesheet" href="{{ asset('css/high-charts.css') }}">
@endsection

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">

            
            <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Cantidad de Cargas</h1>
                    </div>
                  </div>
                </div><!-- /.container-fluid -->
              </section>
            
              <div class="row">
                <div class="col-md-3 inline-block">
                    <div class="info-box shadow-lg">
                    <span class="info-box-icon bg-success"><i class="fas fa-chart-bar"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Docentes para Control:</span>
                        @php
                            $agentesTotal = DB::table('tb_agentes')->count();
                        @endphp
                        <span class="info-box-number">Creados: {{$agentesTotal}}</span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-3 inline-block">
                    <div class="info-box shadow-lg">
                    <span class="info-box-icon bg-success"><i class="fas fa-chart-bar"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Docentes Trabajando:</span>
                        @php
                            $agentesUnicos = DB::table('tb_nodos')
                            ->select('Agente')
                            ->distinct() // Elimina duplicados
                            ->pluck('Agente')
                            ->count(); // Obtiene una lista de agentes únicos
                        @endphp
                        <span class="info-box-number">En Actividad: {{$agentesUnicos}}</span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            </div>          
            <div class="row">
                <div class="col-md-6">
                  <div class="card card-default">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i>
                        Cargos Registrados
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <figure class="highcharts-figure">
                            <div id="container-grafico-1"></div>
                            <p class="highcharts-description">
                                Contiene los cargos actuales registrados hasta el dia de la fecha {{$FechaActual}}
                            </p>
                        </figure>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
      
                <div class="col-md-6">
                  <div class="card card-default">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i>
                        Situación de Revistas Registrados por Jornadas
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <figure class="highcharts-figure">
                            <div id="container-grafico-2"></div>
                            <p class="highcharts-description">
                                Contiene las SitRev actuales registrados hasta el dia de la fecha {{$FechaActual}}
                            </p>
                        </figure>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.fin row -->  
            <div class="row">
                <div class="col-md-6">
                  <div class="card card-default">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i>
                       Instituciones por Turnos Agregadas por Zona
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <figure class="highcharts-figure">
                            <div id="container-grafico-3"></div>
                            <p class="highcharts-description">
                                Contiene las instituciones por turnos actuales registrados hasta el dia de la fecha {{$FechaActual}}
                            </p>
                        </figure>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
      
                <div class="col-md-6">
                  <div class="card card-default">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i>
                       Cantidad de Licencias
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <figure class="highcharts-figure">
                            <div id="container-grafico-4"></div>
                            <p class="highcharts-description">
                                Contiene las licencias actuales registrados hasta el dia de la fecha {{$FechaActual}}
                            </p>
                        </figure>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.fin row -->  
        </section>
    </section>
    {{-- fin main content --}}
</section>
{{-- fin container --}}

@endsection

@section('Script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
{{-- grafico 1 --}}
<script>
// Asegúrate de que $niveles contiene los datos correctos en formato JSON
var cargos = {!! $cargos !!};
Highcharts.chart('container-grafico-1', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Cargos Registrados 2024'
    },
    tooltip: {
        valueSuffix: '%'
    },
    plotOptions: {
        series: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
                enabled: true,
                distance: 20
            }, {
                enabled: true,
                distance: -60,
                format: '{point.percentage:.1f}%',
                style: {
                    fontSize: '1.2em',
                    textOutline: 'none',
                    opacity: 0.7
                },
                filter: {
                    operator: '>',
                    property: 'percentage',
                    value: 10
                }
            }]
        }
    },
    series: [
        {
            name: 'Percentage',
            colorByPoint: true,
            data: cargos.map(item => ({
                        name: item.name,
                        y: item.y
                    }))
        }
    ]
});

var SituacionRevistas = {!! $SituacionRevistas !!};
Highcharts.chart('container-grafico-2', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Situación de Revista Registrados 2024'
    },
    tooltip: {
        valueSuffix: '%'
    },
    plotOptions: {
        series: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
                enabled: true,
                distance: 20
            }, {
                enabled: true,
                distance: -40,
                format: '{point.percentage:.1f}%',
                style: {
                    fontSize: '1.2em',
                    textOutline: 'none',
                    opacity: 0.7
                },
                filter: {
                    operator: '>',
                    property: 'percentage',
                    value: 10
                }
            }]
        }
    },
    series: [
        {
            name: 'Percentage',
            colorByPoint: true,
            data: SituacionRevistas.map(item => ({
                        name: item.name,
                        y: item.y
                    }))
        }
    ]
});

var turnos = {!! $turnos !!};
Highcharts.chart('container-grafico-3', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Escuelas por Turnos Registrados 2024'
    },
    tooltip: {
        valueSuffix: '%'
    },
    plotOptions: {
        series: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
                enabled: true,
                distance: 20
            }, {
                enabled: true,
                distance: -40,
                format: '{point.percentage:.1f}%',
                style: {
                    fontSize: '1.2em',
                    textOutline: 'none',
                    opacity: 0.7
                },
                filter: {
                    operator: '>',
                    property: 'percentage',
                    value: 10
                }
            }]
        }
    },
    series: [
        {
            name: 'Percentage',
            colorByPoint: true,
            data: turnos.map(item => ({
                        name: item.name,
                        y: item.y
                    }))
        }
    ]
});

var licencias = {!! $licencias !!};
Highcharts.chart('container-grafico-4', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Licencias Registrados 2024'
    },
    tooltip: {
        valueSuffix: '%'
    },
    plotOptions: {
        series: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
                enabled: true,
                distance: 20
            }, {
                enabled: true,
                distance: -40,
                format: '{point.percentage:.1f}%',
                style: {
                    fontSize: '1.2em',
                    textOutline: 'none',
                    opacity: 0.7
                },
                filter: {
                    operator: '>',
                    property: 'percentage',
                    value: 10
                }
            }]
        }
    },
    series: [
        {
            name: 'Percentage',
            colorByPoint: true,
            data: licencias.map(item => ({
                        name: item.name,
                        y: item.y
                    }))
        }
    ]
});
</script>
@endsection