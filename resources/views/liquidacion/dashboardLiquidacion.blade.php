@extends('layout.liquidacion')

@section('Titulo', 'Liquidación - Principal')

@section('ContenidoPrincipal')

    <section>
        <div class="d-flex flex-row justify-content-center align-items-center mt-4">
            <h1 class="text-center mx-3">Sistema de Liquidación</h1>
            {{-- <img class="animation__shake" src="{{ asset('img/logo_gob_lr.png') }}" alt="SAGE2.0" height="60" width="60"> --}}
        </div>
        <section id="main-content">
            <div class="container mt-4">
                <div class="row">
                    <!-- Enlaces rápidos -->
                    <div class="col-md-6">
                        <h3><i class="fas fa-link"></i> Enlaces Rápidos</h3>
                        <ul class="list-unstyled row" style="gap: 10px">
                            <li><a href="{{ route('buscar_dni_liq') }}" class="btn btn-primary btn-sm my-2">Buscar por
                                    DNI</a></li>
                            <li><a href="{{ route('buscar_cue_liq') }}" class="btn btn-primary btn-sm my-2">Buscar por
                                    CUE</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Estadísticas clave -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h3>Estadísticas Clave</h3>
                        <div class="d-flex justify-content-around">
                            <div class="card text-center p-3 shadow-sm">
                                <h4>Total de Agentes</h4>
                                <p class="display-6 text-primary" style="font-size: 1.5rem">{{ $totalAgentes }}</p>
                            </div>
                            <div class="card text-center p-3 shadow-sm">
                                <h4>Instituciones Activas</h4>
                                <p class="display-6 text-success" style="font-size: 1.5rem">567</p>
                            </div>
                            <div class="card text-center p-3 shadow-sm">
                                <h4>Zonas Registradas</h4>
                                <p class="text-warning" style="font-size: 1.5rem">89</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Gráficos -->
                <div class="row mt-5">
                    <div class="col-md-6">
                        <h3><i class="fas fa-chart-pie"></i> Cobro de IPE Mes</h3>
                        <div class="card p-3 shadow-sm">
                            <canvas id="agentsPieChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3><i class="fas fa-chart-bar"></i> Cantidad de escuelas por zona</h3>
                        <div class="card p-3 shadow-sm">
                            <canvas id="ipeBarChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <h3><i class="fas fa-chart-line"></i> Comparativa de Liquidaciones por Mes</h3>
                        <div class="card p-3 shadow-sm">
                            <canvas id="monthlyLineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection

@section('Script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Gráfico de torta: Distribución de Agentes por Tipo
        const agentesConIpe = @json($totalAgentesCobroIPE);
        const agentesSinIpe = @json($totalAgentesSinCobroIPE);
        const otrosAgentes = @json($totalAgentesSinIPE);
        const agentsPieCtx = document.getElementById('agentsPieChart').getContext('2d');
        new Chart(agentsPieCtx, {
            type: 'pie',
            data: {
                labels: ['Agentes con IPE', 'Agentes sin IPE', 'Sin confirmar'],
                datasets: [{
                    data: [agentesConIpe, agentesSinIpe, otrosAgentes],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} agentes`;
                            }
                        }
                    }
                }
            }
        });

        // Cantidad de instituciones por zona
        const totalEscuelasPorZona = @json($totalEscuelasPorZonas);
        const zonas = totalEscuelasPorZona.map(item => item.nombre_loc_zona);
        const cantidadEscuelas = totalEscuelasPorZona.map(item => item.total_escuelas);

        const ipeBarCtx = document.getElementById('ipeBarChart').getContext('2d');
        new Chart(ipeBarCtx, {
            type: 'bar',
            data: {
                labels: zonas,
                datasets: [{
                    label: 'Cantidad de Escuelas',
                    data: cantidadEscuelas,
                    backgroundColor: '#36A2EB'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.raw} escuelas`;
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de líneas: Comparativa de Liquidaciones por Mes
        const monthlyLineCtx = document.getElementById('monthlyLineChart').getContext('2d');
        new Chart(monthlyLineCtx, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril'],
                datasets: [{
                    label: 'Liquidaciones',
                    data: [1000, 1200, 1100, 1300],
                    borderColor: '#FF6384',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Liquidaciones: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
