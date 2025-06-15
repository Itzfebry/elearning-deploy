@extends('layouts.app')
@section('title', "Quiz")
@section('titleHeader', "Tambah Quiz")
@section('btnNew')
<div class="field grouped">
    <div class="control">
        <a type="button" class="button green" href="{{ route('quiz.excel.download') }}">
            Download Template Excel
        </a>
    </div>
</div>
@endsection

@section('content')
<section class="section main-section">

    {{-- Form untuk Upload dan Preview --}}
    <form method="POST" action="{{ route('quiz.preview') }}" enctype="multipart/form-data">
        @csrf
        <div class="card mb-6">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="mdi mdi-ballot"></i></span>
                    Form Utama
                </p>
            </header>
            <div class="card-content space-y-6">

                {{-- Judul Quiz --}}
                <div class="grid grid-cols-1">
                    <div class="field">
                        <label class="label">Judul Quiz</label>
                        <input name="judul" type="text" class="input" required placeholder="Masukkan Judul Quiz"
                            value="{{ session('judul', old('judul')) }}">
                    </div>
                </div>

                {{-- Deskripsi dan Mata Pelajaran --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="field">
                        <label class="label">Deskripsi</label>
                        <div
                            class="w-full border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <div class="px-4 py-2 bg-white rounded-b-lg dark:bg-gray-800">
                                <textarea rows="8"
                                    class="block w-full px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400"
                                    placeholder="Masukkkan Deskripsi..." name="deskripsi"
                                    required>{{ session('deskripsi', old('deskripsi')) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="field">
                            <label class="label">Upload Soal (Excel)</label>
                            <input type="file" name="file" required accept=".xlsx,.xls">
                            @if(session('uploaded_filename'))
                            <p class="help is-success">File terakhir: {{ session('uploaded_filename') }}</p>
                            @endif
                        </div>
                        <div class="field">
                            <label class="label">Mata Pelajaran</label>
                            <div class="control">
                                <div class="select">
                                    <select name="matapelajaran_id" required>
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach ($matpel as $item)
                                        <option value="{{ $item->id }}" {{ session('matapelajaran_id',
                                            old('matapelajaran_id'))==$item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pengaturan Level dan KKM --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if (session('total_soal'))
                    <div class="field">
                        <label class="label">Total Soal</label>
                        <input name="total_soal" type="text" class="input" required readonly min="1"
                            value="{{ session('total_soal', old('total_soal')) }}">
                    </div>

                    <div class="field">
                        <label class="label">Total Soal Tampil (jumlah soal yang akan ditampilkan ke quiz)</label>
                        <input name="total_soal_tampil" type="number" class="input" required id="total_soal_tampil"
                            onchange="validasiTotalSoal(this.value)" min="1"
                            value="{{ session('total_soal_tampil', old('total_soal_tampil', 0)) }}">
                    </div>
                    @endif
                    <div class="field">
                        <label class="label">Level Quiz Awal</label>
                        <input name="level_awal" type="number" min="1" class="input" required
                            value="{{ session('level_awal', old('level_awal', 1)) }}" placeholder="Misal: 1">
                    </div>

                    <div class="field">
                        <label class="label">KKM</label>
                        <input name="kkm" type="number" min="0" max="100" class="input" required
                            value="{{ session('kkm', old('kkm', 75)) }}" placeholder="Misal: 70">
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="field">
                        <label class="label">Waktu Pengerjaan (Menit)</label>
                        <input name="waktu" type="number" min="1" class="input" required
                            value="{{ session('waktu', old('waktu', 30)) }}" placeholder="Misal: 30">
                        <p class="help">Waktu maksimal untuk mengerjakan quiz dalam menit</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    @if (session('batas_naik_level'))
                    <div class="field mb-1">
                        <label class="label">Total Soal Setiap Level</label>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            @foreach (session('total_soal_per_level') as $item => $value)
                            <div>
                                <label>{{ $item }}</label>
                                <input type="number" min="1" id="total_soal_per_level_{{ $item }}" class="input"
                                    readonly required value="{{ $value }}" placeholder="Jumlah soal mudah">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    @if (session('batas_naik_level'))
                    <div class="field mb-1">
                        <label class="label">Jumlah yang harus dikerjakan setiap level</label>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            @foreach (session('jumlah_soal_per_level') as $item => $value)
                            <div>
                                <label>{{ $item }}</label>
                                <input type="number" min="1" id="jumlah_soal_per_level_{{ $item }}" class="input"
                                    onchange="updateHiddenInputPerLevel('{{ $item }}')" required value="{{ $value }}"
                                    placeholder="Jumlah soal mudah">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    @if (session('batas_naik_level'))
                    <div class="field mb-1">
                        <label class="label">Batas Naik Level</label>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            @foreach (session('batas_naik_level') as $item => $value)
                            <div>
                                <label>{{ $item }}</label>
                                <input type="number" min="1" id="batas_naik_level_{{ $item }}" class="input"
                                    onchange="updateHiddenInput('{{ $item }}')" required value="{{ $value }}"
                                    placeholder="Jumlah soal mudah">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                <hr>

                {{-- Tombol Aksi --}}
                <div class="flex justify-between items-center">
                    <div class="flex gap-2">
                        @if (session('total_soal'))
                        <a href="{{ route('quiz.preview.reset') }}" class="button red">Reset</a>
                        @endif
                        @if (!session('total_soal'))
                        <button type="submit" class="button blue">Import</button>
                        @endif
                    </div>
                </div>

            </div>


        </div>
    </form>

    {{-- Preview Data dari Excel --}}
    <div class="card mb-6">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-ballot"></i></span>
                Data Soal Dari Excel
                @if(session('preview_soal'))

            <form method="POST" action="{{ route('quiz.store') }}">
                @csrf
                {{-- Simpan field tersembunyi agar data dari form sebelumnya dikirim --}}
                <input type="hidden" name="judul" value="{{ session('judul', old('judul')) }}">
                <input type="hidden" name="deskripsi" value="{{ session('deskripsi', old('deskripsi')) }}">
                <input type="hidden" name="matapelajaran_id"
                    value="{{ session('matapelajaran_id', old('matapelajaran_id')) }}">
                <input type="hidden" name="total_soal" id="total_soal"
                    value="{{ session('total_soal', old('total_soal')) }}">
                <input type="hidden" name="total_soal_tampil" id="total_soal_tampil_hidden"
                    value="{{ session('total_soal_tampil', old('total_soal_tampil', 0)) }}">
                <input type="hidden" name="waktu" value="{{ session('waktu', old('waktu', 30)) }}">

                @foreach (session('jumlah_soal_per_level') as $item => $value)
                <input type="hidden" name="jumlah_soal_per_level[{{ $item }}]" id="hidden_input_per_level{{ $item }}"
                    value="{{ $value }}">
                @endforeach

                @foreach (session('batas_naik_level') as $item => $value)
                <input type="hidden" name="batas_naik_level[{{ $item }}]" id="hidden_input_{{ $item }}"
                    value="{{ $value }}">
                @endforeach
                <button type="submit" class="button green">
                    Simpan Quiz
                </button>

            </form>
            @endif
            </p>
        </header>
        <div class="card-content">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pertanyaan</th>
                        <th>Level</th>
                        <th>Jawaban Benar</th>
                        <th>Skor</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                    </tr>
                </thead>
                <tbody>
                    @php $data = session('preview_soal'); @endphp
                    @if($data)
                    @foreach($data as $index => $row)
                    @if($index == 0 || empty($row[1]))
                    @continue
                    @endif
                    <tr>
                        <td data-label="No">{{ $index }}</td>
                        <td data-label="Pertanyaan">{{ $row[1] ?? '' }}</td>
                        <td data-label="Level">{{ $row[3] ?? '' }}</td>
                        <td data-label="Jawaban Benar">{{ $row[2] ?? '' }}</td>
                        <td data-label="Skor">{{ $row[8] ?? '' }}</td>
                        <td data-label="A">{{ $row[4] ?? '' }}</td>
                        <td data-label="B">{{ $row[5] ?? '' }}</td>
                        <td data-label="C">{{ $row[6] ?? '' }}</td>
                        <td data-label="D">{{ $row[7] ?? '' }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data yang diimpor.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
@push('extraScript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                text: `Jumlah soal yang harus dikerjakan setiap level pada ${item} tidak boleh lebih besar dari total soal setiap level ${item}`,
                icon: 'warning'
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
                text: `Nilai Batas naik level ${item} tidak boleh lebih besar dari jumlah soal yang harus dikerjakan pada level${number}`,
                icon: 'warning'
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
                text: 'Jumlah soal tampil tidak boleh lebih besar dari total soal',
                icon: 'warning'
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
                text: 'Jumlah soal tampil minimal 10 soal',
                icon: 'warning'
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
    });
</script>
@endpush