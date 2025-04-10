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
            <form method="POST" action="{{ route('wali-kelas.update', $waliKelas->id) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                    <div class="field">
                        <label class="label">Kelas</label>
                        <div class="control">
                            <div class="select">
                                <select name="kelas">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelas as $item)
                                    <option value="{{ $item->nama }}" {{ $item->nama == $waliKelas->kelas ? "selected"
                                        :"" }}>
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
                                    <option value="{{ $item->tahun }}" {{ $waliKelas->tahun_ajaran==$item->tahun ?
                                        "selected"
                                        : "" }}>{{ $item->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Wali Kelas</label>
                        <div class="control">
                            <div class="select">
                                <select name="wali_nip" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach ($guru as $item)
                                    <option value="{{ $item->nip }}" {{ $item->nip == $waliKelas->wali_nip ? "selected"
                                        :"" }}>{{ $item->nama }}</option>
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
@push('extraScript')
{{-- <script>
    $( document ).ready(function() {
        var tahunAjaran = $('.tahun-ajaran').data('tahun_ajaran');
        const tahunSekarang = new Date().getFullYear();
        const jumlahPilihan = 5;

        for (let i = jumlahPilihan - 1; i >= 0; i--) {
            const tahunAwal = tahunSekarang - i;
            const tahunAkhir = tahunAwal + 1;
            var value = `${tahunAwal}/${tahunAkhir}`;
            var textContent = `${tahunAwal}/${tahunAkhir}`;
            $('.tahun-ajaran').append(`
                <option value="${value}" ${tahunAjaran == value ? "selected" : ""}>${textContent}</option>
            `);
        }
    });
</script> --}}
@endpush