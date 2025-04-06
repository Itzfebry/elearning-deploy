@extends('layouts.app')
@section('title', "Siswa")
@section('titleHeader', "Edit Siswa")

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
            <form method="get">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                    <div class="field">
                        <label class="label">NISN</label>
                        <input class="input" type="text" name="nisn" placeholder="contoh.1298787288" required>
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <input class="input" type="email" name="email" placeholder="contoh@gmail.com" required>
                    </div>
                    <div class="field">
                        <label class="label">Nama</label>
                        <input class="input" type="text" name="nama" placeholder="contoh.1298787288" required>
                    </div>
                    <div class="field">
                        <label class="label">Jenis Kelamain</label>
                        <div class="control">
                            <div class="select">
                                <select name="jk">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Kelas</label>
                        <div class="control">
                            <div class="select">
                                <select name="kelas">
                                    <option value="6A">6A</option>
                                    <option value="6B">6B</option>
                                    <option value="6C">6C</option>
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