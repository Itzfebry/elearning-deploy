@extends('layouts.app')
@section('title', "Tugas")
@section('titleHeader', "Tambah Tugas")

@section('content')
<section class="section main-section">
    <div class="card mb-6">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-ballot"></i></span>
                Forms
            </p>
        </header>
        <div class="card-content">
            <form method="POST" action="{{ route('tugas.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">

                    <div class="field relative">
                        <label class="label">Tanggal Pembuatan (Tanggal & Jam)</label>
                        <span class="absolute left-3 top-9 text-gray-400 pointer-events-none">
                            <i class="mdi mdi-calendar-clock"></i>
                        </span>
                        <input name="tanggal" type="datetime-local" class="input pl-10" required placeholder="Pilih tanggal & jam" value="{{ old('tanggal') }}">
                        </div>
                    <div class="field relative">
                        <label class="label">Tenggat (Tanggal & Jam)</label>
                        <span class="absolute left-3 top-9 text-gray-400 pointer-events-none">
                            <i class="mdi mdi-clock-outline"></i>
                        </span>
                        <input name="tenggat" type="datetime-local" class="input pl-10" required placeholder="Pilih tenggat (tanggal & jam)" value="{{ old('tenggat') }}">
                    </div>
                    <div class="field">
                        <label class="label">Mata Pelajaran</label>
                        <div class="control">
                            <div class="select">
                                <select name="matapelajaran_id" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach ($mataPelajaran as $item)
                                    <option value="{{ $item->id }}" {{ old('matapelajaran_id')==$item->id }}>
                                        {{ $item->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Kelas</label>
                        <div class="control">
                            <div class="select">
                                <select name="kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelas as $item)
                                    <option value="{{ $item->nama }}" {{ old('kelas')==$item->nama }}>
                                        {{ $item->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Tahun Ajaran</label>
                        <div class="control">
                            <div class="select">
                                <select name="tahun_ajaran" required>
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    @foreach ($tahunAjaran as $item)
                                    <option value="{{ $item->tahun }}" {{ old('tahun_ajaran')==$item->tahun }}>
                                        {{ $item->tahun }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Tugas</label>
                        <div
                            class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <div class="px-4 py-2 bg-white rounded-b-lg dark:bg-gray-800">
                                <textarea rows="8"
                                    class="block w-full px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400"
                                    placeholder="Masukkkan Tugas..." name="nama" required>{{ old('nama') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Deskripsi Tugas (Opsional)</label>
                        <div class="control">
                            <textarea class="textarea" name="deskripsi" placeholder="Deskripsi tugas (boleh dikosongkan)">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="field grouped">
                    <div class="control">
                        <button type="submit" class="button green">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection