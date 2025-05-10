@extends('layouts.app')
@section('title', "Quiz")
@section('titleHeader', "Data Quiz")
@section('addBtn')
<a href="{{ route('quiz.create') }}" class="button blue">
    <span>Tambah</span>
</a>
@endsection

@section('content')
<section class="section main-section">
    <form method="get" action="{{ route('quiz') }}">
        @csrf
        <div class="card has-table">
            <header class="card-header flex flex-wrap items-end gap-4 mb-6 mt-3 mx-4">
                <div class="field flex-1 min-w-[150px]" id="matapelajaran">
                    <label class="block mb-2 text-sm font-medium">Mata Pelajaran</label>
                    <select name="matapelajaran_id" id="matapelajaran_id"
                        class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($matpel as $item)
                        <option value="{{ $item->id }}" {{ request()->matapelajaran_id==$item->id ? "selected" : ""
                            }}>{{
                            $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field flex-1 min-w-[150px]" id="quiz">
                    <label class="block mb-2 text-sm font-medium">Judul Quiz</label>
                    <select name="judul" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"
                        required id="judul">
                        <option value="">-- Pilih Judul Quiz --</option>
                    </select>
                </div>
                <div class="field flex-1 min-w-[150px]">
                    <label class="block mb-2 text-sm font-medium">Kelas</label>
                    <select name="kelas" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"
                        required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelas as $item)
                        <option value="{{ $item->nama }}" {{ request()->kelas==$item->nama ? "selected" : "" }}>{{
                            $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field flex-1 min-w-[150px]">
                    <label class="block mb-2 text-sm font-medium">Tahun Ajaran</label>
                    <select name="tahun_ajaran"
                        class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach ($tahunAjaran as $item)
                        <option value="{{ $item->tahun }}" {{ request()->tahun_ajaran==$item->tahun ? "selected" :
                            ""}}>{{
                            $item->tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end mb-4">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5">
                        Filter
                    </button>
                </div>
            </header>
            <div class="card-content">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pertanyaan</th>
                            <th>Level</th>
                            <th>Jawaban Benar</th>
                            <th>A</th>
                            <th>B</th>
                            <th>C</th>
                            <th>D</th>
                            {{-- <th></th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $no = 1;
                        @endphp
                        @forelse ($quiz as $item)
                        <tr>
                            <td data-label="No">{{ $no++ }}</td>
                            <td data-label="Pertanyaan">{{ $item->pertanyaan }}</td>
                            <td data-label="Level">{{ $item->level }}</td>
                            <td data-label="JawabanBenar">{{ $item->jawaban_benar }}</td>
                            <td data-label="A">{{ $item->opsi_a }}</td>
                            <td data-label="B">{{ $item->opsi_b }}</td>
                            <td data-label="C">{{ $item->opsi_c }}</td>
                            <td data-label="D">{{ $item->opsi_d }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-gray-500">Data Kosong, Silahkan Filter Data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{-- {{ $tugas->links() }} --}}
                </div>
            </div>
        </div>
    </form>
    <div id="modalDelete"></div>
</section>
@endsection
@push('extraScript')
<script>
    const selectedJudul = "{{ request()->judul }}";

    if (selectedJudul != "") {
        $('#matapelajaran_id').ready(function() {
            var id = $('#matapelajaran_id').val();
            $('#judul').empty().append('<option value="">-- Pilih Judul Quiz --</option>');
            $.ajax({
                url: '/get-quiz-by-matpel/' + id,
                method: 'GET',
                success: function(data) {
                    data.forEach(function(quiz) {
                        const selected = (quiz.judul === selectedJudul) ? 'selected' : '';
                        $('#judul').append(`<option value="${quiz.judul}" ${selected}>${quiz.judul}</option>`);
                    });
                }
            });
        });
    }
    
    $('#matapelajaran_id').on('change', function() {
        var id = $(this).val();
        $('#judul').empty().append('<option value="">-- Pilih Judul Quiz --</option>');

        if (id) {
            $.ajax({
                url: '/get-quiz-by-matpel/' + id,
                method: 'GET',
                success: function(data) {
                    data.forEach(function(quiz) {
                        const selected = (quiz.judul === selectedJudul) ? 'selected' : '';
                        $('#judul').append(`<option value="${quiz.judul}" ${selected}>${quiz.judul}</option>`);
                    });
                }
            });
        }
    });
</script>
@endpush