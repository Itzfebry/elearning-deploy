@extends('layouts.app')
@section('title', "Kelas")
@section('titleHeader', "Edit Kelas")

@section('content')
<section class="section main-section">
    <form action="{{ route('kelas.update', $kelas->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="card mb-6">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="mdi mdi-ballot"></i></span>
                    Forms
                </p>
            </header>
            <div class="card-content">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                    <div class="field">
                        <label class="label">Nama Kelas</label>
                        <input class="input" type="text" name="nama" placeholder="contoh.6A" required
                            value="{{ $kelas->nama }}">
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
            </div>
        </div>
    </form>
</section>
@endsection