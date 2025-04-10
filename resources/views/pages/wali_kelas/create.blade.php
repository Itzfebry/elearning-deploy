@extends('layouts.app')
@section('title', "Guru")
@section('titleHeader', "Tambah Guru")

@section('content')
<section class="section main-section">
    <div class="notification blue">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
            <div>
                <span class="icon"><i class="mdi mdi-buffer"></i></span>
                <b>Password Guru otomatis menggunakan NIP</b>
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
            <form method="POST" action="{{ route('guru.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                    <div class="field">
                        <label class="label">NIP</label>
                        <input class="input" type="text" name="nip" placeholder="contoh.1298787288" required
                            maxlength="18" value="{{ old('nip') }}">
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <input class="input" type="email" name="email" placeholder="contoh@gmail.com" required
                            value="{{ old('email') }}">
                    </div>
                    <div class="field">
                        <label class="label">Nama</label>
                        <input class="input" type="text" name="nama" placeholder="masukkan nama" required
                            value="{{ old('nama') }}">
                        <input type="text" name="role" hidden value="guru">
                    </div>
                    <div class="field">
                        <label class="label">Jenis Kelamain</label>
                        <div class="control">
                            <div class="select">
                                <select name="jk">
                                    <option value="L" {{ old('jk')=="L" ? "selected" : "" }}>Laki-laki</option>
                                    <option value="P" {{ old('jk')=="P" ? "selected" : "" }}>Perempuan</option>
                                </select>
                            </div>
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