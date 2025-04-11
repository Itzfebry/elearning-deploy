@extends('layouts.app')
@section('title', "Tahun Ajaran")
@section('titleHeader', "Edit Tahun Ajaran")

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
            <form method="POST"
                action="{{ url('/tahun-ajaran/update?tahun_ajaran='. urlencode($tahunAjaran->tahun)) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                    <div class="field">
                        <label class="label">Tahun Ajaran</label>
                        <div class="control">
                            <div class="select">
                                <select name="status">
                                    <option value="aktif" {{ $tahunAjaran->status == "aktif" ? "selected" : "" }}>Aktif
                                    </option>
                                    <option value="selesai" {{ $tahunAjaran->status == "selesai" ? "selected" : ""
                                        }}>Selesai</option>
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