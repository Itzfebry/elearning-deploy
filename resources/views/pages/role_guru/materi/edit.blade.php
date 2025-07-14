@extends('layouts.app')
@section('title', "Guru")
@section('titleHeader', "Edit Guru")

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
            <form method="POST" action="{{ route('materi.update', $materi->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
                    <div class="field">
                        <label class="label">Mata Pelajaran</label>
                        <div class="control">
                            <div class="select">
                                <select name="matapelajaran_id" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach ($matpel as $item)
                                    <option value="{{ $item->id }}" {{ $materi->matapelajaran_id==$item->id ? "selected"
                                        : "" }}>
                                        {{ $item->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Semester</label>
                        <div class="control">
                            <div class="select">
                                <select name="semester" required>
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="1" {{ $materi->semester=="1" ? "selected" : "" }}>Semester 1</option>
                                    <option value="2" {{ $materi->semester=="2" ? "selected" : "" }}>Semester 2</option>
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
                                    <option value="{{ $item->tahun }}" {{ $materi->tahun_ajaran==$item->tahun
                                        ? "selected" : "" }}>
                                        {{ $item->tahun }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Judul Materi</label>
                        <input class="input" type="text" name="judul_materi" placeholder="contoh. Rumus Aritmatika"
                            required value="{{ $materi->judul_materi }}">
                    </div>
                    <div class="field">
                        <label class="label">Desktipsi</label>
                        <div class="control">
                            <textarea class="textarea" name="deskripsi" required
                                placeholder="Masukkkan Deskripsi Materi">{{ $materi->deskripsi }}</textarea>
                        </div>
                    </div>
                    <div class="field content-dinamis">
                        <label class="label">Tipe Materi</label>
                        <div class="control">
                            <div class="select">
                                <select name="type" class="typeMateri" required>
                                    <option value="">-- Pilih Tipe Materi --</option>
                                    <option value="buku" {{ $materi->type=="buku" ? "selected" : "" }}>Buku</option>
                                    <option value="video" {{ $materi->type=="video" ? "selected" : "" }}>Video</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field relative">
                        <label class="label">Tanggal Pembuatan (Tanggal & Jam)</label>
                        <span class="absolute left-3 top-9 text-gray-400 pointer-events-none">
                            <i class="mdi mdi-calendar-clock"></i>
                        </span>
                        <input name="tanggal" type="datetime-local" class="input pl-10" required placeholder="Pilih tanggal & jam" value="{{ $materi->tanggal ? \Carbon\Carbon::parse($materi->tanggal)->format('Y-m-d\\TH:i') : '' }}">
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
        var type = $('.typeMateri').val();

        $('.form-dinamis').remove();
        if (type == "video") {
            $('.content-dinamis').after(`
                <div class="field form-dinamis">
                    <label class="label">Upload Link Video</label>
                    <input class="input" type="text" name="path" placeholder="contoh. https://www.youtube.com/"
                        required value="{{ $materi->type == "buku" ? "" : $materi->path }}">
                </div>
            `);
        } else if(type == "buku") {
            $('.content-dinamis').after(`
                <div class="field form-dinamis">
                    <label class="label">Upload File (PDF)</label>
                    
                    @if ($materi->path)
                        <p class="text-sm mb-2 text-blue-600">
                            File sebelumnya: 
                            <a href="{{ asset('storage/' . $materi->path) }}" target="_blank" class="underline">Lihat PDF</a>
                        </p>
                    @endif

                    <input
                        class="fileInput block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="user_avatar_help" 
                        type="file" 
                        name="path" 
                        accept="application/pdf">
                        
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="user_avatar_help">
                        Upload file materi berupa PDF. <b>Max 5Mb</b>.
                    </div>
                </div>
            `);
        }
    });

    $('.typeMateri').on('change', function (){
        var type = $(this).val();

        $('.form-dinamis').remove();
        if (type == "video") {
            $('.content-dinamis').after(`
                <div class="field form-dinamis">
                    <label class="label">Upload Link Video</label>
                    <input class="input" type="text" name="path" placeholder="contoh. https://www.youtube.com/"
                        required value="{{ $materi->type == "buku" ? "" : $materi->path }}">
                </div>
            `);
        } else if(type == "buku") {
            $('.content-dinamis').after(`
                <div class="field form-dinamis">
                    <label class="label">Upload File (PDF)</label>
                    
                    @if ($materi->path)
                        <p class="text-sm mb-2 text-blue-600">
                            File sebelumnya: 
                            <a href="{{ asset('storage/' . $materi->path) }}" target="_blank" class="underline">Lihat PDF</a>
                        </p>
                    @endif

                    <input
                        class="fileInput block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="user_avatar_help" 
                        type="file" 
                        name="path" 
                        accept="application/pdf">
                        
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="user_avatar_help">
                        Upload file materi berupa PDF. <b>Max 5Mb</b>.
                    </div>
                </div>
            `);
        }
    });

    $(document).on('change', '.fileInput', function(){
        const file = this.files[0];
        const maxSize = 5 * 1024 * 1024;

        if (file && file.size > maxSize) {
            alert("Ukuran file terlalu besar. Maksimum 5MB.");
            this.value = "";
        }
    });
</script>
@endpush