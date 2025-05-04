@extends('layouts.app')
@section('title', "Mata Pelajaran")
@section('titleHeader', "Edit Mata Pelajaran")

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
            <form method="POST" action="{{ route('mata-pelajaran.update', $mataPelajaran->id) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                    <div class="field">
                        <label class="label">Nama Mata Pelajaran</label>
                        <input class="input" type="text" name="nama" placeholder="contoh.Matematika" required
                            value="{{ $mataPelajaran->nama }}">
                    </div>
                    <div class="field">
                        <label class="label">Guru Pengampu</label>
                        <div class="control">
                            <div class="select">
                                <select name="guru_nip" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach ($guru as $item)
                                    <option value="{{ $item->nip }}" {{ $mataPelajaran->guru_nip == $item->nip ?
                                        "selected" : "" }}>{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Kelas</label>
                        <div class="control">
                            <div class="select">
                                <select name="kelas">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelas as $item)
                                    <option value="{{ $item->nama }}" {{ $mataPelajaran->kelas == $item->nama ?
                                        "selected" : "" }}>
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
                                <select name="tahun_ajaran">
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    @foreach ($tahunAjaran as $item)
                                    <option value="{{ $item->tahun }}" {{ $mataPelajaran->tahun_ajaran == $item->tahun ?
                                        "selected" : "" }}>{{ $item->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="field grouped">
                    <div class="control">
                        <button type="submit" class="button blue">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection