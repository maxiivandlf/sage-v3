@extends('layout.liquidacion')

@section('Titulo', 'Liquidación - Principal')

@section('ContenidoPrincipal')

    <section>
        <div class="d-flex flex-row justify-content-center align-items-center mt-4">
            <h1 class="text-center mx-3">Sistema de Liquidación</h1>
        </div>
        <section id="main-content">
            <div class="container mt-4">
                <div class="row">
                    <!-- Enlaces rápidos -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h3 class="mb-3"><i class="fas fa-link"></i> Enlaces Rápidos</h3>
                                <ul class="list-unstyled d-flex flex-column" style="gap: 10px;">
                                    <li><a href="{{ route('buscar_dni_liq') }}" class="btn btn-primary btn-sm w-100">Buscar
                                            por DNI</a></li>
                                    <li><a href="{{ route('buscar_cue_liq') }}" class="btn btn-primary btn-sm w-100">Buscar
                                            por CUE</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Estadísticas clave -->
                    <div class="col-md-6 col-lg-8 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h3 class="mb-3"><i class="fas fa-chart-bar"></i> Estadísticas</h3>
                                <div class="row text-center">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="stat-card p-3 rounded bg-light">
                                            <h4>Total de Agentes</h4>
                                            <p class="display-6 text-primary" style="font-size: 2rem">{{ $totalAgentes }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="stat-card p-3 rounded bg-light">
                                            <h4>Instituciones Activas</h4>
                                            <p class="display-6 text-success" style="font-size: 2rem">567</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-card p-3 rounded bg-light">
                                            <h4>Zonas Registradas</h4>
                                            <p class="display-6 text-warning" style="font-size: 2rem">89</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Gráficos  -->
                <div class="row mt-4 g-4">
                    <div class="col-md-6">
                        <div class="card p-3 shadow-sm h-100">
                            <h5 class="mb-3 text-center"><i class="fas fa-chart-pie"></i> Cobro de IPE Mes</h5>
                            <canvas id="agentsPieChart" style="min-height: 300px"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card p-3 shadow-sm h-100">
                            <h5 class="mb-3 text-center"><i class="fas fa-chart-bar"></i> Cantidad de escuelas por zona</h5>
                            <canvas id="ipeBarChart" style="min-height: 300px"></canvas>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="card p-3 shadow-sm h-100">
                            <h5 class="mb-3 text-center"><i class="fas fa-chart-line"></i> Comparativa de Liquidaciones por
                                Mes</h5>
                            <canvas id="monthlyLineChart" style="min-height: 300px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <style>
        .stat-card {
            transition: box-shadow 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 0 10px #7F55B1;
        }

        .card {
            border-radius: 1rem;
        }
    </style>
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
                    backgroundColor: ['#7F55B1', '#36A2EB', '#FFCE56']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
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
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.raw} escuelas`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 0
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
                    borderColor: '#7F55B1',
                    backgroundColor: 'rgba(127,85,177,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#7F55B1',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Liquidaciones: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
