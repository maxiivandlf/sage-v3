@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Usuario ADMIN')

@section('LinkCSS')
<style>
    .modal-custom-width {
        width: 90%;
        max-width: none;
    }
    td {
        text-align: center;
    }
</style>
@endsection

@section('ContenidoPrincipal')
<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
            <section class="content">
                <!-- Fila 1: Abril -->
                <div class="row">
                    {{-- Debug temporal --}}
                    {{-- <pre>{{ json_encode(compact('totalIPEAbril', 'totalAgentesAbril', 'totalSinIPENullAbril')) }}</pre> --}}

                    <!-- IPE Abril -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Cantidad de IPE <b style="color:green">Abril</b></h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Totales</th>
                                            <th>SI</th>
                                            <th>NO</th>
                                            <th>NO Confirmados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $totalRegistrosAbril }}</td>
                                            <td>{{ $totalIPEAbril }}</td>
                                            <td>{{ $totalAgentesAbril }}</td>
                                            <td>{{ $totalSinIPENullAbril }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="graficoAbril" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- IPE Rel Abril -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">IPE Relacionados <b style="color:green">Abril</b></h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Totales</th>
                                            <th>SI</th>
                                            <th>NO</th>
                                            <th>NO Confirmados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $totalRegistrosRelAbril }}</td>
                                            <td>{{ $totalIPERelAbril }}</td>
                                            <td>{{ $totalAgentesRelAbril }}</td>
                                            <td>{{ $totalSinIPENullRelAbril }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="graficoRelAbril" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fila 2: Mes actual -->
                <div class="row mt-4">
                    <!-- IPE Actual -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Cantidad de IPE <b style="color:green">{{ session('mesActual') }}</b></h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Totales</th>
                                            <th>SI</th>
                                            <th>NO</th>
                                            <th>NO Confirmados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $totalRegistros }}</td>
                                            <td>{{ $totalIPE }}</td>
                                            <td>{{ $totalAgentes }}</td>
                                            <td>{{ $totalSinIPENull }}     
                                                <button class="btn btn-info btn-sm" id="btnDetalleIPE" data-toggle="modal" data-target="#modalIPE">
                                                    {{ $totalSinIPENull }}
                                                </button>
                                            </td>
                                            
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="graficoActual" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- IPE Rel Actual -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">IPE Relacionados <b style="color:green">{{ session('mesActual') }}</b></h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Totales</th>
                                            <th>SI</th>
                                            <th>NO</th>
                                            <th>NO Confirmados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $totalRegistrosRel }}</td>
                                            <td>{{ $totalIPERel }}</td>
                                            <td>{{ $totalAgentesRel }}</td>
                                            <td>{{ $totalSinIPENullRel }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="graficoRelActual" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>
</section>

<!-- Modal -->
<div class="modal fade" id="modalIPE" tabindex="-1" role="dialog" aria-labelledby="modalIPELabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle de registros sin IPE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="contenidoModalIPE">
        <p>Cargando...</p>
      </div>
    </div>
  </div>
</div>


@endsection

@section('Script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#btnDetalleIPE').click(function () {
        $('#contenidoModalIPE').html('<p>Cargando...</p>');

        $.ajax({
            url: '{{ route("reporte.ipe.detalle") }}',
            method: 'GET',
            success: function (response) {
                $('#contenidoModalIPE').html(response);
            },
            error: function () {
                $('#contenidoModalIPE').html('<p>Error al cargar los datos.</p>');
            }
        });
    });
});
</script>

<script>
    function crearGrafico(containerId, titulo, si, no, noConfirmado) {
        Highcharts.chart(containerId, {
            chart: { type: 'pie' },
            title: { text: titulo },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: { point: { valueSuffix: '%' } },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Registros',
                colorByPoint: true,
                data: [
                    { name: 'SI', y: si },
                    { name: 'NO', y: no },
                    { name: 'NO Confirmado', y: noConfirmado }
                ]
            }]
        });
    }

document.addEventListener('DOMContentLoaded', function () {
    crearGrafico('graficoAbril', 'IPE Abril', {{ $totalIPEAbril }}, {{ $totalAgentesAbril }}, {{ $totalSinIPENullAbril }});
    crearGrafico('graficoRelAbril', 'IPE Rel Abril', {{ $totalIPERelAbril }}, {{ $totalAgentesRelAbril }}, {{ $totalSinIPENullRelAbril }});
    crearGrafico('graficoActual', 'IPE {{ session("mesActual") }}', {{ $totalIPE }}, {{ $totalAgentes }}, {{ $totalSinIPENull }});
    crearGrafico('graficoRelActual', 'IPE Rel {{ session("mesActual") }}', {{ $totalIPERel }}, {{ $totalAgentesRel }}, {{ $totalSinIPENullRel }});
});
</script>
<script>
function exportarExcelIPE() {
    // Capturamos la tabla
    var tabla = document.getElementById("tablaIPE");

    // Convertimos a hoja de cálculo
    var wb = XLSX.utils.table_to_book(tabla, { sheet: "RegistrosSinIPE" });

    // Guardamos como archivo Excel
    XLSX.writeFile(wb, "registros_sin_IPE.xlsx");
}
</script>

@endsection
