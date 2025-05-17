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

                        <div class="field">
                            <label class="label">Total Soal</label>
                            <input name="total_soal" type="text" class="input" required readonly
                                value="{{ session('total_soal', old('total_soal')) }}">
                        </div>

                        <div class="field">
                            <label class="label">Total Soal Tampil (jumlah soal yang akan ditampilkan ke quiz)</label>
                            <input name="total_soal_tampil" type="number" class="input" required
                                value="{{ session('total_soal_tampil', old('total_soal_tampil', 20)) }}">
                        </div>
                    </div>
                </div>

                {{-- Pengaturan Level dan KKM --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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

                    <div class="field">
                        <label class="label">Batas Naik Level Fase 1</label>
                        <input name="batas_naik_level_fase1" type="number" min="1" class="input" required
                            value="{{ session('batas_naik_level_fase1', old('batas_naik_level_fase1', 3)) }}"
                            placeholder="Misal: 3 jawaban benar">
                    </div>

                    <div class="field">
                        <label class="label">Batas Naik Level Fase 2</label>
                        <input name="batas_naik_level_fase2" type="number" min="1" class="input" required
                            value="{{ session('batas_naik_level_fase2', old('batas_naik_level_fase2', 5)) }}"
                            placeholder="Misal: 5 jawaban benar">
                    </div>
                    <div class="field">
                        <label class="label">Upload Soal (Excel)</label>
                        <input type="file" name="file" required accept=".xlsx,.xls">
                        @if(session('uploaded_filename'))
                        <p class="help is-success">File terakhir: {{ session('uploaded_filename') }}</p>
                        @endif
                    </div>
                    @if (session('jumlah_soal_per_level'))
                    <div class="field mb-6">
                        <label class="label">Jumlah Soal per Level</label>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div>
                                <label>Mudah</label>
                                <input type="number" min="1" class="input" required
                                    value="{{ session('jumlah_soal_per_level')['level1'] }}"
                                    placeholder="Jumlah soal mudah" readonly>
                            </div>
                            <div>
                                <label>Sedang</label>
                                <input type="number" min="1" class="input"
                                    value="{{ session('jumlah_soal_per_level')['level2'] }}"
                                    placeholder="Jumlah soal sedang" readonly>
                            </div>
                            <div>
                                <label>Sulit</label>
                                <input type="number" min="1" class="input"
                                    value="{{ session('jumlah_soal_per_level')['level3'] }}"
                                    placeholder="Jumlah soal sulit" readonly>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <hr>

                {{-- Tombol Aksi --}}
                <div class="flex justify-between items-center">
                    <div class="flex gap-2">
                        <a href="{{ route('quiz.preview.reset') }}" class="button orange">Reset</a>
                        <button type="submit" class="button blue">Import</button>
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
                <input type="hidden" name="total_soal" value="{{ session('total_soal', old('total_soal')) }}">
                <input type="hidden" name="total_soal_tampil"
                    value="{{ session('total_soal_tampil', old('total_soal_tampil')) }}">
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
                        <th>Fase</th>
                        <th>Jawaban Benar</th>
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
                        <td data-label="Fase">{{ $row[8] ?? 1 }}</td>
                        <td data-label="Jawaban Benar">{{ $row[2] ?? '' }}</td>
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