@extends('layouts.app')
@section('title', "Materi")
@section('titleHeader', "Detail Materi")

@section('content')
<section class="section main-section">
    <div class="card mb-6">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-ballot"></i></span>
                Detail
            </p>
        </header>
        <div class="card-content">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
                <div class="field">
                    <label class="label">Tanggal</label>
                    <input class="input" type="text" value="{{ $materiDetail->tanggal }}" disabled>
                </div>
                <div class="field">
                    <label class="label">Mata Pelajaran</label>
                    <input class="input" type="text" value="{{ $materiDetail->mataPelajaran->nama }}" disabled>
                </div>
                <div class="field">
                    <label class="label">Semester</label>
                    <input class="input" type="text" value="{{ $materiDetail->semester }}" disabled>
                </div>
                <div class="field">
                    <label class="label">Tipe Materi</label>
                    <input class="input" type="text" value="{{ $materiDetail->type }}" disabled>
                </div>
                <div class="field">
                    <label class="label">Tahun Ajaran</label>
                    <input class="input" type="text" value="{{ $materiDetail->tahun_ajaran }}" disabled>
                </div>
                <div class="field">
                    <label class="label">Judul Materi</label>
                    <input class="input" type="text" value="{{ $materiDetail->judul_materi }}" disabled>
                </div>
                <div class="field">
                    <label class="label">Desktipsi</label>
                    <div class="control">
                        <textarea class="textarea" disabled>{{ $materiDetail->deskripsi }}</textarea>
                    </div>
                </div>
                @if ($materiDetail->type == "video")
                <div class="field">
                    <label class="label">Video</label>
                    <div class="control">
                        <p class="text-gray-500 dark:text-gray-400">Link Video <a href="{{ $materiDetail->path }}"
                                class="font-medium text-blue-600 underline dark:text-blue-500 hover:no-underline">{{
                                $materiDetail->path }}</a>
                        </p>
                    </div>
                </div>
                @endif
            </div>
            @if ($materiDetail->type == "buku")
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-1 mb-6">
                <div class="field">
                    <label class="label">File Materi</label>
                    <div class="control">
                        <iframe src="{{ asset('storage/'. $materiDetail->path) }}"
                            class="w-full h-[80vh] border rounded shadow-lg" frameborder="0">
                        </iframe>
                    </div>
                </div>
            </div>
            @endif
            <hr>
        </div>
    </div>
</section>
@endsection