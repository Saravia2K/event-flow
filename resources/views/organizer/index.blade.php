@extends('templates.organizer-base')

@section('page_title', 'Organizador')

@section('styles')
    <style>
        .card-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .card {
            border-radius: 0.5rem;
        }
    </style>
@endsection

@section('dashboard-content')
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Totales de eventos creados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $events->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total de participantes procesados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_confirmed_requests }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Reportes generados</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $total_reports_generated }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-file-pdf fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total de comentarios en
                                todos tus eventos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_comments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if ($events->isEmpty())
            <div class="alert alert-info">No tienes eventos registrados.</div>
        @else
            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h2 class="card-title mb-0">Tendencia de Participación</h2>
                                    <div class="btn-group" id="timeFilter">
                                        <button class="btn btn-sm btn-outline-primary active"
                                            data-groupby="day">Diario</button>
                                        <button class="btn btn-sm btn-outline-primary" data-groupby="month">Mensual</button>
                                        <button class="btn btn-sm btn-outline-primary"
                                            data-groupby="quarter">Trimestral</button>
                                    </div>
                                </div>

                                <div class="chart-container" style="height: 400px;">
                                    <canvas id="participationTrendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Gráfico de Barras -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Participantes por Evento</h5>
                                <div class="chart-container" style="height: 250px; position: relative;">
                                    <canvas id="participantsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico de Pastel -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Distribución de Estados</h5>
                                <div class="chart-container" style="height: 250px; position: relative;">
                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h2 class="card-title mb-4">Participación de Usuarios en Eventos</h2>
                                <div class="chart-container" style="height: 300px; position: relative;">
                                    <canvas id="userParticipationChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.0.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1.2.0"></script>
    <script>
        // Configuración común para ambos gráficos
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        };

        // Gráfico de Barras
        new Chart(document.getElementById('participantsChart'), {
            type: 'bar',
            data: {
                labels: @json($events->pluck('title')),
                datasets: [{
                    label: 'Participantes Confirmados',
                    data: @json($events->pluck('participants_count')),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Gráfico de Pastel
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: @json($eventStatusStats->keys()->map(fn($status) => ucfirst($status))),
                datasets: [{
                    data: @json($eventStatusStats->values()),
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)', // Active
                        'rgba(255, 99, 132, 0.7)', // Inactive
                        'rgba(153, 102, 255, 0.7)' // Finished
                    ]
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.raw / total) * 100);
                                return `${context.label}: ${context.raw} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        const ctx = document.getElementById('userParticipationChart').getContext('2d');
        const users = @json($usersWithEvents);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: users.map(user => user.name), // Asume que el modelo User tiene 'name'
                datasets: [{
                    label: 'Eventos Confirmados',
                    data: users.map(user => user.participations_count),
                    backgroundColor: 'rgba(153, 102, 255, 0.7)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Opcional: oculta la leyenda si solo hay un dataset
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} eventos`;
                            }
                        }
                    }
                }
            }
        });

        const rawData = @json($participationData);
        let chart;

        // Inicializar gráfico
        function initChart(filterType = 'day') {
            const groupedData = groupData(rawData, filterType);

            const ctx = document.getElementById('participationTrendChart').getContext('2d');

            if (chart) chart.destroy(); // Destruir gráfico existente

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Participaciones Confirmadas',
                        data: groupedData,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: getChartOptions(filterType)
            });
        }

        // Agrupar datos según el filtro
        function groupData(data, groupBy) {
            const groups = {};

            data.forEach(item => {
                const date = new Date(item.created_at);
                let key;

                switch (groupBy) {
                    case 'day':
                        key = date.toISOString().split('T')[0]; // YYYY-MM-DD
                        break;
                    case 'month':
                        key = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`; // YYYY-MM
                        break;
                    case 'quarter':
                        const quarter = Math.floor(date.getMonth() / 3) + 1;
                        key = `${date.getFullYear()} Q${quarter}`; // YYYY Q1
                        break;
                }

                groups[key] = (groups[key] || 0) + 1;
            });

            return Object.entries(groups).map(([period, count]) => ({
                x: groupBy === 'quarter' ? period : period + (groupBy === 'month' ? '-01' : ''),
                y: count
            }));
        }

        // Configuración dinámica del gráfico
        function getChartOptions(groupBy) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: groupBy === 'quarter' ? 'category' : 'time',
                        time: {
                            unit: groupBy === 'month' ? 'month' : 'day',
                            displayFormats: {
                                day: 'dd MMM',
                                month: 'MMM yyyy'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: (context) => {
                                if (groupBy === 'quarter') return `Trimestre: ${context[0].label}`;
                                return new Date(context[0].parsed.x).toLocaleDateString();
                            },
                            label: (context) => `${context.parsed.y} participaciones`
                        }
                    }
                }
            };
        }

        // Manejar clics en los botones de filtro
        document.getElementById('timeFilter').addEventListener('click', (e) => {
            if (e.target.tagName === 'BUTTON') {
                document.querySelectorAll('#timeFilter button').forEach(btn => {
                    btn.classList.remove('active');
                });
                e.target.classList.add('active');
                initChart(e.target.dataset.groupby);
            }
        });

        // Inicializar con filtro diario por defecto
        initChart();
    </script>
@endsection
