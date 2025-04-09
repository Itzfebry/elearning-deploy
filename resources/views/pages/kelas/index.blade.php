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
        <header class="card-header flex justify-between">
            <div class="px-4 py-3">
                <div class="gap-2">
                    <label for="page_length" class="text-sm text-gray-700 mb-0">Show</label>
                    <select name="page_length" id="page_length"
                        class="w-20 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="10" @isset($_GET['page_length']) {{ $_GET['page_length']==10 ? 'selected' : '' }}
                            @endisset>10</option>
                        <option value="20" @isset($_GET['page_length']) {{ $_GET['page_length']==20 ? 'selected' : '' }}
                            @endisset>20</option>
                        <option value="50" @isset($_GET['page_length']) {{ $_GET['page_length']==50 ? 'selected' : '' }}
                            @endisset>50</option>
                    </select>
                    <label for="page_length" class="text-sm text-gray-700 mb-0">entries</label>
                </div>
            </div>
            <div class="px-4 py-3">
                <div class="relative w-60">
                    <input type="text" name="search" placeholder="Search..."
                        value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}"
                        class="w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm" />
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </header>
        <div class="card-content">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 5;
                    $i = $page == 1 ? 1 : $page * $page_length - $page_length + 1;
                    @endphp
                    @forelse ($kelas as $item)
                    <tr>
                        <td data-label="No">{{ $i++ }}</td>
                        <td data-label="Email">{{ $item->nama }}</td>
                        <td class="actions-cell content-delete">
                            <div class="buttons right nowrap">
                                <a href="{{ route('kelas.edit', $item->id) }}" class="button small blue --jb-modal"
                                    data-target="sample-modal-2" type="button">
                                    <span class="icon">
                                        <x-icon name="edit" class="w-4 h-4 text-white" />
                                    </span>
                                </a>
                                <button type="button" id="openModalBtn" class="button small red "
                                    data-form_id="{{ $item->id }}">
                                    <span class="icon">
                                        <x-icon name="delete" class="w-3 h-3 text-white" />
                                    </span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500">Data Kosong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $kelas->links() }}
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