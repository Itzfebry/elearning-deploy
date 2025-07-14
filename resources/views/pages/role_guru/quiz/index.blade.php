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
                                <th>Info</th>
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
                                        <button class="button is-info is-light is-small" onclick='showQuizInfo({!! json_encode([
                                            "total_soal_tampil" => $item->total_soal_tampil,
                                            "jumlah_soal_per_level" => $item->quizLevelSetting->jumlah_soal_per_level ?? '{}',
                                            "batas_naik_level" => $item->quizLevelSetting->batas_naik_level ?? '{}',
                                            "waktu" => $item->waktu,
                                            "kkm" => $item->quizLevelSetting->kkm ?? '-'
                                        ]) !!})'>
                                            <span class="icon"><i class="mdi mdi-information-outline"></i></span>
                                        </button>
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
    <!-- GLOBAL MODAL DI LUAR TABEL -->
    <div id="simpleQuizInfoModalGlobal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:99999; align-items:center; justify-content:center; overflow:auto;">
      <div id="simpleQuizInfoModalContent" onclick="event.stopPropagation()" style="background:#fff; border-radius:10px; max-width:90vw; width:350px; margin:60px auto; padding:24px 20px; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
        <!-- ISI MODAL AKAN DIISI JS -->
      </div>
    </div>
    <div id="modalDelete"></div>
</section>
@endsection

@push('extraScript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal-wide {
        width: 600px !important;
        max-width: 90vw !important;
    }
    
    .swal-title {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
    }
    
    .swal-content {
        font-size: 1rem !important;
        line-height: 1.6 !important;
        text-align: left !important;
    }
    
    /* Styling untuk tombol SweetAlert */
    .swal2-confirm {
        background-color: #dc2626 !important;
        color: white !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 12px 24px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3) !important;
    }
    
    .swal2-confirm:hover {
        background-color: #b91c1c !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4) !important;
    }
    
    .swal2-confirm:focus {
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.3) !important;
    }
    
    /* Styling untuk tombol sukses */
    .swal2-confirm.swal2-confirm-success {
        background-color: #10b981 !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
    }
    
    .swal2-confirm.swal2-confirm-success:hover {
        background-color: #059669 !important;
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4) !important;
    }
</style>
<script>
    // Handle success message dari session
    @if(session('success_message'))
        Swal.fire({
            title: '{{ session("success_message.title") }}',
            html: `{!! session("success_message.message") !!}`,
            icon: 'success',
            allowHtml: true,
            confirmButtonText: 'BAIK',
            confirmButtonColor: '#10b981',
            width: '600px',
            customClass: {
                popup: 'swal-wide',
                title: 'swal-title',
                content: 'swal-content',
                confirmButton: 'swal2-confirm swal2-confirm-success'
            }
        });
    @endif

    // Handle validation error dari session
    @if(session('validation_error'))
        Swal.fire({
            title: '{{ session("validation_error.title") }}',
            html: `{!! session("validation_error.message") !!}`,
            icon: 'error',
            allowHtml: true,
            confirmButtonText: 'MENGERTI',
            confirmButtonColor: '#dc2626',
            width: '600px',
            customClass: {
                popup: 'swal-wide',
                title: 'swal-title',
                content: 'swal-content',
                confirmButton: 'swal2-confirm'
            }
        });
    @endif

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
<script>
function showQuizInfo(data) {
  // Render isi modal
  let html = `<div style='font-weight:bold; font-size:1.1rem; margin-bottom:10px;'>Info Setting Quiz</div>`;
  html += `<div style='font-size:0.97rem;'>`;
  html += `<div><b>Total Soal Tampil:</b> ${data.total_soal_tampil}</div>`;
  html += `<div style='margin-top:8px;'><b>Jumlah Soal per Level:</b></div><ul style='margin-left:18px;'>`;
  let perLevel = JSON.parse(data.jumlah_soal_per_level);
  for (const [level, jumlah] of Object.entries(perLevel)) {
    html += `<li>${level}: ${jumlah} soal</li>`;
  }
  html += `</ul><div style='margin-top:8px;'><b>Batas Naik Level:</b></div><ul style='margin-left:18px;'>`;
  let batas = JSON.parse(data.batas_naik_level);
  for (const [fase, val] of Object.entries(batas)) {
    html += `<li>${fase}: ${val} benar</li>`;
  }
  html += `</ul><div style='margin-top:8px;'><b>Waktu:</b> ${data.waktu} menit</div>`;
  html += `<div style='margin-top:8px;'><b>KKM:</b> ${data.kkm}</div>`;
  html += `</div>`;
  html += `<button onclick='hideQuizInfo()' style='margin-top:18px; width:100%; background:#2563eb; color:#fff; border:none; border-radius:6px; padding:8px 0; font-weight:600; font-size:1rem; cursor:pointer;'>Tutup</button>`;
  document.getElementById('simpleQuizInfoModalContent').innerHTML = html;
  const modal = document.getElementById('simpleQuizInfoModalGlobal');
  modal.style.display = 'flex';
  setTimeout(() => {
    modal.setAttribute('tabindex', '-1');
    modal.focus();
  }, 10);
  function escListener(e) {
    if (e.key === 'Escape') hideQuizInfo();
  }
  modal.escListener = escListener;
  document.addEventListener('keydown', escListener);
  modal.onclick = function() { hideQuizInfo(); };
}
function hideQuizInfo() {
  const modal = document.getElementById('simpleQuizInfoModalGlobal');
  modal.style.display = 'none';
  if (modal.escListener) {
    document.removeEventListener('keydown', modal.escListener);
    modal.escListener = null;
  }
  modal.onclick = null;
}
</script>
@endpush