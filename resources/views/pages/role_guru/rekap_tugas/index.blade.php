@extends('layouts.app')

@section('title', 'Rekap Tugas')
@section('titleHeader', 'Rekap Tugas')

@section('btnNew')
<div class="flex space-x-2">
    <a href="{{ route('rekap.tugas.export') }}?{{ http_build_query(request()->query()) }}" 
       class="button is-primary is-small">
        <span class="icon"><i class="mdi mdi-download"></i></span>
        <span>Export Rekap</span>
    </a>
    <a href="{{ route('rekap.tugas.export.detail') }}?{{ http_build_query(request()->query()) }}" 
       class="button is-info is-small">
        <span class="icon"><i class="mdi mdi-file-document"></i></span>
        <span>Export Detail</span>
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-header-title">Filter Data</h3>
    </div>
    <div class="card-content">
        <form method="GET" action="{{ route('rekap.tugas') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="label">Kelas</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="kelas">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->nama }}" {{ $selectedKelas == $kelas->nama ? 'selected' : '' }}>
                                        {{ $kelas->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="label">Mata Pelajaran</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="matpel">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach($mataPelajaran as $matpel)
                                    <option value="{{ $matpel->id }}" {{ $selectedMatpel == $matpel->id ? 'selected' : '' }}>
                                        {{ $matpel->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="label">Cari Tugas</label>
                    <div class="control">
                        <input class="input" type="text" name="search" value="{{ $search }}" placeholder="Cari nama tugas...">
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" class="button is-primary">
                    <span class="icon"><i class="mdi mdi-filter"></i></span>
                    <span>Filter</span>
                </button>
                <a href="{{ route('rekap.tugas') }}" class="button is-light">
                    <span class="icon"><i class="mdi mdi-refresh"></i></span>
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-header-title">Daftar Tugas</h3>
    </div>
    <div class="card-content">
        @if($tugas->count() > 0)
            <div class="table-container">
                <table class="table is-fullwidth is-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Tugas</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Tenggat</th>
                            <th>Jumlah Submit</th>
                            <th>Sudah Dinilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tugas as $index => $t)
                            <tr>
                                <td>{{ $index + 1 + ($tugas->currentPage() - 1) * $tugas->perPage() }}</td>
                                <td>
                                    <strong>{{ $t->nama }}</strong>
                                </td>
                                <td>{{ $t->mataPelajaran->nama ?? '-' }}</td>
                                <td>{{ $t->kelas }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="tag {{ \Carbon\Carbon::parse($t->tenggat)->isPast() ? 'is-danger' : 'is-success' }}">
                                        {{ \Carbon\Carbon::parse($t->tenggat)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="tag is-info">
                                        {{ $t->submitTugas->count() }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $sudahDinilai = $t->submitTugas->whereNotNull('nilai')->count();
                                        $totalSubmit = $t->submitTugas->count();
                                    @endphp
                                    <span class="tag {{ $sudahDinilai == $totalSubmit && $totalSubmit > 0 ? 'is-success' : 'is-warning' }}">
                                        {{ $sudahDinilai }}/{{ $totalSubmit }}
                                    </span>
                                </td>
                                <td>
                                    <div class="buttons are-small">
                                        <a href="{{ route('rekap.tugas.detail', $t->id) }}" 
                                           class="button is-info">
                                            <span class="icon"><i class="mdi mdi-eye"></i></span>
                                            <span>Detail</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $tugas->appends(request()->query())->links() }}
            </div>
        @else
            <div class="has-text-centered py-6">
                <span class="icon is-large">
                    <i class="mdi mdi-clipboard-text-outline" style="font-size: 4rem; color: #ccc;"></i>
                </span>
                <p class="title is-4 has-text-grey-light mt-3">Tidak ada data tugas</p>
                <p class="subtitle is-6 has-text-grey-light">Tidak ada tugas yang ditemukan dengan filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when select changes
    const selects = document.querySelectorAll('select[name="kelas"], select[name="matpel"]');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
</script>
@endpush 