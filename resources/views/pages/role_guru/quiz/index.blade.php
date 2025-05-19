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
                            <th>Skor</th>
                            <th>Jawaban Benar</th>
                            <th>A</th>
                            <th>B</th>
                            <th>C</th>
                            <th>D</th>
                            @if (count($quiz) > 0)
                            <th>
                                <button type="button" class="button small red openModalBtn"
                                    data-form_id="{{ $judulQuiz->id }}" data-form_name="{{ $judulQuiz->judul }}">
                                    <span class="icon">
                                        <x-icon name="delete" class="w-3 h-3 text-white" />
                                    </span>
                                </button>
                            </th>
                            @endif
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
                            <td data-label="Skor">{{ $item->skor }}</td>
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

    $('.openModalBtn').click(function () {
        var formId = $(this).data('form_id');
        var formName = $(this).data('form_name');
        
        $('#modalDelete').html(`
            <div id="myModal-${formId}" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg w-96 shadow-lg relative">
                    <h2 class="text-xl font-semibold mb-4 text-orange-400">Warning!</h2>
                    <p class="mb-4">Apakah anda ingin menhapus data Quiz : <b>${formName}</b>?</p>
                    <form action="{{ route('quiz.delete') }}" method="POST">
                        @csrf
                        <input type="text" name="formid" value="${formId}" hidden>
                        <div class="flex justify-end space-x-2 mt-4">
                            <button id="submitModalBtn" type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">
                                Submit
                            </button>
                            <button id="closeModalBtn" type="button" class="text-gray-700 bg-gray-200 hover:bg-gray-300 font-medium rounded-lg text-sm px-4 py-2">
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        `);

        $(document).on('click', '#closeModalBtn', function () {
            $(`#myModal-${formId}`).remove();
        });
    });
</script>
@endpush