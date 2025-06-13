@extends('layouts.app')
@section('title', "Dashboard")
@section('titleHeader', "Dashboard")

@section('content')
<!-- Year Filter - Redesigned Dropdown Only -->
<div class="card mb-6 rounded-xl shadow-lg border-0 overflow-hidden bg-gradient-to-br from-white to-gray-50">
    <div class="card-content p-6">
        <form method="GET" action="{{ route('dashboard.admin') }}" class="flex flex-col md:flex-row items-center gap-6">
            <div class="field flex-1 w-full">
                <label class="label block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="mdi mdi-calendar-clock text-blue-600 text-sm"></i>
                    </div>
                    Filter Tahun Ajaran
                </label>
                <div class="control">
                    <div class="relative group">
                        <select name="tahun_ajaran" onchange="this.form.submit()"
                            class="block appearance-none w-full bg-white border-2 border-gray-200 text-gray-700 py-4 px-5 pr-12 rounded-xl leading-tight 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:shadow-lg
                                   transition-all duration-300 ease-in-out hover:border-blue-300 hover:shadow-md
                                   group-hover:transform group-hover:scale-[1.02] cursor-pointer
                                   font-medium text-base">
                            <option value="all" {{ $dashboard['selected_year'] === 'all' ? 'selected' : '' }}>
                                📅 Semua Tahun Ajaran
                            </option>
                            @foreach($dashboard['tahun_ajaran_list'] as $tahun)
                                <option value="{{ $tahun }}" {{ $dashboard['selected_year'] === $tahun ? 'selected' : '' }}>
                                    🗓️ {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                        
                        <!-- Enhanced Custom Arrow -->
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center 
                                        group-hover:from-blue-200 group-hover:to-blue-300 transition-all duration-300
                                        group-hover:scale-110 shadow-sm">
                                <svg class="w-4 h-4 text-blue-600 transition-transform duration-300 group-hover:rotate-180" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Selection Badge -->
                        @if($dashboard['selected_year'] !== 'all')
                        <div class="absolute -top-2 -right-2 w-5 h-5 bg-gradient-to-r from-green-400 to-green-500 rounded-full 
                                    flex items-center justify-center shadow-lg animate-pulse">
                            <i class="mdi mdi-check text-white text-xs"></i>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="field flex-1 w-full">
                <label class="label block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="mdi mdi-google-classroom text-green-600 text-sm"></i>
                    </div>
                    Filter Kelas
                </label>
                <div class="control">
                    <div class="relative group">
                        <select name="kelas" onchange="this.form.submit()"
                            class="block appearance-none w-full bg-white border-2 border-gray-200 text-gray-700 py-4 px-5 pr-12 rounded-xl leading-tight 
                                   focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:shadow-lg
                                   transition-all duration-300 ease-in-out hover:border-green-300 hover:shadow-md
                                   group-hover:transform group-hover:scale-[1.02] cursor-pointer
                                   font-medium text-base">
                            <option value="all" {{ $dashboard['selected_kelas'] === 'all' ? 'selected' : '' }}>
                                🏫 Semua Kelas
                            </option>
                            @foreach($dashboard['kelas_list'] as $kelas)
                                <option value="{{ $kelas }}" {{ $dashboard['selected_kelas'] === $kelas ? 'selected' : '' }}>
                                    📚 {{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                        
                        <!-- Enhanced Custom Arrow -->
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center 
                                        group-hover:from-green-200 group-hover:to-green-300 transition-all duration-300
                                        group-hover:scale-110 shadow-sm">
                                <svg class="w-4 h-4 text-green-600 transition-transform duration-300 group-hover:rotate-180" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Selection Badge -->
                        @if($dashboard['selected_kelas'] !== 'all')
                        <div class="absolute -top-2 -right-2 w-5 h-5 bg-gradient-to-r from-green-400 to-green-500 rounded-full 
                                    flex items-center justify-center shadow-lg animate-pulse">
                            <i class="mdi mdi-check text-white text-xs"></i>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Academic Year Info -->
<div class="card mb-6">
    <div class="card-content">
        <div class="flex items-center justify-between">
            <div class="widget-label">
                <h3 class="text-lg font-semibold">Tahun Ajaran yang Ditampilkan</h3>
                <h1 class="text-2xl font-bold">
                    @if ($dashboard['selected_year'] === 'all')
                        Semua Tahun Ajaran
                    @else
                        {{ $dashboard['selected_year'] }}
                    @endif
                </h1>
            </div>
            <span class="icon widget-icon text-yellow-500"><i class="mdi mdi-calendar mdi-48px"></i></span>
        </div>
    </div>
</div>

<!-- User Statistics -->
<div class="grid gap-6 grid-cols-1 md:grid-cols-3 mb-6">
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Admin</h3>
                    <h1>{{ $dashboard['admin'] }}</h1>
                </div>
                <span class="icon widget-icon text-green-500"><i class="mdi mdi-account-multiple mdi-48px"></i></span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Guru</h3>
                    <h1>{{ $dashboard['guru'] }}</h1>
                </div>
                <span class="icon widget-icon text-red-500"><i class="mdi mdi-account-multiple mdi-48px"></i></span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Siswa</h3>
                    <h1>{{ $dashboard['siswa'] }}</h1>
                </div>
                <span class="icon widget-icon text-blue-500"><i class="mdi mdi-account-multiple mdi-48px"></i></span>
            </div>
        </div>
    </div>
</div>

<!-- Academic Statistics -->
<div class="grid gap-6 grid-cols-1 md:grid-cols-4 mb-6">
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Kelas</h3>
                    <h1>{{ $dashboard['kelas'] }}</h1>
                </div>
                <span class="icon widget-icon text-purple-500"><i class="mdi mdi-home-variant mdi-48px"></i></span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Mata Pelajaran</h3>
                    <h1>{{ $dashboard['mata_pelajaran'] }}</h1>
                </div>
                <span class="icon widget-icon text-indigo-500"><i class="mdi mdi-book-open-variant mdi-48px"></i></span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Wali Kelas</h3>
                    <h1>{{ $dashboard['wali_kelas'] }}</h1>
                </div>
                <span class="icon widget-icon text-teal-500"><i class="mdi mdi-account-tie mdi-48px"></i></span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Tahun Ajaran Aktif</h3>
                    <h1>{{ $dashboard['tahun_ajaran_aktif'] }}</h1>
                </div>
                <span class="icon widget-icon text-yellow-500"><i class="mdi mdi-calendar mdi-48px"></i></span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid gap-6 grid-cols-1 md:grid-cols-2 mb-6">
    <!-- User Distribution Chart -->
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-chart-pie"></i></span>
                Distribusi Pengguna
            </p>
        </header>
        <div class="card-content">
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="userDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Academic Statistics Chart -->
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-chart-bar"></i></span>
                Statistik Akademik
            </p>
        </header>
        <div class="card-content">
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="academicStatsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mb-6">
    <header class="card-header">
        <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-lightning-bolt"></i></span>
            Aksi Cepat
        </p>
    </header>
    <div class="card-content">
        <div class="grid gap-4 grid-cols-1 md:grid-cols-4">
            <a href="{{ route('admin.create') }}" class="button blue">
                <span class="icon"><i class="mdi mdi-account-plus"></i></span>
                <span>Tambah Admin</span>
            </a>
            <a href="{{ route('guru.create') }}" class="button red">
                <span class="icon"><i class="mdi mdi-account-plus"></i></span>
                <span>Tambah Guru</span>
            </a>
            <a href="{{ route('siswa.create') }}" class="button green">
                <span class="icon"><i class="mdi mdi-account-plus"></i></span>
                <span>Tambah Siswa</span>
            </a>
            <a href="{{ route('kelas.create') }}" class="button purple">
                <span class="icon"><i class="mdi mdi-plus-circle"></i></span>
                <span>Tambah Kelas</span>
            </a>
        </div>
    </div>
</div>

<!-- System Status -->
<div class="card mb-6">
    <header class="card-header">
        <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-server"></i></span>
            Status Sistem
        </p>
    </header>
    <div class="card-content">
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                    <span class="icon text-green-500"><i class="mdi mdi-check-circle mdi-36px"></i></span>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold">Database</h4>
                    <p class="text-gray-600">Sistem berjalan dengan baik</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                    <span class="icon text-green-500"><i class="mdi mdi-check-circle mdi-36px"></i></span>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold">Storage</h4>
                    <p class="text-gray-600">Kapasitas penyimpanan mencukupi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Styling for Dropdowns Only -->
<style>
    /* Dropdown specific enhancements */
    .card select {
        background-image: none !important;
        background-size: 0 !important;
    }
    
    .card select:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }
    
    .card select:focus {
        transform: translateY(-1px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
    }
    
    .card select option {
        padding: 12px 16px;
        font-weight: 500;
        background-color: white;
        color: #374151;
    }
    
    .card select option:hover {
        background-color: #f3f4f6;
    }
    
    .card select option:checked {
        background-color: #dbeafe;
        color: #1d4ed8;
        font-weight: 600;
    }
    
    /* Smooth transitions */
    .card .group {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Loading state */
    .card select[disabled] {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    /* Focus ring enhancement */
    .card select:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 8px 25px rgba(59, 130, 246, 0.15);
    }
    
    /* Custom scrollbar for select */
    .card select {
        scrollbar-width: thin;
        scrollbar-color: rgba(59, 130, 246, 0.3) transparent;
    }
    
    .card select::-webkit-scrollbar {
        width: 8px;
    }
    
    .card select::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    .card select::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #3b82f6, #1d4ed8);
        border-radius: 4px;
    }
    
    .card select::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #2563eb, #1e40af);
    }
</style>
@endsection

@push('extraScript')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Distribution Chart
    const userCtx = document.getElementById('userDistributionChart').getContext('2d');
    new Chart(userCtx, {
        type: 'doughnut',
        data: {
            labels: ['Admin', 'Guru', 'Siswa'],
            datasets: [{
                data: [
                    {{ $dashboard['admin'] }},
                    {{ $dashboard['guru'] }},
                    {{ $dashboard['siswa'] }}
                ],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',  // green
                    'rgba(239, 68, 68, 0.8)',  // red
                    'rgba(59, 130, 246, 0.8)'  // blue
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)',
                    'rgb(59, 130, 246)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Distribusi Pengguna Sistem'
                }
            }
        }
    });

    // Academic Statistics Chart
    const academicCtx = document.getElementById('academicStatsChart').getContext('2d');
    new Chart(academicCtx, {
        type: 'bar',
        data: {
            labels: ['Kelas', 'Mata Pelajaran', 'Wali Kelas'],
            datasets: [{
                label: 'Jumlah',
                data: [
                    {{ $dashboard['kelas'] }},
                    {{ $dashboard['mata_pelajaran'] }},
                    {{ $dashboard['wali_kelas'] }}
                ],
                backgroundColor: [
                    'rgba(168, 85, 247, 0.8)',  // purple
                    'rgba(99, 102, 241, 0.8)',  // indigo
                    'rgba(20, 184, 166, 0.8)'   // teal
                ],
                borderColor: [
                    'rgb(168, 85, 247)',
                    'rgb(99, 102, 241)',
                    'rgb(20, 184, 166)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Statistik Akademik'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush 