@extends('layouts.app')
@section('title', "Kelas")
@section('titleHeader', "Data Kelas")
@section('addBtn')
<a href="{{ route('kelas.create') }}" class="button blue">
    <span>Tambah</span>
</a>
@endsection

@section('content')
<section class="section main-section">
    <div class="card has-table">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
                Admin
            </p>
        </header>
        <div class="card-content">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Wali Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="No">1</td>
                        <td data-label="Email">6A</td>
                        <td data-label="Jk">Rani Ayu</td>
                        <td>
                            <div class="buttons right nowrap">
                                <a href="{{ route('kelas.edit', '1') }}" class="button small blue --jb-modal"
                                    data-target="sample-modal-2" type="button">
                                    <span class="icon">
                                        <x-icon name="edit" class="w-2 h-2 text-white" />
                                    </span>
                                </a>
                                <button class="button small red --jb-modal" data-target="sample-modal" type="button">
                                    <span class="icon">
                                        <x-icon name="delete" class="w-2 h-2 text-white" />
                                    </span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="table-pagination">
                <div class="flex items-center justify-between">
                    <div class="buttons">
                        <button type="button" class="button active">1</button>
                        <button type="button" class="button">2</button>
                        <button type="button" class="button">3</button>
                    </div>
                    <small>Page 1 of 3</small>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="sample-modal" class="modal">
    <div class="modal-background --jb-modal-close"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Warning!</p>
        </header>
        <section class="modal-card-body">
            <p>Apakah anda ingin menghapus Kelas <b>6A</b>?</p>
        </section>
        <footer class="modal-card-foot">
            <button class="button --jb-modal-close">Cancel</button>
            <button class="button red --jb-modal-close">Confirm</button>
        </footer>
    </div>
</div>
@endsection