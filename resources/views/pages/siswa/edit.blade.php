@extends('layouts.app')
@section('title', "Siswa")
@section('titleHeader', "Edit Siswa")

@section('content')
<section class="section main-section">
    <div class="notification blue">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
            <div>
                <span class="icon"><i class="mdi mdi-buffer"></i></span>
                <b>Password Siswa otomatis menggunakan NISN</b>
            </div>
            <button type="button" class="button small textual --jb-notification-dismiss">x</button>
        </div>
    </div>
    <div class="card mb-6">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-ballot"></i></span>
                Forms
            </p>
        </header>
        <div class="card-content">
            <form method="POST" action="{{ route('siswa.update', $siswa->id) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                    <div class="field">
                        <label class="label">NISN</label>
                        <input class="input" type="text" name="nisn" placeholder="contoh.1298787288" required
                            maxlength="10" value="{{ $siswa->nisn }}">
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <input class="input" type="email" name="email" placeholder="contoh@gmail.com" required
                            value="{{ $siswa->user->email }}">
                    </div>
                    <div class="field">
                        <label class="label">Nama</label>
                        <input class="input" type="text" name="nama" placeholder="Masukkan Nama" required
                            value="{{ $siswa->nama }}">
                    </div>
                    <input type="text" name="user_id" hidden value="{{ $siswa->user_id }}">
                    <div class="field">
                        <label class="label">Jenis Kelamain</label>
                        <div class="control">
                            <div class="select">
                                <select name="jk">
                                    <option value="L" {{ $siswa->jk == "L" ? "selected" : "" }}>Laki-laki</option>
                                    <option value="P" {{ $siswa->jk == "P" ? "selected" : "" }}>Perempuan</option>
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
                                    <option value="{{ $item->nama }}" {{ $siswa->kelas == $item->nama ? " selected" :
                                        ""}}>
                                        {{ $item->nama}}
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
                                    <option value="{{ $item->tahun }}" {{ $siswa->tahun_ajaran == $item->tahun ? "
                                        selected" :
                                        ""}}>
                                        {{ $item->tahun}}
                                    </option>
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