@extends('layouts.app')

@section('title', 'Detail Rekap Tugas')
@section('titleHeader', 'Detail Rekap Tugas')

@section('btnNew')
<div class="flex space-x-2">
    <a href="{{ route('rekap.tugas') }}" class="button is-light is-small">
        <span class="icon"><i class="mdi mdi-arrow-left"></i></span>
        <span>Kembali</span>
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
        <h3 class="card-header-title">Informasi Tugas</h3>
    </div>
    <div class="card-content">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <table class="table is-fullwidth">
                    <tr>
                        <td class="has-text-weight-bold">Nama Tugas:</td>
                        <td>{{ $tugas->nama }}</td>
                    </tr>
                    <tr>
                        <td class="has-text-weight-bold">Mata Pelajaran:</td>
                        <td>{{ $tugas->mataPelajaran->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="has-text-weight-bold">Kelas:</td>
                        <td>{{ $tugas->kelas }}</td>
                    </tr>
                    <tr>
                        <td class="has-text-weight-bold">Tanggal Dibuat:</td>
                        <td>{{ \Carbon\Carbon::parse($tugas->tanggal)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="has-text-weight-bold">Tenggat Waktu:</td>
                        <td>
                            <span class="tag {{ \Carbon\Carbon::parse($tugas->tenggat)->isPast() ? 'is-danger' : 'is-success' }}">
                                {{ \Carbon\Carbon::parse($tugas->tenggat)->format('d/m/Y') }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <div class="notification is-info is-light">
                    <div class="content">
                        <h4 class="title is-5">Statistik Submit</h4>
                        <ul>
                            <li><strong>Total Submit:</strong> {{ $submitTugas->count() }}</li>
                            <li><strong>Sudah Dinilai:</strong> {{ $submitTugas->whereNotNull('nilai')->count() }}</li>
                            <li><strong>Belum Dinilai:</strong> {{ $submitTugas->whereNull('nilai')->count() }}</li>
                            @if($submitTugas->whereNotNull('nilai')->count() > 0)
                                <li><strong>Rata-rata Nilai:</strong> 
                                    {{ number_format($submitTugas->whereNotNull('nilai')->avg('nilai'), 1) }}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-header-title">Daftar Submit Tugas Siswa</h3>
    </div>
    <div class="card-content">
        @if($submitTugas->count() > 0)
            <div class="table-container">
                <table class="table is-fullwidth is-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>NISN</th>
                            <th>Tanggal Submit</th>
                            <th>Status</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submitTugas as $index => $submit)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $submit->siswa->nama ?? 'Siswa tidak ditemukan' }}</strong>
                                </td>
                                <td>{{ $submit->nisn }}</td>
                                <td>{{ \Carbon\Carbon::parse($submit->tanggal)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($submit->nilai !== null)
                                        <span class="tag is-success">Sudah Dinilai</span>
                                    @else
                                        <span class="tag is-warning">Belum Dinilai</span>
                                    @endif
                                </td>
                                <td>
                                    @if($submit->nilai !== null)
                                        <span class="tag is-info">{{ $submit->nilai }}</span>
                                    @else
                                        <span class="tag is-light">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="buttons are-small">
                                        <button class="button is-info" 
                                                onclick="viewSubmission({{ $submit->id }})">
                                            <span class="icon"><i class="mdi mdi-eye"></i></span>
                                            <span>Lihat</span>
                                        </button>
                                        <button class="button is-success" 
                                                onclick="gradeSubmission({{ $submit->id }}, '{{ $submit->nilai }}')">
                                            <span class="icon"><i class="mdi mdi-pencil"></i></span>
                                            <span>Nilai</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="has-text-centered py-6">
                <span class="icon is-large">
                    <i class="mdi mdi-clipboard-text-outline" style="font-size: 4rem; color: #ccc;"></i>
                </span>
                <p class="title is-4 has-text-grey-light mt-3">Belum ada submit tugas</p>
                <p class="subtitle is-6 has-text-grey-light">Siswa belum mengumpulkan tugas ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection

<!-- Modal Custom untuk Penilaian -->
<div id="customGradeModal" style="display:none; position:fixed; z-index:99999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:10px; padding:2rem; min-width:320px; max-width:90vw; margin:auto; position:relative;">
        <button onclick="closeCustomGradeModal()" style="position:absolute; right:1rem; top:1rem; background:none; border:none; font-size:1.5rem;">&times;</button>
        <h3 style="margin-bottom:1rem;">Berikan Nilai</h3>
        <form id="customGradeForm" method="POST">
            @csrf
            <input type="number" name="nilai" id="customNilaiInput" min="0" max="100" step="0.1" required style="width:100%; margin-bottom:1rem; padding:0.5rem;">
            <div style="text-align:right;">
                <button type="button" onclick="submitCustomGrade()" style="background:#4CAF50; color:#fff; border:none; padding:0.5rem 1rem; border-radius:5px;">Simpan Nilai</button>
                <button type="button" onclick="closeCustomGradeModal()" style="background:#ccc; color:#333; border:none; padding:0.5rem 1rem; border-radius:5px;">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Custom untuk Melihat Submit Tugas -->
<div id="customViewModal" style="display:none; position:fixed; z-index:99999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:10px; padding:2rem; min-width:320px; max-width:90vw; margin:auto; position:relative;">
        <button onclick="closeCustomViewModal()" style="position:absolute; right:1rem; top:1rem; background:none; border:none; font-size:1.5rem;">&times;</button>
        <h3 style="margin-bottom:1rem;">Detail Submit Tugas</h3>
        <div id="customViewContent">
            <!-- Content will be loaded here -->
        </div>
        <div style="text-align:right; margin-top:1rem;">
            <button type="button" onclick="closeCustomViewModal()" style="background:#ccc; color:#333; border:none; padding:0.5rem 1rem; border-radius:5px;">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function gradeSubmission(submitId, currentGrade) {
    document.getElementById('customNilaiInput').value = currentGrade || '';
    document.getElementById('customGradeForm').action = `/rekap-tugas/nilai/${submitId}`;
    document.getElementById('customGradeModal').style.display = 'flex';
}

function closeCustomGradeModal() {
    document.getElementById('customGradeModal').style.display = 'none';
}

function submitCustomGrade() {
    const form = document.getElementById('customGradeForm');
    const nilai = document.getElementById('customNilaiInput').value;
    if (!nilai || nilai < 0 || nilai > 100) {
        alert('Nilai harus antara 0-100');
        return;
    }
    form.submit();
}

function viewSubmission(submitId) {
    const contentDiv = document.getElementById('customViewContent');
    contentDiv.innerHTML = '<div style="text-align:center; padding:2rem;"><i class="mdi mdi-loading mdi-spin"></i> Loading...</div>';
    document.getElementById('customViewModal').style.display = 'flex';
    
    console.log('Fetching data for submit_id:', submitId);
    
    fetch(`/api/web/get-detail-submit-tugas?submit_id=${submitId}`)
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            // Check if response is ok
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Get response text first to debug
            return response.text();
        })
        .then(responseText => {
            console.log('Raw response text:', responseText);
            
            // Try to parse as JSON
            let response;
            try {
                response = JSON.parse(responseText);
            } catch (e) {
                console.error('JSON parse error:', e);
                contentDiv.innerHTML = '<div style="text-align:center; padding:2rem;">Error: Response bukan JSON valid<br><small>' + responseText.substring(0, 200) + '...</small></div>';
                return;
            }
            
            console.log('Parsed API Response:', response);
            
            // Handle API response structure
            const data = response.data || response;
            
            if (!data) {
                contentDiv.innerHTML = '<div style="text-align:center; padding:2rem;">Data tidak ditemukan</div>';
                return;
            }
            
            let content = `
                <div class="content">
                    <h4>Informasi Siswa</h4>
                    <p><strong>Nama:</strong> ${data.siswa ? data.siswa.nama : 'Tidak ditemukan'}</p>
                    <p><strong>NISN:</strong> ${data.nisn || '-'}</p>
                    <p><strong>Tanggal Submit:</strong> ${data.tanggal || '-'}</p>
                    <h4>Jawaban Tugas</h4>
                    <div class="box">
                        ${data.text ? `<p><strong>Teks Jawaban:</strong></p><p>${data.text}</p>` : '<p><em>Tidak ada teks jawaban</em></p>'}
                        ${data.file ? `<p><strong>File:</strong> <a href="/storage/${data.file}" target="_blank">Download File</a></p>` : '<p><em>Tidak ada file</em></p>'}
                    </div>
                    ${data.nilai ? `<h4>Nilai</h4><p><span class="tag is-info is-large">${data.nilai}</span></p>` : '<h4>Nilai</h4><p><span class="tag is-light">Belum dinilai</span></p>'}
                </div>
            `;
            contentDiv.innerHTML = content;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            contentDiv.innerHTML = '<div style="text-align:center; padding:2rem;">Error: ' + error.message + '</div>';
        });
}

function closeCustomViewModal() {
    document.getElementById('customViewModal').style.display = 'none';
}
</script>
@endpush 