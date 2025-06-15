@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('header')
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 rounded-2xl p-8 mb-2">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Dashboard Guru</h1>
                <p class="text-indigo-100 text-lg">Kelola pembelajaran dengan mudah dan efektif</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl p-4 shadow-lg">
                    <svg class="h-12 w-12 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-8 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl p-4">
    <!-- Dashboard Explanation Card -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl shadow-lg border border-gray-100 p-8 animate-fadeInUp" style="background: linear-gradient(135deg,rgb(152, 34, 248) 20%,rgb(153, 130, 154) 100%);">
        <div class="flex items-center mb-4">
            <div class="rounded-lg p-2 mr-3" style="background: linear-gradient(to right, #fb923c, #fbbf24);">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-white">Tentang Dashboard Ini</h2>
        </div>
        <p class="text-gray-100 leading-relaxed text-white">
            Dashboard Guru ini dirancang untuk membantu Anda memantau dan mengelola aktivitas pembelajaran dengan efisien. 
            Lihat statistik kelas, mata pelajaran, siswa, tugas, materi, dan hasil quiz secara real-time. 
            Gunakan filter untuk menganalisis data berdasarkan tahun ajaran dan kelas
        </p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-8">
        <div class="flex items-center mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg p-2 mr-3">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Filter Data</h2>
        </div>
        
        <form action="{{ route('dashboard.guru') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-5 space-y-2">
                <label for="tahun_ajaran" class="block text-sm font-semibold text-gray-700">Tahun Ajaran</label>
                <div class="relative">
                    <select name="tahun_ajaran" id="tahun_ajaran" class="block w-full pl-4 pr-10 py-3 text-base border-0 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent sm:text-sm rounded-xl transition-all duration-200">
                        <option value="all" {{ $dashboard['selected_year'] === 'all' ? 'selected' : '' }}>Semua Tahun Ajaran</option>
                        @foreach($dashboard['tahun_ajaran_list'] as $tahun)
                            <option value="{{ $tahun }}" {{ $dashboard['selected_year'] === $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="md:col-span-5 space-y-2">
                <label for="kelas" class="block text-sm font-semibold text-gray-700">Kelas</label>
                <div class="relative">
                    <select name="kelas" id="kelas" class="block w-full pl-4 pr-10 py-3 text-base border-0 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent sm:text-sm rounded-xl transition-all duration-200">
                        <option value="all" {{ $dashboard['selected_kelas'] === 'all' ? 'selected' : '' }}>Semua Kelas</option>
                        @foreach($dashboard['kelas_list'] as $k)
                            <option value="{{ $k }}" {{ $dashboard['selected_kelas'] === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="md:col-span-2 flex items-end">
                <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl shadow-lg text-white filter-button-gradient focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:scale-105 transition-all duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z" />
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Academic Year Info -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-2xl shadow-lg p-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold mb-2 text-gray-800">Informasi Tahun Ajaran</h2>
                <p class="text-gray-700">
                    Tahun Ajaran Aktif: <span class="font-bold text-2xl text-gray-900">{{ $dashboard['tahun_ajaran_aktif'] }}</span>
                </p>
            </div>
            <div class="hidden md:block">
                <div class="bg-blue-500 rounded-xl p-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <!-- Kelas Card -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="icon-gradient-kelas rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800">{{ $dashboard['kelas'] }}</div>
                        <div class="text-sm font-semibold text-gray-500 -mt-1">Total Kelas</div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-2 mt-2">
                    <div class="text-xs font-medium text-indigo-700">Manajemen Kelas</div>
                </div>
            </div>
        </div>

        <!-- Mata Pelajaran Card -->
        <div class="group bg-gradient-to-br from-white to-emerald-50 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="icon-gradient-mapel rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800">{{ $dashboard['mata_pelajaran'] }}</div>
                        <div class="text-sm font-semibold text-gray-500 -mt-1">Mata Pelajaran</div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-lg p-2 mt-2">
                    <div class="text-xs font-medium text-emerald-700">Kurikulum Aktif</div>
                </div>
            </div>
        </div>

        <!-- Siswa Card -->
        <div class="group bg-gradient-to-br from-white to-amber-50 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="icon-gradient-siswa rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800">{{ intval($dashboard['siswa']) }}</div>
                        <div class="text-sm font-semibold text-gray-500 -mt-1">Total Siswa</div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg p-2 mt-2">
                    <div class="text-xs font-medium text-amber-700">Peserta Didik</div>
                </div>
            </div>
        </div>

        <!-- Total Tugas Card -->
        <div class="group bg-gradient-to-br from-white to-purple-50 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="icon-gradient-tugas rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800">{{ intval($dashboard['total_tugas']) }}</div>
                        <div class="text-sm font-semibold text-gray-500 -mt-1">Total Tugas</div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-2 mt-2">
                    <div class="text-xs font-medium text-purple-700">Aktivitas Belajar</div>
                </div>
            </div>
        </div>

        <!-- Total Materi Card -->
        <div class="group bg-gradient-to-br from-white to-rose-50 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="icon-gradient-materi rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800">{{ intval($dashboard['total_materi']) }}</div>
                        <div class="text-sm font-semibold text-gray-500 -mt-1">Total Materi</div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-lg p-2 mt-2">
                    <div class="text-xs font-medium text-rose-700">Konten Pembelajaran</div>
                </div>
            </div>
        </div>

        <!-- Total Quiz Card -->
        <div class="group bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="icon-gradient-quiz rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.007 12.007 0 002.944 12c.046 2.051.513 4.024 1.341 5.862l-.462.462A13.978 13.978 0 0012 22a13.978 13.978 0 008.118-3.676l-.462-.462c.828-1.838 1.295-3.811 1.341-5.862a12.007 12.007 0 00-3.04-8.618z" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800">{{ intval($dashboard['total_quiz']) }}</div>
                        <div class="text-sm font-semibold text-gray-500 -mt-1">Total Quiz</div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-2 mt-2">
                    <div class="text-xs font-medium text-blue-700">Evaluasi Pembelajaran</div>
                </div>
            </div>
        </div>

        <!-- Lulus Quiz Card -->
        <div class="group bg-gradient-to-br from-white to-green-50 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="icon-gradient-lulus rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800">{{ intval($dashboard['passed_quizzes']) }}</div>
                        <div class="text-sm font-semibold text-gray-500 -mt-1">Quiz Lulus</div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-2 mt-2">
                    <div class="text-xs font-medium text-green-700">Berhasil Lulus</div>
                </div>
            </div>
        </div>

        <!-- Tidak Lulus Quiz Card -->
        <div class="group bg-gradient-to-br from-white to-red-50 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="icon-gradient-tidaklulus rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2L8 10m8 4l-2-2m0 0l2 2m-2-2L8 14m-6 0a9 9 0 1118 0 9 9 0 01-18 0z" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800">{{ intval($dashboard['failed_quizzes']) }}</div>
                        <div class="text-sm font-semibold text-gray-500 -mt-1">Quiz Tidak Lulus</div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-lg p-2 mt-2">
                    <div class="text-xs font-medium text-red-700">Perlu Perbaikan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Siswa per Kelas Chart -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-6">
                <div class="flex items-center">
                    <div class="bg-white/20 rounded-lg p-2 mr-3">
                        <svg class="h-5 w-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Siswa per Kelas</h2>
                </div>
            </div>
            <div class="p-6">
                <div style="height: 350px;">
                    <canvas id="kelasDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Quiz Results Chart -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-6">
                <div class="flex items-center">
                    <div class="bg-white/20 rounded-lg p-2 mr-3">
                        <svg class="h-5 w-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Distribusi Hasil Quiz</h2>
                </div>
            </div>
            <div class="p-6">
                <div style="height: 350px;">
                    <canvas id="quizResultsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai Rata-rata Tugas Chart -->
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-red-600 p-6">
            <div class="flex items-center">
                <div class="bg-white/20 rounded-lg p-2 mr-3">
                    <svg class="h-5 w-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Nilai Rata-rata Tugas per Mata Pelajaran</h2>
            </div>
        </div>
        <div class="p-6">
            <div style="height: 400px;">
                <canvas id="tugasChart"></canvas>
            </div>
            <!-- Detail per tugas -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Detail Nilai per Tugas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($dashboard['tugas_chart']['data_per_tugas'] as $tugas)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-2">{{ $tugas['nama_tugas'] }}</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Mata Pelajaran:</span>
                                    <span class="font-semibold text-gray-800 truncate" title="{{ $tugas['mata_pelajaran'] }}">
                                        {{ Str::limit($tugas['mata_pelajaran'], 15) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Kelas:</span>
                                    <span class="font-semibold text-gray-800">{{ $tugas['kelas'] }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Rata-rata Nilai:</span>
                                    <span class="font-semibold text-gray-800">{{ $tugas['rata_rata'] }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Submit/Dinilai:</span>
                                    <span class="font-semibold text-gray-800">{{ $tugas['jumlah_dinilai'] }}/{{ $tugas['jumlah_submit'] }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Tanggal:</span>
                                    <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($tugas['tanggal_tugas'])->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Tenggat:</span>
                                    <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($tugas['tenggat_waktu'])->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('extraScript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kelas Distribution Chart
        var ctxKelas = document.getElementById('kelasDistributionChart').getContext('2d');
        var kelasDistributionChart = new Chart(ctxKelas, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dashboard['kelas_chart']['labels']) !!},
                datasets: [{
                    label: 'Jumlah Siswa per Kelas',
                    data: {!! json_encode($dashboard['kelas_chart']['data']) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'center',
                        labels: {
                            boxWidth: 20,
                            padding: 20,
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#000000'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return `Jumlah Siswa: ${Math.round(context.parsed.y)} orang`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            min: 0,
                            color: '#000000',
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            callback: function(value) {
                                if (value % 1 === 0) {
                                    return value;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Siswa',
                            color: '#000000',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#000000',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Nama Kelas',
                            color: '#000000',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Quiz Results Chart
        var ctxQuizResults = document.getElementById('quizResultsChart').getContext('2d');
        var quizResultsChart = new Chart(ctxQuizResults, {
            type: 'doughnut',
            data: {
                labels: ['Lulus', 'Tidak Lulus'],
                datasets: [{
                    label: 'Hasil Quiz',
                    data: [Math.round({!! $dashboard['passed_quizzes'] !!}), Math.round({!! $dashboard['failed_quizzes'] !!})],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgba(34, 197, 94, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 3,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            boxWidth: 20,
                            padding: 20,
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#000000',
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.parsed / total) * 100);
                                return `${context.label}: ${context.parsed} siswa (${percentage}%)`;
                            }
                        }
                    }
                },
                elements: {
                    arc: {
                        borderWidth: 3
                    }
                }
            }
        });

        // Nilai Rata-rata Tugas Chart
        var ctxTugas = document.getElementById('tugasChart').getContext('2d');
        var tugasChart = new Chart(ctxTugas, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dashboard['tugas_chart']['labels']) !!},
                datasets: [{
                    label: 'Nilai Rata-rata Tugas',
                    data: {!! json_encode($dashboard['tugas_chart']['data']) !!},
                    backgroundColor: 'rgba(251, 146, 60, 0.8)',
                    borderColor: 'rgba(251, 146, 60, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'center',
                        labels: {
                            boxWidth: 20,
                            padding: 20,
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#000000'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(251, 146, 60, 1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return `Nilai Rata-rata: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 10,
                            min: 0,
                            max: 100,
                            color: '#000000',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Nilai Rata-rata',
                            color: '#000000',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#000000',
                            font: {
                                size: 8,
                                weight: 'bold'
                            },
                            maxRotation: 45,
                            minRotation: 0
                        },
                        title: {
                            display: true,
                            text: 'Mata Pelajaran - Kelas - Nama Tugas',
                            color: '#000000',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Add hover effects to cards
        document.querySelectorAll('.group').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add smooth animations to form elements
        document.querySelectorAll('select').forEach(select => {
            select.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
                this.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
            });
            
            select.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = 'none';
            });
        });

        console.log('Kelas Chart Data:', {!! json_encode($dashboard['kelas_chart']) !!});
        console.log('Quiz Passed:', Math.round({!! $dashboard['passed_quizzes'] !!}));
        console.log('Quiz Failed:', Math.round({!! $dashboard['failed_quizzes'] !!}));
        console.log('Tugas Chart Data:', {!! json_encode($dashboard['tugas_chart']) !!});
    });
</script>

<style>
    /* Custom CSS for enhanced visual effects */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Glassmorphism effect */
    .glass-effect {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    /* Custom gradient borders */
    .gradient-border {
        position: relative;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(45deg, #8B5CF6, #3B82F6) border-box;
        border: 2px solid transparent;
    }

    /* Smooth transitions for all interactive elements */
    * {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(45deg, #8B5CF6, #3B82F6);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(45deg, #7C3AED, #2563EB);
    }

    /* Enhanced button hover effects */
    button:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Card hover glow effect */
    .group:hover {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    /* Loading animation for charts */
    canvas {
        animation: fadeInUp 0.8s ease-out;
    }

    /* Custom Gradients for Icon Cards */
    .icon-gradient-kelas {
        background-image: linear-gradient(to bottom right, #6366F1, #8B5CF6) !important; /* indigo-500 to purple-600 */
    }
    .icon-gradient-mapel {
        background-image: linear-gradient(to bottom right, #10B981, #0D9488) !important; /* emerald-500 to teal-600 */
    }
    .icon-gradient-siswa {
        background-image: linear-gradient(to bottom right, #F59E0B, #EA580C) !important; /* amber-500 to orange-600 */
    }
    .icon-gradient-tugas {
        background-image: linear-gradient(to bottom right, #A855F7, #EC4899) !important; /* purple-500 to pink-600 */
    }
    .icon-gradient-materi {
        background-image: linear-gradient(to bottom right, #F43F5E, #EC4899) !important; /* rose-500 to pink-600 */
    }
    .icon-gradient-quiz {
        background-image: linear-gradient(to bottom right, #3B82F6, #06B6D4) !important; /* blue-500 to cyan-600 */
    }
    .icon-gradient-lulus {
        background-image: linear-gradient(to bottom right, #22C55E, #0D9488) !important; /* green-500 to emerald-600 */
    }
    .icon-gradient-tidaklulus {
        background-image: linear-gradient(to bottom right, #EF4444, #EC4899) !important; /* red-500 to pink-600 */
    }

    .filter-button-gradient {
        background-image: linear-gradient(to right, #4F46E5, #9333EA, #2563EB) !important; /* Contoh gradien yang kuat: indigo-600, purple-600, blue-600 */
    }
</style>
@endpush