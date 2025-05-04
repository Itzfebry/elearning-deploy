@extends('layouts.app')
@section('title', "Tugas")
@section('titleHeader', "Edit Tugas")

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
            <form method="POST" action="{{ route('tugas.update', $tugas->id) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">

                    <div id="date-range-picker" date-rangepicker class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div class="field">
                            <label class="label">Tanggal</label>
                            <input id="datepicker-range-start" name="tanggal" type="text" class="input" required
                                placeholder="Pilih tanggal" value="{{ $tugas->tanggal }}">
                        </div>
                        <div class="field">
                            <label class="label">Tenggat</label>
                            <input id="datepicker-range-end" name="tenggat" type="text" class="input" required
                                placeholder="Pilih batas tanggal" value="{{ $tugas->tenggat }}">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Mata Pelajaran</label>
                        <div class="control">
                            <div class="select">
                                <select name="matapelajaran_id" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach ($mataPelajaran as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $tugas->matapelajaran_id ?
                                        'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Kelas</label>
                        <div class="control">
                            <div class="select">
                                <select name="kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelas as $item)
                                    <option value="{{ $item->nama }}" {{ $tugas->kelas==$item->nama ? 'selected' : ''
                                        }}>
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
                                <select name="tahun_ajaran" required>
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    @foreach ($tahunAjaran as $item)
                                    <option value="{{ $item->tahun }}" {{ $tugas->tahun_ajaran==$item->tahun ?
                                        'selected' : '' }}>
                                        {{ $item->tahun }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Tugas</label>
                        <div
                            class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <div class="px-4 py-2 bg-white rounded-b-lg dark:bg-gray-800">
                                <textarea rows="8"
                                    class="block w-full px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400"
                                    placeholder="Masukkkan Tugas..." name="nama" required>{{ $tugas->nama }}</textarea>
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
    $(document).ready(function() {
        const tanggal = new Date("{{ $tugas->tanggal }}");
        const tenggat = new Date("{{ $tugas->tenggat }}");

        const formatted1 = ("0" + (tanggal.getMonth() + 1)).slice(-2) + "/" +
                        ("0" + tanggal.getDate()).slice(-2) + "/" +
                        tanggal.getFullYear();

        const formatted2 = ("0" + (tenggat.getMonth() + 1)).slice(-2) + "/" +
                        ("0" + tenggat.getDate()).slice(-2) + "/" +
                        tenggat.getFullYear();

        $('#datepicker-range-start').val(formatted1);
        $('#datepicker-range-end').val(formatted2);
    });
</script>
@endpush