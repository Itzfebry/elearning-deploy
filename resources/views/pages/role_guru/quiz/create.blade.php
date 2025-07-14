@extends('layouts.app')
@section('title', "Quiz")
@section('titleHeader', "Tambah Quiz")
@section('btnNew')
<div class="field grouped">
    <div class="control">
        <a type="button" class="button green" href="{{ route('quiz.excel.download') }}">
            <span class="icon"><i class="mdi mdi-download"></i></span>
            Download Template Excel
        </a>
    </div>
</div>
@endsection

@section('content')
<section class="section main-section">
    <!-- Informational Alert -->
    <div class="notification is-info is-light mb-6">
        <div class="flex items-start">
            <span class="icon mr-3 mt-1">
                <i class="mdi mdi-information text-blue-600"></i>
            </span>
            <div>
                <h4 class="text-lg font-semibold text-blue-800 mb-2">Panduan Pembuatan Quiz Adaptif</h4>
                <div class="text-sm text-blue-700 space-y-1">
                    <p><strong>• Total Soal Tampil:</strong> Jumlah soal yang akan ditampilkan kepada siswa (minimal 10 soal)</p>
                    <p><strong>• Level Quiz:</strong> Sistem akan menampilkan soal berdasarkan level kesulitan (1= Mudah, 2= Sedang, 3= Sulit)</p>
                    <p><strong>• Jumlah Soal Per Level:</strong> Berapa soal yang harus dikerjakan di setiap level sebelum naik level</p>
                    <p><strong>• Batas Naik Level:</strong> Berapa soal yang harus benar untuk naik ke level berikutnya</p>
                    <p><strong>• KKM:</strong> Nilai minimum yang harus dicapai siswa untuk lulus quiz</p>
                    <p><strong>• Waktu:</strong> Batas waktu pengerjaan dalam menit (quiz akan otomatis selesai jika waktu habis)</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form untuk Upload dan Preview --}}
    <form method="POST" action="{{ route('quiz.preview') }}" enctype="multipart/form-data">
        @csrf
        <div class="card mb-6">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="mdi mdi-plus-circle text-green-600"></i></span>
                    Form Pembuatan Quiz
                </p>
            </header>
            <div class="card-content space-y-6 p-6">

                {{-- Judul Quiz --}}
                <div class="field">
                    <label class="label text-gray-700 font-medium">
                        <span class="icon mr-2"><i class="mdi mdi-format-title text-blue-500"></i></span>
                        Judul Quiz
                    </label>
                    <div class="control">
                        <input name="judul" type="text" class="input bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                               required placeholder="Contoh: Quiz Matematika Kelas 10 - Bab Persamaan Kuadrat"
                               value="{{ session('judul', old('judul')) }}">
                    </div>
                    <p class="help text-gray-600">Berikan judul yang jelas dan deskriptif untuk quiz ini</p>
                </div>

                {{-- Deskripsi dan Upload --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="field">
                        <label class="label text-gray-700 font-medium">
                            <span class="icon mr-2"><i class="mdi mdi-text text-blue-500"></i></span>
                            Deskripsi Quiz
                        </label>
                        <div class="control">
                            <textarea rows="6" class="textarea bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Jelaskan materi yang akan diuji, instruksi pengerjaan, atau informasi penting lainnya..."
                                      name="deskripsi" required>{{ session('deskripsi', old('deskripsi')) }}</textarea>
                        </div>
                        <p class="help text-gray-600">Deskripsi akan ditampilkan kepada siswa sebelum memulai quiz</p>
                    </div>

                    <div class="space-y-4">
                        <div class="field">
                            <label class="label text-gray-700 font-medium">
                                <span class="icon mr-2"><i class="mdi mdi-file-excel text-green-500"></i></span>
                                Upload File Soal (Excel)
                            </label>
                            <div class="control">
                                <div class="file has-name is-fullwidth">
                                    <label class="file-label">
                                        <input class="file-input" type="file" name="file" required accept=".xlsx,.xls">
                                        <span class="file-cta bg-blue-50 border-blue-300 hover:bg-blue-100">
                                            <span class="file-icon">
                                                <i class="mdi mdi-upload text-blue-600"></i>
                                            </span>
                                            <span class="file-label text-blue-700">Pilih file Excel...</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            @if(session('uploaded_filename'))
                            <p class="help is-success mt-2">
                                <span class="icon"><i class="mdi mdi-check-circle"></i></span>
                                File terakhir: {{ session('uploaded_filename') }}
                            </p>
                            @endif
                            <p class="help text-gray-600">Gunakan template Excel yang telah disediakan</p>
                        </div>

                        <div class="field">
                            <label class="label text-gray-700 font-medium">
                                <span class="icon mr-2"><i class="mdi mdi-book-open-variant text-purple-500"></i></span>
                                Mata Pelajaran
                            </label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="matapelajaran_id" required class="bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach ($matpel as $item)
                                        <option value="{{ $item->id }}" {{ session('matapelajaran_id', old('matapelajaran_id'))==$item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pengaturan Dasar Quiz --}}
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="mdi mdi-cog text-blue-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Pengaturan Dasar Quiz</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Konfigurasi ini menentukan bagaimana quiz akan berjalan dan dinilai.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if (session('total_soal'))
                    <div class="field">
                        <label class="label text-gray-700 font-medium">
                            <span class="icon mr-2"><i class="mdi mdi-counter text-green-500"></i></span>
                            Total Soal Tersedia
                        </label>
                        <input name="total_soal" type="text" class="input bg-gray-100 border-gray-300" 
                               required readonly value="{{ session('total_soal', old('total_soal')) }}">
                        <p class="help text-gray-600">Jumlah soal yang tersedia dari file Excel</p>
                    </div>

                    <div class="field">
                        <label class="label text-gray-700 font-medium">
                            <span class="icon mr-2"><i class="mdi mdi-eye text-blue-500"></i></span>
                            Total Soal Tampil
                        </label>
                        <input name="total_soal_tampil" type="number" class="input bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                               required id="total_soal_tampil" onchange="validasiTotalSoal(this.value)" 
                               min="1" value="{{ session('total_soal_tampil', old('total_soal_tampil', 0)) }}">
                        <p class="help text-gray-600">Jumlah soal yang akan ditampilkan ke siswa (minimal 10 soal)</p>
                    </div>
                    @endif

                    <div class="field">
                        <label class="label text-gray-700 font-medium">
                            <span class="icon mr-2"><i class="mdi mdi-star text-yellow-500"></i></span>
                            Level Quiz Awal
                        </label>
                        <input name="level_awal" type="number" min="1" class="input bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                               required value="{{ session('level_awal', old('level_awal', 1)) }}" placeholder="1">
                        <p class="help text-gray-600">Level kesulitan awal (1= Mudah, 2= Sedang, 3= Sulit)</p>
                    </div>

                    <div class="field">
                        <label class="label text-gray-700 font-medium">
                            <span class="icon mr-2"><i class="mdi mdi-target text-red-500"></i></span>
                            Kriteria Ketuntasan Minimal (KKM)
                        </label>
                        <input name="kkm" type="number" min="0" max="100" class="input bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                               required value="{{ session('kkm', old('kkm', 75)) }}" placeholder="75">
                        <p class="help text-gray-600">Nilai minimum untuk lulus quiz (0-100)</p>
                    </div>

                    <div class="field">
                        <label class="label text-gray-700 font-medium">
                            <span class="icon mr-2"><i class="mdi mdi-clock text-orange-500"></i></span>
                            Waktu Pengerjaan (Menit)
                        </label>
                        <input name="waktu" type="number" min="1" class="input bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                               required value="{{ session('waktu', old('waktu', 30)) }}" placeholder="30">
                        <p class="help text-gray-600">Batas waktu maksimal untuk mengerjakan quiz</p>
                    </div>
                </div>

                {{-- Pengaturan Level Adaptif --}}
                @if (session('batas_naik_level'))
                <div class="bg-purple-50 border-l-4 border-purple-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="mdi mdi-brain text-purple-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-purple-800">Pengaturan Quiz Adaptif</h3>
                            <div class="mt-2 text-sm text-purple-700">
                                <p>Sistem akan menyesuaikan kesulitan soal berdasarkan kemampuan siswa secara real-time.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="field">
                        <label class="label text-gray-700 font-medium">
                            <span class="icon mr-2"><i class="mdi mdi-format-list-numbered text-purple-500"></i></span>
                            Total Soal Setiap Level
                        </label>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            @foreach (session('total_soal_per_level') as $item => $value)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <label class="text-sm font-medium text-gray-700 mb-2 block">{{ $item }}</label>
                                <input type="number" min="1" id="total_soal_per_level_{{ $item }}" 
                                       class="input bg-white border-gray-300" readonly required 
                                       value="{{ $value }}" placeholder="0">
                                <p class="help text-xs text-gray-600 mt-1">Soal tersedia di level ini</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="field">
                        <label class="label text-gray-700 font-medium">
                            <span class="icon mr-2"><i class="mdi mdi-playlist-edit text-blue-500"></i></span>
                            Jumlah Soal yang Harus Dikerjakan Setiap Level
                        </label>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            @foreach (session('jumlah_soal_per_level') as $item => $value)
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <label class="text-sm font-medium text-gray-700 mb-2 block">{{ $item }}
                                    <span class="ml-1 cursor-pointer" tabindex="0" data-tooltip="Maksimal soal yang bisa dikerjakan di level ini adalah {{ session('total_soal_per_level')[$item] ?? '-' }} (jumlah soal di bank soal).">
                                        <i class="mdi mdi-information-outline text-blue-400"></i>
                                    </span>
                                </label>
                                <input type="number" min="1" max="{{ session('total_soal_per_level')[$item] ?? '' }}" id="jumlah_soal_per_level_{{ $item }}" 
                                       class="input bg-white border-blue-300 focus:border-blue-500 focus:ring-blue-500" 
                                       onchange="updateHiddenInputPerLevel('{{ $item }}')" required 
                                       value="{{ $value }}" placeholder="0">
                                <p class="help text-xs text-blue-600 mt-1">Soal yang harus dikerjakan.<br>
                                    <span class="text-gray-500">Tips: Maksimal soal yang bisa dikerjakan adalah jumlah soal di bank soal ({{ session('total_soal_per_level')[$item] ?? '-' }}).</span>
                                </p>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-2 text-xs text-blue-700 bg-blue-100 rounded p-3">
                            <b>Catatan:</b> Jumlah soal yang dikerjakan di setiap level tidak boleh melebihi jumlah soal yang tersedia di bank soal. Sistem akan otomatis membatasi agar quiz berjalan lancar.
                        </div>
                    </div>

                    <div class="field">
                        <label class="label text-gray-700 font-medium">
                            <span class="icon mr-2"><i class="mdi mdi-trending-up text-green-500"></i></span>
                            Batas Naik Level (Soal Benar yang Diperlukan)
                        </label>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            @foreach (session('batas_naik_level') as $item => $value)
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <label class="text-sm font-medium text-gray-700 mb-2 block">{{ $item }}
                                    <span class="ml-1 cursor-pointer" tabindex="0" data-tooltip="Disarankan syarat naik level lebih kecil dari jumlah soal agar siswa masih bisa naik level meskipun ada jawaban yang salah.">
                                        <i class="mdi mdi-information-outline text-green-400"></i>
                                    </span>
                                </label>
                                <input type="number" min="1" id="batas_naik_level_{{ $item }}" 
                                       class="input bg-white border-green-300 focus:border-green-500 focus:ring-green-500" 
                                       onchange="updateHiddenInput('{{ $item }}')" required 
                                       value="{{ $value }}" placeholder="0">
                                <p class="help text-xs text-green-600 mt-1">Soal benar untuk naik level.<br>
                                    <span class="text-gray-500">Tips: Syarat naik level sebaiknya <b>lebih kecil</b> dari jumlah soal di level ini agar siswa masih bisa melakukan kesalahan dan tetap lanjut.</span>
                                </p>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-2 text-xs text-green-700 bg-green-100 rounded p-3">
                            <b>Catatan:</b> Jika syarat naik level sama dengan jumlah soal, siswa harus benar semua. Jika salah satu saja, quiz akan berhenti di level ini.<br>
                            Jika syarat naik level melebihi jumlah soal, quiz tidak bisa dilanjutkan. Mohon sesuaikan syarat naik level agar quiz berjalan lancar.
                        </div>
                    </div>
                </div>
                @endif

                <hr class="my-6">

                {{-- Tombol Aksi --}}
                <div class="flex justify-between items-center">
                    <div class="flex gap-3">
                        @if (session('total_soal'))
                        <a href="{{ route('quiz.preview.reset') }}" class="button red">
                            <span class="icon"><i class="mdi mdi-refresh"></i></span>
                            Reset
                        </a>
                        @endif
                        @if (!session('total_soal'))
                        <button type="submit" class="button blue">
                            <span class="icon"><i class="mdi mdi-upload"></i></span>
                            Import & Preview
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Preview Data dari Excel --}}
    @if(session('preview_soal'))
    <div class="card mb-6">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-table text-green-600"></i></span>
                Preview Data Soal dari Excel
            </p>
            <div class="card-header-icon">
                <form method="POST" action="{{ route('quiz.store') }}">
                    @csrf
                    {{-- Simpan field tersembunyi agar data dari form sebelumnya dikirim --}}
                    <input type="hidden" name="judul" value="{{ session('judul', old('judul')) }}">
                    <input type="hidden" name="deskripsi" value="{{ session('deskripsi', old('deskripsi')) }}">
                    <input type="hidden" name="matapelajaran_id" value="{{ session('matapelajaran_id', old('matapelajaran_id')) }}">
                    <input type="hidden" name="total_soal" id="total_soal" value="{{ session('total_soal', old('total_soal')) }}">
                    <input type="hidden" name="total_soal_tampil" id="total_soal_tampil_hidden" value="{{ session('total_soal_tampil', old('total_soal_tampil', 0)) }}">
                    <input type="hidden" name="waktu" value="{{ session('waktu', old('waktu', 30)) }}">

                    @foreach (session('jumlah_soal_per_level') as $item => $value)
                    <input type="hidden" name="jumlah_soal_per_level[{{ $item }}]" id="hidden_input_per_level{{ $item }}" value="{{ $value }}">
                    @endforeach

                    @foreach (session('batas_naik_level') as $item => $value)
                    <input type="hidden" name="batas_naik_level[{{ $item }}]" id="hidden_input_{{ $item }}" value="{{ $value }}">
                    @endforeach

                    {{-- Tambahkan hidden input untuk total_soal_per_level agar backend selalu menerima data bank soal per level --}}
                    @foreach (session('total_soal_per_level') as $item => $value)
                    <input type="hidden" name="total_soal_per_level[{{ $item }}]" value="{{ $value }}">
                    @endforeach
                    
                    <button type="submit" class="button green">
                        <span class="icon"><i class="mdi mdi-content-save"></i></span>
                        Simpan Quiz
                    </button>
                </form>
            </div>
        </header>
        <div class="card-content">
            <div class="table-container">
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-gray-700 font-medium">No</th>
                            <th class="text-gray-700 font-medium">Pertanyaan</th>
                            <th class="text-gray-700 font-medium">Level</th>
                            <th class="text-gray-700 font-medium">Jawaban Benar</th>
                            <th class="text-gray-700 font-medium">Skor</th>
                            <th class="text-gray-700 font-medium">A</th>
                            <th class="text-gray-700 font-medium">B</th>
                            <th class="text-gray-700 font-medium">C</th>
                            <th class="text-gray-700 font-medium">D</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $data = session('preview_soal'); @endphp
                        @if($data)
                        @foreach($data as $index => $row)
                        @if($index == 0 || empty($row[1]))
                        @continue
                        @endif
                        <tr class="hover:bg-gray-50">
                            <td data-label="No" class="font-medium text-gray-900">{{ $index }}</td>
                            <td data-label="Pertanyaan" class="text-gray-700">{{ $row[1] ?? '' }}</td>
                            <td data-label="Level" class="font-medium text-gray-900">{{ $row[3] ?? '' }}</td>
                            <td data-label="Jawaban Benar" class="font-medium text-green-600">{{ $row[2] ?? '' }}</td>
                            <td data-label="Skor" class="font-medium text-blue-600">{{ $row[8] ?? '' }}</td>
                            <td data-label="A" class="text-gray-600">{{ $row[4] ?? '' }}</td>
                            <td data-label="B" class="text-gray-600">{{ $row[5] ?? '' }}</td>
                            <td data-label="C" class="text-gray-600">{{ $row[6] ?? '' }}</td>
                            <td data-label="D" class="text-gray-600">{{ $row[7] ?? '' }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="9" class="text-center text-gray-500 py-8">
                                <div class="flex flex-col items-center">
                                    <i class="mdi mdi-table-off text-4xl text-gray-300 mb-2"></i>
                                    Belum ada data yang diimpor.
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</section>
@endsection

@push('extraScript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal-wide {
        width: 600px !important;
        max-width: 90vw !important;
    }
    
    .swal-title {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
        color: #dc2626 !important;
    }
    
    .swal-content {
        font-size: 1rem !important;
        line-height: 1.6 !important;
        text-align: left !important;
    }
    
    .swal-content strong {
        color: #dc2626 !important;
        font-weight: 600 !important;
    }
    
    .swal-content br {
        margin-bottom: 0.5rem !important;
    }
    
    /* Styling untuk tombol SweetAlert */
    .swal2-confirm {
        background-color: #dc2626 !important;
        color: white !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 12px 24px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3) !important;
    }
    
    .swal2-confirm:hover {
        background-color: #b91c1c !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4) !important;
    }
    
    .swal2-confirm:focus {
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.3) !important;
    }
    
    /* Styling untuk tombol sukses */
    .swal2-confirm.swal2-confirm-success {
        background-color: #10b981 !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
    }
    
    .swal2-confirm.swal2-confirm-success:hover {
        background-color: #059669 !important;
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4) !important;
    }
</style>
<script>
    // Konfigurasi default untuk SweetAlert2
    const swalConfig = {
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
        allowOutsideClick: false,
        allowEscapeKey: false,
        timer: null,
        timerProgressBar: false,
        showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
        }
    };

    // Konfigurasi SweetAlert2 untuk mendukung HTML
    if (typeof Swal !== 'undefined') {
        Swal.mixin({
            allowHtml: true,
            customClass: {
                popup: 'swal-wide',
                title: 'swal-title',
                content: 'swal-content'
            }
        });
    }

    function updateHiddenInputPerLevel(item) {
        var inputValueTotalLevel = document.getElementById(`total_soal_per_level_${item}`).value;
        var inputValue = document.getElementById(`jumlah_soal_per_level_${item}`).value;
        document.getElementById(`hidden_input_per_level${item}`).value = inputValue;

        console.log(`total ${item} : ` + inputValueTotalLevel);
        console.log(`level ${item} : ` + inputValue);
        
        if (parseInt(inputValue) > parseInt(inputValueTotalLevel)) {
            Swal.fire({
                ...swalConfig,
                title: 'Validasi Gagal',
                html: `Jumlah soal yang harus dikerjakan setiap level pada <strong>${item}</strong> tidak boleh lebih besar dari total soal setiap level <strong>${item}</strong>`,
                icon: 'warning',
                allowHtml: true,
                confirmButtonText: 'MENGERTI',
                customClass: {
                    confirmButton: 'swal2-confirm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#jumlah_soal_per_level_${item}`).val(inputValueTotalLevel);
                }
            });
            return true;
        }

        const number = parseInt(item.replace(/\D/g, ''));
        let inputBatasNaikLevel = parseInt($(`#batas_naik_level_fase${number}`).val());
        if (parseInt(inputValue) < parseInt(inputBatasNaikLevel)) {
            $(`#hidden_input_fase${number}`).val(inputValue);
            $(`#batas_naik_level_fase${number}`).val(inputValue);
        }
    }

    function updateHiddenInput(item) {
        let inputValue = parseInt($(`#batas_naik_level_${item}`).val());
        document.getElementById(`hidden_input_${item}`).value = inputValue;

        const number = parseInt(item.replace(/\D/g, ''));
        
        const jumlahSoalPerLevel = document.getElementById(`jumlah_soal_per_level_level${number}`).value;

        if (inputValue > jumlahSoalPerLevel) {
            Swal.fire({
                ...swalConfig,
                title: 'Validasi Gagal',
                html: `Nilai Batas naik level <strong>${item}</strong> tidak boleh lebih besar dari jumlah soal yang harus dikerjakan pada <strong>level${number}</strong>`,
                icon: 'warning',
                allowHtml: true,
                confirmButtonText: 'MENGERTI',
                customClass: {
                    confirmButton: 'swal2-confirm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#batas_naik_level_${item}`).val(jumlahSoalPerLevel);
                }
            });
            return true;
        }
    }

    function validasiTotalSoal(val){
        const totalSoal = parseInt($('#total_soal').val());
        const soalTampil = parseInt(val);

        if(soalTampil > totalSoal){
            Swal.fire({
                ...swalConfig,
                title: 'Validasi Gagal',
                html: '<strong>Jumlah soal tampil tidak boleh lebih besar dari total soal</strong>',
                icon: 'warning',
                allowHtml: true,
                confirmButtonText: 'MENGERTI',
                customClass: {
                    confirmButton: 'swal2-confirm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#total_soal_tampil').val(0);
                    $('#total_soal_tampil_hidden').val(0);
                }
            });
            return false;
        } else if(soalTampil < 10){
            Swal.fire({
                ...swalConfig,
                title: 'Validasi Gagal',
                html: '<strong>Jumlah soal tampil minimal 10 soal</strong>',
                icon: 'warning',
                allowHtml: true,
                confirmButtonText: 'MENGERTI',
                customClass: {
                    confirmButton: 'swal2-confirm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#total_soal_tampil').val(0);
                    $('#total_soal_tampil_hidden').val(0);
                }
            });
            return false;
        }
        
        // Update hidden input dengan nilai baru
        $('#total_soal_tampil_hidden').val(soalTampil);
        console.log('Updated total_soal_tampil_hidden to:', soalTampil);
    }

    // Tambahkan event listener untuk memastikan nilai tersimpan saat form disubmit
    $(document).ready(function() {
        $('form').on('submit', function() {
            const soalTampil = parseInt($('#total_soal_tampil').val());
            $('#total_soal_tampil_hidden').val(soalTampil);
            console.log('Form submitted with total_soal_tampil:', soalTampil);
        });

        // Handle validation error dari session
        @if(session('validation_error'))
            Swal.fire({
                title: '{{ session("validation_error.title") }}',
                html: `{!! session("validation_error.message") !!}`,
                icon: 'error',
                allowHtml: true,
                confirmButtonText: 'MENGERTI',
                confirmButtonColor: '#dc2626',
                width: '600px',
                customClass: {
                    popup: 'swal-wide',
                    title: 'swal-title',
                    content: 'swal-content',
                    confirmButton: 'swal2-confirm'
                }
            });
        @endif
    });

    // Validasi jumlah soal yang dikerjakan tidak melebihi bank soal
    $(document).ready(function() {
        $('[id^=jumlah_soal_per_level_]').on('input', function() {
            var max = parseInt($(this).attr('max'));
            if (parseInt($(this).val()) > max) {
                $(this).val(max);
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal',
                    html: 'Jumlah soal yang dikerjakan tidak boleh melebihi jumlah soal di bank soal.',
                    confirmButtonText: 'MENGERTI',
                    customClass: { confirmButton: 'swal2-confirm' }
                });
            }
        });
    });

    // Tooltip handler
    $(document).on('mouseenter focus', '[data-tooltip]', function() {
        const tooltip = $(this).attr('data-tooltip');
        const offset = $(this).offset();
        const tooltipDiv = $('<div class="custom-tooltip"></div>').text(tooltip).css({
            top: offset.top - 40,
            left: offset.left,
            position: 'absolute',
            background: '#f0fdf4',
            color: '#166534',
            padding: '6px 12px',
            borderRadius: '6px',
            fontSize: '13px',
            boxShadow: '0 2px 8px rgba(0,0,0,0.08)',
            zIndex: 9999
        });
        $('body').append(tooltipDiv);
        $(this).on('mouseleave blur', function() {
            tooltipDiv.remove();
        });
    });
</script>
@endpush