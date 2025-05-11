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
                        <span class="info-box-text">Escuelas Totales En Planeamiento:</span>
                        @php
                            $total = DB::table('padron')->count();
                        @endphp
                        <span class="info-box-number">Registradas: {{$total}}</span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-3 inline-block">
                    <div class="info-box shadow-lg">
                    <span class="info-box-icon bg-success"><i class="fas fa-chart-bar"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Instituciones Agregadas:</span>
                        @php
                            $totalCUE = DB::table('tb_institucion_extension')
                            ->select('CUECOMPLETO')
                            ->distinct() // Elimina duplicados
                            ->pluck('CUECOMPLETO')
                            ->count(); // Obtiene una lista de agentes únicos
                        @endphp
                        <span class="info-box-number">En Actividad: {{$totalCUE}}</span>
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
                        Instituciones Agregadas por Niveles
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <figure class="highcharts-figure">
                            <div id="container-grafico-1"></div>
                            <p class="highcharts-description">
                                Contiene los niveles actuales registrados hasta el dia de la fecha {{$FechaActual}}
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
                        Instituciones Agregadas por Jornadas
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <figure class="highcharts-figure">
                            <div id="container-grafico-2"></div>
                            <p class="highcharts-description">
                                Contiene las Jornadas actuales registrados hasta el dia de la fecha {{$FechaActual}}
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
                        Instituciones Agregadas por Zona
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <figure class="highcharts-figure">
                            <div id="container-grafico-3"></div>
                            <p class="highcharts-description">
                                Contiene las zonas departamentales actuales registrados hasta el dia de la fecha {{$FechaActual}}
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
                        Instituciones Agregadas por Ambitos
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <figure class="highcharts-figure">
                            <div id="container-grafico-4"></div>
                            <p class="highcharts-description">
                                Contiene las ambitos actuales registrados hasta el dia de la fecha {{$FechaActual}}
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
var niveles = {!! $niveles !!};
Highcharts.chart('container-grafico-1', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Niveles Registrados 2024'
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
            data: niveles.map(item => ({
                        name: item.name,
                        y: item.y
                    }))
        }
    ]
});

var jornadas = {!! $jornadas !!};
Highcharts.chart('container-grafico-2', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Jornadas Registrados 2024'
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
            data: jornadas.map(item => ({
                        name: item.name,
                        y: item.y
                    }))
        }
    ]
});

var zonas = {!! $zonas !!};
Highcharts.chart('container-grafico-3', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Escuelas por Zona Registrados 2024'
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
            data: zonas.map(item => ({
                        name: item.name,
                        y: item.y
                    }))
        }
    ]
});

var ambitos = {!! $ambitos !!};
Highcharts.chart('container-grafico-4', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Escuelas por Ambitos Registrados 2024'
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
            data: ambitos.map(item => ({
                        name: item.name,
                        y: item.y
                    }))
        }
    ]
});
</script>
@endsection