@extends('layouts.app')
@section('title', "Admin")
@section('titleHeader', "Edit Admin")

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
            <form method="post" action="{{ route('admin.update', $admin->id) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                    <div class="field">
                        <label class="label">NIP</label>
                        <input class="input" type="text" name="nip" placeholder="contoh.1298787288" required
                            maxlength="18" value="{{ $admin->nip }}">
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <input class="input" type="email" name="email" placeholder="contoh@gmail.com" required
                            value="{{ $admin->user->email }}">
                    </div>
                    <div class="field">
                        <label class="label">Nama</label>
                        <input class="input" type="text" name="nama" placeholder="masukkan nama" required
                            value="{{ $admin->nama }}">
                    </div>
                    <input class="input" type="text" name="user_id" required value="{{ $admin->user_id }}" hidden>
                    <div class="field">
                        <label class="label">Jenis Kelamain</label>
                        <div class="control">
                            <div class="select">
                                <select name="jk">
                                    <option value="L" {{ $admin->jk == "L" ? "selected" : "" }}>Laki-laki</option>
                                    <option value="P" {{ $admin->jk == "P" ? "selected" : "" }}>Perempuan</option>
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