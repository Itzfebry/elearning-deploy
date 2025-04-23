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
    <div class="card mb-6">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-ballot"></i></span>
                Form Utama
            </p>
        </header>
        <div class="card-content">
            <form method="POST" action="">
                @csrf
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-1 mb-6">
                    <div class="field">
                        <label class="label">Judul Quiz</label>
                        <input name="judul" type="text" class="input" required placeholder="Masukkan Judul Quiz"
                            value="{{ old('judul') }}">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                    <div class="field">
                        <label class="label">Deskripsi</label>
                        <div
                            class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <div class="px-4 py-2 bg-white rounded-b-lg dark:bg-gray-800">
                                <textarea rows="8"
                                    class="block w-full px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400"
                                    placeholder="Masukkkan Tugas..." name="deskripsi"
                                    required>{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                        <div class="field">
                            <label class="label">Mata Pelajaran</label>
                            <div class="control">
                                <div class="select">
                                    <select name="matapelajaran_id" required>
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach ($matpel as $item)
                                        <option value="{{ $item->id }}" {{ old('id')==$item->id ? "selected" : "" }}>
                                            {{$item->nama }}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Total Soal</label>
                            <input name="total_soal" type="text" class="input" required value="{{ old('total_soal') }}"
                                readonly>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="flex justify-between items-center">
                    <div class="flex gap-2">
                        <button type="button" class="button orange">
                            Reset
                        </button>
                        <button type="button" class="button blue">
                            Import Data
                        </button>
                    </div>
                    <div>
                        <button type="submit" class="button green">
                            Simpan Quiz
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mb-6">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-ballot"></i></span>
                Data Soal Dari Excel
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
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="No">1</td>
                        <td data-label="Pertanyaan">Pertanyaan</td>
                        <td data-label="Level">Level</td>
                        <td data-label="Jawaban Benar">Jawaban Benar</td>
                        <td data-label="A">A</td>
                        <td data-label="B">B</td>
                        <td data-label="C">C</td>
                        <td data-label="D">D</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection