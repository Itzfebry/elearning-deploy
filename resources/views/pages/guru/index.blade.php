@extends('layouts.app')
@section('title', "Guru")
@section('titleHeader', "Data Guru")
@section('addBtn')
<a href="{{ route('guru.create') }}" class="button blue">
    <span>Tambah</span>
</a>
@endsection

@section('content')
<section class="section main-section">
    <div class="card has-table">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
                Guru
            </p>
        </header>
        <div class="card-content">
            <table>
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jenis Kelamin</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="NISN">12783127631829398</td>
                        <td data-label="Name">Jaka Rian</td>
                        <td data-label="Email">JakaRian@gmail.com</td>
                        <td data-label="Jk">Laki-laki</td>
                        <td class="actions-cell">
                            <div class="buttons right nowrap">
                                <a href="{{ route('guru.edit', '1') }}" class="button small blue --jb-modal"
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
            <p>Apakah anda ingin menghapus Guru <b>Jaka Rian</b>?</p>
        </section>
        <footer class="modal-card-foot">
            <button class="button --jb-modal-close">Cancel</button>
            <button class="button red --jb-modal-close">Confirm</button>
        </footer>
    </div>
</div>
@endsection