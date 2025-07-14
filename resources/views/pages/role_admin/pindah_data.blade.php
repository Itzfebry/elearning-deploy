@extends('layouts.app')

@section('title', 'Pemindahan Data Guru Mata Pelajaran')

@section('content')
<div class="container mx-auto max-w-xl mt-10">
    <div class="card shadow-lg rounded-lg border-0 mb-6">
        <div class="card-header bg-yellow-400 text-gray-900 flex items-center rounded-t-lg" style="padding: 1.2rem 1.5rem;">
            <span class="icon mr-2"><i class="mdi mdi-alert-circle" style="font-size: 1.5rem;"></i></span>
            <h3 class="text-lg font-bold">Catatan Penting Pemindahan Data</h3>
        </div>
        <div class="card-body p-6 bg-yellow-50 rounded-b-lg">
            <ol class="list-decimal ml-6 mb-2">
                <li><b>Pastikan guru lama masih menjadi pengampu mata pelajaran</b> yang akan dipindahkan.</li>
                <li>Lakukan proses pemindahan data melalui form di bawah ini.</li>
                <li>Setelah proses berhasil, <b>pengampu mata pelajaran akan otomatis menjadi guru baru</b> dan semua data terkait (tugas, submit tugas, dsb.) akan berpindah ke guru baru.</li>
            </ol>
            <div class="mt-2 text-sm text-gray-700">
                <b>Alasan urutan ini harus diikuti:</b>
                <ul class="list-disc ml-5 mt-1">
                    <li>Jika Anda mengubah pengampu mata pelajaran ke guru baru sebelum proses pemindahan data, maka proses transfer data akan gagal karena sistem tidak menemukan guru lama sebagai pengampu mapel.</li>
                    <li>Dengan mengikuti urutan di atas, data akan tetap konsisten dan tidak ada kehilangan akses pada data tugas, materi, maupun submit tugas.</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card shadow-lg rounded-lg border-0">
        <div class="card-header bg-indigo-700 text-white flex items-center rounded-t-lg" style="padding: 1.2rem 1.5rem;">
            <span class="icon mr-2"><i class="mdi mdi-database-sync" style="font-size: 1.5rem;"></i></span>
            <h3 class="text-lg font-bold">Pemindahan Data Guru Mata Pelajaran</h3>
        </div>
        <div class="card-body p-6 bg-white rounded-b-lg">
            <form action="{{ route('admin.pindah-data.proses') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="mata_pelajaran_id" class="block font-semibold mb-1">Mata Pelajaran</label>
                    <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-control w-full" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($mataPelajaran as $mp)
                            <option value="{{ $mp->id }}">{{ $mp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="guru_lama_nip" class="block font-semibold mb-1">Guru Lama</label>
                    <select name="guru_lama_nip" id="guru_lama_nip" class="form-control w-full" required>
                        <option value="">-- Pilih Guru Lama --</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->nip }}">{{ $g->nama }} ({{ $g->nip }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="guru_baru_nip" class="block font-semibold mb-1">Guru Baru</label>
                    <select name="guru_baru_nip" id="guru_baru_nip" class="form-control w-full" required>
                        <option value="">-- Pilih Guru Baru --</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->nip }}">{{ $g->nama }} ({{ $g->nip }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow">Pindahkan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 