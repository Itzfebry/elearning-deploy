@extends('layouts.app')
@section('title', "Dashboard")
@section('titleHeader', "Dashboard")

@section('content')
<div class="grid gap-6 grid-cols-1 md:grid-cols-3 mb-6">
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>
                        Materi
                    </h3>
                    <h1>
                        {{ $dashboard['materi'] }}
                    </h1>
                </div>
                <span class="icon widget-icon text-indigo-500"><i class="mdi mdi-file-document mdi-48px"></i></span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>
                        Tugas
                    </h3>
                    <h1>
                        {{ $dashboard['tugas'] }}
                    </h1>
                </div>
                <span class="icon widget-icon text-teal-500"><i class="mdi mdi-clipboard-text mdi-48px"></i></span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>
                        Quiz
                    </h3>
                    <h1>
                        {{ $dashboard['quiz'] }}
                    </h1>
                </div>
                <span class="icon widget-icon text-pink-500"><i class="mdi mdi-help-circle mdi-48px"></i></span>
            </div>
        </div>
    </div>
</div>

<div class="grid gap-6 grid-cols-1 md:grid-cols-2 mb-6">
    <!-- Additional statistics cards -->
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>
                        Kelas
                    </h3>
                    <h1>
                        {{ $dashboard['kelas'] }}
                    </h1>
                </div>
                <span class="icon widget-icon text-purple-500"><i class="mdi mdi-home-variant mdi-48px"></i></span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>
                        Mata Pelajaran
                    </h3>
                    <h1>
                        {{ $dashboard['mata_pelajaran'] }}
                    </h1>
                </div>
                <span class="icon widget-icon text-yellow-500"><i class="mdi mdi-book-open-variant mdi-48px"></i></span>
            </div>
        </div>
    </div>
</div>

<!-- Chart for monthly activity -->
<div class="card mb-6">
    <header class="card-header">
        <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-finance"></i></span>
            Aktivitas Bulanan
        </p>
        <a href="#" class="card-header-icon">
            <span class="icon"><i class="mdi mdi-reload"></i></span>
        </a>
    </header>
    <div class="card-content">
        <div class="chart-area">
            <div class="h-full">
                <div class="chartjs-size-monitor">
                    <div class="chartjs-size-monitor-expand">
                        <div></div>
                    </div>
                    <div class="chartjs-size-monitor-shrink">
                        <div></div>
                    </div>
                </div>
                <canvas id="big-line-chart" width="2992" height="1000" class="chartjs-render-monitor block"
                    style="height: 400px; width: 1197px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Content Distribution Chart -->
<div class="card mb-6">
    <header class="card-header">
        <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-file-document"></i></span>
            Distribusi Konten
        </p>
    </header>
    <div class="card-content">
        <div class="chart-area">
            <div class="h-full">
                <canvas id="content-bar-chart" width="400" height="400" class="chartjs-render-monitor block"
                    style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('extraScript')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Chart colors
    const chartColors = {
        primary: '#00D1B2',
        info: '#209CEE',
        danger: '#FF3860',
        success: '#48C78E',
        warning: '#FFDD57',
        purple: '#9C27B0',
        pink: '#E91E63',
        indigo: '#3F51B5',
        teal: '#009688'
    };

    // Monthly Activity Line Chart
    const monthlyChartCtx = document.getElementById('big-line-chart').getContext('2d');
    new Chart(monthlyChartCtx, {
        type: 'line',
        data: {
            labels: @json($dashboard['monthly_activity']['months']),
            datasets: [{
                label: 'Materi',
                fill: false,
                borderColor: chartColors.primary,
                backgroundColor: chartColors.primary,
                borderWidth: 2,
                pointBackgroundColor: chartColors.primary,
                pointBorderColor: 'rgba(255,255,255,0)',
                pointHoverBackgroundColor: chartColors.primary,
                pointBorderWidth: 20,
                pointHoverRadius: 4,
                pointHoverBorderWidth: 15,
                pointRadius: 4,
                data: @json($dashboard['monthly_activity']['materi'])
            }, {
                label: 'Tugas',
                fill: false,
                borderColor: chartColors.info,
                backgroundColor: chartColors.info,
                borderWidth: 2,
                pointBackgroundColor: chartColors.info,
                pointBorderColor: 'rgba(255,255,255,0)',
                pointHoverBackgroundColor: chartColors.info,
                pointBorderWidth: 20,
                pointHoverRadius: 4,
                pointHoverBorderWidth: 15,
                pointRadius: 4,
                data: @json($dashboard['monthly_activity']['tugas'])
            }, {
                label: 'Quiz',
                fill: false,
                borderColor: chartColors.danger,
                backgroundColor: chartColors.danger,
                borderWidth: 2,
                pointBackgroundColor: chartColors.danger,
                pointBorderColor: 'rgba(255,255,255,0)',
                pointHoverBackgroundColor: chartColors.danger,
                pointBorderWidth: 20,
                pointHoverRadius: 4,
                pointHoverBorderWidth: 15,
                pointRadius: 4,
                data: @json($dashboard['monthly_activity']['quiz'])
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: true,
                position: 'top',
                labels: {
                    fontColor: '#9a9a9a',
                    usePointStyle: true
                }
            },
            responsive: true,
            tooltips: {
                backgroundColor: '#f5f5f5',
                titleFontColor: '#333',
                bodyFontColor: '#666',
                bodySpacing: 4,
                xPadding: 12,
                mode: 'nearest',
                intersect: 0,
                position: 'nearest'
            },
            scales: {
                yAxes: [{
                    barPercentage: 1.6,
                    gridLines: {
                        drawBorder: false,
                        color: 'rgba(29,140,248,0.0)',
                        zeroLineColor: 'transparent'
                    },
                    ticks: {
                        padding: 20,
                        fontColor: '#9a9a9a',
                        beginAtZero: true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Jumlah',
                        fontColor: '#9a9a9a'
                    }
                }],
                xAxes: [{
                    barPercentage: 1.6,
                    gridLines: {
                        drawBorder: false,
                        color: 'rgba(225,78,202,0.1)',
                        zeroLineColor: 'transparent'
                    },
                    ticks: {
                        padding: 20,
                        fontColor: '#9a9a9a'
                    }
                }]
            }
        }
    });

    // Content distribution bar chart
    const contentBarChartCtx = document.getElementById('content-bar-chart').getContext('2d');
    new Chart(contentBarChartCtx, {
        type: 'bar',
        data: {
            labels: ['Kelas', 'Mata Pelajaran', 'Materi', 'Tugas', 'Quiz'],
            datasets: [{
                label: 'Jumlah',
                data: [
                    {{ $dashboard['kelas'] }}, 
                    {{ $dashboard['mata_pelajaran'] }}, 
                    {{ $dashboard['materi'] }}, 
                    {{ $dashboard['tugas'] }}, 
                    {{ $dashboard['quiz'] }}
                ],
                backgroundColor: [
                    chartColors.purple, 
                    chartColors.warning, 
                    chartColors.indigo, 
                    chartColors.teal, 
                    chartColors.pink
                ],
                borderColor: [
                    chartColors.purple, 
                    chartColors.warning, 
                    chartColors.indigo, 
                    chartColors.teal, 
                    chartColors.pink
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontColor: '#9a9a9a'
                    },
                    gridLines: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }],
                xAxes: [{
                    ticks: {
                        fontColor: '#9a9a9a'
                    },
                    gridLines: {
                        display: false
                    }
                }]
            }
        }
    });
});
</script>
@endpush