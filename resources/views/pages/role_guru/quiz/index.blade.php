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
    <div class="card has-table">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-ballot"></i></span>
                Daftar Quiz
            </p>
        </header>
        <div class="card-content">
            @if(count($quiz) > 0)
                <div class="table-container">
                    <table class="table is-fullwidth is-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Quiz</th>
                                <th>Mata Pelajaran</th>
                                <th>Total Soal</th>
                                <th>Soal Tampil</th>
                                <th>Waktu (Menit)</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quiz as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->judul }}</strong>
                                    </td>
                                    <td>{{ $item->mataPelajaran->nama ?? '-' }}</td>
                                    <td>
                                        <span class="tag is-info">{{ $item->total_soal }}</span>
                                    </td>
                                    <td>
                                        <span class="tag is-warning">{{ $item->total_soal_tampil }}</span>
                                    </td>
                                    <td>
                                        <span class="tag is-success">{{ $item->waktu ?? '-' }} menit</span>
                                    </td>
                                    <td>
                                        <div class="text-sm text-gray-600">
                                            {{ Str::limit($item->deskripsi, 50) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="buttons are-small">
                                            <button type="button" class="button red openModalBtn"
                                                data-form_id="{{ $item->id }}" data-form_name="{{ $item->judul }}">
                                                <span class="icon">
                                                    <i class="mdi mdi-delete"></i>
                                                </span>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="has-text-centered py-6">
                    <span class="icon is-large">
                        <i class="mdi mdi-ballot-outline" style="font-size: 4rem; color: #ccc;"></i>
                    </span>
                    <p class="title is-4 has-text-grey-light mt-3">Belum ada quiz</p>
                    <p class="subtitle is-6 has-text-grey-light">Silakan buat quiz baru untuk memulai.</p>
                </div>
            @endif
        </div>
    </div>
    <div id="modalDelete"></div>
</section>
@endsection

@push('extraScript')
<script>
    $('.openModalBtn').click(function () {
        var formId = $(this).data('form_id');
        var formName = $(this).data('form_name');
        
        $('#modalDelete').html(`
            <div id="myModal-${formId}" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg w-96 shadow-lg relative">
                    <h2 class="text-xl font-semibold mb-4 text-orange-400">Warning!</h2>
                    <p class="mb-4">Apakah anda ingin menghapus data Quiz : <b>${formName}</b>?</p>
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