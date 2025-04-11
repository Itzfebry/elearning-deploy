@extends('layouts.app')
@section('title', "Guru")
@section('titleHeader', "Tambah Guru")

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
            <form method="POST" action="{{ route('tahun-ajaran.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                    <div class="field">
                        <label class="label">Tahun Ajaran</label>
                        <div class="control">
                            <div class="select">
                                <select name="tahun" class="tahun-ajaran">
                                    <option value="">-- Pilih Tahun Ajaran --</option>
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
@push('extraScript')
<script>
    $( document ).ready(function() {
        const tahunSekarang = new Date().getFullYear();
        const jumlahPilihan = 1;

        for (let i = 0; i < jumlahPilihan; i++) {
            const tahunAwal = tahunSekarang + i;
            const tahunAkhir = tahunAwal + 1;
            var value = `${tahunAwal}/${tahunAkhir}`;
            var textContent = `${tahunAwal}/${tahunAkhir}`;
            $('.tahun-ajaran').append(`
                <option value="${value}">${textContent}</option>
            `);
        }
    });
</script>
@endpush