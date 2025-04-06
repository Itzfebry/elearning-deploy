@extends('layouts.app')
@section('title', "Kelas")
@section('titleHeader', "Tambah Kelas")

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
                        <label class="label">Nama Kelas</label>
                        <input class="input" type="text" name="nama" placeholder="contoh.6A" required>
                    </div>
                    <div class="field">
                        <label class="label">Wali Kelas</label>
                        <div class="control">
                            <div class="select">
                                <select name="nip_wali">
                                    <option value="">Ayu Dewi</option>
                                    <option value="">Putri Lestari</option>
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